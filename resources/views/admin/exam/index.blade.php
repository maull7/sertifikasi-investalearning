@extends('layouts.app')

@section('title', 'Ujian')

@section('content')
    <div class="space-y-8 pb-20" x-data="{
        deleteModalOpen: false,
        deleteUrl: '',
        examTitle: '',
        confirmDelete(url, title) {
            this.deleteUrl = url;
            this.examTitle = title;
            this.deleteModalOpen = true;
        }
    }">

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Ujian</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Kelola Data Ujian Anda</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="primary" href="{{ route('exams.create') }}" icon="plus"
                    class="rounded-xl shadow-lg shadow-indigo-500/20">
                    Tambah Ujian Baru
                </x-button>
            </div>
        </div>

        {{-- Search --}}
        <div class="flex flex-col md:flex-row gap-4">
            <form action="{{ route('exams.index') }}" method="GET" class="relative flex-1 group">
                <i
                    class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-600 transition-colors"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ujian..."
                    class="w-full pl-11 pr-12 py-3 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl text-sm outline-none focus:ring-4 focus:ring-indigo-500/5 transition-all dark:text-white">
                @if (request('search'))
                    <a href="{{ route('exams.index') }}"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition-colors">
                        <i class="ti ti-x"></i>
                    </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <x-card :padding="false" title="Daftar Ujian">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Paket</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Mata
                                Pelajaran</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Judul</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Durasi</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">KKM</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">
                                Jumlah Soal</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse ($data as $value)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                                <td class="py-4 px-8">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                        {{ $value->package->title ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-8">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                        {{ $value->subjects->isNotEmpty() ? $value->subjects->pluck('name')->join(', ') : '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-8">
                                    <div class="max-w-md">
                                        <div class="text-sm text-gray-900 dark:text-white font-semibold">
                                            {{ $value->title }}
                                        </div>
                                        @if ($value->description)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">
                                                {{ Str::limit($value->description, 60) }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-8">
                                    <span
                                        class="inline-flex items-center gap-1 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                        <i class="ti ti-clock text-amber-500"></i>
                                        {{ $value->duration }} menit
                                    </span>
                                </td>
                                <td class="py-4 px-8">
                                    <span
                                        class="inline-flex items-center justify-center px-3 py-1 rounded-lg bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 font-bold text-sm">
                                        {{ $value->passing_grade }}
                                    </span>
                                </td>
                                <td class="py-4 px-8 text-center">
                                    @php($total = $value->total_questions ?? $value->mappingQuestions()->count())
                                    <span
                                        class="inline-flex items-center justify-center px-3 py-1 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-200 text-xs font-semibold">
                                        {{ $total }} Soal
                                    </span>
                                </td>
                                <td class="py-4 px-8">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-button variant="info" size="sm"
                                            href="{{ route('exams.show', $value->id) }}"
                                            class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                            <i class="ti ti-eye text-base"></i>
                                        </x-button>
                                        <x-button variant="secondary" size="sm"
                                            href="{{ route('exams.edit', $value->id) }}"
                                            class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                            <i class="ti ti-pencil text-base"></i>
                                        </x-button>
                                        <x-button variant="danger" size="sm" type="button"
                                            @click="confirmDelete('{{ route('exams.destroy', $value->id) }}', '{{ $value->title }}')"
                                            class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                            <i class="ti ti-trash text-base"></i>
                                        </x-button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-24">
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
                                                    Tidak ada hasil untuk kata kunci "{{ request('search') }}".
                                                @else
                                                    Belum ada ujian. Mulai tambahkan ujian baru.
                                                @endif
                                            </p>
                                        </div>
                                        <div class="mt-6">
                                            @if (request('search'))
                                                <x-button variant="secondary" href="{{ route('exams.index') }}">
                                                    Reset Pencarian
                                                </x-button>
                                            @else
                                                <x-button variant="secondary" href="{{ route('exams.create') }}">
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

        {{-- Delete Confirmation Modal --}}
        <div x-show="deleteModalOpen"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" x-cloak
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

            <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800"
                @click.away="deleteModalOpen = false">
                <div class="p-8 text-center">
                    <div
                        class="w-20 h-20 bg-rose-50 dark:bg-rose-500/10 text-rose-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="ti ti-trash-x text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Hapus Ujian?</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                        Anda akan menghapus ujian <span class="font-bold text-gray-900 dark:text-white"
                            x-text="examTitle"></span>. Tindakan ini tidak dapat dibatalkan.
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
