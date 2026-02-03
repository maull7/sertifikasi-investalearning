<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\MasterType;
use App\Models\Material;
use App\Models\Package;
use App\Models\TransQuestion;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        $package = Package::count();
        $types = MasterType::count();
        $material = Material::count();
        $user = User::where('role', 'User')->count();
        $data = [
            'package' => $package,
            'types' => $types,
            'material' => $material,
            'user' => $user,
        ];

        // Recent user 
        $recents = TransQuestion::with('User', 'Exam', 'Package', 'Type')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Exam summary metrics
        $totalExamsTaken = TransQuestion::count();
        $totalExamsPassed = TransQuestion::where('status', 'lulus')->count();
        $passRate = $totalExamsTaken > 0 ? round(($totalExamsPassed / $totalExamsTaken) * 100, 1) : 0.0;

        // User growth & activation
        $newUsers7 = User::where('role', 'User')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $newUsers30 = User::where('role', 'User')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        $pendingActivation = User::where('role', 'User')
            ->where('status_user', 'Belum Teraktivasi')
            ->count();

        // Recent certificates
        $recentCertificates = Certificate::query()
            ->with(['user', 'package', 'type'])
            ->latest()
            ->take(5)
            ->get();

        // Data untuk chart - Get all types and packages
        $typesData = MasterType::all();
        $packagesData = Package::all();

        return view('admin.dashboard.index', compact(
            'data',
            'recents',
            'typesData',
            'packagesData',
            'totalExamsTaken',
            'totalExamsPassed',
            'passRate',
            'newUsers7',
            'newUsers30',
            'pendingActivation',
            'recentCertificates'
        ));
    }

    // API endpoint untuk data chart
    public function getChartData(Request $request)
    {
        $typeId = $request->get('type_id');
        $packageId = $request->get('package_id');
        $period = (int) $request->get('period', 7); // default 7 days

        $query = TransQuestion::with('User')
            ->where('created_at', '>=', now()->subDays($period));

        if ($typeId) {
            $query->where('id_type', $typeId);
        }

        if ($packageId) {
            $query->where('id_package', $packageId);
        }

        // Ambil nilai tertinggi per user dalam periode & filter,
        // lalu urutkan dari yang terbesar dan batasi misalnya 10 besar.
        // Hanya pilih kolom yang sudah di-group/di-aggregate agar tidak konflik dengan ONLY_FULL_GROUP_BY.
        $topScores = $query->select('id_user')
            ->selectRaw('MAX(total_score) as max_score')
            ->groupBy('id_user')
            ->orderByDesc('max_score')
            ->limit(10)
            ->get();

        $chartData = $topScores->map(function (TransQuestion $row) {
            return [
                'label' => optional($row->User)->name ?? 'User ' . $row->id_user,
                'score' => (float) $row->max_score,
            ];
        });

        return response()->json($chartData->values());
    }
}
