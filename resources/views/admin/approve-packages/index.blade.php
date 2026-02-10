@extends('layouts.app')

@section('title', 'Approve Paket')

@section('content')
<div class="space-y-8 pb-20" x-data="{ 
    rejectModal: false,
    approveModal: false, 
    approveUrl: '', 
    userName: '',
    paket: '',
    confirmApprove(url, name,paket) {
        this.approveUrl = url;
        this.userName = name;
        this.approveModal = true;
    },
    confirmReject(url, name,paket) {
        this.rejectUrl = url;
        this.userName = name;
        this.paket = paket;
        this.rejectModal = true;
    }
}">

    
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Konfirmasi Paket User</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Silahkan Kelola dan konfirmasi Paket User</p>
        </div>
       
    </div>

    {{-- Quick Stats --}}
    

    {{-- Search & Filter Section --}}
    <div class="flex flex-col md:flex-row gap-4">
        <form action="{{ route('approve-packages.index') }}" method="GET" class="relative flex-1 group">
            <i class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-600 transition-colors"></i>
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Cari paket terbaru..." 
                class="w-full pl-11 pr-12 py-3 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl text-sm outline-none focus:ring-4 focus:ring-indigo-500/5 transition-all dark:text-white"
            >
            @if(request('search'))
                <a href="{{ route('approve-packages.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition-colors">
                    <i class="ti ti-x"></i>
                </a>
            @endif
        </form>
    </div>

    {{-- Main Data Card --}}
    <x-card :padding="false" title="Daftar User Paket">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Nama Peserta</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Email</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Paket yang ingin di ikuti</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Status</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">Aksi</th>
                      
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse ($data as $value)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                           
                       
                            <td class="py-4 px-8 text-sm text-gray-500 font-medium">
                                {{ $value->user->name }}
                            </td>
                            <td class="py-4 px-8 text-sm text-gray-500 font-medium">
                                {{ $value->user->email }}
                            </td>
                            <td class="py-4 px-8 text-sm text-gray-500 font-medium">
                                {{ $value->package->title }}
                            </td>
                            <td class="py-4 px-8 text-sm text-gray-500 font-medium">
                                @if ($value->status === 'pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-300">
                                        <i class="ti ti-clock mr-1"></i> Menunggu Konfirmasi
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-8">
                                <div class="flex items-center justify-end gap-2">
                                    <x-button variant="success" size="sm" 
                                    class="rounded-lg h-9 w-9 p-0 flex items-center justify-center"
                                    @click="confirmApprove('{{ route('approve-packages.approve', $value->id) }}', '{{ $value->user->name }}', '{{ $value->package->title }}')"
                                    >
                                       <i class="ti ti-circle-dashed-check text-xl"></i>
                                    </x-button>
                                    <x-button variant="danger" size="sm" class="rounded-lg h-9 w-9 p-0 flex items-center justify-center"
                                    @click="confirmReject('{{ route('approve-packages.reject', $value->id) }}', '{{ $value->user->name }}', '{{ $value->package->title }}')"
                                    >
                                        <i class="ti ti-circle-dashed-x text-xl"></i>
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
                                                Tidak ada hasil untuk kata kunci "{{ request('search') }}". Coba gunakan kata kunci lain.
                                            @else
                                                Sepertinya data jenis Anda masih kosong. Mulai tambahkan data baru untuk mengelola data.
                                            @endif
                                        </p>
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


    {{-- Approve Confirmation Modal --}}
    <div x-show="approveModal" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        
        <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800"
            @click.away="approveModal = false">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-green-50 dark:bg-green-500/10 text-green-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i class="ti ti-circle-dashed-check text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Konfirmasi Pengguna ?</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    Anda akan menyetujui pengguna untuk mengikuti paket <span x-text="paket"></span> <span class="font-bold text-gray-900 dark:text-white" x-text="userName"></span>. Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            
            <div class="flex gap-3 p-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800">
                <x-button variant="secondary" class="flex-1 rounded-xl" @click="approveModal = false">
                    Batal
                </x-button>
                <form :action="approveUrl" method="POST" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <x-button variant="success" type="submit" class="w-full rounded-xl shadow-lg shadow-emerald-500/20">
                        Ya, Setujui
                    </x-button>
                </form>
            </div>
        </div>
    </div>
    <div x-show="rejectModal" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        
        <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800"
            @click.away="rejectModal = false">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-red-50 dark:bg-red-500/10 text-red-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                     <i class="ti ti-circle-dashed-x text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Tolak Pengguna ?</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    Anda akan menolak pengguna untuk mengikuti paket <span x-text="paket"></span> <span class="font-bold text-gray-900 dark:text-white" x-text="userName"></span>. Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            
            <div class="flex gap-3 p-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800">
                <x-button variant="secondary" class="flex-1 rounded-xl" @click="rejectModal = false">
                    Batal
                </x-button>
                <form :action="rejectUrl" method="POST" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <x-button variant="danger" type="submit" class="w-full rounded-xl shadow-lg shadow-red-500/20">
                        Ya, Tolak
                    </x-button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection