<?php

namespace App\Repositories\Contracts;

use App\Models\Exams;
use App\Models\MappingQuestions;
use App\Models\Package;
use App\Models\TransQuestions;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ExamRepositoryInterface
{
    public function userJoinedPackage(User $user, Package $package): bool;

    public function countQuestions(Exams $exam): int;

    public function getQuestionsPage(Exams $exam, int $page = 1, int $perPage = 1): LengthAwarePaginator;

    /**
     * @return Collection<int, MappingQuestions>
     */
    public function getAllMappingsWithQuestions(Exams $exam): Collection;

    public function createTransQuestion(User $user, Package $package, Exams $exam, int $questionsAnswered, int $totalQuestions): TransQuestions;

    public function updateTransQuestionResult(TransQuestions $trans, float $score, string $status): void;

    public function createDetailResult(TransQuestions $trans, int $questionId, ?string $userAnswer, ?string $correctAnswer, float $scoreObtained): void;
}





