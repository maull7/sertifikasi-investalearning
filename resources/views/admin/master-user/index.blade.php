@extends('layouts.app')

@section('title', 'Master User')

@section('content')
    <div class="space-y-8 pb-20" x-data="{
        deleteModalOpen: false,
        deleteUrl: '',
        subjectName: ''
    }">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Master User</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                    Kelola user Admin dan Petugas. Petugas dapat login dengan menu sama seperti admin kecuali Master dan
                    Pelatihan.
                </p>
            </div>
            <x-button variant="primary" href="{{ route('master-user.create') }}"
                class="rounded-xl shadow-lg shadow-indigo-500/20">
                <i class="ti ti-plus mr-2"></i> Tambah User
            </x-button>
        </div>

        <form action="{{ route('master-user.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
            <div class="relative flex-1 min-w-[200px]">
                <i class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau email..."
                    class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500">
            </div>
            <select name="role"
                class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                <option value="">Semua Role</option>
                <option value="Admin" {{ $roleFilter === 'Admin' ? 'selected' : '' }}>Admin</option>
                <option value="Petugas" {{ $roleFilter === 'Petugas' ? 'selected' : '' }}>Petugas</option>
            </select>
            <x-button type="submit" variant="secondary" class="rounded-xl">Filter</x-button>
        </form>

        @if (session('success'))
            <div
                class="rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 px-4 py-3 text-sm text-emerald-800 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        <x-card :padding="false" title="Daftar User (Admin & Petugas)">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Nama
                            </th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Email
                            </th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Role
                            </th>
                            <th
                                class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse ($users as $u)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors">
                                <td class="py-3 px-4 sm:px-6 font-semibold text-gray-900 dark:text-white">
                                    {{ $u->name }}</td>
                                <td class="py-3 px-4 sm:px-6 text-gray-600 dark:text-gray-400">{{ $u->email }}</td>
                                <td class="py-3 px-4 sm:px-6">
                                    <span
                                        class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        {{ $u->role === 'Admin' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-500/20 dark:text-indigo-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-500/20 dark:text-gray-300' }}">
                                        {{ $u->role }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 sm:px-6 text-right">
                                    <x-button variant="warning" size="sm" href="{{ route('master-user.edit', $u) }}"
                                        class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                        <i class="ti ti-pencil text-base"></i>
                                    </x-button>
                                    <x-button
                                        @click="deleteModalOpen = true; deleteUrl='{{ route('master-user.destroy', $u) }}'; subjectName='{{ $u->name }}';"
                                        variant="danger" size="sm"
                                        class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                        <i class="ti ti-trash text-base"></i>
                                    </x-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-24 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada user Admin/Petugas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-50 dark:border-gray-800">
                    {{ $users->links() }}
                </div>
            @endif
        </x-card>
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
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Hapus Data User ?</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                        Anda akan menghapus User <span class="font-bold text-gray-900 dark:text-white"
                            x-text="subjectName"></span>. Tindakan ini tidak dapat dibatalkan.
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
