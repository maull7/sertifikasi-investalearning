@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard User</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Selamat datang kembali, {{ $user->name }}.
            </p>
        </div>
        <div>
            <x-button href="{{ route('profile.edit') }}" variant="secondary">
                Ubah Profil
            </x-button>
        </div>
    </div>

    <x-card>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Profil Singkat</h2>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-gray-500 dark:text-gray-400">Email</dt>
                <dd class="font-medium text-gray-900 dark:text-white">{{ $user->email }}</dd>
            </div>
            <div>
                <dt class="text-gray-500 dark:text-gray-400">Nomor Telepon</dt>
                <dd class="font-medium text-gray-900 dark:text-white">{{ $user->phone ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500 dark:text-gray-400">Jenis Kelamin</dt>
                <dd class="font-medium text-gray-900 dark:text-white">{{ $user->jenis_kelamin ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500 dark:text-gray-400">Profesi</dt>
                <dd class="font-medium text-gray-900 dark:text-white">{{ $user->profesi ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500 dark:text-gray-400">Tanggal Lahir</dt>
                <dd class="font-medium text-gray-900 dark:text-white">
                    {{ $user->tanggal_lahir ? \Illuminate\Support\Carbon::parse($user->tanggal_lahir)->format('d M Y') : '-' }}
                </dd>
            </div>
            <div>
                <dt class="text-gray-500 dark:text-gray-400">Institusi</dt>
                <dd class="font-medium text-gray-900 dark:text-white">{{ $user->institusi ?? '-' }}</dd>
            </div>
            <div class="md:col-span-2">
                <dt class="text-gray-500 dark:text-gray-400">Alamat</dt>
                <dd class="font-medium text-gray-900 dark:text-white">{{ $user->alamat ?? '-' }}</dd>
            </div>
        </dl>
    </x-card>
</div>
@endsection


