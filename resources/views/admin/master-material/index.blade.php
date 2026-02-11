@extends('layouts.app')

@section('title', 'Master Material')

@section('content')
<div class="space-y-8 pb-20" x-data="{ 
    deleteModalOpen: false, 
    deleteUrl: '', 
    materialName: '',
    previewModalOpen: false,
    previewContent: '',
    previewTitle: '',
    confirmDelete(url, name) {
        this.deleteUrl = url;
        this.materialName = name;
        this.deleteModalOpen = true;
    },
    showPreview(title, description) {
        this.previewTitle = title;
        this.previewContent = description;
        this.previewModalOpen = true;
    }
}">

    
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Material</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Kelola Data Material Anda</p>
        </div>
        <div class="flex items-center gap-3">
            <x-button variant="primary" href="{{ route('master-materials.create') }}" icon="plus" class="rounded-xl shadow-lg shadow-indigo-500/20">
                Tambah Material Baru
            </x-button>
        </div>
    </div>

    {{-- Search & Filter Section --}}
    <div class="flex flex-col md:flex-row gap-4">
        <form action="{{ route('master-materials.index') }}" method="GET" class="relative flex-1 group">
            <i class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-600 transition-colors"></i>
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Cari material..." 
                class="w-full pl-11 pr-12 py-3 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl text-sm outline-none focus:ring-4 focus:ring-indigo-500/5 transition-all dark:text-white"
            >
            @if(request('search'))
                <a href="{{ route('master-materials.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition-colors">
                    <i class="ti ti-x"></i>
                </a>
            @endif
        </form>
    </div>

    {{-- Main Data Card --}}
    <x-card :padding="false" title="Daftar Material">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Mapel</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Title</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Jenis Materi</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Preview</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse ($data as $value)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                            <td class="py-4 px-8">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                    {{ $value->subject->name ?? '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-8">
                                <div class="max-w-xs">
                                    <p class="text-sm text-gray-900 dark:text-white font-semibold mb-1">{{ $value->title }}</p>
                                    <button type="button" @click="showPreview('{{ $value->title }}', `{!! addslashes($value->description ?? '') !!}`)" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">
                                        Lihat Deskripsi →
                                    </button>
                                </div>
                            </td>
                            <td class="py-4 px-8">
                                @if($value->materi_type === 'File')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                                        File
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">
                                        Link Video
                                    </span>
                                @endif
                            </td>
                    
                            <td class="py-4 px-8">
                                <div class="flex items-center justify-center gap-2">
                                    @if($value->materi_type === 'File')
                                        <a href="{{ route('master-materials.preview', $value->id) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:hover:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 text-xs font-semibold rounded-lg transition-colors">
                                            <i class="ti ti-eye text-sm"></i>
                                            Preview
                                        </a>

                                    @else
                                         <a href="{{ $value->value }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 hover:bg-red-100 dark:bg-red-500/10 dark:hover:bg-red-500/20 text-red-600 dark:text-red-400 text-xs font-semibold rounded-lg transition-colors">
                                            <i class="ti ti-eye text-sm"></i>
                                            Lihat Video
                                        </a>

                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-8">
                                <div class="flex items-center justify-end gap-2">
                                    <x-button variant="secondary" size="sm" href="{{ route('master-materials.edit', $value->id) }}" class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                        <i class="ti ti-pencil text-base"></i>
                                    </x-button>

                                    <x-button variant="danger" size="sm" type="button"
                                        @click="confirmDelete('{{ route('master-materials.destroy', $value->id) }}', '{{ $value->title }}')"
                                        class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                        <i class="ti ti-trash text-base"></i>
                                    </x-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-24">
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
                                                Tidak ada hasil untuk kata kunci "{{ request('search') }}". Coba gunakan kata kunci lain.
                                            @else
                                                Sepertinya data material Anda masih kosong. Mulai tambahkan data baru untuk mengelola data.
                                            @endif
                                        </p>
                                    </div>
                                    <div class="mt-6">
                                        @if(request('search'))
                                            <x-button variant="secondary"  href="{{ route('master-materials.index') }}">
                                                Reset Pencarian
                                            </x-button>
                                        @else
                                            <x-button variant="secondary"  href="{{ route('master-materials.create') }}">
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

    {{-- Preview Modal --}}
    <div x-show="previewModalOpen" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        
        <div class="bg-white dark:bg-gray-900 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800 max-h-[60vh] flex flex-col"
            @click.away="previewModalOpen = false">
            
            {{-- Header --}}
            <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-800">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-indigo-50 dark:bg-indigo-500/10 rounded-lg flex items-center justify-center">
                        <i class="ti ti-file-text text-lg text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white" x-text="previewTitle"></h3>
                        <p class="text-[10px] text-gray-500">Deskripsi Material</p>
                    </div>
                </div>
                <button @click="previewModalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <i class="ti ti-x text-xl"></i>
                </button>
            </div>

            {{-- Content --}}
            <div class="flex-1 overflow-y-auto p-4">
                <div class="prose prose-sm dark:prose-invert max-w-none text-sm" x-html="previewContent"></div>
            </div>

            {{-- Footer --}}
            <div class="p-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                <x-button variant="secondary" @click="previewModalOpen = false" class="w-full rounded-lg text-sm py-2">
                    Tutup
                </x-button>
            </div>
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
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Hapus Material?</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    Anda akan menghapus material <span class="font-bold text-gray-900 dark:text-white" x-text="materialName"></span>. File terkait juga akan dihapus. Tindakan ini tidak dapat dibatalkan.
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

<style>
    .prose {
        color: inherit;
    }
    .prose p {
        margin-bottom: 1em;
    }
    .prose ul, .prose ol {
        margin-left: 1.5em;
        margin-bottom: 1em;
    }
    .prose li {
        margin-bottom: 0.5em;
    }
    .prose strong {
        font-weight: 700;
    }
    .prose a {
        color: #4f46e5;
        text-decoration: underline;
    }
    .dark .prose a {
        color: #818cf8;
    }
</style>
@endsection
