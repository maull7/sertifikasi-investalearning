<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserJoin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
            ->paginate(10)
            ->withQueryString(); // biar search kebawa pas ganti page
        return view('admin.approve-packages.index', compact('data', 'search'));
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
