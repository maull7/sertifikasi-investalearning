<?php

namespace App\Repositories\Contracts;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Package;
use App\Models\Quiz;
use App\Models\QuizAttempt;
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
     * @return \Illuminate\Support\Collection<int, \App\Models\MappingQuestion>
     */
    public function getAllMappingsWithQuestions(Exam $exam): Collection;

    public function getOrCreateExamAttempt(User $user, Package $package, Exam $exam): ExamAttempt;

    public function deleteExamAttempt(User $user, Package $package, Exam $exam): void;

    public function createTransQuestion(User $user, Package $package, Exam $exam, int $questionsAnswered, int $totalQuestions): TransQuestion;

    public function updateTransQuestionResult(TransQuestion $trans, float $score, string $status): void;

    public function createDetailResult(TransQuestion $trans, int $questionId, ?string $userAnswer, ?string $correctAnswer, float $scoreObtained): void;

    public function countQuestionsForQuiz(Quiz $quiz): int;

    public function getQuestionsPageForQuiz(Quiz $quiz, int $page = 1, int $perPage = 1): LengthAwarePaginator;

    /**
     * @return \Illuminate\Support\Collection<int, \App\Models\MappingQuestion>
     */
    public function getAllMappingsWithQuestionsForQuiz(Quiz $quiz): Collection;

    public function getOrCreateQuizAttempt(User $user, Package $package, Quiz $quiz): QuizAttempt;

    public function deleteQuizAttempt(User $user, Package $package, Quiz $quiz): void;

    public function createTransQuestionForQuiz(User $user, Package $package, Quiz $quiz, int $questionsAnswered, int $totalQuestions): TransQuestion;
}

