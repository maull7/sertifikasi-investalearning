<?php

namespace App\Http\Controllers\User;

use App\Models\Exam;
use App\Models\Package;
use App\Models\Quiz;
use App\Models\DetailResult;
use Illuminate\Http\Request;
use App\Models\TransQuestion;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class HistoryExamController extends Controller
{
    public function index(Request $request)
    {
        $packages = Package::all();
        $exams = Exam::all();
        $quizzes = Quiz::all();
        $packageId = $request->get('package_id');
        $examId = $request->get('exam_id');
        $quizId = $request->get('quiz_id');
        $type = $request->get('type', '');

        $history = TransQuestion::with(['Package', 'Exam', 'Quiz'])
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
            ->where('id_user', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('user.history.index', compact('history', 'packages', 'exams', 'quizzes', 'packageId', 'examId', 'quizId', 'type'));
    }
    public function detail($id)
    {
        $historyDetail = DetailResult::with('Question', 'TransQuestion')
            ->where('id_trans_question', $id)
            ->paginate(10);
        return view('user.history.detail', compact('historyDetail'));
    }
}
