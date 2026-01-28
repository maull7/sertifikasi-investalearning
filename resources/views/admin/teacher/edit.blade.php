@extends('layouts.app')

@section('title', 'Edit Pengajar')

@section('content')
    <div class="space-y-8 pb-20">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Edit Pengajar</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">
                    Perbarui data pengajar.
                </p>
            </div>

            <x-button variant="secondary" href="{{ route('teacher.index') }}" class="rounded-xl">
                <i class="ti ti-arrow-left mr-2"></i> Kembali
            </x-button>
        </div>

        <x-card>
            <form action="{{ route('teacher.update', $teacher) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400">Nama Pengajar</label>
                        <input type="text" name="name" value="{{ old('name', $teacher->name) }}"
                            class="mt-2 w-full rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 px-4 py-3 text-sm dark:text-white outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('name')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400">NIP</label>
                        <input type="text" name="nip" value="{{ old('nip', $teacher->nip) }}"
                            class="mt-2 w-full rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 px-4 py-3 text-sm dark:text-white outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('nip')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400">Email</label>
                        <input type="email" name="email" value="{{ old('email', $teacher->email) }}"
                            class="mt-2 w-full rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 px-4 py-3 text-sm dark:text-white outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('email')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end">
                    <x-button type="submit" variant="primary" class="rounded-xl">
                        Update Pengajar
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection



