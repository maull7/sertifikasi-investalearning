<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\Package;
use App\Models\TransQuestion;
use App\Models\User;
use App\Repositories\Contracts\ExamRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ExamService
{
    public function __construct(
        protected ExamRepositoryInterface $examRepository
    ) {}

    public function ensureUserCanAccessExam(User $user, Package $package, Exam $exam): void
    {
        if (!$this->examRepository->userJoinedPackage($user, $package)) {
            abort(403, 'Anda belum bergabung dengan package ini.');
        }

        if ($exam->package_id !== $package->id) {
            abort(404, 'Ujian tidak ditemukan dalam package ini.');
        }
    }

    public function getTotalQuestions(Exam $exam): int
    {
        return $this->examRepository->countQuestions($exam);
    }

    /**
     * Pretest: soal random dari mapel paket (attempt.question_ids).
     * Posttest: soal dari mapping_questions.
     */
    public function getQuestionPage(User $user, Package $package, Exam $exam, int $page, int $perPage = 1): LengthAwarePaginator
    {
        $this->ensureUserCanAccessExam($user, $package, $exam);

        if ($this->isPretest($exam)) {
            $attempt = $this->examRepository->getOrCreateExamAttempt($user, $package, $exam);
            return $this->examRepository->getQuestionsPageForExamAttempt($attempt, $page, $perPage);
        }

        return $this->examRepository->getQuestionsPage($exam, $page, $perPage);
    }

    private function isPretest(Exam $exam): bool
    {
        return strtolower((string) ($exam->type ?? '')) === 'pretest';
    }

    /**
     * Get or create exam attempt (untuk timer server-side). Panggil saat user load soal.
     */
    public function getOrCreateExamAttempt(User $user, Package $package, Exam $exam): \App\Models\ExamAttempt
    {
        $this->ensureUserCanAccessExam($user, $package, $exam);

        return $this->examRepository->getOrCreateExamAttempt($user, $package, $exam);
    }

    /**
     * Hitung sisa waktu (detik) berdasarkan started_at di server. Tetap berjalan meski device mati.
     *
     * @return array{remaining_seconds: int, server_timestamp: int}
     */
    public function getExamTimer(User $user, Package $package, Exam $exam): ?array
    {
        $durationMinutes = (int) $exam->duration;
        if ($durationMinutes <= 0) {
            return null;
        }

        $attempt = $this->getOrCreateExamAttempt($user, $package, $exam);
        $endsAt = $attempt->started_at->copy()->addMinutes($durationMinutes);
        $now = now();
        $remainingSeconds = (int) max(0, $endsAt->timestamp - $now->timestamp);

        return [
            'remaining_seconds' => $remainingSeconds,
            'server_timestamp' => $now->timestamp,
        ];
    }

    /**
     * Menghitung nilai, menyimpan ke trans_questions & detail_results,
     * dan mengembalikan ringkasan hasil.
     *
     * @param  array<int, string|null>  $answers
     * @return array{score: float, correct: int, total: int, status: string}
     */
    public function submitExam(User $user, Package $package, Exam $exam, array $answers): array
    {
        $this->ensureUserCanAccessExam($user, $package, $exam);

        $isPretest = $this->isPretest($exam);

        if ($isPretest) {
            $attempt = $this->examRepository->getExamAttempt($user, $package, $exam);
            if (! $attempt) {
                abort(404, 'Sesi ujian tidak ditemukan. Silakan mulai ujian dari halaman package.');
            }
            $questionsCollection = $this->examRepository->getQuestionsForExamAttempt($attempt);
        } else {
            $questionsCollection = $this->examRepository->getAllMappingsWithQuestions($exam);
        }

        $totalQuestions = $questionsCollection->count();
        $correctAnswers = 0;

        DB::beginTransaction();

        try {
            $trans = $this->examRepository->createTransQuestion(
                $user,
                $package,
                $exam,
                questionsAnswered: count($answers),
                totalQuestions: $totalQuestions
            );

            $perQuestionScore = $totalQuestions > 0 ? 100 / $totalQuestions : 0;

            foreach ($questionsCollection as $index => $item) {
                $page = $index + 1;
                $userAnswer = $answers[$page] ?? $answers[(string) $page] ?? null;
                if ($userAnswer) {
                    $userAnswer = strtoupper(trim($userAnswer));
                }

                $question = $isPretest ? $item->questionBank : $item->questionBank;
                if (! $question) {
                    continue;
                }

                $correctAnswer = $question->answer ? strtoupper(trim($question->answer)) : null;
                $isCorrect = $userAnswer && $correctAnswer && $userAnswer === $correctAnswer;
                $scoreObtained = $isCorrect ? $perQuestionScore : 0.0;

                if ($isCorrect) {
                    $correctAnswers++;
                }

                $this->examRepository->createDetailResult(
                    $trans,
                    $question->id,
                    $userAnswer ?? '-',
                    $correctAnswer ?? '-',
                    $scoreObtained
                );
            }

            $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0.0;
            $status = $score >= $exam->passing_grade ? 'lulus' : 'tidak lulus';

            $this->examRepository->updateTransQuestionResult($trans, $score, $status);

            DB::commit();

            $attemptCount = TransQuestion::where('id_user', $user->id)
                ->where('id_exam', $exam->id)
                ->count();

            $showSolutions = $attemptCount >= 3;

            $this->examRepository->deleteExamAttempt($user, $package, $exam);

            return [
                'score' => $score,
                'correct' => $correctAnswers,
                'total' => $totalQuestions,
                'status' => $status,
                'trans_question_id' => $trans->id,
                'show_solutions' => $showSolutions,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
