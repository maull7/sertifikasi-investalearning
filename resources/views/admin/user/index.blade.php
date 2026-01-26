@extends('layouts.app')

@section('title', 'Aktivasi Akun')

@section('content')
<div class="space-y-6 pb-20" x-data="{
    modalActivation: false,
    activateUrl: null,
    userName: ''
}">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Aktivasi Akun</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">Kelola akun peserta yang belum teraktivasi</p>
        </div>
    </div>

    {{-- Search & Filter Section --}}
    <div class="flex flex-col md:flex-row gap-4">
        <form action="{{ route('user.not.active') }}" method="GET" class="relative flex-1 group">
            <i class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors text-base"></i>
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Cari nama atau email user..." 
                class="w-full pl-11 pr-12 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
            >
            @if(request('search'))
                <a href="{{ route('user.not.active') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 dark:hover:text-rose-400 transition-colors">
                    <i class="ti ti-x text-lg"></i>
                </a>
            @endif
        </form>
    </div>

    {{-- Main Data Card --}}
    <x-card :padding="false" title="Daftar Akun Belum Teraktivasi">
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
                                <div class="flex flex-col">
                                    <span class="font-semibold text-sm text-gray-900 dark:text-white">
                                        {{ $data->name ?? '-' }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-3 px-4 sm:px-6">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-300">
                                    {{ $data->email ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 sm:px-6 text-center">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $data->institusi ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 sm:px-6 text-center">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $data->phone ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 sm:px-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <x-button 
                                        variant="primary" 
                                        size="sm"
                                        @click="
                                            modalActivation = true;
                                            activateUrl = '{{ route('user.activate', $data->id) }}';
                                            userName = '{{ $data->name }}';
                                        "
                                        class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                        <i class="ti ti-user-check text-base"></i>
                                    </x-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-24">
                                <div class="flex flex-col items-center justify-center text-center max-w-[320px] mx-auto">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                        <i class="ti ti-users-off text-2xl text-gray-400"></i>
                                    </div>
                                    <div class="space-y-2">
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">
                                            @if(request('search')) 
                                                Hasil tidak ditemukan 
                                            @else 
                                                Belum Ada Data 
                                            @endif
                                        </h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                                            @if(request('search'))
                                                Tidak ada hasil untuk kata kunci "{{ request('search') }}".
                                            @else
                                                Belum ada akun peserta yang belum teraktivasi.
                                            @endif
                                        </p>
                                    </div>
                                    @if(request('search'))
                                        <div class="mt-6">
                                            <x-button variant="secondary" href="{{ route('user.not.active') }}" class="rounded-xl">
                                                Reset Pencarian
                                            </x-button>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($list->hasPages())
            <div class="px-4 sm:px-6 lg:px-8 py-6 border-t border-gray-50 dark:border-gray-800">
                {{ $list->links() }}
            </div>
        @endif
    </x-card>

    {{-- Activation Confirmation Modal --}}
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

                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                    Aktivasi Akun?
                </h3>

                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    Anda akan mengaktifkan akun atas nama
                    <span class="font-bold text-gray-900 dark:text-white" x-text="userName"></span>.
                    Email aktivasi akan dikirim ke user.
                </p>
            </div>
            
            <div class="flex gap-3 p-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800">
                <x-button 
                    variant="secondary" 
                    class="flex-1 rounded-xl"
                    @click="modalActivation = false">
                    Batal
                </x-button>

                <form :action="activateUrl" method="POST" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <x-button 
                        variant="primary" 
                        type="submit" 
                        class="w-full rounded-xl">
                        Ya, Aktifkan
                    </x-button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

