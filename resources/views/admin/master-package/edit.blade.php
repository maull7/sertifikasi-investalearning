@extends('layouts.app')

@section('title', 'Tambah Paket Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-20">
    
    {{-- Header & Breadcrumb --}}
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Edit Paket</h1>
            <nav class="flex items-center gap-2 text-xs font-medium text-gray-400">
                <a href="{{ route('master-packages.index') }}" class="hover:text-indigo-600 transition-colors">Paket</a>
                <i class="ti ti-chevron-right"></i>
                <span class="text-gray-500 dark:text-gray-500">Edit Paket</span>
            </nav>
        </div>
        <x-button variant="secondary" href="{{ route('master-packages.index') }}" class="rounded-xl">
            <i class="ti ti-arrow-left mr-2"></i> Kembali
        </x-button>
    </div>

    {{-- Form Card --}}
    <x-card title="Paket Baru">
        <form action="{{ route('master-packages.update', $data->id) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                @if ($errors->any())
                    <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-300">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{-- Name Input --}}
                <div class="md:col-span-2">
                    <x-input 
                        label="Nama Paket" 
                        name="title" 
                        placeholder="Contoh: PAKET A" 
                        value="{{ old('title', $data->title) }}"
                        required 
                    />
                </div>
                <div class="md:col-span-2">
                    <x-textarea 
                        label="Deskripsi Paket" 
                        name="description" 
                        placeholder="Deskripsi singkat mengenai paket ini..." 
                        rows="4"
                        required
                    >{{ old('description', $data->description) }}</x-textarea>
                </div>
                <div class="md:col-span-2">
                    <x-select 
                        label="Status Paket" 
                        name="status" 
                        required
                    >
                        <option value="active" {{ old('status', $data->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $data->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </x-select>
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