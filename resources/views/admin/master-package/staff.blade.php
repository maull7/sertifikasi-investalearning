@extends('layouts.app')

@section('title', 'Petugas Paket')

@section('content')
    <div class="space-y-8 pb-20">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                    Petugas Paket
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">
                    Daftar petugas yang mengelola paket ini.
                </p>
                <p class="text-sm text-gray-700 dark:text-gray-300 mt-2">
                    <span class="font-semibold">Paket:</span> {{ $package->title }}
                    @if ($package->masterType)
                        <span class="mx-2 text-gray-400">•</span>
                        <span class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            {{ $package->masterType->name_type }}
                        </span>
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-2">
                <x-button variant="secondary" href="{{ route('master-packages.index') }}" class="rounded-xl">
                    <i class="ti ti-arrow-left mr-2"></i> Kembali
                </x-button>
            </div>
        </div>

        <x-card title="Daftar Petugas Pengelola">
            @if ($staff->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Belum ada petugas yang di-assign untuk paket ini.
                </p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                                <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">
                                    Nama
                                </th>
                                <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">
                                    Email
                                </th>
                                <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">
                                    Role
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($staff as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors">
                                    <td class="py-3 px-6 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $user->name }}
                                    </td>
                                    <td class="py-3 px-6 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $user->email }}
                                    </td>
                                    <td class="py-3 px-6 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $user->role }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-card>
    </div>
@endsection

