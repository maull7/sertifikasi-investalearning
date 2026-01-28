<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailResult;
use App\Models\Exam;
use App\Models\Package;
use App\Models\TransQuestion;
use Illuminate\Http\Request;

class ShowGradeController extends Controller
{
    public function index(Request $request)
    {
        $exams = Exam::all();
        $packages = Package::all();

        $packageId = $request->get('package_id');
        $examId = $request->get('exam_id');

        $list = TransQuestion::with(['User', 'Package', 'Exam', 'Type'])
            ->when($packageId, function ($query) use ($packageId) {
                $query->where('id_package', $packageId);
            })
            ->when($examId, function ($query) use ($examId) {
                $query->where('id_exam', $examId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString(); // biar pagination tetep bawa filter

        // Chart data: hanya ditampilkan jika ada filter yang diterapkan
        $chartData = collect();
        if ($packageId || $examId) {
            $chartData = TransQuestion::with('User')
                ->when($packageId, function ($query) use ($packageId) {
                    $query->where('id_package', $packageId);
                })
                ->when($examId, function ($query) use ($examId) {
                    $query->where('id_exam', $examId);
                })
                ->orderByDesc('total_score')
                ->limit(10)
                ->get(['id_user', 'total_score']);
        }

        return view('admin.show-grade.index', compact('list', 'exams', 'packages', 'packageId', 'examId', 'chartData'));
    }

    public function detail($id)
    {
        $historyDetail = DetailResult::with('Question', 'TransQuestion')
            ->where('id_trans_question', $id)
            ->paginate(10);

        return view('admin.show-grade.detail', compact('historyDetail'));
    }
}
