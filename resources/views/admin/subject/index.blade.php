@extends('layouts.app')

@section('title', 'Master Mata Pelajaran')

@section('content')
<div class="space-y-8 pb-20" x-data="{ 
    ModalMapel: false,
    deleteModalOpen: false,
    deleteUrl: '',
    subjectName: '' }">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Mata Pelajaran</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Kelola data mata pelajaran per jenis</p>
        </div>
        <div class="flex items-center gap-3">
         
            <x-button variant="secondary" href="{{ route('subjects.template-export') }}" icon="download" class="rounded-xl shadow-lg shadow-indigo-500/20">
                Download Template
            </x-button>
            <x-button variant="success" @click="ModalMapel = true" icon="plus" class="rounded-xl shadow-lg shadow-indigo-500/20">
                Import Mapel
            </x-button>
            <x-button variant="primary" href="{{ route('subjects.create') }}" icon="plus" class="rounded-xl shadow-lg shadow-indigo-500/20">
                Tambah Mapel
            </x-button>
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="flex flex-col lg:flex-row gap-4">
        <form action="{{ route('subjects.index') }}" method="GET" class="flex-1 flex flex-col md:flex-row gap-3 items-end">
            <div class="relative flex-1 group">
                <i class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-600 transition-colors"></i>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search ?? request('search') }}" 
                    placeholder="Cari mata pelajaran..." 
                    class="w-full pl-11 pr-12 py-3 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl text-sm outline-none focus:ring-4 focus:ring-indigo-500/5 transition-all dark:text-white"
                >
                @if(!empty($search ?? request('search')))
                    <a href="{{ route('subjects.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition-colors">
                        <i class="ti ti-x"></i>
                    </a>
                @endif
            </div>
            <div class="w-full md:w-64">
                <x-select name="type_id" label="Filter Jenis" inline>
                    <option value="">Semua Jenis</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ (int) ($typeId ?? 0) === $type->id ? 'selected' : '' }}>
                            {{ $type->name_type }}
                        </option>
                    @endforeach
                </x-select>
            </div>
            <div class="flex gap-2">
                <x-button type="submit" variant="primary" class="rounded-xl">
                    Terapkan
                </x-button>
            </div>
        </form>
    </div>

    {{-- Main Data Card --}}
    <x-card :padding="false" title="Daftar Mata Pelajaran">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Jenis</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Mata Pelajaran</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Kode</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse ($data as $value)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                            <td class="py-4 px-8">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-300">
                                    {{ $value->type->name_type ?? '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-8">
                                <div class="max-w-md">
                                    <div class="text-sm text-gray-900 dark:text-white font-semibold">
                                        {{ $value->name }}
                                    </div>
                                    @if($value->description)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">
                                            {{ \Illuminate\Support\Str::limit($value->description, 80) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-8 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                {{ $value->code ?? '-' }}
                            </td>
                            <td class="py-4 px-8">
                                <div class="flex items-center justify-end gap-2">
                                    <x-button variant="secondary" size="sm" href="{{ route('subjects.edit', $value->id) }}" class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                        <i class="ti ti-pencil text-base"></i>
                                    </x-button>

                                    <x-button variant="danger" size="sm" type="button"
                                        @click="deleteModalOpen = true; deleteUrl='{{ route('subjects.destroy', $value->id) }}'; subjectName='{{ $value->name }}';"
                                        class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                        <i class="ti ti-trash text-base"></i>
                                    </x-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-24">
                                <div class="flex flex-col items-center justify-center text-center max-w-[280px] mx-auto">
                                    <div class="space-y-1">
                                        <h4 class="text-base font-bold text-gray-900 dark:text-white">
                                            @if($search || $typeId) 
                                                Hasil tidak ditemukan 
                                            @else 
                                                Belum Ada Data 
                                            @endif
                                        </h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                            @if($search || $typeId)
                                                Tidak ada hasil untuk filter yang diterapkan. Coba gunakan kata kunci atau jenis lain.
                                            @else
                                                Belum ada mata pelajaran. Mulai tambahkan data baru untuk mengelola mata pelajaran per jenis.
                                            @endif
                                        </p>
                                    </div>
                                    <div class="mt-6">
                                        @if($search || $typeId)
                                            <x-button variant="secondary"  href="{{ route('subjects.index') }}">
                                                Reset Filter
                                            </x-button>
                                        @else
                                            <x-button variant="secondary"  href="{{ route('subjects.create') }}">
                                                <i class="ti ti-plus mr-2"></i> Tambah Sekarang
                                            </x-button>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($data->hasPages())
            <div class="px-8 py-6 border-t border-gray-50 dark:border-gray-800">
                {{ $data->links() }}
            </div>
        @endif
    </x-card>


       <div x-show="ModalMapel" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        
        <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800"
            @click.away="ModalMapel = false">
            
            <form action="{{ route('subjects.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-8">
                    <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="ti ti-file-upload text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 text-center">Import Mapel dari Excel</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed text-center mb-6">
                        Upload file Excel (.xlsx atau .xls) untuk import mapel dalam jumlah banyak.
                    </p>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                File Excel <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="file" 
                                name="file" 
                                accept=".xlsx,.xls"
                                required
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                            >
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                Format: .xlsx atau .xls, Max: 5MB
                            </p>
                        </div>

                        <div class="bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 rounded-xl p-4">
                            <div class="flex gap-3">
                                <div class="flex-shrink-0">
                                    <i class="ti ti-info-circle text-blue-500"></i>
                                </div>
                                <div class="text-xs text-blue-700 dark:text-blue-300 space-y-1">
                                    <p class="font-semibold">Petunjuk:</p>
                                    <ul class="list-disc list-inside space-y-1 ml-2">
                                        <li>Download template Excel terlebih dahulu</li>
                                        <li>Sesuaikan kode jenis dari sumber data</li>
                                        <li>Isi data sesuai format yang ada di template</li>
                                        <li>Pastikan data code tidak ada yang duplikat</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-3 p-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800">
                    <x-button variant="secondary" type="button" class="flex-1 rounded-xl" @click="ModalMapel = false">
                        Batal
                    </x-button>
                    <x-button variant="success" type="submit" class="flex-1 rounded-xl shadow-lg shadow-emerald-500/20">
                        <i class="ti ti-upload mr-2"></i> Import
                    </x-button>
                </div>
            </form>
        </div>
    </div>


    {{-- Delete Confirmation Modal --}}
    <div x-show="deleteModalOpen" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        
        <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800"
            @click.away="deleteModalOpen = false">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-rose-50 dark:bg-rose-500/10 text-rose-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i class="ti ti-trash-x text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Hapus Mata Pelajaran?</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    Anda akan menghapus mata pelajaran <span class="font-bold text-gray-900 dark:text-white" x-text="subjectName"></span>. Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            
            <div class="flex gap-3 p-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800">
                <x-button variant="secondary" class="flex-1 rounded-xl" @click="deleteModalOpen = false">
                    Batal
                </x-button>
                <form :action="deleteUrl" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <x-button variant="danger" type="submit" class="w-full rounded-xl shadow-lg shadow-rose-500/20">
                        Ya, Hapus
                    </x-button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


