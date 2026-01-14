<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterTypes;
use App\Models\Materials;
use App\Models\Package;
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
        return view('dashboard.index', compact('data'));
    }
}
