<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\User;
use App\Models\UserJoin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ApprovePackageController extends Controller
{
    /**
     * Jumlah pendaftaran paket yang masih pending (untuk notif real-time di sidebar).
     */
    public function pendingCount(): JsonResponse
    {
        $count = UserJoin::where('status', 'pending')->count();

        return response()->json(['count' => $count]);
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $user = Auth::user();

        // default kosong
        $packageIds = [];

        // cek kalau role petugas
        if ($user->role === 'Petugas') {
            $packageIds = $user->managedPackages()
                ->pluck('package_id')
                ->toArray();
        }

        $dataQuery = UserJoin::with(['user.managedPackages', 'package', 'payment'])
            ->when($search, function ($query, $search) {
                $query->whereHas('package', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
            })
            ->where('status', 'pending');

        // kalau petugas baru pakai filter package
        if ($user->role === 'Petugas') {
            $dataQuery->whereIn('id_package', $packageIds);
        }

        $data = $dataQuery
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'pending_page')
            ->withQueryString();

        $rejectedQuery = UserJoin::with(['user', 'package'])
            ->when($search, function ($query, $search) {
                $query->whereHas('package', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
            })
            ->where('status', 'rejected');

        if ($user->role === 'Petugas') {
            $rejectedQuery->whereIn('id_package', $packageIds);
        }

        $rejectedData = $rejectedQuery
            ->orderBy('updated_at', 'desc')
            ->paginate(10, ['*'], 'rejected_page')
            ->withQueryString();


        $activeQuery = Package::with('masterType')
            ->withCount(['userJoins as approved_members_count' => function ($q) {
                $q->where('status', 'approved');
            }])
            ->where('status', 'active')
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            });

        if ($user->role === 'Petugas') {
            $activeQuery->whereIn('id', $packageIds);
        }

        $activePackages = $activeQuery
            ->orderBy('title')
            ->paginate(10, ['*'], 'active_page')
            ->withQueryString();

        return view('admin.approve-packages.index', compact('data', 'rejectedData', 'activePackages', 'search'));
    }

    /**
     * Detail paket aktif: daftar user yang mengikuti paket tersebut.
     */
    public function showPackage(Request $request, Package $package): View
    {
        if ($package->status !== 'active') {
            abort(404);
        }

        $package->load('masterType');

        $members = UserJoin::with('user')
            ->where('id_package', $package->id)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.approve-packages.show-package', compact('package', 'members'));
    }

    public function approve(UserJoin $userJoin)
    {
        $userJoin->status = 'approved';
        $userJoin->save();

        // Konfirmasi payment jika ada
        if ($userJoin->payment) {
            $userJoin->payment->update(['status' => 'confirmed']);
        }

        // Daftarkan ke jadwal jika ada
        if ($userJoin->schedule_id) {
            \App\Models\FaceToFaceScheduleRegistration::firstOrCreate(
                ['schedule_id' => $userJoin->schedule_id, 'user_id' => $userJoin->user_id],
                ['participant_email' => $userJoin->user->email]
            );
        }

        return redirect()->route('approve-packages.index')->with('success', 'User join approved successfully.');
    }

    public function reject(UserJoin $userJoin)
    {
        $userJoin->status = 'rejected';
        $userJoin->save();

        return redirect()->route('approve-packages.index')->with('success', 'User join rejected successfully.');
    }

    public function destroy(UserJoin $userJoin)
    {
        $userJoin->payment?->delete();
        $userJoin->delete();

        return redirect()->route('approve-packages.index', ['tab' => 'rejected'] + request()->only('search'))
            ->with('success', 'Data pendaftaran berhasil dihapus. Peserta dapat mendaftar ulang.');
    }
}
