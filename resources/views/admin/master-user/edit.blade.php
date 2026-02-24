@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="space-y-8 pb-20">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Edit User</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">
                    Perbarui data user. Kosongkan password jika tidak ingin mengubah.
                </p>
            </div>
            <x-button variant="secondary" href="{{ route('master-user.index') }}" class="rounded-xl">
                <i class="ti ti-arrow-left mr-2"></i> Kembali
            </x-button>
        </div>

        <x-card>
            <form action="{{ route('master-user.update', $user) }}" method="POST" class="space-y-6" x-data="{ role: '{{ old('role', $user->role) }}' }">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input label="Nama" name="name" placeholder="Nama lengkap" value="{{ old('name', $user->name) }}"
                        required />
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role <span
                                class="text-red-500">*</span></label>
                        <select name="role" required x-model="role"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                            <option value="Admin" {{ old('role', $user->role) === 'Admin' ? 'selected' : '' }}>Admin
                            </option>
                            <option value="Petugas" {{ old('role', $user->role) === 'Petugas' ? 'selected' : '' }}>Petugas
                            </option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input label="Email" name="email" type="email" placeholder="email@contoh.com"
                        value="{{ old('email', $user->email) }}" required />
                    <x-input label="No Telepon" name="phone" type="number" placeholder="6258181992"
                        value="{{ old('phone', $user->phone) }}" required />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password baru
                            (kosongkan jika tidak ubah)</label>
                        <input type="password" name="password" minlength="8" placeholder="Min. 8 karakter"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Konfirmasi
                            Password</label>
                        <input type="password" name="password_confirmation" minlength="8"
                            placeholder="Ulangi password baru"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-6" x-show="role === 'Petugas'">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Paket yang Dikelola
                        </label>
                        @php
                            $selectedPackages = collect(old('managed_package_ids', $user->managedPackages->pluck('id')->all()));
                        @endphp
                        <div class="mt-2 space-y-2 max-h-64 overflow-y-auto rounded-xl border border-gray-200 dark:border-gray-700 p-3 bg-white dark:bg-gray-900">
                            @foreach ($packages as $package)
                                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                                    <input type="checkbox" name="managed_package_ids[]" value="{{ $package->id }}"
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                        {{ $selectedPackages->contains($package->id) ? 'checked' : '' }}>
                                    <span>{{ $package->title }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Checklist paket yang akan dikelola oleh petugas ini.
                        </p>
                        @error('managed_package_ids.*')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex justify-end">
                    <x-button type="submit" variant="primary" class="rounded-xl">Update User</x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
