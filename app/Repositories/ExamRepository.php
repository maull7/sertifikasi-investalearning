<?php

namespace App\Repositories;

use App\Models\Exam;
use App\Models\MappingQuestion;
use App\Models\Package;
use App\Models\TransQuestion;
use App\Models\DetailResult;
use App\Models\User;
use App\Repositories\Contracts\ExamRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ExamRepository implements ExamRepositoryInterface
{
    public function userJoinedPackage(User $user, Package $package): bool
    {
        return \App\Models\UserJoin::where('user_id', $user->id)
            ->where('id_package', $package->id)
            ->exists();
    }

    public function countQuestions(Exam $exam): int
    {
        return MappingQuestion::where('id_exam', $exam->id)->count();
    }

    public function getQuestionsPage(Exam $exam, int $page = 1, int $perPage = 1): LengthAwarePaginator
    {
        return MappingQuestion::where('id_exam', $exam->id)
            ->with('questionBank.type')
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function getAllMappingsWithQuestions(Exam $exam): Collection
    {
        return MappingQuestion::where('id_exam', $exam->id)
            ->with('questionBank')
            ->orderBy('id')
            ->get();
    }

    public function createTransQuestion(
        User $user,
        Package $package,
        Exam $exam,
        int $questionsAnswered,
        int $totalQuestions
    ): TransQuestion {
        return TransQuestion::create([
            'id_user' => $user->id,
            'id_package' => $package->id,
            'user_id' => $user->id,
            'id_exam' => $exam->id,
            'id_type' => $package->id_master_types,
            'questions_answered' => $questionsAnswered,
            'total_questions' => $totalQuestions,
            'total_score' => 0,
            'status' => 'tidak lulus',
        ]);
    }

    public function updateTransQuestionResult(TransQuestion $trans, float $score, string $status): void
    {
        $trans->update([
            'total_score' => $score,
            'status' => $status,
        ]);
    }

    public function createDetailResult(
        TransQuestion $trans,
        int $questionId,
        ?string $userAnswer,
        ?string $correctAnswer,
        float $scoreObtained
    ): void {
        DetailResult::create([
            'id_trans_question' => $trans->id,
            'id_question' => $questionId,
            'user_answer' => $userAnswer ?? '-',
            'correct_answer' => $correctAnswer ?? '-',
            'score_obtained' => round($scoreObtained, 2),
        ]);
    }
}
