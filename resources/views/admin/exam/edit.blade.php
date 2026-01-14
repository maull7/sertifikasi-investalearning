@extends('layouts.app')

@section('title', 'Edit Ujian')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 pb-20">
    
    {{-- Header & Breadcrumb --}}
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Edit Ujian</h1>
            <nav class="flex items-center gap-2 text-xs font-medium text-gray-400">
                <a href="{{ route('exams.index') }}" class="hover:text-indigo-600 transition-colors">Ujian</a>
                <i class="ti ti-chevron-right"></i>
                <span class="text-gray-500 dark:text-gray-500">Edit</span>
            </nav>
        </div>
        <x-button variant="secondary" href="{{ route('exams.index') }}" class="rounded-xl">
            <i class="ti ti-arrow-left mr-2"></i> Kembali
        </x-button>
    </div>

    {{-- Form Card --}}
    <x-card title="Edit Data Ujian">
        <form action="{{ route('exams.update', $data->id) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                @if ($errors->any())
                    <div class="md:col-span-2 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-300">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Package Select --}}
                <div class="md:col-span-2">
                    <x-select 
                        label="Paket" 
                        name="package_id" 
                        required
                    >
                        <option value="">Pilih Paket</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" {{ old('package_id', $data->package_id) == $package->id ? 'selected' : '' }}>
                                {{ $package->title }}
                            </option>
                        @endforeach
                    </x-select>
                </div>

                {{-- Title --}}
                <div class="md:col-span-2">
                    <x-input 
                        label="Judul Ujian" 
                        name="title" 
                        placeholder="Masukkan judul ujian" 
                        value="{{ old('title', $data->title) }}"
                        required 
                    />
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Deskripsi
                    </label>
                    <textarea 
                        name="description" 
                        rows="4"
                        placeholder="Masukkan deskripsi ujian..."
                        class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl text-sm outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all dark:text-white resize-none"
                    >{{ old('description', $data->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Duration --}}
                <div>
                    <x-input 
                        label="Durasi (Menit)" 
                        name="duration" 
                        type="number"
                        min="1"
                        placeholder="Contoh: 90" 
                        value="{{ old('duration', $data->duration) }}"
                        required 
                    />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Durasi ujian dalam menit</p>
                </div>

                {{-- Passing Grade --}}
                <div>
                    <x-input 
                        label="Nilai Kelulusan (KKM)" 
                        name="passing_grade" 
                        type="number"
                        min="0"
                        max="100"
                        placeholder="Contoh: 75" 
                        value="{{ old('passing_grade', $data->passing_grade) }}"
                        required 
                    />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Nilai minimum untuk lulus (0-100)</p>
                </div>

                {{-- Total Questions --}}
                <div>
                    <x-input 
                        label="Jumlah Soal Ujian (Opsional)" 
                        name="total_questions" 
                        type="number"
                        min="1"
                        placeholder="Contoh: 50" 
                        value="{{ old('total_questions', $data->total_questions) }}"
                    />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Isi jumlah soal yang direncanakan untuk ujian ini (boleh dikosongkan).
                    </p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-50 dark:border-gray-800">
                <x-button type="reset" variant="secondary" class="rounded-xl">
                    Reset
                </x-button>
                <x-button type="submit" variant="primary" class="rounded-xl shadow-lg shadow-indigo-500/20">
                    Update Data
                </x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection


