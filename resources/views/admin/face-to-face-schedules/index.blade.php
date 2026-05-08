@extends('layouts.app')

@section('title', 'Jadwal Tatap Muka')

@section('content')
    <div class="space-y-8 pb-20" x-data="{
        deleteModal: false,
        deleteUrl: '',
        deleteName: '',
        confirmDelete(url, name) {
            this.deleteUrl = url;
            this.deleteName = name;
            this.deleteModal = true;
        }
    }">

        {{-- Header --}}
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Jadwal Tatap Muka</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola jadwal guru per paket dan meeting Zoom.</p>
            </div>
            <a href="{{ route('admin.face-to-face-schedules.create') }}"
                class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                <i class="ti ti-plus mr-1"></i> Tambah Jadwal
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Daftar Jadwal Tatap Muka</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[860px] text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                            <th class="py-3 px-4 w-10"></th>
                            <th class="py-3 px-4 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Judul</th>
                            <th class="py-3 px-4 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Paket</th>
                            <th class="py-3 px-4 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Ruangan</th>
                            <th class="py-3 px-4 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Sesi</th>
                            <th class="py-3 px-4 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Peserta</th>
                            <th class="py-3 px-4 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Status</th>
                            <th class="py-3 px-4 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>

                    @forelse ($schedules as $schedule)
                        {{-- One tbody per row so x-data spans both the main row and the expand row --}}
                        <tbody x-data="{ open: false }" class="border-b border-gray-100 dark:border-gray-800">

                            {{-- Main row --}}
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors">
                                <td class="py-3 px-4">
                                    <button type="button" @click="open = !open"
                                        class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors mx-auto">
                                        <i class="ti text-sm transition-transform duration-200"
                                            :class="open ? 'ti-chevron-down' : 'ti-chevron-right'"></i>
                                    </button>
                                </td>
                                <td class="py-3 px-4">
                                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $schedule->title }}</p>
                                </td>
                                <td class="py-3 px-4">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $schedule->package?->title ?? '-' }}</p>
                                </td>
                                <td class="py-3 px-4">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $schedule->room_name }}</p>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                        <i class="ti ti-calendar-event text-[10px]"></i> {{ $schedule->sessions_count }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                                        <i class="ti ti-users text-[10px]"></i> {{ $schedule->registrations_count }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if ($schedule->is_active)
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">Aktif</span>
                                    @else
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.face-to-face-schedules.participants', $schedule) }}" title="Peserta"
                                            class="h-8 w-8 rounded-lg flex items-center justify-center bg-sky-50 dark:bg-sky-900/20 text-sky-600 hover:bg-sky-100 dark:hover:bg-sky-900/40 transition-colors">
                                            <i class="ti ti-users text-sm"></i>
                                        </a>
                                        <a href="{{ route('admin.face-to-face-schedules.edit', $schedule) }}" title="Edit"
                                            class="h-8 w-8 rounded-lg flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                                            <i class="ti ti-pencil text-sm"></i>
                                        </a>
                                        <button type="button" title="Hapus"
                                            @click="confirmDelete('{{ route('admin.face-to-face-schedules.destroy', $schedule) }}', '{{ addslashes($schedule->title) }}')"
                                            class="h-8 w-8 rounded-lg flex items-center justify-center bg-rose-50 dark:bg-rose-900/20 text-rose-600 hover:bg-rose-100 dark:hover:bg-rose-900/40 transition-colors">
                                            <i class="ti ti-trash text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Expand row --}}
                            <tr x-show="open" x-collapse>
                                <td colspan="8" class="bg-gray-50/60 dark:bg-gray-800/20 px-0 py-0">
                                    @if ($schedule->sessions->isNotEmpty())
                                        <div class="px-14 py-3">
                                            <table class="w-full text-left">
                                                <thead>
                                                    <tr class="text-[10px] font-bold uppercase text-gray-400 tracking-wider border-b border-gray-200 dark:border-gray-700">
                                                        <th class="pb-2 pr-8 whitespace-nowrap">Nama Sesi</th>
                                                        <th class="pb-2 pr-8 whitespace-nowrap">Tanggal</th>
                                                        <th class="pb-2 pr-8 whitespace-nowrap">Waktu</th>
                                                        <th class="pb-2 whitespace-nowrap">Guru</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                                                    @foreach ($schedule->sessions as $session)
                                                        <tr>
                                                            <td class="py-2 pr-8 text-sm font-medium text-gray-700 dark:text-gray-200 whitespace-nowrap">{{ $session->name }}</td>
                                                            <td class="py-2 pr-8 text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $session->session_date->format('d M Y') }}</td>
                                                            <td class="py-2 pr-8 text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ substr((string)$session->start_time,0,5) }} – {{ substr((string)$session->end_time,0,5) }}</td>
                                                            <td class="py-2 text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $session->teacher?->name ?? '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="px-14 py-3 text-xs text-gray-400">Belum ada sesi.</p>
                                    @endif
                                </td>
                            </tr>

                        </tbody>
                    @empty
                        <tbody>
                            <tr>
                                <td colspan="8" class="py-10 text-center text-sm text-gray-400">Belum ada jadwal tatap muka.</td>
                            </tr>
                        </tbody>
                    @endforelse

                </table>
            </div>

            @if ($schedules->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                    {{ $schedules->links() }}
                </div>
            @endif
        </div>

        {{-- Delete Modal --}}
        <div x-show="deleteModal"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" x-cloak
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
            <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800"
                @click.away="deleteModal = false">
                <div class="p-8 text-center">
                    <div class="w-20 h-20 bg-rose-50 dark:bg-rose-500/10 text-rose-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="ti ti-trash text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Hapus Jadwal?</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                        Anda akan menghapus jadwal <span class="font-bold text-gray-900 dark:text-white" x-text="deleteName"></span>.
                        Semua sesi dan data peserta akan ikut terhapus.
                    </p>
                </div>
                <div class="flex gap-3 p-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800">
                    <x-button variant="secondary" class="flex-1 rounded-xl" @click="deleteModal = false">Batal</x-button>
                    <form :action="deleteUrl" method="POST" class="flex-1">
                        @csrf @method('DELETE')
                        <x-button variant="danger" type="submit" class="w-full rounded-xl shadow-lg shadow-rose-500/20">Ya, Hapus</x-button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
