<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ===== DUMMY STATS =====
        $totalPackages = 3;
        $completedMaterials = 12;
        $totalExams = 2;
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
            'completedMaterials',
            'totalExams',
            'avgProgress',
            'activePackages',
            'activities'
        ));
    }
}

