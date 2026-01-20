@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Dashboard
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Selamat datang kembali, <span class="font-semibold">{{ $user->name }}</span>
            </p>
        </div>
        <x-button href="{{ route('profile.edit') }}" variant="secondary" class="rounded-xl">
            <i class="ti ti-user-edit mr-2"></i> Ubah Profil
        </x-button>
    </div>

    {{-- Stats --}}
    @php
        $stats = [
            [
                'label' => 'Paket Diikuti',
                'value' => $totalPackages ?? 0,
                'icon'  => 'book',
                'color' => 'indigo',
            ],
            // [
            //     'label' => 'Materi DiPelajari',
            //     'value' => $completedMaterials ?? 0,
            //     'icon'  => 'checklist',
            //     'color' => 'emerald',
            // ],
            [
                'label' => 'Ujian Diikuti',
                'value' => $totalExams ?? 0,
                'icon'  => 'file-text',
                'color' => 'amber',
            ],
            // [
            //     'label' => 'Progress Rata-rata',
            //     'value' => ($avgProgress ?? 0) . '%',
            //     'icon'  => 'chart-line',
            //     'color' => 'rose',
            // ],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($stats as $stat)
            <x-card>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center
                        bg-{{ $stat['color'] }}-50 dark:bg-{{ $stat['color'] }}-500/10
                        text-{{ $stat['color'] }}-600">
                        <i class="ti ti-{{ $stat['icon'] }} text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400 font-semibold">{{ $stat['label'] }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $stat['value'] }}
                        </p>
                    </div>
                </div>
            </x-card>
        @endforeach
    </div>

    {{-- Paket Aktif --}}
    <x-card title="Paket yang Sedang Diikuti">
        @forelse($packageFollow ?? [] as $package)
            <div class="flex items-center justify-between py-3 border-b last:border-0 border-gray-100 dark:border-gray-800">
                <div>
                    <p class="font-semibold text-gray-900 dark:text-white">
                        {{ $package->package->title }}
                    </p>
                    <p class="text-xs text-gray-400">
                         {{ $package->package->description }}
                    </p>
                </div>
                <x-button size="sm" href="{{ route('user.my-packages.show', $package->package->id) }}" variant="primary" class="rounded-lg">
                    Lanjutkan
                </x-button>
            </div>
        @empty
            <div class="py-10 text-center">
                <i class="ti ti-books text-4xl text-gray-300 mb-3"></i>
                <p class="text-sm text-gray-500">
                    Anda belum mengikuti paket apa pun
                </p>
            </div>
        @endforelse
    </x-card>
    <x-card title="Paket Terbaru">
        @forelse($packageActive ?? [] as $package)
            <div class="flex items-center justify-between py-3 border-b last:border-0 border-gray-100 dark:border-gray-800">
                <div>
                    <p class="font-semibold text-gray-900 dark:text-white">
                        {{ $package->title }}
                    </p>
                    <p class="text-xs text-gray-400">
                         {{ $package->description }}
                    </p>
                </div>
                <x-button size="sm" href="{{ route('my-packages.index') }}" variant="primary" class="rounded-lg">
                    Lihat Paket
                </x-button>
            </div>
        @empty
            <div class="py-10 text-center">
                <i class="ti ti-books text-4xl text-gray-300 mb-3"></i>
                <p class="text-sm text-gray-500">
                   Belum ada paket terbaru
                </p>
            </div>
        @endforelse
    </x-card>

</div>
@endsection
