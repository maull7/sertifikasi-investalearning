@extends('layouts.app')

@section('title', 'Peserta Paket - ' . $package->title)

@section('content')
<div class="space-y-8 pb-20">
    {{-- Header & Breadcrumb --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs font-medium text-gray-400 mb-1">
                <a href="{{ route('approve-packages.index') }}" class="hover:text-indigo-600 transition-colors">Persetujuan Paket</a>
                <i class="ti ti-chevron-right"></i>
                <a href="{{ route('approve-packages.index', ['tab' => 'active']) }}" class="hover:text-indigo-600 transition-colors">Paket Aktif</a>
                <i class="ti ti-chevron-right"></i>
                <span class="text-gray-500 dark:text-gray-500">Peserta</span>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $package->title }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">
                {{ $package->masterType->name_type ?? '-' }} · {{ $members->total() }} peserta
            </p>
        </div>
        <x-button variant="secondary" href="{{ route('approve-packages.index', ['tab' => 'active']) }}" class="rounded-xl">
            <i class="ti ti-arrow-left mr-2"></i> Kembali ke Paket Aktif
        </x-button>
    </div>

    <x-card :padding="false" title="Daftar Peserta">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">No</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Nama</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Email</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Bergabung</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse ($members as $join)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                            <td class="py-4 px-8 text-sm text-gray-500 dark:text-gray-400">{{ $members->firstItem() + $loop->index }}</td>
                            <td class="py-4 px-8 font-medium text-gray-900 dark:text-white">{{ $join->user->name }}</td>
                            <td class="py-4 px-8 text-sm text-gray-600 dark:text-gray-400">{{ $join->user->email }}</td>
                            <td class="py-4 px-8 text-sm text-gray-500 dark:text-gray-400">{{ $join->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-24">
                                <div class="flex flex-col items-center justify-center text-center max-w-[280px] mx-auto">
                                    <h4 class="text-base font-bold text-gray-900 dark:text-white">Belum ada peserta</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mt-1">
                                        Belum ada user yang disetujui untuk paket ini.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($members->hasPages())
            <div class="px-8 py-6 border-t border-gray-50 dark:border-gray-800">
                {{ $members->links() }}
            </div>
        @endif
    </x-card>
</div>
@endsection
