<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DetailResult;
use App\Models\Exam;
use App\Models\Package;
use App\Models\TransQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryExamController extends Controller
{
    public function index(Request $request)
    {
        $packages = Package::select('id', 'title')->orderBy('title')->get();
        $exams = Exam::select('id', 'title', 'type')->orderBy('title')->get();
        $packageId = $request->get('package_id');
        $examId = $request->get('exam_id');
        $examType = $request->get('exam_type', 'posttest'); // tab: pretest | posttest

        $history = TransQuestion::with(['package:id,title,id_master_types', 'package.masterType:id,name_type', 'exam:id,title,type'])
            ->whereNotNull('id_exam')
            ->whereHas('Exam', fn ($q) => $q->where('type', $examType))
            ->when($packageId, fn ($query) => $query->where('id_package', $packageId))
            ->when($examId, fn ($query) => $query->where('id_exam', $examId))
            ->where('id_user', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('user.history.index', compact('history', 'packages', 'exams', 'packageId', 'examId', 'examType'));
    }

    public function detail($id)
    {
        $historyDetail = DetailResult::with(['Question.subject', 'TransQuestion.exam', 'TransQuestion.package'])
            ->where('id_trans_question', $id)
            ->paginate(10);

        return view('user.history.detail', compact('historyDetail'));
    }
}
