@extends('layouts.app')

@section('title', 'Status Akun Peserta')

@section('content')
    <div class="space-y-6 pb-20" x-data="{
        selectAll: false,
        toggleAll() {
            this.selectAll = !this.selectAll;
            document.querySelectorAll('input[name=\'user_ids[]\']').forEach(cb => {
                cb.checked = this.selectAll;
            });
        }
    }">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                    Status Akun Peserta
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">
                    Nonaktifkan atau aktifkan kembali akun peserta secara massal, dengan filter berdasarkan paket.
                </p>
            </div>
        </div>

        <x-card>
            <form method="GET" action="{{ route('user-status.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-1">
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">
                            Cari Peserta
                        </label>
                        <div class="relative">
                            <i
                                class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500"></i>
                            <input type="text" name="search" value="{{ $search }}"
                                placeholder="Nama, email, atau telepon..."
                                class="w-full pl-9 pr-3 py-2.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white">
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">
                            Filter Paket
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-40 overflow-y-auto border border-gray-200 dark:border-gray-800 rounded-xl p-3 bg-gray-50 dark:bg-gray-900/40">
                            @foreach ($packages as $package)
                                <label class="flex items-center gap-2 text-xs text-gray-700 dark:text-gray-300">
                                    <input type="checkbox" name="package_ids[]"
                                           value="{{ $package->id }}"
                                           {{ in_array($package->id, $selectedPackageIds, true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span>{{ $package->title }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <x-button type="submit" variant="secondary" class="rounded-xl">
                        Terapkan Filter
                    </x-button>
                    <x-button type="link" href="{{ route('user-status.index') }}" variant="secondary" class="rounded-xl">
                        Reset
                    </x-button>
                </div>
            </form>
        </x-card>

        <x-card :padding="false" title="Daftar Peserta">
            <form method="POST" action="{{ route('user-status.bulk') }}">
                @csrf
                <div class="w-full overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                        <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                            <th class="py-3 px-4 sm:px-6">
                                <input type="checkbox" @click.prevent="toggleAll"
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">
                                Nama
                            </th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">
                                Email
                            </th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">
                                Status
                            </th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">
                                Paket Diikuti
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors">
                                <td class="py-3 px-4 sm:px-6 align-top">
                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </td>
                                <td class="py-3 px-4 sm:px-6 align-top">
                                    <div class="font-semibold text-sm text-gray-900 dark:text-white">
                                        {{ $user->name }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        {{ $user->phone ?? '-' }}
                                    </div>
                                </td>
                                <td class="py-3 px-4 sm:px-6 align-top">
                                    <span
                                        class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">
                                        {{ $user->email }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 sm:px-6 align-top text-center">
                                    @if($user->is_active)
                                        <span
                                            class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 sm:px-6 align-top">
                                    @php
                                        $packagesForUser = $user->joinedPackages->pluck('package.title')->unique()->values();
                                    @endphp
                                    @if($packagesForUser->isEmpty())
                                        <span class="text-xs text-gray-400 dark:text-gray-500">Belum ada paket</span>
                                    @else
                                        <ul class="space-y-0.5">
                                            @foreach($packagesForUser as $title)
                                                <li class="text-xs text-gray-700 dark:text-gray-300">
                                                    • {{ $title }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-16">
                                    <div
                                        class="flex flex-col items-center justify-center text-center max-w-[320px] mx-auto">
                                        <div
                                            class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                            <i class="ti ti-users text-2xl text-gray-400"></i>
                                        </div>
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">
                                            Tidak ada data peserta
                                        </h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                            Coba ubah filter pencarian atau paket untuk melihat peserta lain.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="px-4 sm:px-6 lg:px-8 py-4 border-t border-gray-50 dark:border-gray-800 flex items-center justify-between gap-4 flex-wrap">
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} peserta
                        </div>
                        <div>
                            {{ $users->links() }}
                        </div>
                    </div>
                @endif

                <div class="px-4 sm:px-6 lg:px-8 py-4 border-t border-gray-50 dark:border-gray-800 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                    <div class="flex items-center gap-2">
                        <select name="action"
                                class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm text-gray-700 dark:text-gray-200 px-3 py-2">
                            <option value="deactivate">Nonaktifkan akun yang dipilih</option>
                            <option value="activate">Aktifkan kembali akun yang dipilih</option>
                        </select>
                    </div>
                    <x-button type="submit" variant="primary" class="rounded-xl">
                        Terapkan ke akun terpilih
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection

