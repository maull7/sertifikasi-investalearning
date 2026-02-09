<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DetailResult;
use App\Models\Package;
use App\Models\Quiz;
use App\Models\TransQuestion;
use App\Services\QuizService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function __construct(
        protected QuizService $quizService
    ) {}

    public function show(Package $package, Quiz $quiz): View
    {
        $user = Auth::user();
        $this->quizService->ensureUserCanAccessQuiz($user, $package, $quiz);

        $totalQuestions = $this->quizService->getTotalQuestions($quiz);

        return view('user.quizzes.show', compact('package', 'quiz', 'totalQuestions'));
    }

    public function getQuestions(Request $request, Package $package, Quiz $quiz): JsonResponse
    {
        $user = Auth::user();
        $page = (int) $request->get('page', 1);
        $perPage = 1;

        $mappingQuestions = $this->quizService->getQuestionPage($user, $package, $quiz, $page, $perPage);

        $timer = $this->quizService->getQuizTimer($user, $package, $quiz);

        $questions = $mappingQuestions->map(function ($mapping) {
            $question = $mapping->questionBank;

            return [
                'id' => $question->id,
                'mapping_id' => $mapping->id,
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
        });

        $payload = [
            'questions' => $questions,
            'current_page' => $mappingQuestions->currentPage(),
            'last_page' => $mappingQuestions->lastPage(),
            'total' => $mappingQuestions->total(),
            'has_more' => $mappingQuestions->hasMorePages(),
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

            if ($result['show_solutions'] ?? false) {
                return response()->json([
                    'redirect' => route('user.quizzes.review', [
                        'package' => $package->id,
                        'quiz' => $quiz->id,
                        'trans' => $result['trans_question_id'],
                    ]),
                ]);
            }

            return response()->json([
                'redirect' => route('user.my-packages.show', $package->id),
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'error' => 'Terjadi kesalahan saat menyimpan hasil kuis.',
            ], 500);
        }
    }

    public function review(Request $request, Package $package, Quiz $quiz, TransQuestion $trans): View
    {
        $user = Auth::user();

        if ($trans->id_user !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke hasil kuis ini.');
        }

        if ($trans->id_package !== $package->id || $trans->id_quiz !== $quiz->id) {
            abort(404, 'Hasil kuis tidak ditemukan.');
        }

        $detailResults = DetailResult::with('Question.subject')
            ->where('id_trans_question', $trans->id)
            ->orderBy('id')
            ->paginate(1)
            ->withQueryString();

        return view('user.quizzes.review', compact('package', 'quiz', 'trans', 'detailResults'));
    }
}
