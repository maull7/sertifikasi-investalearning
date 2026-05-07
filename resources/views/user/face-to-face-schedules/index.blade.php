@extends('layouts.app')

@section('title', 'Jadwal Tatap Muka')

@section('content')
    <div class="space-y-8 pb-20">

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Jadwal Tatap Muka</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">
                    Daftar jadwal sesi tatap muka dari paket yang Anda ikuti.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('user.face-to-face-schedules.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold border transition-colors
                        {{ ($activeTab ?? 'all') === 'all'
                            ? 'bg-indigo-600 text-white border-indigo-600 shadow-lg shadow-indigo-500/20'
                            : 'bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-indigo-400 hover:text-indigo-600' }}">
                    <i class="ti ti-calendar-event"></i>
                    Semua Jadwal
                </a>
                <a href="{{ route('user.face-to-face-schedules.registered') }}"
                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold border transition-colors
                        {{ ($activeTab ?? 'all') === 'registered'
                            ? 'bg-emerald-600 text-white border-emerald-600 shadow-lg shadow-emerald-500/20'
                            : 'bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-emerald-400 hover:text-emerald-600' }}">
                    <i class="ti ti-circle-check"></i>
                    Terdaftar
                </a>
            </div>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 dark:border-emerald-900/40 dark:bg-emerald-900/20">
                <i class="ti ti-circle-check text-emerald-600 dark:text-emerald-400 mt-0.5 text-lg flex-shrink-0"></i>
                <p class="text-sm font-medium text-emerald-700 dark:text-emerald-300">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Content --}}
        @php
            $displaySchedules = ($activeTab ?? 'all') === 'registered' ? $registeredSchedules : $allSchedules;
            $isRegisteredTab = ($activeTab ?? 'all') === 'registered';
        @endphp

        @if ($displaySchedules->isNotEmpty())
            <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                    <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $isRegisteredTab ? 'Jadwal Terdaftar' : 'Semua Jadwal' }}</h2>
                </div>
                <div class="w-full overflow-x-auto">
                    <table class="w-full min-w-[980px] text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                                <th class="py-4 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Tanggal</th>
                                <th class="py-4 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Waktu</th>
                                <th class="py-4 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Paket</th>
                                <th class="py-4 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Mata Pelajaran</th>
                                <th class="py-4 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Pengajar</th>
                                <th class="py-4 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Ruangan</th>
                                <th class="py-4 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Status</th>
                                @if (! $isRegisteredTab)
                                    <th class="py-4 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                            @foreach ($displaySchedules as $schedule)
                                @php
                                    $alreadyRegistered = in_array($schedule->id, $registeredScheduleIds, true);
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors">
                                    <td class="py-4 px-6 text-sm text-gray-600 dark:text-gray-300">
                                        {{ optional($schedule->schedule_date)->format('d M Y') ?? '-' }}
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-600 dark:text-gray-300">
                                        {{ substr((string) $schedule->start_time, 0, 5) }} - {{ substr((string) $schedule->end_time, 0, 5) }} WIB
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-600 dark:text-gray-300">{{ $schedule->package?->title ?? '-' }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600 dark:text-gray-300">{{ $schedule->subject?->name ?? '-' }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600 dark:text-gray-300">{{ $schedule->teacher?->name ?? '-' }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600 dark:text-gray-300">{{ $schedule->room_name ?? '-' }}</td>
                                    <td class="py-4 px-6 text-center">
                                        @if ($alreadyRegistered)
                                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/25 dark:text-emerald-300">
                                                <i class="ti ti-circle-check"></i> Terdaftar
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-semibold bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                                <i class="ti ti-clock"></i> Belum
                                            </span>
                                        @endif
                                    </td>
                                    @if (! $isRegisteredTab)
                                        <td class="py-4 px-6 text-center">
                                            @if ($alreadyRegistered)
                                                <span class="inline-flex items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 px-3 py-2 text-xs font-semibold text-emerald-700 dark:text-emerald-300">
                                                    Sudah Terdaftar
                                                </span>
                                            @else
                                                <form method="POST" action="{{ route('user.face-to-face-schedules.register', $schedule) }}">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 px-4 py-2 text-sm font-semibold text-white transition-colors">
                                                        <i class="ti ti-send"></i>
                                                        Daftar
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
                <div class="flex flex-col items-center justify-center text-center py-14">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                        <i class="ti ti-calendar-off text-2xl text-gray-400"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                        {{ $isRegisteredTab ? 'Belum Ada Jadwal Terdaftar' : 'Belum Ada Jadwal Tersedia' }}
                    </h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                        {{ $isRegisteredTab
                            ? 'Anda belum mendaftar ke jadwal tatap muka manapun. Kunjungi tab Semua Jadwal untuk mendaftar.'
                            : 'Saat ini belum ada jadwal tatap muka yang tersedia untuk paket yang Anda ikuti.' }}
                    </p>
                    @if ($isRegisteredTab)
                        <div class="mt-6">
                            <a href="{{ route('user.face-to-face-schedules.index') }}"
                                class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 transition-colors">
                                <i class="ti ti-calendar-event"></i>
                                Lihat Semua Jadwal
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

    </div>
@endsection
