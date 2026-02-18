@extends('layouts.app')

@section('title', 'Master Paket')

@section('content')
    <div class="space-y-8 pb-20" x-data="{
        ModalPaket: false,
        toggleModal: false,
        toggleUrl: '',
        userName: '',
        confirmToggle(url, name) {
            this.toggleUrl = url;
            this.userName = name;
            this.toggleModal = true;
        }
    }">


        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Paket</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Kelola Data Paket Anda</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="secondary" href="{{ route('master-packages.download-template') }}" icon="plus"
                    class="rounded-xl shadow-lg shadow-indigo-500/20">
                    Download Paket
                </x-button>
                <x-button variant="success" @click="ModalPaket = true" icon="plus"
                    class="rounded-xl shadow-lg shadow-indigo-500/20">
                    Import Paket
                </x-button>
                <x-button variant="primary" href="{{ route('master-packages.create') }}" icon="plus"
                    class="rounded-xl shadow-lg shadow-indigo-500/20">
                    Tambah Paket Baru
                </x-button>
            </div>
        </div>

        {{-- Quick Stats --}}


        {{-- Search & Filter Section --}}
        <div class="flex flex-col md:flex-row gap-4">
            <form action="{{ route('master-packages.index') }}" method="GET" class="relative flex-1 group">
                <i
                    class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-600 transition-colors"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari paket terbaru..."
                    class="w-full pl-11 pr-12 py-3 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl text-sm outline-none focus:ring-4 focus:ring-indigo-500/5 transition-all dark:text-white">
                @if (request('search'))
                    <a href="{{ route('master-types.index') }}"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition-colors">
                        <i class="ti ti-x"></i>
                    </a>
                @endif
            </form>
        </div>

        {{-- Main Data Card --}}
        <x-card :padding="false" title="Daftar Paket Aktif">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Jenis</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Title</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Description
                            </th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Status</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">
                                Aksi</th>

                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse ($data as $value)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                                <td class="py-4 px-8 text-sm text-gray-500 font-medium">
                                    {{ $value->masterType->name_type }}
                                </td>
                                <td class="py-4 px-8 text-sm text-gray-500 font-medium">
                                    {{ $value->title }}
                                </td>
                                <td class="py-4 px-8 text-sm text-gray-500 font-medium">
                                    {{ $value->description }}
                                </td>
                                <td class="py-4 px-8 text-sm text-gray-500 font-medium">
                                    @if ($value->status === 'active')
                                        <span
                                            class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">
                                            Nonaktif
                                        </span>
                                    @endif

                                </td>
                                <td class="py-4 px-8">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-button variant="secondary" size="sm"
                                            href="{{ route('master-packages.edit', $value->id) }}"
                                            class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                            <i class="ti ti-pencil text-base"></i>
                                        </x-button>

                                        <x-button variant="{{ $value->status === 'active' ? 'danger' : 'success' }}"
                                            size="sm" type="button"
                                            @click="confirmToggle('{{ route('master-packages.toggle-active', $value->id) }}', '{{ $value->title }}', '{{ $value->status === 'active' ? 'nonaktifkan' : 'aktifkan' }}')"
                                            class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                            <i class="ti ti-toggle-left text-base"></i>
                                        </x-button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-24">
                                    <div
                                        class="flex flex-col items-center justify-center text-center max-w-[280px] mx-auto">

                                        <div class="space-y-1">
                                            <h4 class="text-base font-bold text-gray-900 dark:text-white">
                                                @if (request('search'))
                                                    Hasil tidak ditemukan
                                                @else
                                                    Belum Ada Data
                                                @endif
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                                @if (request('search'))
                                                    Tidak ada hasil untuk kata kunci "{{ request('search') }}". Coba
                                                    gunakan kata kunci lain.
                                                @else
                                                    Sepertinya data jenis Anda masih kosong. Mulai tambahkan data baru untuk
                                                    mengelola data.
                                                @endif
                                            </p>
                                        </div>
                                        <div class="mt-6">
                                            @if (request('search'))
                                                <x-button variant="secondary" href="{{ route('master-types.index') }}">
                                                    Reset Pencarian
                                                </x-button>
                                            @else
                                                <x-button variant="secondary" href="{{ route('master-types.create') }}">
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

            @if ($data->hasPages())
                <div class="px-8 py-6 border-t border-gray-50 dark:border-gray-800">
                    {{ $data->links() }}
                </div>
            @endif
        </x-card>

        <div x-show="ModalPaket"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" x-cloak
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

            <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800"
                @click.away="ModalPaket = false">

                <form action="{{ route('master-packages.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-8">
                        <div
                            class="w-20 h-20 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                            <i class="ti ti-file-upload text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 text-center">Import Paket dari Excel
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed text-center mb-6">
                            Upload file Excel (.xlsx atau .xls) untuk import paket dalam jumlah banyak.
                        </p>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    File Excel <span class="text-rose-500">*</span>
                                </label>
                                <input type="file" name="file" accept=".xlsx,.xls" required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    Format: .xlsx atau .xls, Max: 5MB
                                </p>
                            </div>

                            <div
                                class="bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 rounded-xl p-4">
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

                    <div
                        class="flex gap-3 p-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800">
                        <x-button variant="secondary" type="button" class="flex-1 rounded-xl"
                            @click="ModalPaket = false">
                            Batal
                        </x-button>
                        <x-button variant="success" type="submit"
                            class="flex-1 rounded-xl shadow-lg shadow-emerald-500/20">
                            <i class="ti ti-upload mr-2"></i> Import
                        </x-button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Toggle Confirmation Modal --}}
        <div x-show="toggleModal"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" x-cloak
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

            <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800"
                @click.away="toggleModal = false">
                <div class="p-8 text-center">
                    <div
                        class="w-20 h-20 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="ti ti-toggle-left text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Ubah Status Paket?</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                        Anda akan mengubah status paket <span class="font-bold text-gray-900 dark:text-white"
                            x-text="userName"></span>
                    </p>
                </div>

                <div class="flex gap-3 p-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800">
                    <x-button variant="secondary" class="flex-1 rounded-xl" @click="toggleModal = false">
                        Batal
                    </x-button>
                    <form :action="toggleUrl" method="POST" class="flex-1">
                        @csrf
                        @method('PATCH')
                        <x-button variant="success" type="submit"
                            class="w-full rounded-xl shadow-lg shadow-emerald-500/20">
                            Ya, Ubah Status
                        </x-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
