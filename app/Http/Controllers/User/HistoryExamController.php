<?php

namespace App\Http\Controllers\User;

use App\Models\Exam;
use App\Models\Package;
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
        $history = TransQuestion::with('Package', 'Exam')
            ->when($request->package_id, function ($query) use ($request) {
                $query->where('id_package', $request->package_id);
            })
            ->when($request->exam_id, function ($query) use ($request) {
                $query->where('id_exam', $request->exam_id);
            })
            ->where('id_user', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('user.history.index', compact('history', 'packages', 'exams'));
    }
    public function detail($id)
    {
        $historyDetail = DetailResult::with('Question', 'TransQuestion')
            ->where('id_trans_question', $id)
            ->paginate(10);
        return view('user.history.detail', compact('historyDetail'));
    }
}
