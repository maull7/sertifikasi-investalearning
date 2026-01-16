<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Exams;
use App\Models\Package;
use App\Services\ExamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function __construct(
        protected ExamService $examService
    ) {
    }

    public function show(Package $package, Exams $exam): View
    {
        $user = Auth::user();
        $this->examService->ensureUserCanAccessExam($user, $package, $exam);

        $totalQuestions = $this->examService->getTotalQuestions($exam);

        return view('user.exams.show', compact('package', 'exam', 'totalQuestions'));
    }

    public function submit(Request $request, Package $package, Exams $exam): JsonResponse
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

    public function getQuestions(Request $request, Package $package, Exams $exam): JsonResponse
    {
        $user = Auth::user();
        $page = (int) $request->get('page', 1);
        $perPage = 1;

        $mappingQuestions = $this->examService->getQuestionPage($user, $package, $exam, $page, $perPage);

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
                'type' => $question->type ? $question->type->name_type : null,
            ];
        });

        return response()->json([
            'questions' => $questions,
            'current_page' => $mappingQuestions->currentPage(),
            'last_page' => $mappingQuestions->lastPage(),
            'total' => $mappingQuestions->total(),
            'has_more' => $mappingQuestions->hasMorePages(),
        ]);
    }
}

