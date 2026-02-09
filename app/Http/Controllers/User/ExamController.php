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

            // Jika sudah 3 kali mengerjakan, redirect ke halaman review dengan kunci jawaban
            if ($result['show_solutions'] ?? false) {
                return response()->json([
                    'redirect' => route('user.exams.review', [
                        'package' => $package->id,
                        'exam' => $exam->id,
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
                'error' => 'Terjadi kesalahan saat menyimpan hasil ujian.',
            ], 500);
        }
    }

    public function getQuestions(Request $request, Package $package, Exam $exam): JsonResponse
    {
        $user = Auth::user();
        $page = (int) $request->get('page', 1);
        $perPage = 1;

        $mappingQuestions = $this->examService->getQuestionPage($user, $package, $exam, $page, $perPage);

        $timer = $this->examService->getExamTimer($user, $package, $exam);

        // Jangan kirim kunci jawaban saat mengerjakan ujian (meskipun sudah 3x)
        // Kunci jawaban hanya muncul di halaman review setelah submit
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
                // Tidak kirim kunci jawaban saat mengerjakan ujian
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

    public function review(Request $request, Package $package, Exam $exam, TransQuestion $trans): View
    {
        $user = Auth::user();

        // Pastikan trans_question milik user yang sedang login
        if ($trans->id_user !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke hasil ujian ini.');
        }

        // Pastikan trans_question sesuai dengan package dan exam
        if ($trans->id_package !== $package->id || $trans->id_exam !== $exam->id) {
            abort(404, 'Hasil ujian tidak ditemukan.');
        }

        // Ambil detail hasil dengan pagination (1 soal per halaman)
        $detailResults = DetailResult::with('Question.subject')
            ->where('id_trans_question', $trans->id)
            ->orderBy('id')
            ->paginate(1)
            ->withQueryString();

        return view('user.exams.review', compact('package', 'exam', 'trans', 'detailResults'));
    }
}
