@extends('layouts.app')

@section('title', 'Bank Question')

@section('content')
<div class="space-y-8 pb-20" x-data="{ 
    deleteModalOpen: false, 
    deleteUrl: '', 
    questionTitle: '',
    importModalOpen: false,
    confirmDelete(url, title) {
        this.deleteUrl = url;
        this.questionTitle = title;
        this.deleteModalOpen = true;
    }
}">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">History Exam</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Lihat riwayat ujian Anda</p>
        </div>
      
    </div>

    {{-- Search & Filter Section --}}
    {{-- <div class="flex flex-col lg:flex-row gap-4">
        <form 
            action="{{ route('bank-questions.index') }}" 
            method="GET" 
            class="flex-1 flex flex-col md:flex-row gap-3 items-end"
        >
            <!-- Search -->
            <div class="relative flex-1 group">
                <i class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-600 transition-colors"></i>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search ?? request('search') }}" 
                    placeholder="Cari soal..." 
                    class="w-full h-12 pl-11 pr-12 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl text-sm outline-none focus:ring-4 focus:ring-indigo-500/5 transition-all dark:text-white"
                >
                @if(!empty($search ?? request('search')))
                    <a href="{{ route('bank-questions.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition-colors">
                        <i class="ti ti-x"></i>
                    </a>
                @endif
            </div>

            <!-- Select -->
            <div class="w-full md:w-64">
                <x-select 
                    name="type_id" 
                    label="Filter Tipe Soal" 
                    inline
                    class="h-12"
                >
                    <option value="">Semua Tipe</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ (int) ($typeId ?? 0) === $type->id ? 'selected' : '' }}>
                            {{ $type->name_type }}
                        </option>
                    @endforeach
                </x-select>
            </div>

            <!-- Button -->
            <div class="flex gap-2">
                <x-button 
                    type="submit" 
                    variant="primary" 
                    class="h-12 px-6 rounded-xl"
                >
                    Terapkan
                </x-button>
            </div>
        </form>
    </div> --}}

    {{-- Main Data Card --}}
    <x-card :padding="false" title="Riwayat Ujian Anda">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Paket</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Ujian / Exam</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Tipe</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Total Soal</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Terjawab</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Total Poin</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Aksi</th>
                        
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse ($history as $data)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                            <td class="py-4 px-8">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-300">
                                    {{ $data->Package->title ?? '-' }}
                                </span>
                            </td>
                          
                            <td class="py-4 px-8 text-center">
                                <span class="inline-flex items-center justify-center rounded-lg font-bold text-sm ">
                                    {{ strtoupper($data->Exam->title) }}
                                </span>
                            </td>
                            <td class="py-4 px-8 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg font-bold text-sm">
                                    {{ strtoupper($data->Type->name_type) }}
                                </span>
                            </td>
                            <td class="py-4 px-8 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg font-bold text-sm">
                                    {{ strtoupper($data->total_questions) }}
                                </span>
                            </td>
                            <td class="py-4 px-8 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg font-bold text-sm">
                                    {{ strtoupper($data->questions_answered) }}
                                </span>
                            </td>
                            <td class="py-4 px-8 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg font-bold text-sm">
                                    {{ strtoupper($data->total_score) }}
                                </span>
                            </td>
                            <td class="py-4 px-8 text-center">
                                <div class="flex items-center justify-end gap-2">
                                    <x-button variant="primary" size="sm" href="{{ route('user.history-exams.detail', $data->id) }}" class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                        <i class="ti ti-eye text-base"></i>
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
                                            @if(request('search')) 
                                                Hasil tidak ditemukan 
                                            @else 
                                                Belum Ada Data 
                                            @endif
                                        </h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                            @if(request('search'))
                                                Tidak ada hasil untuk kata kunci "{{ request('search') }}".
                                            @else
                                                Belum ada soal. Mulai tambahkan soal baru.
                                            @endif
                                        </p>
                                    </div>
                                    <div class="mt-6">
                                        @if(request('search'))
                                            <x-button variant="secondary"  href="{{ route('bank-questions.index') }}">
                                                Reset Pencarian
                                            </x-button>
                                        @else
                                            <x-button variant="secondary"  href="{{ route('bank-questions.create') }}">
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

        @if($history->hasPages())
            <div class="px-8 py-6 border-t border-gray-50 dark:border-gray-800">
                {{ $history->links() }}
            </div>
        @endif
    </x-card>

    {{-- Import Modal --}}
    <div x-show="importModalOpen" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        
        <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800"
            @click.away="importModalOpen = false">
            
            <form action="{{ route('bank-questions.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-8">
                    <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="ti ti-file-upload text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 text-center">Import Soal dari Excel</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed text-center mb-6">
                        Upload file Excel (.xlsx atau .xls) untuk import soal dalam jumlah banyak.
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
                                        <li>Isi data sesuai format yang ada di template</li>
                                        <li>Pastikan kolom "tipe_soal" sesuai dengan master tipe yang ada</li>
                                        <li>Jawaban harus berupa huruf A, B, C, atau D</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-3 p-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800">
                    <x-button variant="secondary" type="button" class="flex-1 rounded-xl" @click="importModalOpen = false">
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
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Hapus Soal?</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    Anda akan menghapus soal <span class="font-bold text-gray-900 dark:text-white" x-text="questionTitle"></span>. Gambar terkait juga akan dihapus. Tindakan ini tidak dapat dibatalkan.
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

