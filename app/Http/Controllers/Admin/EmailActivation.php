<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AktivasiAkunNotification;
use Illuminate\Http\Request;

class EmailActivation extends Controller
{
    public function index()
    {
        $list = User::where('role', 'User')
            ->where('status_user', 'Belum Teraktivasi')
            ->paginate(10);
        return view('admin.user.index', compact('list'));
    }

    // 👇 FUNGSI AKTIVASI + KIRIM EMAIL
    public function activate(User $user)
    {
        // kirim email
        $user->notify(new AktivasiAkunNotification());

        // update status user
        $user->update([
            'status_user' => 'Teraktivasi'
        ]);

        return back()->with('success', 'Email aktivasi berhasil dikirim & user diaktifkan');
    }
}
