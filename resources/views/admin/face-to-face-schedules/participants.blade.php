@extends('layouts.app')

@section('title', 'Peserta Jadwal')

@section('content')
<div class="space-y-8 pb-20">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Peserta Jadwal</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $schedule->title }} &mdash; {{ $schedule->package?->title }}</p>
        </div>
        <x-button variant="secondary" href="{{ route('admin.face-to-face-schedules.index') }}" class="rounded-xl">
            <i class="ti ti-arrow-left mr-1"></i> Kembali
        </x-button>
    </div>

    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Daftar Peserta</h2>
            <span class="text-xs text-gray-400">{{ $schedule->registrations->count() }} peserta</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                        <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">#</th>
                        <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Nama</th>
                        <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Email</th>
                        <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Undangan Dikirim</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse ($schedule->registrations as $i => $reg)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors">
                            <td class="py-3 px-6 text-sm text-gray-400">{{ $i + 1 }}</td>
                            <td class="py-3 px-6 text-sm text-gray-700 dark:text-gray-200 font-medium">{{ $reg->user?->name ?? '-' }}</td>
                            <td class="py-3 px-6 text-sm text-gray-500">{{ $reg->participant_email }}</td>
                            <td class="py-3 px-6 text-sm text-gray-500">
                                {{ $reg->invitation_sent_at ? $reg->invitation_sent_at->format('d M Y H:i') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-sm text-gray-400">Belum ada peserta terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
