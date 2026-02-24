@extends('layouts.app')

@section('title', 'Detail User')

@section('content')
    <div class="space-y-8 pb-20">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Detail User</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">
                    Informasi lengkap user dan paket yang dikelola (untuk Petugas).
                </p>
            </div>
            <div class="flex items-center gap-2">
                <x-button variant="secondary" href="{{ route('master-user.edit', $user) }}" class="rounded-xl">
                    <i class="ti ti-pencil mr-2"></i> Edit
                </x-button>
                <x-button variant="secondary" href="{{ route('master-user.index') }}" class="rounded-xl">
                    <i class="ti ti-arrow-left mr-2"></i> Kembali
                </x-button>
            </div>
        </div>

        <x-card title="Profil User">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">
                        Nama</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">
                        Email</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">
                        Role</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->role }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">
                        No. Telepon</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->phone ?? '-' }}</p>
                </div>
            </div>
        </x-card>

        <x-card title="Paket yang Dikelola">
            @if ($user->role !== 'Petugas')
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Role ini bukan Petugas, sehingga tidak memiliki paket yang dikelola khusus.
                </p>
            @else
                @php
                    $packages = $user->managedPackages;
                @endphp
                @if ($packages->isEmpty())
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Belum ada paket yang di-assign ke petugas ini.
                    </p>
                @else
                    <div class="space-y-3">
                        @foreach ($packages as $package)
                            <div
                                class="flex items-center justify-between p-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $package->title }}
                                    </p>
                                    @if ($package->masterType)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ $package->masterType->name_type }}
                                        </p>
                                    @endif
                                </div>
                                <x-button size="sm" variant="secondary"
                                    href="{{ route('master-packages.show', $package->id ?? $package) }}"
                                    class="rounded-lg hidden">
                                    <i class="ti ti-eye"></i>
                                </x-button>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </x-card>
    </div>
@endsection

