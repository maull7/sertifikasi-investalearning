<?php

namespace App\Repositories;

use App\Models\BankQuestion;
use App\Models\Exam;
use App\Models\Quiz;
use App\Models\User;
use App\Models\Package;
use App\Models\ExamAttempt;
use App\Models\QuizAttempt;
use App\Models\DetailResult;
use App\Models\TransQuestion;
use App\Models\TransQuiz;
use App\Models\DetailResultQuiz;
use App\Models\MappingQuestion;
use Illuminate\Support\Collection;
use App\Repositories\Contracts\ExamRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
        if ($this->isPretest($exam)) {
            return (int) $exam->total_questions;
        }
        return MappingQuestion::where('id_exam', $exam->id)->count();
    }

    public function getQuestionsPage(Exam $exam, int $page = 1, int $perPage = 1): LengthAwarePaginator
    {
        return MappingQuestion::where('id_exam', $exam->id)
            ->with('questionBank.subject')
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    private function isPretest(Exam $exam): bool
    {
        return strtolower((string) ($exam->type ?? '')) === 'pretest';
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
        $attempt = ExamAttempt::firstOrCreate(
            [
                'user_id' => $user->id,
                'package_id' => $package->id,
                'exam_id' => $exam->id,
            ],
            ['started_at' => now()]
        );

        if ($this->isPretest($exam)) {
            $ids = $attempt->question_ids ?? [];

            if (empty($ids)) {
                $package->loadMissing(['masterType.subjects']);

                $subjectIds = $exam->subject_id
                    ? collect([$exam->subject_id])
                    : $package->masterType?->subjects?->pluck('id') ?? collect();

                $limit = (int) max(1, $exam->total_questions);

                $query = BankQuestion::query();

                if ($subjectIds->count() === 1) {
                    $query->where('subject_id', $subjectIds->first());
                } else {
                    $query->whereIn('subject_id', $subjectIds);
                }

                $ids = $query
                    ->inRandomOrder()
                    ->limit($limit)
                    ->pluck('id')
                    ->values()
                    ->all();

                $attempt->update(['question_ids' => $ids]);
            }
        }

        return $attempt->fresh();
    }

    public function getExamAttempt(User $user, Package $package, Exam $exam): ?ExamAttempt
    {
        return ExamAttempt::where('user_id', $user->id)
            ->where('package_id', $package->id)
            ->where('exam_id', $exam->id)
            ->first();
    }

    public function getQuestionsPageForExamAttempt(ExamAttempt $attempt, int $page = 1, int $perPage = 1): LengthAwarePaginator
    {
        $ids = $attempt->question_ids ?? [];
        $total = count($ids);
        if ($total === 0) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage, $page);
        }
        $offset = ($page - 1) * $perPage;
        $chunk = array_slice($ids, $offset, $perPage);
        $questions = BankQuestion::whereIn('id', $chunk)->get()->keyBy('id');
        $ordered = collect($chunk)->map(fn($id) => $questions->get($id))->filter()->values();
        return new \Illuminate\Pagination\LengthAwarePaginator($ordered, $total, $perPage, $page);
    }

    /**
     * @return \Illuminate\Support\Collection<int, object{questionBank: \App\Models\BankQuestion}>
     */
    public function getQuestionsForExamAttempt(ExamAttempt $attempt): Collection
    {
        $ids = $attempt->question_ids ?? [];
        if (empty($ids)) {
            return collect();
        }
        $questions = BankQuestion::whereIn('id', $ids)->get()->keyBy('id');
        return collect($ids)->map(function ($id) use ($questions) {
            $q = $questions->get($id);
            return $q ? (object) ['questionBank' => $q] : null;
        })->filter()->values();
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
            'questions_answered' => $questionsAnswered,
            'total_questions' => $totalQuestions,
            'total_score' => 0,
            'status' => 'tidak lulus',
        ]);
    }

    public function countQuestionsForQuiz(Quiz $quiz): int
    {
        return (int) $quiz->total_questions;
    }

    public function getQuestionsPageForQuizAttempt(QuizAttempt $attempt, int $page = 1, int $perPage = 1): LengthAwarePaginator
    {
        $ids = $attempt->question_ids ?? [];
        $total = count($ids);
        if ($total === 0) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage, $page);
        }
        $offset = ($page - 1) * $perPage;
        $chunk = array_slice($ids, $offset, $perPage);
        $questions = BankQuestion::whereIn('id', $chunk)->get()->keyBy('id');
        $ordered = collect($chunk)->map(fn($id) => $questions->get($id))->filter()->values();
        return new \Illuminate\Pagination\LengthAwarePaginator($ordered, $total, $perPage, $page);
    }

    /**
     * @return \Illuminate\Support\Collection<int, object{questionBank: \App\Models\BankQuestion}>
     */
    public function getQuestionsForQuizAttempt(QuizAttempt $attempt): Collection
    {
        $ids = $attempt->question_ids ?? [];
        if (empty($ids)) {
            return collect();
        }
        $questions = BankQuestion::whereIn('id', $ids)->get()->keyBy('id');
        return collect($ids)->map(function ($id) use ($questions) {
            $q = $questions->get($id);
            return $q ? (object) ['questionBank' => $q] : null;
        })->filter()->values();
    }

    public function getOrCreateQuizAttempt(User $user, Package $package, Quiz $quiz): QuizAttempt
    {
        $quiz->load('subject');
        $attempt = QuizAttempt::firstOrCreate(
            [
                'user_id' => $user->id,
                'package_id' => $package->id,
                'quiz_id' => $quiz->id,
            ],
            ['started_at' => now()]
        );

        $ids = $attempt->question_ids ?? [];
        if (empty($ids) && $quiz->subject_id) {
            $ids = $this->pickRandomQuizQuestionIds($user, $quiz);
            $attempt->update(['question_ids' => $ids]);
        }

        return $attempt->fresh();
    }

    /**
     * Ambil ID soal acak dari bank untuk kuis: prioritaskan soal yang belum pernah
     * dikerjakan user untuk kuis ini; kalau sudah habis, cycle dari full bank.
     */
    private function pickRandomQuizQuestionIds(User $user, Quiz $quiz): array
    {
        $limit = (int) max(1, $quiz->total_questions);

        $usedQuestionIds = DetailResultQuiz::whereIn('id_trans_quiz', function ($q) use ($user, $quiz) {
            $q->select('id')
                ->from('trans_quiz')
                ->where('user_id', $user->id)
                ->where('quiz_id', $quiz->id);
        })->pluck('id_question')->unique()->values()->all();

        $bankIds = BankQuestion::where('subject_id', $quiz->subject_id)->pluck('id')->all();
        $availableIds = array_values(array_diff($bankIds, $usedQuestionIds));

        if (count($availableIds) >= $limit) {
            $pick = collect($availableIds)->random($limit)->values()->all();
            shuffle($pick);
            return $pick;
        }

        if (empty($bankIds)) {
            return [];
        }

        return BankQuestion::where('subject_id', $quiz->subject_id)
            ->inRandomOrder()
            ->limit($limit)
            ->pluck('id')
            ->values()
            ->all();
    }

    public function getQuizAttempt(User $user, Package $package, Quiz $quiz): ?QuizAttempt
    {
        return QuizAttempt::where('user_id', $user->id)
            ->where('package_id', $package->id)
            ->where('quiz_id', $quiz->id)
            ->first();
    }

    public function deleteQuizAttempt(User $user, Package $package, Quiz $quiz): void
    {
        QuizAttempt::where('user_id', $user->id)
            ->where('package_id', $package->id)
            ->where('quiz_id', $quiz->id)
            ->delete();
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

    public function updateOrCreateTransQuiz(
        User $user,
        Package $package,
        Quiz $quiz,
        int $questionsAnswered,
        int $totalQuestions,
        float $totalScore,
        string $status
    ): TransQuiz {
        $trans = TransQuiz::firstOrCreate(
            [
                'user_id' => $user->id,
                'quiz_id' => $quiz->id,
            ],
            [
                'package_id' => $package->id,
                'questions_answered' => 0,
                'total_questions' => 0,
                'total_score' => 0,
                'status' => 'tidak lulus',
                'attempted_count' => 1,
            ]
        );

        $trans->update([
            'package_id' => $package->id,
            'questions_answered' => $questionsAnswered,
            'total_questions' => $totalQuestions,
            'total_score' => $totalScore,
            'status' => $status,
            'attempted_count' => $trans->wasRecentlyCreated ? 1 : $trans->attempted_count + 1,
        ]);

        return $trans->fresh();
    }

    public function deleteDetailResultQuizByTransQuiz(TransQuiz $transQuiz): void
    {
        DetailResultQuiz::where('id_trans_quiz', $transQuiz->id)->delete();
    }

    public function createDetailResultQuiz(
        TransQuiz $transQuiz,
        int $questionId,
        ?string $userAnswer,
        ?string $correctAnswer,
        float $scoreObtained
    ): void {
        DetailResultQuiz::create([
            'id_trans_quiz' => $transQuiz->id,
            'id_question' => $questionId,
            'user_answer' => $userAnswer ?? '-',
            'correct_answer' => $correctAnswer ?? '-',
            'score_obtained' => round($scoreObtained, 2),
        ]);
    }
}
