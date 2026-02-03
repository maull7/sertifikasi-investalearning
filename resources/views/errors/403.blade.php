@extends('errors.layout')

@section('title', '403 - Akses Ditolak')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center p-6" x-data>
    <div class="absolute top-8 right-8">
        <button @click="$store.theme?.toggle?.() ?? (document.documentElement.classList.toggle('dark'))" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 hover:text-amber-600 transition-all shadow-sm">
            <i class="ti text-xl ti-moon dark:ti-sun"></i>
        </button>
    </div>

    <a href="{{ url('/') }}" class="mb-8 flex flex-col items-center gap-4 group">
        <div class="w-16 h-16 p-2 bg-slate-100 dark:bg-slate-700 rounded-[2rem] flex items-center justify-center shadow-xl shadow-amber-500/20 rotate-3 group-hover:rotate-0 transition-transform duration-500">
            <img src="{{ asset('img/favicon.png') }}" alt="InvestaLearning">
        </div>
        <span class="text-lg font-bold text-gray-900 dark:text-white">InvestaLearning</span>
    </a>

    <div class="w-full max-w-md text-center">
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 mb-6 shadow-lg shadow-amber-500/10">
            <i class="ti ti-lock-off text-5xl"></i>
        </div>
        <p class="text-7xl sm:text-8xl font-extrabold text-amber-600 dark:text-amber-400 tracking-tight">403</p>
        <h1 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">Akses Ditolak</h1>
        <p class="mt-2 text-gray-500 dark:text-gray-400">
            {{ $exception->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini.' }}
        </p>
        <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold text-white bg-amber-600 hover:bg-amber-700 shadow-lg shadow-amber-500/25 transition-all">
                <i class="ti ti-home"></i> Beranda
            </a>
            <a href="javascript:history.back()" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                <i class="ti ti-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <p class="mt-12 text-xs text-gray-400 font-semibold uppercase tracking-widest">&copy; {{ date('Y') }} InvestaLearning</p>
</div>
@endsection
