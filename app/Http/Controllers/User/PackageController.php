<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MasterType;
use App\Models\Package;
use App\Models\UserJoin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $types = MasterType::with('subjects', 'packages')->get();

        $query = Package::with(['masterType', 'mappedSubjects.materials', 'userJoins'])
            ->where('status', 'active');

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->has('id_type')) {
            $query->where('id_master_types', $request->id_type);
        }
        $packages = $query->paginate(12);

        $joinedPackageIds = UserJoin::where('user_id', $user->id)
            ->pluck('id_package')
            ->toArray();
        $joinedStatus = UserJoin::where('user_id', $user->id)
            ->pluck('status', 'id_package')
            ->toArray();

        return view('user.packages.index', compact('packages', 'joinedPackageIds', 'joinedStatus', 'types'));
    }

    public function show(Package $package): View
    {
        $user = Auth::user();

        $isJoined = UserJoin::where('user_id', $user->id)
            ->where('id_package', $package->id)
            ->exists();

        $subjects = $package->getSubjectsForPackage();
        if ($subjects->isNotEmpty()) {
            $subjects->load(['materials' => fn($q) => $q->with('subject'), 'quizzes' => fn($q) => $q->with('subject')]);
        }

        return view('user.packages.show', compact('package', 'isJoined', 'subjects'));
    }

    public function join(Package $package): RedirectResponse
    {
        $user = Auth::user();

        $existingJoin = UserJoin::where('user_id', $user->id)
            ->where('id_package', $package->id)
            ->first();

        if ($existingJoin) {
            return redirect()->route('user.packages.show', $package)
                ->with('error', 'Anda sudah bergabung dengan package ini.');
        }

        UserJoin::create([
            'user_id' => $user->id,
            'id_package' => $package->id,
        ]);

        return redirect()->route('user.my-packages.index')
            ->with('success', 'Berhasil Daftar ke paket ini, tunggu konfirmasi dari admin!');
    }

    public function landing(Request $request): View
    {
        $user = Auth::user();
        $types = MasterType::with('subjects', 'packages')->get();

        $query = Package::with(['masterType.subjects.materials'])
            ->where('status', 'active');

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->has('id_type')) {
            $query->where('id_master_types', $request->id_type);
        }
        $packages = $query->paginate(12);

        $joinedPackageIds = UserJoin::where('user_id', $user->id)
            ->pluck('id_package')
            ->toArray();

        return view('user.landing', compact('packages', 'joinedPackageIds', 'types'));
    }
}
