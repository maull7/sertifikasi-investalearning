<?php

namespace App\Http\Controllers\User;

use App\Models\Package;
use App\Models\Material;
use App\Models\UserJoin;
use Illuminate\View\View;
use App\Models\MasterType;
use App\Models\StatusMateri;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class MyPackageController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $joinedPackages = UserJoin::where('user_id', $user->id)
            ->with(['package.masterType.subjects.materials'])
            ->paginate(12);

        return view('user.my-packages.index', compact('joinedPackages'));
    }

    public function show(Package $package): View
    {
        $user = Auth::user();

        $userJoin = UserJoin::where('user_id', $user->id)
            ->where('id_package', $package->id)
            ->first();

        if (! $userJoin) {
            abort(403, 'Anda belum bergabung dengan package ini.');
        }

        $package->load(['masterType.subjects.materials' => fn ($q) => $q->with('subject')]);
        $subjects = $package->masterType ? $package->masterType->subjects : collect();

        return view('user.my-packages.show', compact('package', 'subjects'));
    }
    public function markAsRead(Material $material): RedirectResponse
    {
        $user = Auth::user();

        $statusMateri = StatusMateri::where('id_user', $user->id)
            ->where('id_material', $material->id)
            ->first();

        if ($statusMateri) {
            $statusMateri->update(['status' => 'completed']);
        } else {
            StatusMateri::create([
                'id_user' => $user->id,
                'id_material' => $material->id,
                'status' => 'completed',
            ]);
        }

        return redirect()->back()->with('success', 'Materi telah ditandai sebagai dibaca.');
    }
}
