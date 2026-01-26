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

    public function getQuestionPage(User $user, Package $package, Exam $exam, int $page, int $perPage = 1): LengthAwarePaginator
    {
        $this->ensureUserCanAccessExam($user, $package, $exam);

        return $this->examRepository->getQuestionsPage($exam, $page, $perPage);
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

        $mappingQuestions = $this->examRepository->getAllMappingsWithQuestions($exam);
        $totalQuestions = $mappingQuestions->count();
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

            foreach ($mappingQuestions as $index => $mapping) {
                $page = $index + 1;

                // Handle both string and integer keys from frontend
                $userAnswer = $answers[$page] ?? $answers[(string) $page] ?? null;

                // Normalize answer (trim and uppercase)
                if ($userAnswer) {
                    $userAnswer = strtoupper(trim($userAnswer));
                }

                $question = $mapping->questionBank;

                if (!$question) {
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

            // Hitung jumlah attempt setelah submit (termasuk yang baru saja dibuat)
            $attemptCount = TransQuestion::where('id_user', $user->id)
                ->where('id_exam', $exam->id)
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
