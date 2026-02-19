@extends('layouts.app')

@section('title', 'Approve Paket')

@section('content')
<div class="space-y-8 pb-20" x-data="{
    rejectModal: false,
    approveModal: false,
    approveUrl: '',
    rejectUrl: '',
    userName: '',
    paket: '',
    confirmApprove(url, name, pkt) {
        this.approveUrl = url;
        this.userName = name;
        this.paket = pkt;
        this.approveModal = true;
    },
    confirmReject(url, name, pkt) {
        this.rejectUrl = url;
        this.userName = name;
        this.paket = pkt;
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
    

    {{-- Search --}}
    <form action="{{ route('approve-packages.index') }}" method="GET" class="relative max-w-xl">
        @if(request('active_page'))<input type="hidden" name="active_page" value="{{ request('active_page') }}">@endif
        @if(request('pending_page'))<input type="hidden" name="pending_page" value="{{ request('pending_page') }}">@endif
        @if(request('tab'))<input type="hidden" name="tab" value="{{ request('tab') }}">@endif
        <i class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-600 transition-colors"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari paket..."
            class="w-full pl-11 pr-12 py-3 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl text-sm outline-none focus:ring-4 focus:ring-indigo-500/5 transition-all dark:text-white">
        @if(request('search'))
            <a href="{{ route('approve-packages.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition-colors"><i class="ti ti-x"></i></a>
        @endif
    </form>

    {{-- Tabs --}}
    <div class="flex gap-2 border-b border-gray-200 dark:border-gray-700">
        <a href="{{ route('approve-packages.index', array_filter(array_merge(request()->only('search', 'pending_page'), ['tab' => null, 'active_page' => null]))) }}"
            class="px-4 py-3 text-sm font-semibold border-b-2 transition-colors {{ !request('tab') ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-400' }}">
            <i class="ti ti-clock mr-2"></i> User Perlu Konfirmasi
        </a>
        <a href="{{ route('approve-packages.index', array_filter(array_merge(request()->only('search', 'active_page'), ['tab' => 'active', 'pending_page' => null]))) }}"
            class="px-4 py-3 text-sm font-semibold border-b-2 transition-colors {{ request('tab') === 'active' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-400' }}">
            <i class="ti ti-package mr-2"></i> Paket Aktif
        </a>
    </div>

    @if(!request('tab'))
    {{-- Tab 1: User yang harus dikonfirmasi --}}
    <x-card :padding="false" title="User Perlu Konfirmasi Paket">
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
                           
                       
                            <td class="py-4 px-8 text-sm font-medium text-gray-900 dark:text-white">{{ $value->user->name }}</td>
                            <td class="py-4 px-8 text-sm text-gray-500 dark:text-gray-400">{{ $value->user->email }}</td>
                            <td class="py-4 px-8 text-sm text-gray-700 dark:text-gray-300">{{ $value->package->title }}</td>
                            <td class="py-4 px-8">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-300">
                                    <i class="ti ti-clock mr-1"></i> Menunggu Konfirmasi
                                </span>
                            </td>
                            <td class="py-4 px-8">
                                <div class="flex items-center justify-end gap-2">
                                    <x-button variant="success" size="sm" class="rounded-lg h-9 w-9 p-0 flex items-center justify-center"
                                        @click="confirmApprove('{{ route('approve-packages.approve', $value->id) }}', '{{ addslashes($value->user->name) }}', '{{ addslashes($value->package->title) }}')">
                                        <i class="ti ti-circle-dashed-check text-xl"></i>
                                    </x-button>
                                    <x-button variant="danger" size="sm" class="rounded-lg h-9 w-9 p-0 flex items-center justify-center"
                                        @click="confirmReject('{{ route('approve-packages.reject', $value->id) }}', '{{ addslashes($value->user->name) }}', '{{ addslashes($value->package->title) }}')">
                                        <i class="ti ti-circle-dashed-x text-xl"></i>
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
                {{ $data->appends(request()->only(['search', 'pending_page']))->links() }}
            </div>
        @endif
    </x-card>
    @else
    {{-- Tab 2: Paket Aktif --}}
    <x-card :padding="false" title="Daftar Paket Aktif">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Paket</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Jenis</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Jumlah Peserta</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse ($activePackages as $pkg)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                            <td class="py-4 px-8 font-medium text-gray-900 dark:text-white">{{ $pkg->title }}</td>
                            <td class="py-4 px-8 text-sm text-gray-600 dark:text-gray-400">{{ $pkg->masterType->name_type ?? '-' }}</td>
                            <td class="py-4 px-8">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                    {{ $pkg->approved_members_count ?? 0 }} Peserta
                                </span>
                            </td>
                            <td class="py-4 px-8">
                                <div class="flex items-center justify-end">
                                    <x-button variant="secondary" size="sm" href="{{ route('approve-packages.package.show', $pkg) }}" class="rounded-lg">
                                        <i class="ti ti-users mr-1"></i> Lihat Peserta
                                    </x-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-24">
                                <div class="flex flex-col items-center justify-center text-center max-w-[280px] mx-auto">
                                    <h4 class="text-base font-bold text-gray-900 dark:text-white">
                                        @if(request('search')) Hasil tidak ditemukan @else Belum ada paket aktif @endif
                                    </h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mt-1">
                                        @if(request('search')) Coba kata kunci lain. @else Paket dengan status aktif akan muncul di sini. @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($activePackages->hasPages())
            <div class="px-8 py-6 border-t border-gray-50 dark:border-gray-800">
                {{ $activePackages->appends(array_merge(request()->only('search', 'active_page'), ['tab' => 'active']))->links() }}
            </div>
        @endif
    </x-card>
    @endif

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
                    Anda akan menyetujui <span class="font-bold text-gray-900 dark:text-white" x-text="userName"></span> untuk mengikuti paket <span x-text="paket"></span>. Tindakan ini tidak dapat dibatalkan.
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
                    Anda akan menolak <span class="font-bold text-gray-900 dark:text-white" x-text="userName"></span> untuk mengikuti paket <span x-text="paket"></span>. Tindakan ini tidak dapat dibatalkan.
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