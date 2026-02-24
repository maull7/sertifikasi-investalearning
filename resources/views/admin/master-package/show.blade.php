@extends('layouts.app')

@section('title', 'Detail Paket')

@section('content')
    <div class="space-y-8 pb-20">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Detail Paket</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">
                    Informasi lengkap Paket dan User yang dikelola.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <x-button variant="secondary" href="{{ route('master-packages.edit', $data->id) }}" class="rounded-xl">
                    <i class="ti ti-pencil mr-2"></i> Edit
                </x-button>
                <x-button variant="secondary" href="{{ route('master-packages.index') }}" class="rounded-xl">
                    <i class="ti ti-arrow-left mr-2"></i> Kembali
                </x-button>
            </div>
        </div>

        <x-card title="Data Paket">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">
                        Nama Paket</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $data->title }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">
                        Deskripsi</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $data->description }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">
                        Status</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white mt-2">
                        <span class="p-2 {{ $data->status === 'active' ? 'bg-green-400' : 'bg-red-500' }}  rounded-md">
                            {{ $data->status }}
                        </span>
                    </p>
                </div>
            </div>
        </x-card>

        <x-card title="DiKelola Oleh">
            @if ($data->users->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Belum ada user yang mengelola paket ini
                </p>
            @else
                <div class="space-y-3">
                    @foreach ($data->users as $user)
                        <div
                            class="flex items-center justify-between p-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-indigo-100 dark:bg-gray-900/40">
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $user->name }}
                                </p>

                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    {{ $user->email }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    {{ $user->phone }}
                                </p>

                            </div>

                        </div>
                    @endforeach
                </div>
            @endif

        </x-card>
    </div>
@endsection
