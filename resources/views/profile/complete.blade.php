@extends('layouts.landing')

@section('title', 'Lengkapi Profil')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 pb-20">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Lengkapi Profil Anda</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Anda masuk dengan Google. Silakan lengkapi data diri di bawah agar dapat menggunakan layanan dengan baik.
        </p>
    </div>

    <x-card class="shadow-sm border-gray-200 dark:border-gray-800">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/50">
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</p>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</p>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->email }}</p>
            </div>
        </div>

        <form method="post" action="{{ route('profile.complete.update') }}" class="space-y-6">
            @csrf
            @method('patch')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-input
                    label="Nomor Telepon"
                    name="phone"
                    placeholder="081234567890"
                    :value="old('phone', $user->phone)"
                    required
                />

                <x-select label="Jenis Kelamin" name="jenis_kelamin" required>
                    <option value="">Pilih jenis kelamin</option>
                    <option value="Laki-laki" @selected(old('jenis_kelamin', $user->jenis_kelamin) === 'Laki-laki')>Laki-laki</option>
                    <option value="Perempuan" @selected(old('jenis_kelamin', $user->jenis_kelamin) === 'Perempuan')>Perempuan</option>
                </x-select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-input
                    label="Profesi"
                    name="profesi"
                    placeholder="Guru, Siswa, Profesional, dll"
                    :value="old('profesi', $user->profesi)"
                />

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Lahir</label>
                    <input
                        type="date"
                        name="tanggal_lahir"
                        value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}"
                        class="w-full rounded-2xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-4 py-3"
                    />
                    @error('tanggal_lahir')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <x-input
                    label="Institusi"
                    name="institusi"
                    placeholder="Nama sekolah / kampus / instansi"
                    :value="old('institusi', $user->institusi)"
                />
            </div>

            <div>
                <x-textarea
                    label="Alamat"
                    name="alamat"
                    rows="3"
                    placeholder="Alamat lengkap"
                    required
                >{{ old('alamat', $user->alamat) }}</x-textarea>
            </div>

            <div class="pt-2">
                <x-button type="submit" variant="primary" class="w-full md:w-auto">
                    Simpan dan Lanjutkan
                </x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
