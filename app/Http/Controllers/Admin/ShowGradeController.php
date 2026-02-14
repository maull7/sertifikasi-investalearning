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
    /**
     * Riwayat peserta mengerjakan ujian (exam only). Tab: pretest | posttest.
     */
    public function index(Request $request)
    {
        $packages = Package::all();
        $exams = Exam::orderBy('title')->get();

        $packageId = $request->get('package_id');
        $examId = $request->get('exam_id');
        $examType = $request->get('exam_type', 'posttest'); // tab: pretest | posttest

        $list = TransQuestion::with(['User', 'Package', 'Exam'])
            ->whereNotNull('id_exam')
            ->when($packageId, function ($query) use ($packageId) {
                $query->where('id_package', $packageId);
            })
            ->when($examId, function ($query) use ($examId) {
                $query->where('id_exam', $examId);
            })
            ->when($examType, function ($query) use ($examType) {
                $query->whereHas('exam', fn ($q) => $q->where('type', $examType));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $chartData = collect();
        if ($packageId || $examId) {
            $chartData = TransQuestion::with('User')
                ->whereNotNull('id_exam')
                ->when($packageId, fn ($query) => $query->where('id_package', $packageId))
                ->when($examId, fn ($query) => $query->where('id_exam', $examId))
                ->when($examType, fn ($query) => $query->whereHas('exam', fn ($q) => $q->where('type', $examType)))
                ->orderByDesc('total_score')
                ->limit(10)
                ->get(['id_user', 'total_score']);
        }

        return view('admin.show-grade.index', compact('list', 'exams', 'packages', 'packageId', 'examId', 'examType', 'chartData'));
    }

    public function detail($id)
    {
        $historyDetail = DetailResult::with('Question', 'TransQuestion')
            ->where('id_trans_question', $id)
            ->paginate(10);

        return view('admin.show-grade.detail', compact('historyDetail'));
    }
}
