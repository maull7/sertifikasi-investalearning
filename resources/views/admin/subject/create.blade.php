@extends('layouts.app')

@section('title', 'Tambah Mata Pelajaran')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-20">
    
    {{-- Header & Breadcrumb --}}
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Tambah Mata Pelajaran</h1>
            <nav class="flex items-center gap-2 text-xs font-medium text-gray-400">
                <a href="{{ route('subjects.index') }}" class="hover:text-indigo-600 transition-colors">Mata Pelajaran</a>
                <i class="ti ti-chevron-right"></i>
                <span class="text-gray-500 dark:text-gray-500">Tambah Baru</span>
            </nav>
        </div>
        <x-button variant="secondary" href="{{ route('subjects.index') }}" class="rounded-xl">
            <i class="ti ti-arrow-left mr-2"></i> Kembali
        </x-button>
    </div>

    {{-- Form Card --}}
    <x-card title="Mata Pelajaran Baru">
        <form action="{{ route('subjects.store') }}" method="POST" class="space-y-8">
            @csrf
            
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

                {{-- Jurusan --}}
                <div class="md:col-span-2">
                    <x-select 
                        label="Jurusan" 
                        name="master_type_id" 
                        required
                    >
                        <option value="">Pilih Jenis</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ old('master_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name_type }}
                            </option>
                        @endforeach
                    </x-select>
                </div>

                {{-- Nama --}}
                <div class="md:col-span-2">
                    <x-input 
                        label="Nama Mata Pelajaran" 
                        name="name" 
                        placeholder="Contoh: Matematika Wajib" 
                        value="{{ old('name') }}"
                        required 
                    />
                </div>

                {{-- Kode --}}
                <div>
                    <x-input 
                        label="Kode (Opsional)" 
                        name="code" 
                        placeholder="Contoh: MTK-01" 
                        value="{{ old('code') }}"
                    />
                </div>

                {{-- Deskripsi --}}
                <div class="md:col-span-2">
                    <x-textarea 
                        label="Deskripsi (Opsional)" 
                        name="description" 
                        placeholder="Deskripsi singkat mengenai mata pelajaran ini..." 
                        rows="4"
                    >{{ old('description') }}</x-textarea>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-50 dark:border-gray-800">
                <x-button type="reset" variant="secondary" class="rounded-xl">
                    Reset
                </x-button>
                <x-button type="submit" variant="primary" class="rounded-xl shadow-lg shadow-indigo-500/20">
                    Simpan Data
                </x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection



