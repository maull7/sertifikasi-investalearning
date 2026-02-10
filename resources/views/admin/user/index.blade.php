@extends('layouts.app')

@section('title', 'Aktivasi Akun')

@section('content')
@php
    $currentTab = $tab ?? 'pending';
    $baseUrl = route('user.not.active');
@endphp
<div class="space-y-6 pb-20" x-data="{
    modalActivation: false,
    activateUrl: null,
    userName: ''
}">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Aktivasi Akun</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">Kelola dan riwayat akun peserta</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="border-b border-gray-200 dark:border-gray-800">
        <nav class="flex gap-1" aria-label="Tabs">
            <a href="{{ $baseUrl }}?tab=pending{{ request('search') ? '&search='.urlencode(request('search')) : '' }}"
               class="px-4 py-3 text-sm font-semibold rounded-t-xl transition-colors {{ $currentTab === 'pending' ? 'bg-white dark:bg-gray-900 border border-b-0 border-gray-200 dark:border-gray-800 text-indigo-600 dark:text-indigo-400 -mb-px' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                <span class="flex items-center gap-2">
                    <i class="ti ti-user-question"></i>
                    Belum Teraktivasi
                </span>
            </a>
            <a href="{{ $baseUrl }}?tab=activated{{ request('search') ? '&search='.urlencode(request('search')) : '' }}"
               class="px-4 py-3 text-sm font-semibold rounded-t-xl transition-colors {{ $currentTab === 'activated' ? 'bg-white dark:bg-gray-900 border border-b-0 border-gray-200 dark:border-gray-800 text-indigo-600 dark:text-indigo-400 -mb-px' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                <span class="flex items-center gap-2">
                    <i class="ti ti-user-check"></i>
                    Riwayat Teraktivasi
                </span>
            </a>
            <a href="{{ $baseUrl }}?tab=google{{ request('search') ? '&search='.urlencode(request('search')) : '' }}"
               class="px-4 py-3 text-sm font-semibold rounded-t-xl transition-colors {{ $currentTab === 'google' ? 'bg-white dark:bg-gray-900 border border-b-0 border-gray-200 dark:border-gray-800 text-indigo-600 dark:text-indigo-400 -mb-px' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                <span class="flex items-center gap-2">
                    <i class="ti ti-brand-google"></i>
                    Registrasi Google
                </span>
            </a>
        </nav>
    </div>

    {{-- Search (tab-aware) --}}
    <form action="{{ $baseUrl }}" method="GET" class="flex flex-col md:flex-row gap-4">
        <input type="hidden" name="tab" value="{{ $currentTab }}">
        <div class="relative flex-1 group">
            <i class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors text-base"></i>
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama, email, atau telepon..."
                   class="w-full pl-11 pr-12 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
            @if(request('search'))
                <a href="{{ $baseUrl }}?tab={{ $currentTab }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 dark:hover:text-rose-400 transition-colors">
                    <i class="ti ti-x text-lg"></i>
                </a>
            @endif
        </div>
        <x-button type="submit" variant="secondary" class="rounded-xl shrink-0">Cari</x-button>
    </form>

    {{-- Tab: Belum Teraktivasi --}}
    @if($currentTab === 'pending')
        <x-card :padding="false" title="Akun yang Perlu Diaktivasi">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider whitespace-nowrap">Nama Peserta</th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider whitespace-nowrap">Email</th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center whitespace-nowrap">Instansi</th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center whitespace-nowrap">Phone</th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse ($list as $data)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                                <td class="py-3 px-4 sm:px-6">
                                    <span class="font-semibold text-sm text-gray-900 dark:text-white">{{ $data->name ?? '-' }}</span>
                                </td>
                                <td class="py-3 px-4 sm:px-6">
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-300">{{ $data->email ?? '-' }}</span>
                                </td>
                                <td class="py-3 px-4 sm:px-6 text-center text-sm text-gray-700 dark:text-gray-300">{{ $data->institusi ?? '-' }}</td>
                                <td class="py-3 px-4 sm:px-6 text-center text-sm text-gray-700 dark:text-gray-300">{{ $data->phone ?? '-' }}</td>
                                <td class="py-3 px-4 sm:px-6 text-center">
                                    <x-button variant="primary" size="sm"
                                        @click="modalActivation = true; activateUrl = '{{ route('user.activate', $data->id) }}'; userName = {{ \Illuminate\Support\Js::from($data->name ?? '') }};"
                                        class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                        <i class="ti ti-user-check text-base"></i>
                                    </x-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-24">
                                    <div class="flex flex-col items-center justify-center text-center max-w-[320px] mx-auto">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                            <i class="ti ti-users-off text-2xl text-gray-400"></i>
                                        </div>
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">
                                            @if(request('search')) Hasil tidak ditemukan @else Belum Ada Data @endif
                                        </h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                            @if(request('search')) Tidak ada hasil untuk "{{ request('search') }}". @else Belum ada akun yang perlu diaktivasi. @endif
                                        </p>
                                        @if(request('search'))
                                            <x-button variant="secondary" href="{{ $baseUrl }}?tab=pending" class="rounded-xl mt-6">Reset Pencarian</x-button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($list->hasPages())
                <div class="px-4 sm:px-6 lg:px-8 py-6 border-t border-gray-50 dark:border-gray-800">{{ $list->links() }}</div>
            @endif
        </x-card>
    @endif

    {{-- Tab: Riwayat Teraktivasi --}}
    @if($currentTab === 'activated')
        <x-card :padding="false" title="Riwayat Akun yang Sudah Diaktivasi">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider whitespace-nowrap">Nama Peserta</th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider whitespace-nowrap">Email</th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center whitespace-nowrap">Instansi</th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center whitespace-nowrap">Phone</th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider whitespace-nowrap">Terakhir Diperbarui</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse ($list as $data)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors">
                                <td class="py-3 px-4 sm:px-6 font-semibold text-sm text-gray-900 dark:text-white">{{ $data->name ?? '-' }}</td>
                                <td class="py-3 px-4 sm:px-6">
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">{{ $data->email ?? '-' }}</span>
                                </td>
                                <td class="py-3 px-4 sm:px-6 text-center text-sm text-gray-700 dark:text-gray-300">{{ $data->institusi ?? '-' }}</td>
                                <td class="py-3 px-4 sm:px-6 text-center text-sm text-gray-700 dark:text-gray-300">{{ $data->phone ?? '-' }}</td>
                                <td class="py-3 px-4 sm:px-6 text-sm text-gray-500 dark:text-gray-400">{{ $data->updated_at?->format('d/m/Y H:i') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-24">
                                    <div class="flex flex-col items-center justify-center text-center max-w-[320px] mx-auto">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                            <i class="ti ti-user-check text-2xl text-gray-400"></i>
                                        </div>
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">Belum ada riwayat</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Belum ada akun yang pernah diaktivasi.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($list->hasPages())
                <div class="px-4 sm:px-6 lg:px-8 py-6 border-t border-gray-50 dark:border-gray-800">{{ $list->links() }}</div>
            @endif
        </x-card>
    @endif

    {{-- Tab: Registrasi Google --}}
    @if($currentTab === 'google')
        <x-card :padding="false" title="Riwayat Akun Registrasi Google">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider whitespace-nowrap">Nama</th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider whitespace-nowrap">Email</th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center whitespace-nowrap">Status</th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center whitespace-nowrap">Phone</th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider whitespace-nowrap">Tanggal Daftar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse ($list as $data)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors">
                                <td class="py-3 px-4 sm:px-6">
                                    <div class="flex items-center gap-2">
                                        @if($data->avatar)
                                            <img src="{{ $data->avatar }}" alt="" class="w-8 h-8 rounded-full object-cover">
                                        @else
                                            <span class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                                                <i class="ti ti-user text-sm"></i>
                                            </span>
                                        @endif
                                        <span class="font-semibold text-sm text-gray-900 dark:text-white">{{ $data->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 sm:px-6">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">
                                        <i class="ti ti-brand-google text-xs"></i> {{ $data->email ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 sm:px-6 text-center">
                                    @if(($data->status_user ?? '') === 'Teraktivasi')
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">Teraktivasi</span>
                                    @else
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">Belum Teraktivasi</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 sm:px-6 text-center text-sm text-gray-700 dark:text-gray-300">{{ $data->phone ?? '-' }}</td>
                                <td class="py-3 px-4 sm:px-6 text-sm text-gray-500 dark:text-gray-400">{{ $data->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-24">
                                    <div class="flex flex-col items-center justify-center text-center max-w-[320px] mx-auto">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                            <i class="ti ti-brand-google text-2xl text-gray-400"></i>
                                        </div>
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">Belum ada data</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Belum ada akun yang mendaftar lewat Google.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($list->hasPages())
                <div class="px-4 sm:px-6 lg:px-8 py-6 border-t border-gray-50 dark:border-gray-800">{{ $list->links() }}</div>
            @endif
        </x-card>
    @endif

    {{-- Modal konfirmasi aktivasi (hanya dipakai di tab pending) --}}
    <div x-show="modalActivation"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-2xl shadow-2xl overflow-hidden border border-gray-200 dark:border-gray-800"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.away="modalActivation = false">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="ti ti-user-check text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Aktivasi Akun?</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    Anda akan mengaktifkan akun atas nama <span class="font-bold text-gray-900 dark:text-white" x-text="userName"></span>. Email aktivasi akan dikirim ke user.
                </p>
            </div>
            <div class="flex gap-3 p-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800">
                <x-button variant="secondary" class="flex-1 rounded-xl" @click="modalActivation = false">Batal</x-button>
                <form :action="activateUrl" method="POST" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <x-button variant="primary" type="submit" class="w-full rounded-xl">Ya, Aktifkan</x-button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
