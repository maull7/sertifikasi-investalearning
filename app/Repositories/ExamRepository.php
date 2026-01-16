<?php

namespace App\Repositories;

use App\Models\Exams;
use App\Models\MappingQuestions;
use App\Models\Package;
use App\Models\TransQuestions;
use App\Models\DetailResults;
use App\Models\User;
use App\Repositories\Contracts\ExamRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ExamRepository implements ExamRepositoryInterface
{
    public function userJoinedPackage(User $user, Package $package): bool
    {
        return \App\Models\UserJoins::where('user_id', $user->id)
            ->where('id_package', $package->id)
            ->exists();
    }

    public function countQuestions(Exams $exam): int
    {
        return MappingQuestions::where('id_exam', $exam->id)->count();
    }

    public function getQuestionsPage(Exams $exam, int $page = 1, int $perPage = 1): LengthAwarePaginator
    {
        return MappingQuestions::where('id_exam', $exam->id)
            ->with('questionBank.type')
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function getAllMappingsWithQuestions(Exams $exam): Collection
    {
        return MappingQuestions::where('id_exam', $exam->id)
            ->with('questionBank')
            ->orderBy('id')
            ->get();
    }

    public function createTransQuestion(
        User $user,
        Package $package,
        Exams $exam,
        int $questionsAnswered,
        int $totalQuestions
    ): TransQuestions {
        return TransQuestions::create([
            'id_user' => $user->id,
            'id_package' => $package->id,
            'user_id' => $user->id,
            'id_question' => $exam->id,
            'questions_answered' => $questionsAnswered,
            'total_questions' => $totalQuestions,
            'total_score' => 0,
            'status' => 'tidak lulus',
        ]);
    }

    public function updateTransQuestionResult(TransQuestions $trans, float $score, string $status): void
    {
        $trans->update([
            'total_score' => $score,
            'status' => $status,
        ]);
    }

    public function createDetailResult(
        TransQuestions $trans,
        int $questionId,
        ?string $userAnswer,
        ?string $correctAnswer,
        float $scoreObtained
    ): void {
        DetailResults::create([
            'id_trans_question' => $trans->id,
            'id_question' => $questionId,
            'user_answer' => $userAnswer ?? '-',
            'correct_answer' => $correctAnswer ?? '-',
            'score_obtained' => round($scoreObtained, 2),
        ]);
    }
}



