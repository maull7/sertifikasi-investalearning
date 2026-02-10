@extends('layouts.landing')

@section('title', 'Paket Tersedia')

@section('content')
<div class="space-y-10 pb-12">
    {{-- Hero --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-indigo-600 to-cyan-600 dark:from-indigo-700 dark:via-indigo-800 dark:to-cyan-800 px-6 py-10 sm:px-10 sm:py-14 md:px-14 md:py-16 text-white shadow-2xl shadow-indigo-500/25 dark:shadow-indigo-900/30">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.06\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-80"></div>
        <div class="relative z-10 max-w-2xl">
            <div class="inline-flex items-center gap-2 rounded-full bg-white/15 backdrop-blur-sm px-4 py-1.5 text-sm font-medium text-white/95 mb-6">
                <i class="ti ti-package text-lg"></i>
                <span>Katalog Paket Belajar</span>
            </div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight text-white drop-shadow-sm">
                Paket Tersedia
            </h1>
            <p class="mt-3 sm:mt-4 text-lg text-white/90 font-medium max-w-xl">
                Jelajahi paket belajar yang tersedia dan daftar untuk mulai belajar.
            </p>
        </div>
        <div class="absolute right-4 top-1/2 -translate-y-1/2 hidden lg:block opacity-20">
            <i class="ti ti-school text-[12rem]"></i>
        </div>
    </div>

    {{-- Search Section (card) --}}
    <x-card class="border-0 shadow-xl shadow-gray-200/50 dark:shadow-none dark:bg-gray-900/80 backdrop-blur-sm">
        <div>
            <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Cari paket</h2>
            <form
                action="{{ route('user.packages.index') }}"
                method="GET"
                class="flex flex-col md:flex-row items-stretch gap-3 w-full"
            >
                <div class="relative flex-1 group">
                    <i class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari paket..."
                        class="w-full pl-11 pr-12 py-3 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all dark:text-white h-12"
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
                <div class="w-full md:w-56">
                    <x-select name="id_type" inline class="h-12 rounded-xl bg-gray-50 dark:bg-gray-800/50 border-gray-200 dark:border-gray-700">
                        <option value="">Semua Tipe</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ (int) request('id_type') === $type->id ? 'selected' : '' }}>
                                {{ $type->name_type }}
                            </option>
                        @endforeach
                    </x-select>
                </div>
                <div class="flex">
                    <x-button type="submit" variant="primary" class="h-12 px-6 rounded-xl w-full md:w-auto shadow-lg shadow-indigo-500/20">
                        <i class="ti ti-search mr-2"></i> Terapkan
                    </x-button>
                </div>
            </form>
        </div>
    </x-card>

    {{-- Section title --}}
    <div class="flex items-center gap-4">
        <div class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-200 dark:via-gray-700 to-transparent"></div>
        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Daftar Paket</span>
        <div class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-200 dark:via-gray-700 to-transparent"></div>
    </div>

    {{-- Packages Grid --}}
    @if($packages->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($packages as $package)
                <x-card class="relative overflow-hidden border border-gray-100 dark:border-gray-800 hover:border-indigo-200 dark:hover:border-indigo-800 hover:shadow-xl hover:shadow-indigo-500/10 dark:hover:shadow-indigo-500/5 transition-all duration-300 group">
                    {{-- Accent bar --}}
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-indigo-500 to-cyan-500 opacity-0 group-hover:opacity-100 transition-opacity rounded-l-3xl"></div>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-500/20 transition-colors shrink-0">
                                <i class="ti ti-book-2 text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2">
                                    {{ $package->title }}
                                </h3>
                                @if($package->masterType)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300 mt-2">
                                        {{ $package->masterType->name_type }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if($package->description)
                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3">
                                {{ Str::limit(strip_tags($package->description), 120) }}
                            </p>
                        @endif
                        <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                            <span class="flex items-center gap-1.5">
                                <i class="ti ti-book text-sm"></i>
                                {{ $package->materials->count() }} Materi
                            </span>
                            <span class="flex items-center gap-1.5">
                                <i class="ti ti-users text-sm"></i>
                                {{ $package->userJoins->count() }} Peserta
                            </span>
                        </div>
                        <div class="pt-2">
                            <x-button variant="primary" href="{{ route('profile.complete') }}" class="w-full rounded-xl shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 transition-shadow">
                                <i class="ti ti-plus mr-2"></i> Daftar Sekarang
                            </x-button>
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>

        @if($packages->hasPages())
            <div class="pt-8">
                {{ $packages->links() }}
            </div>
        @endif
    @else
        <x-card class="border border-dashed border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50">
            <div class="flex flex-col items-center justify-center text-center py-16">
                <div class="w-20 h-20 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-5">
                    <i class="ti ti-book-off text-4xl text-gray-400 dark:text-gray-500"></i>
                </div>
                <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                    @if(request('search'))
                        Paket tidak ditemukan
                    @else
                        Belum ada paket tersedia
                    @endif
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                    @if(request('search'))
                        Tidak ada hasil untuk "{{ request('search') }}". Coba kata kunci lain atau hapus filter.
                    @else
                        Saat ini belum ada paket yang tersedia. Nantikan update dari kami.
                    @endif
                </p>
            </div>
        </x-card>
    @endif
</div>
@endsection
