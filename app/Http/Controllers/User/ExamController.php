<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DetailResult;
use App\Models\Exam;
use App\Models\Package;
use App\Models\TransQuestion;
use App\Services\ExamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function __construct(
        protected ExamService $examService
    ) {}

    public function show(Package $package, Exam $exam): View
    {
        $user = Auth::user();
        $this->examService->ensureUserCanAccessExam($user, $package, $exam);

        $totalQuestions = $this->examService->getTotalQuestions($exam);

        return view('user.exams.show', compact('package', 'exam', 'totalQuestions'));
    }

    public function submit(Request $request, Package $package, Exam $exam): JsonResponse
    {
        $user = Auth::user();

        try {
            $answers = $request->input('answers', []);
            $result = $this->examService->submitExam($user, $package, $exam, $answers);

            session()->flash('exam_result', [
                'score' => $result['score'],
                'correct' => $result['correct'],
                'total' => $result['total'],
                'status' => $result['status'],
                'exam_title' => $exam->title,
            ]);

            $attemptsUrl = route('user.exams.attempts', ['package' => $package->id, 'exam' => $exam->id]);
            $backUrl = route('user.my-packages.show', $package->id);

            return response()->json([
                'score' => $result['score'],
                'correct' => $result['correct'],
                'total' => $result['total'],
                'status' => $result['status'],
                'exam_title' => $exam->title,
                'attempts_url' => $attemptsUrl,
                'back_url' => $backUrl,
                'show_choices' => (bool) ($exam->show_result_after ?? true),
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'error' => 'Terjadi kesalahan saat menyimpan hasil ujian.',
            ], 500);
        }
    }

    public function getQuestions(Request $request, Package $package, Exam $exam): JsonResponse
    {
        $user = Auth::user();
        $page = (int) $request->get('page', 1);
        $perPage = 1;

        $questionPage = $this->examService->getQuestionPage($user, $package, $exam, $page, $perPage);

        $timer = $this->examService->getExamTimer($user, $package, $exam);

        $questions = collect($questionPage->items())->map(function ($item) {
            $question = isset($item->questionBank) ? $item->questionBank : $item;

            return [
                'id' => $question->id,
                'question_type' => $question->question_type,
                'question' => $question->question,
                'question_image_url' => $question->question_type === 'Image'
                    ? (str_starts_with($question->question, 'http')
                        ? $question->question
                        : asset('storage/'.ltrim($question->question, '/')))
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

    public function review(Request $request, Package $package, Exam $exam, TransQuestion $trans): View
    {
        $user = Auth::user();

        if ($trans->id_user !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke hasil ujian ini.');
        }

        if ($trans->id_package !== $package->id || $trans->id_exam !== $exam->id) {
            abort(404, 'Hasil ujian tidak ditemukan.');
        }

        $detailResults = DetailResult::with('Question.subject')
            ->where('id_trans_question', $trans->id)
            ->orderBy('id')
            ->paginate(1)
            ->withQueryString();

        return view('user.exams.review', compact('package', 'exam', 'trans', 'detailResults'));
    }

    public function result(Package $package, Exam $exam, TransQuestion $trans): View
    {
        $user = Auth::user();

        if ($trans->id_user !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke hasil ujian ini.');
        }

        if ($trans->id_package !== $package->id || $trans->id_exam !== $exam->id) {
            abort(404, 'Hasil ujian tidak ditemukan.');
        }

        return view('user.exams.result', compact('package', 'exam', 'trans'));
    }

    public function attempts(Package $package, Exam $exam): View
    {
        $user = Auth::user();
        $this->examService->ensureUserCanAccessExam($user, $package, $exam);

        $attempts = TransQuestion::where('id_user', $user->id)
            ->where('id_package', $package->id)
            ->where('id_exam', $exam->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.exams.attempts', compact('package', 'exam', 'attempts'));
    }
}
