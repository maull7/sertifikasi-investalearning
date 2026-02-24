<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailResult;
use App\Models\Exam;
use App\Models\Package;
use App\Models\TransQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShowGradeController extends Controller
{
    /**
     * Riwayat peserta mengerjakan ujian (exam only). Tab: pretest | posttest.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // ambil package yg dikelola petugas
        $packageIds = [];
        if ($user->role === 'Petugas') {
            $packageIds = $user->managedPackages()
                ->pluck('package_id')
                ->toArray();
        }

        // dropdown package
        $packages = Package::when($user->role === 'Petugas', function ($q) use ($packageIds) {
            $q->whereIn('id', $packageIds);
        })
            ->get();

        $exams = Exam::orderBy('title')->get();

        $packageId = $request->get('package_id');
        $examId = $request->get('exam_id');
        $examType = $request->get('exam_type', 'posttest');

        $list = TransQuestion::with(['User', 'Package', 'Exam'])
            ->whereNotNull('id_exam')

            // 🔥 FILTER ROLE
            ->when($user->role === 'Petugas', function ($q) use ($packageIds) {
                $q->whereIn('id_package', $packageIds);
            })

            ->when($packageId, fn($q) => $q->where('id_package', $packageId))
            ->when($examId, fn($q) => $q->where('id_exam', $examId))
            ->when(
                $examType,
                fn($q) =>
                $q->whereHas('exam', fn($e) => $e->where('type', $examType))
            )
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();


        $chartData = collect();

        if ($packageId || $examId) {
            $chartData = TransQuestion::with('User')
                ->whereNotNull('id_exam')

                // 🔥 FILTER ROLE
                ->when($user->role === 'Petugas', function ($q) use ($packageIds) {
                    $q->whereIn('id_package', $packageIds);
                })

                ->when($packageId, fn($q) => $q->where('id_package', $packageId))
                ->when($examId, fn($q) => $q->where('id_exam', $examId))
                ->when(
                    $examType,
                    fn($q) =>
                    $q->whereHas('exam', fn($e) => $e->where('type', $examType))
                )
                ->orderByDesc('total_score')
                ->limit(10)
                ->get(['id_user', 'total_score']);
        }

        return view('admin.show-grade.index', compact(
            'list',
            'exams',
            'packages',
            'packageId',
            'examId',
            'examType',
            'chartData'
        ));
    }

    public function detail($id)
    {
        $historyDetail = DetailResult::with('Question', 'TransQuestion')
            ->where('id_trans_question', $id)
            ->paginate(10);

        return view('admin.show-grade.detail', compact('historyDetail'));
    }
}
