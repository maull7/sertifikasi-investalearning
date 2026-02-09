<?php

namespace App\Repositories;

use App\Models\DetailResult;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\MappingQuestion;
use App\Models\Package;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\TransQuestion;
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
            ->with('questionBank.subject')
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

    public function getOrCreateExamAttempt(User $user, Package $package, Exam $exam): ExamAttempt
    {
        return ExamAttempt::firstOrCreate(
            [
                'user_id' => $user->id,
                'package_id' => $package->id,
                'exam_id' => $exam->id,
            ],
            ['started_at' => now()]
        );
    }

    public function deleteExamAttempt(User $user, Package $package, Exam $exam): void
    {
        ExamAttempt::where('user_id', $user->id)
            ->where('package_id', $package->id)
            ->where('exam_id', $exam->id)
            ->delete();
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
            'id_exam' => $exam->id,
            'id_quiz' => null,
            'questions_answered' => $questionsAnswered,
            'total_questions' => $totalQuestions,
            'total_score' => 0,
            'status' => 'tidak lulus',
        ]);
    }

    public function countQuestionsForQuiz(Quiz $quiz): int
    {
        return MappingQuestion::where('id_quiz', $quiz->id)->count();
    }

    public function getQuestionsPageForQuiz(Quiz $quiz, int $page = 1, int $perPage = 1): LengthAwarePaginator
    {
        return MappingQuestion::where('id_quiz', $quiz->id)
            ->with('questionBank.subject')
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function getAllMappingsWithQuestionsForQuiz(Quiz $quiz): Collection
    {
        return MappingQuestion::where('id_quiz', $quiz->id)
            ->with('questionBank')
            ->orderBy('id')
            ->get();
    }

    public function getOrCreateQuizAttempt(User $user, Package $package, Quiz $quiz): QuizAttempt
    {
        return QuizAttempt::firstOrCreate(
            [
                'user_id' => $user->id,
                'package_id' => $package->id,
                'quiz_id' => $quiz->id,
            ],
            ['started_at' => now()]
        );
    }

    public function deleteQuizAttempt(User $user, Package $package, Quiz $quiz): void
    {
        QuizAttempt::where('user_id', $user->id)
            ->where('package_id', $package->id)
            ->where('quiz_id', $quiz->id)
            ->delete();
    }

    public function createTransQuestionForQuiz(
        User $user,
        Package $package,
        Quiz $quiz,
        int $questionsAnswered,
        int $totalQuestions
    ): TransQuestion {
        return TransQuestion::create([
            'id_user' => $user->id,
            'id_package' => $package->id,
            'id_exam' => null,
            'id_quiz' => $quiz->id,
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
