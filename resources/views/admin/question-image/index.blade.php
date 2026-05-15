@extends('layouts.app')

@section('title', 'Gambar Soal')

@section('content')
<div class="space-y-8 pb-20" x-data="{
    uploadModalOpen: false,
    deleteModalOpen: false,
    deleteUrl: '',
    imageName: '',
    copiedId: null,
    copyUrl(url, id) {
        navigator.clipboard.writeText(url).then(() => {
            this.copiedId = id;
            setTimeout(() => this.copiedId = null, 2000);
        });
    }
}">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Gambar Soal</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Kelola gambar untuk soal</p>
        </div>
        <div class="flex items-center gap-3">
            <x-button variant="primary" type="button" @click="uploadModalOpen = true" icon="plus"
                class="rounded-xl shadow-lg shadow-indigo-500/20">
                Upload Gambar
            </x-button>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    <x-card :padding="false" title="Semua Gambar">
        @if ($images->count() > 0)
            <div class="p-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach ($images as $image)
                    <div class="group relative rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden bg-white dark:bg-gray-900 hover:shadow-lg transition-shadow">
                        <div class="aspect-square overflow-hidden bg-gray-100 dark:bg-gray-800">
                            <img src="{{ $image->url }}" alt="{{ $image->filename }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="p-3 space-y-1.5">
                            <p class="text-xs font-medium text-gray-700 dark:text-gray-300 truncate" title="{{ $image->filename }}">
                                {{ $image->filename }}
                            </p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500">
                                {{ $image->file_size_for_humans }}
                            </p>
                            <div class="flex items-center gap-1 pt-1">
                                <button @click="copyUrl('{{ $image->url }}', {{ $image->id }})"
                                    class="flex-1 text-[10px] font-semibold py-1.5 rounded-lg transition-colors"
                                    :class="copiedId === {{ $image->id }} ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-500/20'">
                                    <template x-if="copiedId === {{ $image->id }}">
                                        <span><i class="ti ti-check mr-1"></i>Copied!</span>
                                    </template>
                                    <template x-if="copiedId !== {{ $image->id }}">
                                        <span><i class="ti ti-copy mr-1"></i>Salin Link</span>
                                    </template>
                                </button>
                                <button @click="deleteModalOpen = true; deleteUrl = '{{ route('question-images.destroy', $image->id) }}'; imageName = '{{ $image->filename }}'"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                                    <i class="ti ti-trash text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($images->hasPages())
                <div class="px-8 py-6 border-t border-gray-50 dark:border-gray-800">
                    {{ $images->links() }}
                </div>
            @endif
        @else
            <div class="py-24">
                <div class="flex flex-col items-center justify-center text-center max-w-[280px] mx-auto">
                    <div class="w-16 h-16 bg-indigo-50 dark:bg-indigo-500/10 rounded-2xl flex items-center justify-center mb-4">
                        <i class="ti ti-photo text-2xl text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <h4 class="text-base font-bold text-gray-900 dark:text-white mb-1">Belum Ada Gambar</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mb-6">
                        Upload gambar soal untuk mulai mengelola gambar.
                    </p>
                    <x-button variant="primary" type="button" @click="uploadModalOpen = true">
                        <i class="ti ti-plus mr-2"></i> Upload Gambar
                    </x-button>
                </div>
            </div>
        @endif
    </x-card>

    {{-- Upload Modal --}}
    <div x-show="uploadModalOpen"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

        <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800"
            @click.away="uploadModalOpen = false">

            <form action="{{ route('question-images.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-8">
                    <div class="w-20 h-20 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="ti ti-upload text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 text-center">Upload Gambar</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed text-center mb-6">
                        Upload gambar untuk digunakan pada soal tipe Image.
                    </p>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Pilih Gambar <span class="text-rose-500">*</span>
                            </label>
                            <input type="file" name="image" accept="image/jpeg,image/jpg,image/png,image/webp" required
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                JPG, PNG, WEBP. Maksimal 5MB.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 p-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800">
                    <x-button variant="secondary" type="button" class="flex-1 rounded-xl" @click="uploadModalOpen = false">
                        Batal
                    </x-button>
                    <x-button variant="primary" type="submit" class="flex-1 rounded-xl shadow-lg shadow-indigo-500/20">
                        <i class="ti ti-upload mr-2"></i> Upload
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="deleteModalOpen"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

        <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800"
            @click.away="deleteModalOpen = false">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-rose-50 dark:bg-rose-500/10 text-rose-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i class="ti ti-trash-x text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Hapus Gambar?</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    Anda akan menghapus <span class="font-bold text-gray-900 dark:text-white" x-text="imageName"></span>.
                    Gambar yang terpakai di soal tidak akan tampil. Tindakan ini tidak dapat dibatalkan.
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
