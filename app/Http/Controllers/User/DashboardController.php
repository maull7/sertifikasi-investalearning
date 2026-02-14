<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\MasterType;
use App\Models\Package;
use App\Models\TransQuestion;
use App\Models\UserJoin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // info info
        $totalPackages = UserJoin::where('user_id', $user->id)->count();
        $totalExams = TransQuestion::where('id_user', $user->id)->count();

        // paket di ikuti
        $packageFollow = UserJoin::where('user_id', $user->id)
            ->with('package')
            ->get();

        $types = MasterType::query()->orderBy('name_type')->get();

        // daftar paket untuk filter chart (hanya paket yang diikuti user)
        $joinedPackageIds = UserJoin::query()
            ->where('user_id', $user->id)
            ->pluck('id_package');

        $packagesForFilter = Package::query()
            ->whereIn('id', $joinedPackageIds)
            ->orderBy('title')
            ->get();

        $examsForFilter = Exam::query()
            ->whereIn('package_id', $joinedPackageIds)
            ->orderBy('title')
            ->get();

        $packageActive = Package::where('status', 'active')
            ->whereDoesntHave('userJoins', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->limit(5)
            ->get();
        // ===== DUMMY STATS =====
        $completedMaterials = 12;
        $avgProgress = 65;

        // ===== DUMMY ACTIVE PACKAGES =====
        $activePackages = collect([
            (object) [
                'title' => 'Paket Laravel Dasar',
                'progress' => 80,
            ],
            (object) [
                'title' => 'Paket PHP Intermediate',
                'progress' => 45,
            ],
            (object) [
                'title' => 'Paket UI/UX Design',
                'progress' => 20,
            ],
        ]);

        // ===== DUMMY ACTIVITIES =====
        $activities = collect([
            (object) [
                'description' => 'Menyelesaikan materi "Routing Laravel"',
                'created_at' => now()->subMinutes(10),
            ],
            (object) [
                'description' => 'Mengikuti ujian "PHP Dasar"',
                'created_at' => now()->subHours(2),
            ],
            (object) [
                'description' => 'Mendaftar paket "Laravel Dasar"',
                'created_at' => now()->subDays(1),
            ],
        ]);

        return view('user.dashboard', compact(
            'user',
            'totalPackages',
            'totalExams',
            'packageFollow',
            'packageActive',
            'types',
            'packagesForFilter',
            'examsForFilter'
        ));
    }

    public function getChartData(Request $request): JsonResponse
    {
        $user = Auth::user();

        $typeId = $request->integer('type_id') ?: null;
        $packageId = $request->integer('package_id') ?: null;
        $examId = $request->integer('exam_id') ?: null;
        $periodDays = $request->integer('period_days') ?: null;

        // keamanan: hanya izinkan paket yang memang diikuti user
        $joinedPackageIds = UserJoin::query()
            ->where('user_id', $user->id)
            ->pluck('id_package')
            ->all();

        $query = TransQuestion::query()
            ->where('id_user', $user->id)
            ->whereIn('id_package', $joinedPackageIds)
            ->when($packageId, fn ($q) => $q->where('id_package', $packageId))
            ->when($examId, fn ($q) => $q->where('id_exam', $examId))
            ->when($periodDays, function ($q) use ($periodDays) {
                $q->where('created_at', '>=', Carbon::now()->subDays($periodDays));
            })
            ->orderBy('created_at')
            ->limit(30);

        $rows = $query
            ->with(['Package', 'Exam'])
            ->get(['created_at', 'total_score', 'id_package', 'id_exam']);

        $chartData = $rows->values()->map(function (TransQuestion $row, int $idx) {
            return [
                'label' => 'Attempt ' . ($idx + 1) . ' • ' . $row->created_at?->format('d M Y H:i'),
                'score' => (float) $row->total_score,
                'type' => $row->Exam?->type ?? '-',
                'package' => $row->Package?->title ?? '-',
                'exam' => $row->Exam?->title ?? '-',
            ];
        });

        return response()->json($chartData);
    }
}

