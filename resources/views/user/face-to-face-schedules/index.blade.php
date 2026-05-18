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
                    <i class="ti ti-calendar-event"></i> Semua Jadwal
                </a>
                <a href="{{ route('user.face-to-face-schedules.registered') }}"
                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold border transition-colors
                        {{ ($activeTab ?? 'all') === 'registered'
                            ? 'bg-emerald-600 text-white border-emerald-600 shadow-lg shadow-emerald-500/20'
                            : 'bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-emerald-400 hover:text-emerald-600' }}">
                    <i class="ti ti-circle-check"></i> Terdaftar
                </a>
            </div>
        </div>

        @if (session('success'))
            <div
                class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 dark:border-emerald-900/40 dark:bg-emerald-900/20">
                <i class="ti ti-circle-check text-emerald-600 dark:text-emerald-400 mt-0.5 text-lg flex-shrink-0"></i>
                <p class="text-sm font-medium text-emerald-700 dark:text-emerald-300">{{ session('success') }}</p>
            </div>
        @endif

        @php
            $displaySchedules = ($activeTab ?? 'all') === 'registered' ? $registeredSchedules : $allSchedules;
            $isRegisteredTab = ($activeTab ?? 'all') === 'registered';
        @endphp

        @if ($displaySchedules->isNotEmpty())
            <div class="space-y-4">
                @foreach ($displaySchedules as $schedule)
                    @php $alreadyRegistered = in_array($schedule->id, $registeredScheduleIds, true); @endphp
                    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden"
                        x-data="{ open: false }">

                        {{-- Header jadwal --}}
                        <div class="flex items-center justify-between gap-4 px-6 py-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors"
                            @click="open = !open">
                            <div class="flex items-center gap-3 min-w-0">
                                <div
                                    class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center shrink-0">
                                    <i class="ti ti-calendar-event text-indigo-600 dark:text-indigo-400 text-lg"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                        {{ $schedule->title }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $schedule->package?->title ?? '-' }} &middot; {{ $schedule->room_name }}
                                        &middot; <span
                                            class="text-indigo-600 dark:text-indigo-400">{{ $schedule->sessions->count() }}
                                            sesi</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                @if ($alreadyRegistered)
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/25 dark:text-emerald-300">
                                        <i class="ti ti-circle-check"></i> Terdaftar
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-semibold bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                        <i class="ti ti-clock"></i> Belum
                                    </span>
                                @endif
                                @if ($schedule->zoom_join_url && $alreadyRegistered)
                                    <a href="{{ $schedule->zoom_join_url }}" target="_blank"
                                        class="text-[11px]  text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                        <i class="ti ti-brand-zoom"></i> Join Zoom
                                    </a>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-semibold bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                        <i class="ti ti-brand-zoom"></i>Belum Tersedia
                                    </span>
                                @endif

                                <i class="ti text-gray-400 text-base transition-transform duration-200"
                                    :class="open ? 'ti-chevron-up' : 'ti-chevron-down'"></i>
                            </div>
                        </div>

                        {{-- Sesi (expand) --}}
                        <div x-show="open" x-collapse class="border-t border-gray-100 dark:border-gray-800">
                            @if ($schedule->sessions->isNotEmpty())
                                <div class="divide-y divide-gray-50 dark:divide-gray-800">
                                    @foreach ($schedule->sessions as $session)
                                        <div class="flex items-center gap-4 px-6 py-3">
                                            <div class="w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></div>
                                            <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-1 text-sm">
                                                <span
                                                    class="font-medium text-gray-800 dark:text-gray-100">{{ $session->name }}</span>
                                                <span class="text-gray-500 dark:text-gray-400">
                                                    <i
                                                        class="ti ti-calendar text-xs mr-1"></i>{{ $session->session_date->format('d M Y') }}
                                                    &nbsp;
                                                    <i
                                                        class="ti ti-clock text-xs mr-1"></i>{{ substr((string) $session->start_time, 0, 5) }}–{{ substr((string) $session->end_time, 0, 5) }}
                                                </span>
                                                <span class="text-gray-500 dark:text-gray-400">
                                                    <i
                                                        class="ti ti-user text-xs mr-1"></i>{{ $session->teacher?->name ?? '-' }}
                                                </span>
                                                @if ($schedule->zoom_join_url && $alreadyRegistered)
                                                    <a href="{{ $schedule->zoom_join_url }}" target="_blank"
                                                        class="text-[11px]  text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                                        <i class="ti ti-brand-zoom"></i> Join Zoom
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="px-6 py-3 text-sm text-gray-400">Belum ada sesi.</p>
                            @endif

                            {{-- Aksi daftar --}}
                            @if (!$isRegisteredTab)
                                <div
                                    class="px-6 py-4 bg-gray-50 dark:bg-gray-800/30 border-t border-gray-100 dark:border-gray-800">
                                    @if ($alreadyRegistered)
                                        <span
                                            class="inline-flex items-center gap-2 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 px-4 py-2 text-xs font-semibold text-emerald-700 dark:text-emerald-300">
                                            <i class="ti ti-circle-check"></i> Sudah Terdaftar
                                        </span>
                                    @elseif (in_array($schedule->package_id, $registeredPackageIds, true))
                                        <span
                                            class="inline-flex items-center gap-2 rounded-lg bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 px-4 py-2 text-xs font-semibold text-gray-400 dark:text-gray-500 cursor-not-allowed">
                                            <i class="ti ti-ban"></i> Sudah Terdaftar di Jadwal Lain
                                        </span>
                                    @else
                                        <form method="POST"
                                            action="{{ route('user.face-to-face-schedules.register', $schedule) }}">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 px-5 py-2.5 text-sm font-semibold text-white transition-colors shadow-lg shadow-indigo-500/20">
                                                <i class="ti ti-send"></i> Daftar Sekarang
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
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
                            ? 'Anda belum mendaftar ke jadwal tatap muka manapun.'
                            : 'Saat ini belum ada jadwal tatap muka yang tersedia untuk paket yang Anda ikuti.' }}
                    </p>
                    @if ($isRegisteredTab)
                        <a href="{{ route('user.face-to-face-schedules.index') }}"
                            class="mt-6 inline-flex items-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 transition-colors">
                            <i class="ti ti-calendar-event"></i> Lihat Semua Jadwal
                        </a>
                    @endif
                </div>
            </div>
        @endif

    </div>
@endsection
