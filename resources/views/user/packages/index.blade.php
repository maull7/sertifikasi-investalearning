@extends('layouts.app')

@section('title', 'Package / Kursus')

@section('content')
<div class="space-y-8 pb-20">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Package / Kursus</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Jelajahi semua package yang tersedia</p>
        </div>
    </div>

    {{-- Search Section --}}
        <div class="flex flex-col md:flex-row gap-4">
            <form 
                action="{{ route('user.packages.index') }}" 
                method="GET" 
                class="flex flex-col md:flex-row items-stretch gap-3 w-full"
            >

            {{-- SEARCH --}}
                <div class="relative flex-1 group">
                    <i class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-600 transition-colors"></i>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Cari package..." 
                        class="w-full pl-11 pr-12 py-3 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl text-sm outline-none focus:ring-4 focus:ring-indigo-500/5 transition-all dark:text-white h-12"
                    >

                    @if(request('search'))
                        <a 
                            href="{{ route('user.packages.index', request()->except('search')) }}" 
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition-colors"
                        >
                            <i class="ti ti-x"></i>
                        </a>
                    @endif
                </div>

                {{-- SELECT --}}
                <div class="w-full md:w-64">
                    <x-select 
                        name="id_type" 
                        inline
                        class="h-12"
                    >
                        <option value="">Semua Tipe</option>
                        @foreach($types as $type)
                            <option 
                                value="{{ $type->id }}" 
                                {{ (int) request('id_type') === $type->id ? 'selected' : '' }}
                            >
                                {{ $type->name_type }}
                            </option>
                        @endforeach
                    </x-select>
                </div>

                {{-- BUTTON --}}
                <div class="flex">
                    <x-button 
                        type="submit" 
                        variant="primary" 
                        class="h-12 px-6 rounded-xl w-full md:w-auto"
                    >
                        Terapkan
                    </x-button>
                </div>

            </form>
        </div>


    {{-- Packages Grid --}}
    @if($packages->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($packages as $package)
                <x-card class="hover:shadow-xl transition-all duration-300 group">
                    <div class="space-y-4">
                        {{-- Package Header --}}
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 transition-colors">
                                    {{ $package->title }}
                                </h3>
                                @if($package->masterType)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300 mt-2">
                                        {{ $package->masterType->name_type }}
                                    </span>
                                @endif
                            </div>
                            @if(in_array($package->id, $joinedPackageIds))
                                    @if ($joinedStatus[$package->id] == 'approved')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                                            <i class="ti ti-check mr-1"></i> Terdaftar
                                        </span>
                                    @elseif ($joinedStatus[$package->id] == 'rejected')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300">
                                            <i class="ti ti-x mr-1"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-300">
                                            <i class="ti ti-clock mr-1"></i> Menunggu Persetujuan
                                        </span>
                                    @endif
                            @endif
                        </div>

                        {{-- Description --}}
                        @if($package->description)
                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3">
                                {{ Str::limit(strip_tags($package->description), 120) }}
                            </p>
                        @endif

                        {{-- Package Info --}}
                        <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                            <div class="flex items-center gap-1">
                                <i class="ti ti-book"></i>
                                <span>{{ $package->materials->count() }} Materi</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <i class="ti ti-users"></i>
                                <span>{{ $package->userJoins->count() }} Peserta</span>
                            </div>
                        </div>


                        {{-- Action Button --}}
                        <div class="pt-2">
                            @if(in_array($package->id, $joinedPackageIds))
                                @if($joinedStatus[$package->id] === 'approved')
                                    <x-button variant="secondary" href="{{ route('user.my-packages.show', $package) }}" class="w-full rounded-xl">
                                        <i class="ti ti-eye mr-2"></i> Lihat Package
                                    </x-button>
                                @elseif($joinedStatus[$package->id] === 'rejected')
                                    <x-button variant="danger" disabled class="w-full rounded-xl">
                                        <i class="ti ti-x mr-2"></i> Pendaftaran Ditolak
                                    </x-button>
                                @else
                                    <x-button variant="warning" disabled class="w-full rounded-xl">
                                        <i class="ti ti-clock mr-2"></i> Menunggu Persetujuan
                                    </x-button>
                                @endif
                            @else
                                <x-button variant="primary" href="{{ route('user.packages.show', $package) }}" class="w-full rounded-xl shadow-lg shadow-indigo-500/20">
                                    <i class="ti ti-plus mr-2"></i> Lihat Detail
                                </x-button>
                            @endif
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($packages->hasPages())
            <div class="pt-6">
                {{ $packages->links() }}
            </div>
        @endif
    @else
        <x-card>
            <div class="flex flex-col items-center justify-center text-center py-12">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                    <i class="ti ti-book-off text-2xl text-gray-400"></i>
                </div>
                <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                    @if(request('search'))
                        Package tidak ditemukan
                    @else
                        Belum ada package tersedia
                    @endif
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    @if(request('search'))
                        Tidak ada hasil untuk kata kunci "{{ request('search') }}".
                    @else
                        Saat ini belum ada package yang tersedia.
                    @endif
                </p>
            </div>
        </x-card>
    @endif
</div>
@endsection











