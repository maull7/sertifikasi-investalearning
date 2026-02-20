@extends('layouts.app')

@section('title', 'Mapping Mapel - ' . $package->title)

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

        {{-- Header (sama seperti mapping soal) --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                    Mapping Mapel - {{ $package->title }}
                </h1>
                <span class="text-gray-600 dark:text-gray-400 text-sm">
                    Deskripsi :
                </span>
                <p class="text-sm text-gray-800 dark:text-gray-400 font-semibold">
                    {!! $package->description !!}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="secondary" href="{{ route('mapping-package.index') }}" class="rounded-xl">
                    <i class="ti ti-arrow-left mr-2"></i> Kembali ke Daftar
                </x-button>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 px-4 py-3 text-sm text-emerald-800 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-8">
            {{-- Kiri: Pilih Mapel dari Bank (sama struktur mapping soal) --}}
            <div class="space-y-4">
                <x-card :padding="false" title="Pilih Mapel dari Jenis Paket">
                    <div class="border-b border-gray-100 dark:border-gray-800 px-6 py-4 flex flex-col md:flex-row gap-4 md:items-center md:justify-between">
                        <div class="flex-1 flex flex-col md:flex-row gap-3 md:items-center">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Jenis paket: <span class="font-semibold text-gray-900 dark:text-white">{{ $package->masterType->name_type ?? '-' }}</span>. Pilih mapel di bawah lalu tambah ke paket.
                            </div>
                            <x-button type="button" variant="secondary" class="rounded-xl" href="{{ route('mapping-package.manage', $package) }}">
                                Reset
                            </x-button>
                        </div>
                    </div>
                    <form action="{{ route('mapping-package.store', $package) }}" method="POST">
                        @csrf
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                                        <th class="py-3 px-6 w-10"></th>
                                        <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Mapel</th>
                                        <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Kode</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                    @forelse ($availableSubjects as $subject)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                                            <td class="py-3 px-6 align-top">
                                                <input type="checkbox" name="subject_ids[]" value="{{ $subject->id }}"
                                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            </td>
                                            <td class="py-3 px-6 align-top">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-300">
                                                    {{ $subject->name }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-6 align-top text-sm text-gray-600 dark:text-gray-400">{{ $subject->code ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-10">
                                                <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                                                    Tidak ada mapel tersedia / semua sudah ter-mapping ke paket ini.
                                                </p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($availableSubjects->isNotEmpty())
                            <div class="px-6 py-4 border-t border-gray-50 dark:border-gray-800 flex justify-end">
                                <x-button type="submit" variant="primary" class="rounded-xl shadow-lg shadow-indigo-500/20">
                                    Tambah ke Paket
                                </x-button>
                            </div>
                        @endif
                    </form>
                </x-card>
            </div>

            {{-- Kanan: Mapel yang sudah di paket --}}
            <div class="space-y-4">
                <x-card :padding="false" title="Mapel di Paket Ini">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                                    <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Mapel</th>
                                    <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Kode</th>
                                    <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                @forelse ($mappedSubjects as $subject)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                                        <td class="py-3 px-6 align-top">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-300">
                                                {{ $subject->name }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6 align-top text-sm text-gray-600 dark:text-gray-400">{{ $subject->code ?? '-' }}</td>
                                        <td class="py-3 px-6 align-top">
                                            <div class="flex items-center justify-end gap-2">
                                                <x-button variant="danger" size="sm" type="button"
                                                    @click="confirmDelete('{{ route('mapping-package.destroy', [$package, $subject]) }}', '{{ addslashes($subject->name) }}')"
                                                    class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                                    <i class="ti ti-trash text-base"></i>
                                                </x-button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-10">
                                            <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                                                Belum ada mapel yang di-mapping ke paket ini.
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-card>
            </div>
        </div>

        {{-- Delete Confirmation Modal (sama seperti mapping soal) --}}
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
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Hapus Mapel?</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                        Anda akan menghapus mapel <span class="font-bold text-gray-900 dark:text-white" x-text="subjectName"></span> dari paket. Tindakan ini tidak dapat dibatalkan.
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
