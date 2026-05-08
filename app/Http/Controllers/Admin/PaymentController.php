<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\UserJoin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $payments = Payment::with(['userJoin.user', 'userJoin.package', 'userJoin.schedule'])
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    public function confirm(Payment $payment): RedirectResponse
    {
        $payment->update(['status' => 'confirmed']);
        $payment->userJoin->update(['status' => 'approved']);

        // Daftarkan ke jadwal jika ada
        if ($payment->userJoin->schedule_id) {
            \App\Models\FaceToFaceScheduleRegistration::firstOrCreate(
                ['schedule_id' => $payment->userJoin->schedule_id, 'user_id' => $payment->userJoin->user_id],
                ['participant_email' => $payment->userJoin->user->email]
            );
        }

        return back()->with('success', 'Pembayaran dikonfirmasi dan peserta disetujui.');
    }

    public function reject(Request $request, Payment $payment): RedirectResponse
    {
        $payment->update(['status' => 'rejected', 'note' => $request->input('note')]);
        $payment->userJoin->update(['status' => 'rejected']);

        return back()->with('success', 'Pembayaran ditolak.');
    }
}
