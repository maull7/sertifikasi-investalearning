@extends('layouts.app')

@section('title', 'Mapping Mapel')

@section('content')
    <div class="space-y-8 pb-20" x-data="{
        deleteModalOpen: false,
        deleteUrl: '',
        subjectName: '',
        confirmDelete(url, name) {
            this.deleteUrl = url;
            this.subjectName = name;
            this.deleteModalOpen = true;
        }
    }">

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Mapping Mapel</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Kelola mapping mapel untuk tiap paket</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="primary" href="{{ route('mapping-package.create') }}" icon="plus"
                    class="rounded-xl shadow-lg shadow-indigo-500/20">
                    Tambah Mapping Mapel
                </x-button>
            </div>
        </div>

        {{-- Search --}}
        <div class="flex flex-col md:flex-row gap-4">
            <form action="{{ route('mapping-package.index') }}" method="GET" class="relative flex-1 group">
                <i class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-600 transition-colors"></i>
                <input type="text" name="search" value="{{ $search ?? request('search') }}"
                    placeholder="Cari paket atau jenis..."
                    class="w-full pl-11 pr-12 py-3 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl text-sm outline-none focus:ring-4 focus:ring-indigo-500/5 transition-all dark:text-white">
                @if (!empty($search ?? request('search')))
                    <a href="{{ route('mapping-package.index') }}"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition-colors">
                        <i class="ti ti-x"></i>
                    </a>
                @endif
            </form>
        </div>

        {{-- Tabel Paket --}}
        <x-card :padding="false" title="Daftar Paket dengan Mapping Mapel">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Paket</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Jenis</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Total Mapel</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse ($packages as $value)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                                <td class="py-4 px-8">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                        {{ $value->title }}
                                    </span>
                                </td>
                                <td class="py-4 px-8">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $value->masterType->name_type ?? '-' }}</span>
                                </td>
                                <td class="py-4 px-8">
                                    @php($mappedCount = $value->mappedSubjects->count())
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-700 dark:bg-gray-800 dark:text-gray-200">
                                        {{ $mappedCount }} Mapel
                                    </span>
                                </td>
                                <td class="py-4 px-8">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-button variant="secondary" size="sm"
                                            href="{{ route('mapping-package.manage', $value) }}"
                                            class="rounded-lg px-3 py-1.5 text-xs font-semibold">
                                            <i class="ti ti-list-details mr-1"></i> Lihat Mapping
                                        </x-button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-16 text-center">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        @if ($search ?? null)
                                            Tidak ada hasil untuk "{{ $search }}".
                                        @else
                                            Belum ada paket.
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($packages->hasPages())
                <div class="px-8 py-6 border-t border-gray-50 dark:border-gray-800">
                    {{ $packages->links() }}
                </div>
            @endif
        </x-card>
    </div>
@endsection
