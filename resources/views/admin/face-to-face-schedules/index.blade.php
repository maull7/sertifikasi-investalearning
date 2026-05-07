@extends('layouts.app')

@section('title', 'Jadwal Tatap Muka')

@section('content')
    <div class="space-y-8 pb-20">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Jadwal Tatap Muka</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola jadwal guru per paket dan meeting Zoom.</p>
            </div>
            <a href="{{ route('admin.face-to-face-schedules.create') }}"
                class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                <i class="ti ti-plus mr-1"></i> Tambah Jadwal
            </a>
        </div>

        @if (session('success'))
            <div
                class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Daftar Jadwal Tatap Muka</h2>
            </div>
            <div class="w-full overflow-x-auto">
                <table class="w-full min-w-[1100px] text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Tanggal</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Waktu</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Paket</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Mata Pelajaran</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Guru</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Ruangan</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Status</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse ($schedules as $schedule)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors">
                                <td class="py-4 px-8 text-sm text-gray-500 font-medium">{{ optional($schedule->schedule_date)->format('d M Y') }}</td>
                                <td class="py-4 px-8 text-sm text-gray-500 font-medium">
                                    {{ substr((string) $schedule->start_time, 0, 5) }} - {{ substr((string) $schedule->end_time, 0, 5) }}
                                </td>
                                <td class="py-4 px-8 text-sm text-gray-500 font-medium">{{ $schedule->package?->title ?? '-' }}</td>
                                <td class="py-4 px-8 text-sm text-gray-500 font-medium">{{ $schedule->subject?->name ?? '-' }}</td>
                                <td class="py-4 px-8 text-sm text-gray-500 font-medium">{{ $schedule->teacher?->name ?? '-' }}</td>
                                <td class="py-4 px-8 text-sm text-gray-500 font-medium">{{ $schedule->room_name }}</td>
                                <td class="py-4 px-8">
                                    @if ($schedule->is_active)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">Aktif</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-300">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="py-4 px-8">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-button variant="secondary" size="sm" href="{{ route('admin.face-to-face-schedules.edit', $schedule) }}"
                                            class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                            <i class="ti ti-pencil text-base"></i>
                                        </x-button>
                                        <form action="{{ route('admin.face-to-face-schedules.destroy', $schedule) }}" method="POST"
                                            onsubmit="return confirm('Hapus jadwal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="h-9 w-9 rounded-lg flex items-center justify-center bg-rose-50 dark:bg-rose-900/20 text-rose-600 hover:bg-rose-100 transition-colors">
                                                <i class="ti ti-trash text-base"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-sm text-gray-400">Belum ada jadwal tatap muka.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($schedules->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                    {{ $schedules->links() }}
                </div>
            @endif
        </div>
@endsection

