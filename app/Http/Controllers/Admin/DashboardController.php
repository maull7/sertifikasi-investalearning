<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterTypes;
use App\Models\Materials;
use App\Models\Package;
use App\Models\TransQuestions;
use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $package = Package::count();
        $types = MasterTypes::count();
        $material = Materials::count();
        $user = User::where('role', 'User')->count();
        $data = [
            'package' => $package,
            'types' => $types,
            'material' => $material,
            'user' => $user,
        ];

        //recent user 
        $recents = TransQuestions::with('User', 'Exam', 'Package', 'Type')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        return view('dashboard.index', compact('data', 'recents'));
    }
}
