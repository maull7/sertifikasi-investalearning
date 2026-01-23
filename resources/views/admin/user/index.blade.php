@extends('layouts.app')

@section('title', 'Bank Question')

@section('content')
<div class="space-y-8 pb-20" x-data="{
    modalActivation: false,
    activateUrl: null,
    userName: ''
}"
>

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Aktivasi akun</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Akun belum teraktivasi</p>
        </div>
      
    </div>

    {{-- Search & Filter Section --}}
     <div class="flex flex-col md:flex-row gap-4">
        <form action="{{ route('user.not.active') }}" method="GET" class="relative flex-1 group">
            <i class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-600 transition-colors"></i>
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Cari nama user..." 
                class="w-full pl-11 pr-12 py-3 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl text-sm outline-none focus:ring-4 focus:ring-indigo-500/5 transition-all dark:text-white"
            >
            @if(request('search'))
                <a href="{{ route('master-types.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition-colors">
                    <i class="ti ti-x"></i>
                </a>
            @endif
        </form>
    </div>

    {{-- Main Data Card --}}
    <x-card :padding="false" title="Daftar Akun Peserta yang belum teraktivasi">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Nama Peserta</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Email</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Instansi</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Phone</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse ($list as $data)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ">
                                    {{ $data->name ?? '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-8">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-300">
                                    {{ $data->email ?? '-' }}
                                </span>
                            </td>
                          
                            <td class="py-4 px-8 text-center">
                                <span class="inline-flex items-center justify-center rounded-lg font-bold text-sm ">
                                    {{ strtoupper($data->institusi) }}
                                </span>
                            </td>
                            <td class="py-4 px-8 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg font-bold text-sm">
                                    {{ strtoupper($data->phone) }}
                                </span>
                            </td>
        
                            <td class="py-4 px-8 text-center">
                                <div class="flex items-center justify-end gap-2">
                             <x-button 
                                variant="primary" 
                                size="sm"
                                @click="
                                    modalActivation = true;
                                    activateUrl = '{{ route('user.activate', $data->id) }}';
                                    userName = '{{ $data->name }}';
                                "
                            >
                                <i class="ti ti-user-check"></i>
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
                                                Belum ada data riwayat ujian.
                                            @endif
                                        </p>
                                    </div>
                                    <div class="mt-6">
                                        @if(request('search'))
                                            <x-button variant="secondary"  href="{{ route('show-grades.index') }}">
                                                Reset Pencarian
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

        @if($list->hasPages())
            <div class="px-8 py-6 border-t border-gray-50 dark:border-gray-800">
                {{ $list->links() }}
            </div>
        @endif
    </x-card>

  {{-- Activation Confirmation Modal --}}
<div x-show="modalActivation" 
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
    x-cloak
    x-transition>
    
    <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800"
        @click.away="modalActivation = false">

        <div class="p-8 text-center">
            <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
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
                    class="w-full rounded-xl shadow-lg shadow-emerald-500/20">
                    Ya, Aktifkan
                </x-button>
            </form>
        </div>
    </div>
</div>



</div>
@endsection

