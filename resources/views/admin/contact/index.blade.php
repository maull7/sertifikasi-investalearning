@extends('layouts.app')

@section('title', 'Pesan Kontak')

@section('content')
<div class="space-y-6 pb-20">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Pesan Kontak</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">Pesan masuk dari form kontak landing page.</p>
    </div>

    <form action="{{ route('admin.contact.index') }}" method="GET" class="relative max-w-sm">
        <i class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
            class="w-full pl-11 pr-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white">
    </form>

    <x-card :padding="false">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                        <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Nama</th>
                        <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Email</th>
                        <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Pesan</th>
                        <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Waktu</th>
                        <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Status</th>
                        <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse ($messages as $msg)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors {{ $msg->is_read ? '' : 'bg-indigo-50/40 dark:bg-indigo-900/10' }}">
                            <td class="py-3 px-6 font-semibold text-gray-900 dark:text-white whitespace-nowrap">
                                {{ $msg->name }}
                                @if (!$msg->is_read)
                                    <span class="ml-1 inline-flex w-2 h-2 rounded-full bg-indigo-500"></span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-gray-500 dark:text-gray-400">{{ $msg->email }}</td>
                            <td class="py-3 px-6 text-gray-700 dark:text-gray-300 max-w-xs">
                                <p class="line-clamp-2">{{ $msg->message }}</p>
                            </td>
                            <td class="py-3 px-6 text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                {{ $msg->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="py-3 px-6 text-center">
                                @if ($msg->is_read)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400">Dibaca</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">Baru</span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if (!$msg->is_read)
                                        <form action="{{ route('admin.contact.read', $msg) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <x-button variant="secondary" size="sm" type="submit" class="rounded-lg h-8 px-2 text-xs">
                                                <i class="ti ti-check"></i>
                                            </x-button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.contact.destroy', $msg) }}" method="POST"
                                        onsubmit="return confirm('Hapus pesan ini?')">
                                        @csrf @method('DELETE')
                                        <x-button variant="danger" size="sm" type="submit" class="rounded-lg h-8 px-2 text-xs">
                                            <i class="ti ti-trash"></i>
                                        </x-button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-20 text-center text-sm text-gray-400">Belum ada pesan masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($messages->hasPages())
            <div class="px-6 py-4 border-t border-gray-50 dark:border-gray-800">
                {{ $messages->links() }}
            </div>
        @endif
    </x-card>
</div>
@endsection
