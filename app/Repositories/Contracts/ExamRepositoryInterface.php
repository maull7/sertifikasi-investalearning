<?php

namespace App\Repositories\Contracts;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Package;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\TransQuestion;
use App\Models\TransQuiz;
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

    /**
     * Soal untuk kuis diambil dari bank soal (by quiz subject_id), random per attempt.
     */
    public function getQuestionsPageForQuizAttempt(QuizAttempt $attempt, int $page = 1, int $perPage = 1): LengthAwarePaginator;

    /**
     * Soal untuk submit kuis (urutan sama dengan attempt).
     * @return \Illuminate\Support\Collection<int, object{questionBank: \App\Models\BankQuestion}>
     */
    public function getQuestionsForQuizAttempt(QuizAttempt $attempt): Collection;

    public function getOrCreateQuizAttempt(User $user, Package $package, Quiz $quiz): QuizAttempt;

    public function getQuizAttempt(User $user, Package $package, Quiz $quiz): ?QuizAttempt;

    public function deleteQuizAttempt(User $user, Package $package, Quiz $quiz): void;

    public function createTransQuestionForQuiz(User $user, Package $package, Quiz $quiz, int $questionsAnswered, int $totalQuestions): TransQuestion;

    /** Kuis: satu record per user per quiz, update or create. */
    public function updateOrCreateTransQuiz(User $user, Package $package, Quiz $quiz, int $questionsAnswered, int $totalQuestions, float $totalScore, string $status): TransQuiz;

    public function deleteDetailResultQuizByTransQuiz(TransQuiz $transQuiz): void;

    public function createDetailResultQuiz(TransQuiz $transQuiz, int $questionId, ?string $userAnswer, ?string $correctAnswer, float $scoreObtained): void;
}
