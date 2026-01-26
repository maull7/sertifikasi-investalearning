<?php

namespace App\Repositories\Contracts;

use App\Models\Exam;
use App\Models\MappingQuestion;
use App\Models\Package;
use App\Models\TransQuestion;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ExamRepositoryInterface
{
    public function userJoinedPackage(User $user, Package $package): bool;

    public function countQuestions(Exam $exam): int;

    public function getQuestionsPage(Exam $exam, int $page = 1, int $perPage = 1): LengthAwarePaginator;

    /**
     * @return Collection<int, MappingQuestions>
     */
    public function getAllMappingsWithQuestions(Exam $exam): Collection;

    public function createTransQuestion(User $user, Package $package, Exam $exam, int $questionsAnswered, int $totalQuestions): TransQuestion;

    public function updateTransQuestionResult(TransQuestion $trans, float $score, string $status): void;

    public function createDetailResult(TransQuestion $trans, int $questionId, ?string $userAnswer, ?string $correctAnswer, float $scoreObtained): void;
}



