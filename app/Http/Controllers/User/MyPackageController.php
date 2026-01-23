<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Package;
use App\Models\UserJoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MyPackageController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $joinedPackages = UserJoin::where('user_id', $user->id)
            ->with(['package.masterType', 'package.materials'])
            ->paginate(12);

        return view('user.my-packages.index', compact('joinedPackages'));
    }

    public function show(Package $package): View
    {
        $user = Auth::user();

        $userJoin = UserJoin::where('user_id', $user->id)
            ->where('id_package', $package->id)
            ->first();

        if (!$userJoin) {
            abort(403, 'Anda belum bergabung dengan package ini.');
        }

        $materials = Material::where('package_id', $package->id)
            ->with('subject')
            ->get();

        return view('user.my-packages.show', compact('package', 'materials'));
    }
}


