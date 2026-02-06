<?php

namespace App\Services;

use App\Models\Package;
use App\Models\Quiz;
use App\Models\TransQuestion;
use App\Models\User;
use App\Repositories\Contracts\ExamRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
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

        if ($quiz->package_id !== $package->id) {
            abort(404, 'Kuis tidak ditemukan dalam package ini.');
        }
    }

    public function getTotalQuestions(Quiz $quiz): int
    {
        return $this->examRepository->countQuestionsForQuiz($quiz);
    }

    public function getQuestionPage(User $user, Package $package, Quiz $quiz, int $page, int $perPage = 1): LengthAwarePaginator
    {
        $this->ensureUserCanAccessQuiz($user, $package, $quiz);

        return $this->examRepository->getQuestionsPageForQuiz($quiz, $page, $perPage);
    }

    /**
     * @param  array<int, string|null>  $answers
     * @return array{score: float, correct: int, total: int, status: string, trans_question_id: int, show_solutions: bool}
     */
    public function submitQuiz(User $user, Package $package, Quiz $quiz, array $answers): array
    {
        $this->ensureUserCanAccessQuiz($user, $package, $quiz);

        $mappingQuestions = $this->examRepository->getAllMappingsWithQuestionsForQuiz($quiz);
        $totalQuestions = $mappingQuestions->count();
        $correctAnswers = 0;

        DB::beginTransaction();

        try {
            $trans = $this->examRepository->createTransQuestionForQuiz(
                $user,
                $package,
                $quiz,
                questionsAnswered: count($answers),
                totalQuestions: $totalQuestions
            );

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
            $status = $score >= $quiz->passing_grade ? 'lulus' : 'tidak lulus';

            $this->examRepository->updateTransQuestionResult($trans, $score, $status);

            DB::commit();

            $attemptCount = TransQuestion::where('id_user', $user->id)
                ->where('id_quiz', $quiz->id)
                ->count();

            $showSolutions = $attemptCount >= 3;

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
