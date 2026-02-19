@extends('layouts.app')

@section('title', 'Monitor Peserta')

@section('content')
<div class="space-y-8 pb-20">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Monitor Peserta</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">Pilih paket untuk melihat daftar peserta dan pantau nilainya</p>
        </div>
    </div>

    <form action="{{ route('monitor-participants.index') }}" method="GET" class="relative max-w-xl">
        <i class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari paket..."
            class="w-full pl-11 pr-12 py-3 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl text-sm outline-none focus:ring-4 focus:ring-indigo-500/5 transition-all dark:text-white">
        @if(request('search'))
            <a href="{{ route('monitor-participants.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition-colors"><i class="ti ti-x"></i></a>
        @endif
    </form>

    <x-card :padding="false" title="Daftar Paket">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Paket</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Jenis</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Jumlah Peserta</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse ($packages as $pkg)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                            <td class="py-4 px-8 font-medium text-gray-900 dark:text-white">{{ $pkg->title }}</td>
                            <td class="py-4 px-8 text-sm text-gray-600 dark:text-gray-400">{{ $pkg->masterType->name_type ?? '-' }}</td>
                            <td class="py-4 px-8 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                    {{ $pkg->participants_count ?? 0 }} Peserta
                                </span>
                            </td>
                            <td class="py-4 px-8">
                                <div class="flex items-center justify-end">
                                    <x-button variant="primary" size="sm" href="{{ route('monitor-participants.package', $pkg) }}" class="rounded-xl">
                                        <i class="ti ti-users mr-1"></i> Lihat Peserta
                                    </x-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-24">
                                <div class="flex flex-col items-center justify-center text-center max-w-[280px] mx-auto">
                                    <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-4">
                                        <i class="ti ti-package text-3xl text-gray-400"></i>
                                    </div>
                                    <h4 class="text-base font-bold text-gray-900 dark:text-white">
                                        @if(request('search')) Hasil tidak ditemukan @else Belum ada paket dengan peserta @endif
                                    </h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        @if(request('search')) Coba kata kunci lain. @else Paket yang punya peserta disetujui akan muncul di sini. @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($packages->hasPages())
            <div class="px-8 py-6 border-t border-gray-50 dark:border-gray-800">
                {{ $packages->links() }}
            </div>
        @endif
    </x-card>
</div>
@endsection
