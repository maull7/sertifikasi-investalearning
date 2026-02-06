<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailResult;
use App\Models\Exam;
use App\Models\Package;
use App\Models\Quiz;
use App\Models\TransQuestion;
use Illuminate\Http\Request;

class ShowGradeController extends Controller
{
    public function index(Request $request)
    {
        $exams = Exam::all();
        $quizzes = Quiz::all();
        $packages = Package::all();

        $packageId = $request->get('package_id');
        $examId = $request->get('exam_id');
        $quizId = $request->get('quiz_id');
        $type = $request->get('type', '');

        $list = TransQuestion::with(['User', 'Package', 'Exam', 'Quiz'])
            ->when($packageId, function ($query) use ($packageId) {
                $query->where('id_package', $packageId);
            })
            ->when($type === 'kuis' && $quizId, function ($query) use ($quizId) {
                $query->where('id_quiz', $quizId);
            })
            ->when(($type === 'ujian' || $type === '') && $examId, function ($query) use ($examId) {
                $query->where('id_exam', $examId);
            })
            ->when($type === 'ujian', function ($query) {
                $query->whereNotNull('id_exam');
            })
            ->when($type === 'kuis', function ($query) {
                $query->whereNotNull('id_quiz');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $chartData = collect();
        if ($packageId || $examId || $quizId || $type) {
            $chartData = TransQuestion::with('User')
                ->when($packageId, function ($query) use ($packageId) {
                    $query->where('id_package', $packageId);
                })
                ->when($type === 'kuis' && $quizId, function ($query) use ($quizId) {
                    $query->where('id_quiz', $quizId);
                })
                ->when(($type === 'ujian' || $type === '') && $examId, function ($query) use ($examId) {
                    $query->where('id_exam', $examId);
                })
                ->when($type === 'ujian', function ($query) {
                    $query->whereNotNull('id_exam');
                })
                ->when($type === 'kuis', function ($query) {
                    $query->whereNotNull('id_quiz');
                })
                ->orderByDesc('total_score')
                ->limit(10)
                ->get(['id_user', 'total_score']);
        }

        return view('admin.show-grade.index', compact('list', 'exams', 'quizzes', 'packages', 'packageId', 'examId', 'quizId', 'type', 'chartData'));
    }

    public function detail($id)
    {
        $historyDetail = DetailResult::with('Question', 'TransQuestion')
            ->where('id_trans_question', $id)
            ->paginate(10);

        return view('admin.show-grade.detail', compact('historyDetail'));
    }
}
