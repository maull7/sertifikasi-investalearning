<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\UserJoin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        $data = UserJoin::with('user', 'package')
            ->when($search, function ($query, $search) {
                $query->whereHas('package', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
            })
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'pending_page')
            ->withQueryString();

        $activePackages = Package::with('masterType')
            ->withCount(['userJoins as approved_members_count' => function ($q) {
                $q->where('status', 'approved');
            }])
            ->where('status', 'active')
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy('title')
            ->paginate(10, ['*'], 'active_page')
            ->withQueryString();

        return view('admin.approve-packages.index', compact('data', 'activePackages', 'search'));
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

        return redirect()->route('approve-packages.index')->with('success', 'User join approved successfully.');
    }

    public function reject(UserJoin $userJoin)
    {
        $userJoin->status = 'rejected';
        $userJoin->save();

        return redirect()->route('approve-packages.index')->with('success', 'User join rejected successfully.');
    }
}
