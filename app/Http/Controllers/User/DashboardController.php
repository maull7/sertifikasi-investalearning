<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\TransQuestion;
use App\Models\UserJoin;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        //info info
        $totalPackages = UserJoin::where('user_id', $user->id)->count();
        $totalExams = TransQuestion::where('id_user', $user->id)->count();

        //paket di ikuti
        $packageFollow = UserJoin::where('user_id', $user->id)
            ->with('package')
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
            'packageActive'
        ));
    }
}


