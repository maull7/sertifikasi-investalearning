@extends('layouts.app')

@section('title', $package->title)

@section('content')
    <div class="space-y-8 pb-20">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('user.packages.index') }}" class="hover:text-indigo-600 transition-colors">Package</a>
            <i class="ti ti-chevron-right text-xs"></i>
            <span class="text-gray-900 dark:text-white">{{ $package->title }}</span>
        </nav>

        {{-- Package Header --}}
        <x-card>
            <div class="space-y-6">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $package->title }}</h1>
                            @if ($package->masterType)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                    {{ $package->masterType->name_type }}
                                </span>
                            @endif
                        </div>
                        @if ($package->description)
                            <div class="prose prose-sm dark:prose-invert max-w-none">
                                {!! $package->description !!}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Package Stats --}}
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 pt-4 border-t border-gray-100 dark:border-gray-800">

                    <div class="text-center">
                        <p class="text-2xl font-bold text-emerald-600">
                            {{ $package->price ? 'Rp ' . number_format($package->price, 0, ',', '.') : 'Call' }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Harga</p>
                    </div>

                    <div class="text-center">
                        <p class="text-2xl font-bold text-violet-600">
                            {{ $package->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Status</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-rose-600">{{ $package->created_at->format('M Y') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Dibuat</p>
                    </div>
                </div>

                {{-- Price --}}
                <div class="pt-4 border-t border-gray-100 dark:border-gray-800">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Biaya Investasi</p>
                    @if ($package->price)
                        <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                            Rp {{ number_format($package->price, 0, ',', '.') }}
                        </p>
                    @else
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">Call</p>
                    @endif
                </div>

                {{-- Action Button --}}
                <div class="pt-4 border-t border-gray-100 dark:border-gray-800">
                    @if ($isJoined)
                        <x-button variant="secondary" href="{{ route('user.my-packages.show', $package) }}"
                            class="w-full md:w-auto rounded-xl">
                            <i class="ti ti-eye mr-2"></i> Lihat Package Saya
                        </x-button>
                    @else
                        <x-button variant="primary" href="{{ route('user.packages.checkout', $package) }}"
                            class="w-full md:w-auto rounded-xl shadow-lg shadow-indigo-500/20">
                            <i class="ti ti-plus mr-2"></i> Daftar Paket Sekarang
                        </x-button>
                    @endif
                </div>
            </div>
        </x-card>

        {{-- Jadwal Tatap Muka (hanya untuk peserta terdaftar) --}}
        @if ($isJoined && $schedules->isNotEmpty())
            <x-card title="Jadwal Tatap Muka">
                <div class="space-y-3">
                    @foreach ($schedules as $schedule)
                        <div class="rounded-xl border border-gray-100 dark:border-gray-800 overflow-hidden"
                            x-data="{ open: false }">
                            <button type="button"
                                class="w-full flex items-center justify-between gap-4 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors text-left"
                                @click="open = !open">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center shrink-0">
                                        <i class="ti ti-calendar-event text-indigo-600 dark:text-indigo-400"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $schedule->title }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $schedule->room_name }} &middot;
                                            <span class="text-indigo-600 dark:text-indigo-400">{{ $schedule->sessions->count() }} sesi</span>
                                        </p>
                                    </div>
                                </div>
                                <i class="ti text-gray-400 shrink-0 transition-transform duration-200"
                                    :class="open ? 'ti-chevron-up' : 'ti-chevron-down'"></i>
                            </button>

                            <div x-show="open" x-collapse class="border-t border-gray-100 dark:border-gray-800">
                                @if ($schedule->sessions->isNotEmpty())
                                    <div class="divide-y divide-gray-50 dark:divide-gray-800">
                                        @foreach ($schedule->sessions as $session)
                                            <div class="flex items-center gap-4 px-4 py-3 bg-gray-50/50 dark:bg-gray-800/20">
                                                <div class="w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></div>
                                                <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-1 text-sm">
                                                    <span class="font-medium text-gray-800 dark:text-gray-100">{{ $session->name ?? 'Sesi ' . $loop->iteration }}</span>
                                                    <span class="text-gray-500 dark:text-gray-400">
                                                        <i class="ti ti-calendar text-xs mr-1"></i>{{ $session->session_date->format('d M Y') }}
                                                        &nbsp;
                                                        <i class="ti ti-clock text-xs mr-1"></i>{{ substr((string)$session->start_time, 0, 5) }}–{{ substr((string)$session->end_time, 0, 5) }}
                                                    </span>
                                                    <span class="text-gray-500 dark:text-gray-400">
                                                        <i class="ti ti-user text-xs mr-1"></i>{{ $session->teacher?->name ?? '-' }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="px-4 py-3 text-sm text-gray-400">Belum ada sesi.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-card>
        @endif

        {{-- Materials Preview --}}
        {{-- @if ($subjects->sum(fn($s) => $s->materials->count()) > 0)
            <x-card title="Materi dalam Package">
                <div class="space-y-8">
                    @foreach ($subjects as $subject)
                        <div>
                            <h3
                                class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2 mb-3 pb-2 border-b border-gray-200 dark:border-gray-700">
                                <span
                                    class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400">
                                    <i class="ti ti-book text-xs"></i>
                                </span>
                                {{ $subject->name }}
                                @if ($subject->code)
                                    <span
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">({{ $subject->code }})</span>
                                @endif
                            </h3>
                            <div
                                class="space-y-3 pl-0 md:pl-5 border-l-0 md:border-l-2 border-indigo-100 dark:border-indigo-900/50">
                                @forelse ($subject->materials as $material)
                                    <div
                                        class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-800">

                                        <div
                                            class="w-10 h-10 bg-indigo-50 dark:bg-indigo-500/10 rounded-lg flex items-center justify-center shrink-0">

                                            @if ($material->materi_type == 'File')
                                                <i
                                                    class="{{ $material->file_icon }} text-lg 
                                                    {{ $material->file_type === 'pdf' ? 'text-rose-600' : 'text-blue-600' }}">
                                                </i>
                                            @else
                                                <i class="ti ti-video text-lg text-indigo-600"></i>
                                            @endif

                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-900 dark:text-white truncate">
                                                {{ $material->title }}
                                            </h4>

                                            @if ($material->topic)
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $material->topic }}
                                                </p>
                                            @endif
                                        </div>

                                        @if ($material->value)
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $material->file_size_formatted }}
                                            </span>
                                        @endif
                                    </div>

                                @empty
                                    <div class="p-4 text-sm text-gray-500 dark:text-gray-400 italic">
                                        Belum ada materi untuk mata pelajaran ini.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-card>
        @endif --}}
    </div>
@endsection
