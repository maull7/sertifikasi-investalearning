@extends('layouts.app')

@section('title', 'Tambah Material Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-20">
    
    {{-- Header & Breadcrumb --}}
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Tambah Material</h1>
            <nav class="flex items-center gap-2 text-xs font-medium text-gray-400">
                <a href="{{ route('master-materials.index') }}" class="hover:text-indigo-600 transition-colors">Material</a>
                <i class="ti ti-chevron-right"></i>
                <span class="text-gray-500 dark:text-gray-500">Tambah Baru</span>
            </nav>
        </div>
        <x-button variant="secondary" href="{{ route('master-materials.index') }}" class="rounded-xl">
            <i class="ti ti-arrow-left mr-2"></i> Kembali
        </x-button>
    </div>

    {{-- Form Card --}}
    <x-card title="Material Baru">
        <form action="{{ route('master-materials.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
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

                {{-- Package Select --}}
                <div class="md:col-span-2">
                    <x-select 
                        label="Paket" 
                        name="package_id" 
                        required
                    >
                        <option value="">Pilih Paket</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                {{ $package->title }}
                            </option>
                        @endforeach
                    </x-select>
                </div>
                <div class="md:col-span-2">
                    <x-select 
                        label="Mata Pelajaran" 
                        name="id_subject" 
                        required
                    >
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('id_subject') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </x-select>
                </div>

                {{-- Title Input --}}
                <div class="md:col-span-2">
                    <x-input 
                        label="Judul Material" 
                        name="title" 
                        placeholder="Contoh: Modul 1 - Pengenalan" 
                        value="{{ old('title') }}"
                        required 
                    />
                </div>
                  {{-- Topic Input --}}
                <div class="md:col-span-2">
                    <x-textarea 
                        label="Topik Materi" 
                        name="topic" 
                        placeholder="Topik singkat mengenai materi ini..." 
                        rows="3"
                        required 
                    >{{ old('topic') }}</x-textarea>
                </div>

                {{-- Description Input --}}
                <div class="md:col-span-2">
                    <x-textarea 
                        label="Deskripsi Material" 
                        name="description" 
                        placeholder="Deskripsi singkat mengenai material ini..." 
                        rows="3"
                        required 
                    >{{ old('description') }}</x-textarea>
                </div>

                 <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Jenis Materi <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-4 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 cursor-pointer">
                            <input type="radio" name="materi_type" value="File" class="accent-indigo-600" {{ old('materi_type', 'File') === 'File' ? 'checked' : '' }}>
                            <div class="flex items-center gap-2">
                                <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center">
                                    <i class="ti ti-file-type-docx text-indigo-600 dark:text-indigo-400"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">File</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Materi berupa file</p>
                                </div>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-4 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 cursor-pointer">
                            <input type="radio" name="materi_type" value="Video" class="accent-indigo-600" {{ old('materi_type') === 'Video' ? 'checked' : '' }}>
                            <div class="flex items-center gap-2">
                                <div class="w-9 h-9 rounded-xl bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center">
                                    <i class="ti ti-photo text-rose-600 dark:text-rose-400"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">Video</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Materi berupa video</p>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('materi_type')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- File Upload --}}
                <div class="md:col-span-2" id="materi-file">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        File Material (PDF / Word) <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="file" 
                        name="file" 
                        accept=".pdf,.doc,.docx"
                        class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl text-sm outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                    >
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Format: PDF, DOC, DOCX. Maksimal 10MB.</p>
                </div>
                <div class="md:col-span-2" id="materi-video">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                       Link Video <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="url" 
                        name="url_link" 
                        class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl text-sm outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                    >
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Format: Link berupa youtube</p>
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const materiFileDiv = document.getElementById('materi-file');
            const materiVideoDiv = document.getElementById('materi-video');
            const materiTypeRadios = document.getElementsByName('materi_type');

            function toggleMateriFields() {
                const selectedType = Array.from(materiTypeRadios).find(radio => radio.checked).value;
                if (selectedType === 'File') {
                    materiFileDiv.style.display = 'block';
                    materiVideoDiv.style.display = 'none';
                } else if (selectedType === 'Video') {
                    materiFileDiv.style.display = 'none';
                    materiVideoDiv.style.display = 'block';
                }
            }

            // Initial toggle based on old input or default
            toggleMateriFields();

            // Add event listeners to radio buttons
            materiTypeRadios.forEach(radio => {
                radio.addEventListener('change', toggleMateriFields);
            });
        });
    </script>
@endpush


