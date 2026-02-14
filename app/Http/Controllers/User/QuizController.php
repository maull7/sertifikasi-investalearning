<?php

namespace App\Http\Controllers\User;

use App\Models\Quiz;
use App\Models\Package;
use App\Models\Subject;
use Illuminate\View\View;
use App\Models\DetailResultQuiz;
use App\Models\TransQuiz;
use Illuminate\Http\Request;
use App\Services\QuizService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function __construct(
        protected QuizService $quizService
    ) {}

    public function show(Package $package, Quiz $quiz, Subject $subject): View
    {
        $user = Auth::user();
        $this->quizService->ensureUserCanAccessQuiz($user, $package, $quiz);
        $totalQuestions = (int) $quiz->total_questions;
        return view('user.quizzes.show', compact('package', 'quiz', 'totalQuestions', 'subject'));
    }

    public function getQuestions(Request $request, Package $package, Quiz $quiz): JsonResponse
    {
        $user = Auth::user();
        $page = (int) $request->get('page', 1);
        $perPage = 1;

        $questionPage = $this->quizService->getQuestionPage($user, $package, $quiz, $page, $perPage);

        $timer = $this->quizService->getQuizTimer($user, $package, $quiz);

        $questions = collect($questionPage->items())->map(function ($question) {
            return [
                'id' => $question->id,
                'question_type' => $question->question_type,
                'question' => $question->question,
                'question_image_url' => $question->question_type === 'Image'
                    ? (str_starts_with($question->question, 'http')
                        ? $question->question
                        : asset('storage/' . ltrim($question->question, '/')))
                    : null,
                'option_a' => $question->option_a,
                'option_b' => $question->option_b,
                'option_c' => $question->option_c,
                'option_d' => $question->option_d,
                'option_e' => $question->option_e,
                'show_solutions' => false,
                'correct_answer' => null,
                'explanation' => null,
                'solution' => null,
            ];
        })->all();

        $payload = [
            'questions' => $questions,
            'current_page' => $questionPage->currentPage(),
            'last_page' => $questionPage->lastPage(),
            'total' => $questionPage->total(),
            'has_more' => $questionPage->hasMorePages(),
        ];

        if ($timer !== null) {
            $payload['timer'] = $timer;
        }

        return response()->json($payload);
    }

    /**
     * Soal kuis dari bank soal (quiz.subject_id), random per attempt.
     * Request pertama (page=1) mengembalikan semua soal sekaligus agar navigasi next/prev tidak ganti set soal.
     */
    public function getQuestionsWithSubject(Request $request, Package $package, Quiz $quiz): JsonResponse
    {
        $user = Auth::user();
        $page = (int) $request->get('page', 1);
        $attempt = $this->quizService->getOrCreateQuizAttempt($user, $package, $quiz);
        $totalQuestions = count($attempt->question_ids ?? []);
        $perPage = ($page === 1 && $totalQuestions > 0) ? $totalQuestions : 1;

        $questionPage = $this->quizService->getQuestionsWithSubject($user, $package, $quiz, $page, $perPage);

        $timer = $this->quizService->getQuizTimer($user, $package, $quiz);

        $questions = collect($questionPage->items())->map(function ($question) {
            return [
                'id' => $question->id,
                'question_type' => $question->question_type,
                'question' => $question->question,
                'question_image_url' => $question->question_type === 'Image'
                    ? (str_starts_with($question->question, 'http')
                        ? $question->question
                        : asset('storage/' . ltrim($question->question, '/')))
                    : null,
                'option_a' => $question->option_a,
                'option_b' => $question->option_b,
                'option_c' => $question->option_c,
                'option_d' => $question->option_d,
                'option_e' => $question->option_e,
                'show_solutions' => false,
                'correct_answer' => null,
                'explanation' => null,
                'solution' => null,
            ];
        })->all();

        $payload = [
            'questions' => $questions,
            'current_page' => $questionPage->currentPage(),
            'last_page' => $questionPage->lastPage(),
            'total' => $questionPage->total(),
            'has_more' => $questionPage->hasMorePages(),
        ];

        if ($timer !== null) {
            $payload['timer'] = $timer;
        }

        return response()->json($payload);
    }


    public function submit(Request $request, Package $package, Quiz $quiz): JsonResponse
    {
        $user = Auth::user();

        try {
            $answers = $request->input('answers', []);
            $result = $this->quizService->submitQuiz($user, $package, $quiz, $answers);

            session()->flash('quiz_result', [
                'score' => $result['score'],
                'correct' => $result['correct'],
                'total' => $result['total'],
                'status' => $result['status'],
                'quiz_title' => $quiz->title,
            ]);

            return response()->json([
                'redirect' => route('user.quizzes.review', [
                    'package' => $package->id,
                    'quiz' => $quiz->id,
                    'transQuiz' => $result['trans_quiz_id'],
                ]),
                'score' => $result['score'],
                'correct' => $result['correct'],
                'total' => $result['total'],
                'status' => $result['status'],
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'error' => 'Terjadi kesalahan saat menyimpan hasil kuis.',
            ], 500);
        }
    }

    public function review(Request $request, Package $package, Quiz $quiz, TransQuiz $transQuiz): View
    {
        $user = Auth::user();

        if ($transQuiz->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke hasil kuis ini.');
        }

        if ($transQuiz->quiz_id !== $quiz->id) {
            abort(404, 'Hasil kuis tidak ditemukan.');
        }

        $detailResults = DetailResultQuiz::with('question.subject')
            ->where('id_trans_quiz', $transQuiz->id)
            ->orderBy('id')
            ->paginate(1)
            ->withQueryString();

        return view('user.quizzes.review', compact('package', 'quiz', 'transQuiz', 'detailResults'));
    }
}
