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
            ->where('status', 'active')
            ->where('is_hidden', false);

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

        $schedules = \App\Models\FaceToFaceSchedule::with(['sessions.teacher:id,name'])
            ->where('package_id', $package->id)
            ->where('is_active', true)
            ->orderByDesc('id')
            ->get();

        return view('user.packages.show', compact('package', 'isJoined', 'subjects', 'schedules'));
    }

    public function checkout(Package $package): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();

        if (UserJoin::where('user_id', $user->id)->where('id_package', $package->id)->exists()) {
            return redirect()->route('user.packages.show', $package)->with('error', 'Anda sudah mendaftar paket ini.');
        }

        $schedules = \App\Models\FaceToFaceSchedule::where('package_id', $package->id)
            ->where('is_active', true)
            ->withCount('sessions')
            ->orderByDesc('id')
            ->get();

        $qrisImage       = \App\Models\Setting::get('qris_image');
        $qrisName        = \App\Models\Setting::get('qris_name');
        $qrisDescription = \App\Models\Setting::get('qris_description');

        return view('user.packages.checkout', compact('package', 'schedules', 'qrisImage', 'qrisName', 'qrisDescription'));
    }

    public function join(Package $package, \Illuminate\Http\Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (UserJoin::where('user_id', $user->id)->where('id_package', $package->id)->exists()) {
            return redirect()->route('user.packages.show', $package)->with('error', 'Anda sudah mendaftar paket ini.');
        }

        $request->validate([
            'schedule_id' => 'required|exists:face_to_face_schedules,id',
            'proof_image' => 'required|image|max:3072',
        ]);

        $userJoin = UserJoin::create([
            'user_id'     => $user->id,
            'id_package'  => $package->id,
            'schedule_id' => $request->schedule_id,
            'status'      => 'pending',
        ]);

        $path = $request->file('proof_image')->store('payments', 'public');
        \App\Models\Payment::create([
            'user_join_id' => $userJoin->id,
            'proof_image'  => $path,
            'status'       => 'pending',
        ]);

        return redirect()->route('user.my-packages.index')
            ->with('success', 'Pendaftaran berhasil! Menunggu konfirmasi admin.');
    }

    public function landing(Request $request): View
    {
        $user = Auth::user();
        $types = MasterType::with('subjects', 'packages')->get();

        $query = Package::with(['masterType.subjects.materials'])
            ->where('status', 'active')
            ->where('is_hidden', false);

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
