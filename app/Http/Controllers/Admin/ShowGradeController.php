<?php

namespace App\Http\Controllers\Admin;

use App\Models\Exam;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Models\DetailResult;
use App\Models\TransQuestion;
use App\Http\Controllers\Controller;

class ShowGradeController extends Controller
{
    public function index(Request $request)
    {

        $exams = Exam::all();
        $packages = Package::all();
        $list = TransQuestion::with(['User', 'Package', 'Exam', 'Type'])
            ->when($request->package_id, function ($query) use ($request) {
                $query->where('id_package', $request->package_id);
            })
            ->when($request->exam_id, function ($query) use ($request) {
                $query->where('id_exam', $request->exam_id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString(); // biar pagination tetep bawa filter

        return view('admin.show-grade.index', compact('list', 'exams', 'packages'));
    }

    public function detail($id)
    {
        $historyDetail = DetailResult::with('Question', 'TransQuestion')
            ->where('id_trans_question', $id)
            ->paginate(10);
        return view('admin.show-grade.detail', compact('historyDetail'));
    }
}
