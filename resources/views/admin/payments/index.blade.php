@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran')

@section('content')
<div class="space-y-8 pb-20" x-data="{ rejectModal: false, rejectUrl: '', rejectName: '' }">

    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Konfirmasi Pembayaran</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Verifikasi bukti transfer peserta.</p>
        </div>
        <div class="flex gap-2">
            @foreach ([''=>'Semua', 'pending'=>'Pending', 'confirmed'=>'Dikonfirmasi', 'rejected'=>'Ditolak'] as $val => $label)
                <a href="{{ route('admin.payments.index', $val ? ['status'=>$val] : []) }}"
                    class="px-3 py-1.5 rounded-xl text-xs font-semibold border transition-colors
                        {{ request('status') === $val || (request('status') === null && $val === '')
                            ? 'bg-indigo-600 text-white border-indigo-600'
                            : 'bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-indigo-400' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-300">{{ session('success') }}</div>
    @endif

    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px] text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                        <th class="py-3 px-5 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Peserta</th>
                        <th class="py-3 px-5 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Paket</th>
                        <th class="py-3 px-5 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Jadwal</th>
                        <th class="py-3 px-5 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Bukti</th>
                        <th class="py-3 px-5 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Status</th>
                        <th class="py-3 px-5 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Tanggal</th>
                        <th class="py-3 px-5 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse ($payments as $payment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors">
                            <td class="py-3 px-5">
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $payment->userJoin->user?->name ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ $payment->userJoin->user?->email }}</p>
                            </td>
                            <td class="py-3 px-5 text-sm text-gray-500 dark:text-gray-400">{{ $payment->userJoin->package?->title ?? '-' }}</td>
                            <td class="py-3 px-5 text-sm text-gray-500 dark:text-gray-400">{{ $payment->userJoin->schedule?->title ?? '-' }}</td>
                            <td class="py-3 px-5">
                                <a href="{{ Storage::url($payment->proof_image) }}" target="_blank"
                                    class="inline-flex items-center gap-1 text-xs text-indigo-600 hover:underline">
                                    <i class="ti ti-photo"></i> Lihat Bukti
                                </a>
                            </td>
                            <td class="py-3 px-5 text-center">
                                @if ($payment->status === 'pending')
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-semibold bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-300">Pending</span>
                                @elseif ($payment->status === 'confirmed')
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">Dikonfirmasi</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300">Ditolak</span>
                                @endif
                            </td>
                            <td class="py-3 px-5 text-sm text-gray-400">{{ $payment->created_at->format('d M Y') }}</td>
                            <td class="py-3 px-5">
                                @if ($payment->status === 'pending')
                                    <div class="flex items-center justify-end gap-1.5">
                                        <form action="{{ route('admin.payments.confirm', $payment) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="h-8 px-3 rounded-lg text-xs font-semibold bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 hover:bg-emerald-100 transition-colors">
                                                <i class="ti ti-check mr-1"></i>Konfirmasi
                                            </button>
                                        </form>
                                        <button type="button"
                                            @click="rejectModal = true; rejectUrl = '{{ route('admin.payments.reject', $payment) }}'; rejectName = '{{ addslashes($payment->userJoin->user?->name) }}'"
                                            class="h-8 px-3 rounded-lg text-xs font-semibold bg-rose-50 dark:bg-rose-900/20 text-rose-600 hover:bg-rose-100 transition-colors">
                                            <i class="ti ti-x mr-1"></i>Tolak
                                        </button>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-10 text-center text-sm text-gray-400">Tidak ada data pembayaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($payments->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">{{ $payments->links() }}</div>
        @endif
    </div>

    {{-- Reject Modal --}}
    <div x-show="rejectModal"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" x-cloak
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800"
            @click.away="rejectModal = false">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-rose-50 dark:bg-rose-500/10 text-rose-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i class="ti ti-x text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Tolak Pembayaran?</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Pembayaran dari <span class="font-bold text-gray-900 dark:text-white" x-text="rejectName"></span> akan ditolak.</p>
            </div>
            <form :action="rejectUrl" method="POST" class="px-6 pb-6 space-y-4">
                @csrf @method('PATCH')
                <textarea name="note" rows="2" placeholder="Alasan penolakan (opsional)"
                    class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-4 py-2.5 text-sm text-gray-900 dark:text-gray-100 outline-none focus:ring-2 focus:ring-rose-500"></textarea>
                <div class="flex gap-3">
                    <x-button variant="secondary" type="button" class="flex-1 rounded-xl" @click="rejectModal = false">Batal</x-button>
                    <x-button variant="danger" type="submit" class="flex-1 rounded-xl">Ya, Tolak</x-button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
