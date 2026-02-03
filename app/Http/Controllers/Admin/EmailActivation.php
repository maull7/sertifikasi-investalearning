<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AktivasiAkunNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailActivation extends Controller
{
    /**
     * Jumlah user belum teraktivasi (untuk badge real-time di sidebar).
     */
    public function unverifiedCount(): JsonResponse
    {
        $count = User::query()
            ->where('role', 'User')
            ->where('status_user', 'Belum Teraktivasi')
            ->count();

        return response()->json(['count' => $count]);
    }

    public function index(Request $request)
    {
        $search = $request->search;

        $list = User::where('role', 'User')
            ->where('status_user', 'Belum Teraktivasi')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString(); // biar pagination ga ilang search

        return view('admin.user.index', compact('list', 'search'));
    }

    // 👇 FUNGSI AKTIVASI + KIRIM EMAIL
    public function activate(User $user): RedirectResponse
    {
        $user->update([
            'status_user' => 'Teraktivasi',
        ]);

        // kirim email
        $user->notify(new AktivasiAkunNotification);

        return back()->with('success', 'Email aktivasi berhasil dikirim & user diaktifkan');
    }
}
