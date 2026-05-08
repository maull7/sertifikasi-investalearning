@extends('layouts.app')

@section('title', 'Pengaturan QRIS')

@section('content')
<div class="max-w-2xl mx-auto space-y-8 pb-20">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pengaturan QRIS</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Atur gambar dan informasi QRIS untuk pembayaran peserta.</p>
    </div>

    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-300">{{ session('success') }}</div>
    @endif

    <x-card>
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            @if ($qris_image)
                <div class="flex flex-col items-center gap-2 pb-4 border-b border-gray-100 dark:border-gray-800">
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">QRIS Saat Ini</p>
                    <img src="{{ Storage::url($qris_image) }}" alt="QRIS" class="w-48 h-48 object-contain rounded-2xl border border-gray-200 dark:border-gray-700 p-2 bg-white">
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Gambar QRIS {{ $qris_image ? '(kosongkan jika tidak ingin mengubah)' : '' }}
                </label>
                <input type="file" name="qris_image" accept="image/*"
                    class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-200 dark:border-gray-700 rounded-xl p-2 bg-white dark:bg-gray-900">
                @error('qris_image')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
            </div>

            <x-input label="Nama / Atas Nama QRIS" name="qris_name" :value="old('qris_name', $qris_name)" placeholder="Contoh: PT Investalearning" />

            <x-textarea label="Keterangan Pembayaran" name="qris_description" rows="3"
                placeholder="Contoh: Scan QRIS di atas lalu upload bukti transfer">{{ old('qris_description', $qris_description) }}</x-textarea>

            <x-button type="submit" variant="primary" class="rounded-xl">
                <i class="ti ti-device-floppy mr-2"></i>Simpan Pengaturan
            </x-button>
        </form>
    </x-card>
</div>
@endsection
