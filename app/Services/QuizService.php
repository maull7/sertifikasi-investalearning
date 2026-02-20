<?php

namespace App\Services;

use App\Models\Package;
use App\Models\Quiz;
use App\Models\StatusMateri;
use App\Models\User;
use App\Repositories\Contracts\ExamRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class QuizService
{
    public function __construct(
        protected ExamRepositoryInterface $examRepository
    ) {}

    public function ensureUserCanAccessQuiz(User $user, Package $package, Quiz $quiz): void
    {
        if (! $this->examRepository->userJoinedPackage($user, $package)) {
            abort(403, 'Anda belum bergabung dengan package ini.');
        }

        $subjectIds = $package->getSubjectsForPackage()->pluck('id');
        if (! $subjectIds->contains($quiz->subject_id)) {
            abort(404, 'Kuis tidak ditemukan dalam package ini.');
        }

        $quiz->loadMissing('subject.materials');
        $subject = $quiz->subject;
        if ($subject && $subject->materials->isNotEmpty()) {
            $materialIds = $subject->materials->pluck('id');
            $completedCount = StatusMateri::where('id_user', $user->id)
                ->whereIn('id_material', $materialIds)
                ->where('status', 'completed')
                ->count();
            if ($completedCount < $materialIds->count()) {
                abort(403, 'Selesaikan semua materi mata pelajaran '.$subject->name.' terlebih dahulu sebelum mengerjakan kuis.');
            }
        }
    }

    public function getTotalQuestions(Quiz $quiz): int
    {
        return $this->examRepository->countQuestionsForQuiz($quiz);
    }

    public function getQuestionPage(User $user, Package $package, Quiz $quiz, int $page, int $perPage = 1): LengthAwarePaginator
    {
        $this->ensureUserCanAccessQuiz($user, $package, $quiz);
        $attempt = $this->examRepository->getOrCreateQuizAttempt($user, $package, $quiz);

        return $this->examRepository->getQuestionsPageForQuizAttempt($attempt, $page, $perPage);
    }

    public function getOrCreateQuizAttempt(User $user, Package $package, Quiz $quiz): \App\Models\QuizAttempt
    {
        $this->ensureUserCanAccessQuiz($user, $package, $quiz);

        return $this->examRepository->getOrCreateQuizAttempt($user, $package, $quiz);
    }

    /**
     * @return array{remaining_seconds: int, server_timestamp: int}|null
     */
    public function getQuizTimer(User $user, Package $package, Quiz $quiz): ?array
    {
        $durationMinutes = (int) $quiz->duration;
        if ($durationMinutes <= 0) {
            return null;
        }

        $attempt = $this->getOrCreateQuizAttempt($user, $package, $quiz);
        $endsAt = $attempt->started_at->copy()->addMinutes($durationMinutes);
        $now = now();
        $remainingSeconds = (int) max(0, $endsAt->timestamp - $now->timestamp);

        return [
            'remaining_seconds' => $remainingSeconds,
            'server_timestamp' => $now->timestamp,
        ];
    }

    /**
     * @param  array<int, string|null>  $answers
     * @return array{score: float, correct: int, total: int, status: string, trans_quiz_id: int, show_solutions: bool}
     */
    public function submitQuiz(User $user, Package $package, Quiz $quiz, array $answers): array
    {
        $this->ensureUserCanAccessQuiz($user, $package, $quiz);

        $attempt = $this->examRepository->getQuizAttempt($user, $package, $quiz);
        if (! $attempt) {
            abort(404, 'Sesi kuis tidak ditemukan. Silakan mulai kuis dari halaman kuis.');
        }

        $mappingQuestions = $this->examRepository->getQuestionsForQuizAttempt($attempt);
        $totalQuestions = $mappingQuestions->count();
        $correctAnswers = 0;

        DB::beginTransaction();

        try {
            $perQuestionScore = $totalQuestions > 0 ? 100 / $totalQuestions : 0;

            foreach ($mappingQuestions as $index => $mapping) {
                $page = $index + 1;
                $userAnswer = $answers[$page] ?? $answers[(string) $page] ?? null;

                if ($userAnswer) {
                    $userAnswer = strtoupper(trim($userAnswer));
                }

                $question = $mapping->questionBank;

                if (! $question) {
                    continue;
                }

                $correctAnswer = $question->answer ? strtoupper(trim($question->answer)) : null;
                $isCorrect = $userAnswer && $correctAnswer && $userAnswer === $correctAnswer;

                if ($isCorrect) {
                    $correctAnswers++;
                }
            }

            $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0.0;
            $status = $score >= $quiz->passing_grade ? 'lulus' : 'tidak lulus';

            $transQuiz = $this->examRepository->updateOrCreateTransQuiz(
                $user,
                $package,
                $quiz,
                questionsAnswered: count($answers),
                totalQuestions: $totalQuestions,
                totalScore: $score,
                status: $status
            );

            $this->examRepository->deleteDetailResultQuizByTransQuiz($transQuiz);

            foreach ($mappingQuestions as $index => $mapping) {
                $page = $index + 1;
                $userAnswer = $answers[$page] ?? $answers[(string) $page] ?? null;

                if ($userAnswer) {
                    $userAnswer = strtoupper(trim($userAnswer));
                }

                $question = $mapping->questionBank;

                if (! $question) {
                    continue;
                }

                $correctAnswer = $question->answer ? strtoupper(trim($question->answer)) : null;
                $isCorrect = $userAnswer && $correctAnswer && $userAnswer === $correctAnswer;
                $scoreObtained = $isCorrect ? $perQuestionScore : 0.0;

                $this->examRepository->createDetailResultQuiz(
                    $transQuiz,
                    $question->id,
                    $userAnswer ?? '-',
                    $correctAnswer ?? '-',
                    $scoreObtained
                );
            }

            $this->examRepository->deleteQuizAttempt($user, $package, $quiz);

            DB::commit();

            return [
                'score' => $score,
                'correct' => $correctAnswers,
                'total' => $totalQuestions,
                'status' => $status,
                'trans_quiz_id' => $transQuiz->id,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Soal kuis dari bank soal (quiz.subject_id), random per attempt.
     */
    public function getQuestionsWithSubject(User $user, Package $package, Quiz $quiz, int $page, int $perPage = 1): LengthAwarePaginator
    {
        $this->ensureUserCanAccessQuiz($user, $package, $quiz);
        $attempt = $this->examRepository->getOrCreateQuizAttempt($user, $package, $quiz);

        return $this->examRepository->getQuestionsPageForQuizAttempt($attempt, $page, $perPage);
    }
}
