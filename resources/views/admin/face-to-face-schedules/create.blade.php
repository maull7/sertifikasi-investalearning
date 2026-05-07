@extends('layouts.app')

@section('title', 'Tambah Jadwal Tatap Muka')

@section('content')
    <div class="space-y-6 pb-20">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Jadwal Tatap Muka</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Atur guru, paket, jadwal, dan data meeting Zoom.</p>
        </div>

        <form action="{{ route('admin.face-to-face-schedules.store') }}" method="POST"
            class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            @csrf
            @include('admin.face-to-face-schedules._form', ['schedule' => null])

            <div class="mt-6 flex items-center gap-3">
                <button type="submit"
                    class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                    Simpan Jadwal
                </button>
                <a href="{{ route('admin.face-to-face-schedules.index') }}"
                    class="inline-flex items-center rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection

