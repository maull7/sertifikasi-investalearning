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
        $tab = $request->get('tab', 'pending'); // pending | activated | google
        if (!in_array($tab, ['pending', 'activated', 'google'], true)) {
            $tab = 'pending';
        }

        $query = User::where('role', 'User');

        if ($tab === 'pending') {
            $query->where('status_user', 'Belum Teraktivasi');
        } elseif ($tab === 'activated') {
            $query->where('status_user', 'Teraktivasi')->orderByDesc('updated_at');
        } else {
            // google: registrasi pakai Google
            $query->whereNotNull('google_id')->orderByDesc('created_at');
        }

        $list = $query
            ->when($search, function ($q, $search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view('admin.user.index', compact('list', 'search', 'tab'));
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
