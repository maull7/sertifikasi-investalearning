@extends('layouts.app')

@section('title', 'Daftar Paket - ' . $package->title)

@section('content')
<div class="max-w-2xl mx-auto space-y-6 pb-20">

    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('user.packages.index') }}" class="hover:text-indigo-600">Package</a>
        <i class="ti ti-chevron-right text-xs"></i>
        <a href="{{ route('user.packages.show', $package) }}" class="hover:text-indigo-600">{{ $package->title }}</a>
        <i class="ti ti-chevron-right text-xs"></i>
        <span class="text-gray-900 dark:text-white">Daftar</span>
    </nav>

    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $package->title }}</h1>
            @if ($package->masterType)
                <span class="inline-flex mt-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">{{ $package->masterType->name_type }}</span>
            @endif
        </div>
        <div class="text-right shrink-0">
            <p class="text-xs text-gray-400 mb-0.5">Biaya Investasi</p>
            @if ($package->price)
                <p class="text-xl font-bold text-emerald-600">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
            @else
                <p class="text-xl font-bold text-blue-600">Gratis</p>
            @endif
        </div>
    </div>

    @if ($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-300">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user.packages.join', $package) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Pilih Jadwal --}}
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-200"><i class="ti ti-calendar-event mr-2 text-indigo-500"></i>Pilih Jadwal Pelatihan</h2>
            </div>
            <div class="p-6 space-y-3">
                @forelse ($schedules as $schedule)
                    <label class="flex items-start gap-4 p-4 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/30 dark:hover:bg-indigo-500/5 transition-colors has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 dark:has-[:checked]:bg-indigo-500/10">
                        <input type="radio" name="schedule_id" value="{{ $schedule->id }}" class="mt-1 accent-indigo-600" required {{ old('schedule_id') == $schedule->id ? 'checked' : '' }}>
                        <div>
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $schedule->title }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                <i class="ti ti-door mr-1"></i>{{ $schedule->room_name }}
                                &middot; <i class="ti ti-calendar-event mr-1"></i>{{ $schedule->sessions_count }} sesi
                            </p>
                        </div>
                    </label>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4">Belum ada jadwal tersedia untuk paket ini.</p>
                @endforelse
            </div>
        </div>

        {{-- QRIS & Upload Bukti --}}
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-200"><i class="ti ti-qrcode mr-2 text-indigo-500"></i>Pembayaran via QRIS</h2>
            </div>
            <div class="p-6 space-y-5">
                @if ($qrisImage)
                    <div class="flex flex-col items-center gap-2">
                        <img src="{{ Storage::url($qrisImage) }}" alt="QRIS" class="w-56 h-56 object-contain rounded-2xl border border-gray-200 dark:border-gray-700 p-2 bg-white">
                        @if ($qrisName)<p class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $qrisName }}</p>@endif
                        @if ($qrisDescription)<p class="text-xs text-gray-500 dark:text-gray-400 text-center max-w-xs">{{ $qrisDescription }}</p>@endif
                    </div>
                @else
                    <div class="flex items-center gap-3 rounded-xl bg-yellow-50 dark:bg-yellow-500/10 border border-yellow-200 dark:border-yellow-500/30 px-4 py-3">
                        <i class="ti ti-alert-triangle text-yellow-500"></i>
                        <p class="text-sm text-yellow-700 dark:text-yellow-300">QRIS belum dikonfigurasi oleh admin.</p>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload Bukti Transfer <span class="text-rose-500">*</span></label>
                    <input type="file" name="proof_image" accept="image/*" required
                        class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-200 dark:border-gray-700 rounded-xl p-2 bg-white dark:bg-gray-900">
                    @error('proof_image')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('user.packages.show', $package) }}" class="flex-1 text-center rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-3 text-sm font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Batal</a>
            <button type="submit" class="flex-1 rounded-xl bg-indigo-600 hover:bg-indigo-700 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 transition-colors">
                <i class="ti ti-send mr-2"></i>Kirim Pendaftaran
            </button>
        </div>
    </form>
</div>
@endsection
