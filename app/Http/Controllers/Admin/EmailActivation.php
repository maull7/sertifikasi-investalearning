<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AktivasiAkunNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailActivation extends Controller
{
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
