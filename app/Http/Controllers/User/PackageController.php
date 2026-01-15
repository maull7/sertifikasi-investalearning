<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\UserJoins;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        
        $query = Package::with(['masterType', 'materials'])
            ->where('status', 'active');

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $packages = $query->paginate(12);

        $joinedPackageIds = UserJoins::where('user_id', $user->id)
            ->pluck('id_package')
            ->toArray();

        return view('user.packages.index', compact('packages', 'joinedPackageIds'));
    }

    public function show(Package $package): View
    {
        $user = Auth::user();
        
        $isJoined = UserJoins::where('user_id', $user->id)
            ->where('id_package', $package->id)
            ->exists();

        $materials = $package->materials()->with('subject')->get();

        return view('user.packages.show', compact('package', 'isJoined', 'materials'));
    }

    public function join(Package $package): RedirectResponse
    {
        $user = Auth::user();

        $existingJoin = UserJoins::where('user_id', $user->id)
            ->where('id_package', $package->id)
            ->first();

        if ($existingJoin) {
            return redirect()->route('user.packages.show', $package)
                ->with('error', 'Anda sudah bergabung dengan package ini.');
        }

        UserJoins::create([
            'user_id' => $user->id,
            'id_package' => $package->id,
        ]);

        return redirect()->route('user.my-packages.show', $package)
            ->with('success', 'Berhasil bergabung dengan package!');
    }
}

