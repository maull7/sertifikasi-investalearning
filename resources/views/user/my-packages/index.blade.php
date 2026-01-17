@extends('layouts.app')

@section('title', 'Package Saya')

@section('content')
<div class="space-y-8 pb-20">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Package Saya</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Package yang sudah Anda ikuti</p>
        </div>
        <x-button variant="primary" href="{{ route('user.packages.index') }}" icon="plus" class="rounded-xl shadow-lg shadow-indigo-500/20">
            Jelajahi Package Lain
        </x-button>
    </div>

    {{-- Packages Grid --}}
    @if($joinedPackages->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($joinedPackages as $userJoin)
                @php $package = $userJoin->package; @endphp
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
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                                <i class="ti ti-check mr-1"></i> Terdaftar
                            </span>
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
                                <i class="ti ti-calendar"></i>
                                <span>Bergabung {{ $userJoin->created_at->format('d M Y') }}</span>
                            </div>
                        </div>

                        {{-- Progress (optional) --}}
                        <div class="pt-2">
                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                                <span>Progress</span>
                                <span>0%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: 0%"></div>
                            </div>
                        </div>

                        {{-- Action Button --}}
                        <div class="pt-2">
                            <x-button variant="primary" href="{{ route('user.my-packages.show', $package) }}" class="w-full rounded-xl shadow-lg shadow-indigo-500/20">
                                <i class="ti ti-arrow-right mr-2"></i> Lanjutkan Belajar
                            </x-button>
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($joinedPackages->hasPages())
            <div class="pt-6">
                {{ $joinedPackages->links() }}
            </div>
        @endif
    @else
        <x-card>
            <div class="flex flex-col items-center justify-center text-center py-12">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                    <i class="ti ti-book-off text-2xl text-gray-400"></i>
                </div>
                <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                    Belum ada package yang diikuti
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    Mulai jelajahi package yang tersedia dan bergabung untuk memulai pembelajaran.
                </p>
                <x-button variant="primary" href="{{ route('user.packages.index') }}" icon="plus" class="rounded-xl shadow-lg shadow-indigo-500/20">
                    Jelajahi Package
                </x-button>
            </div>
        </x-card>
    @endif
</div>
@endsection



