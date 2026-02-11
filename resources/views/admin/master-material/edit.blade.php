@extends('layouts.app')

@section('title', 'Edit Material')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-20">
    
    {{-- Header & Breadcrumb --}}
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Edit Material</h1>
            <nav class="flex items-center gap-2 text-xs font-medium text-gray-400">
                <a href="{{ route('master-materials.index') }}" class="hover:text-indigo-600 transition-colors">Material</a>
                <i class="ti ti-chevron-right"></i>
                <span class="text-gray-500 dark:text-gray-500">Edit</span>
            </nav>
        </div>
        <x-button variant="secondary" href="{{ route('master-materials.index') }}" class="rounded-xl">
            <i class="ti ti-arrow-left mr-2"></i> Kembali
        </x-button>
    </div>

    {{-- Form Card --}}
    <x-card title="Edit Material">
        <form action="{{ route('master-materials.update', $data->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
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

                <div class="md:col-span-2">
                    <x-select 
                        label="Mata Pelajaran" 
                        name="id_subject" 
                        required
                    >
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('id_subject', $data->id_subject) == $subject->id ? 'selected' : '' }}>
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
                        value="{{ old('title', $data->title) }}"
                        required 
                    />
                </div>

                {{-- Topic Input --}}
                <div class="md:col-span-2">
                    <x-textarea 
                        label="Topik Material" 
                        name="topic" 
                        placeholder="Topik singkat mengenai materi ini..." 
                        rows="3"
                        required 
                    >{{ old('topic', $data->topic) }}</x-textarea>
                </div>
                {{-- Description Input --}}
                <div class="md:col-span-2">
                    <x-textarea 
                        label="Deskripsi Material" 
                        name="description" 
                        placeholder="Deskripsi singkat mengenai material ini..." 
                        rows="3"
                        required 
                    >{{ old('description', $data->description) }}</x-textarea>
                </div>

                  <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Jenis Materi <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-4 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 cursor-pointer">
                            <input type="radio" name="materi_type" value="File" class="accent-indigo-600" {{ old('materi_type', $data->materi_type ?? 'File') === 'File' ? 'checked' : '' }}>
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
                            <input type="radio" name="materi_type" value="Video" class="accent-indigo-600" {{ old('materi_type', $data->materi_type ?? 'Video') === 'Video' ? 'checked' : '' }}>
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

                {{-- Existing File --}}
                @if($data->value)
                <div class="md:col-span-2" id="materi-file">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        File Saat Ini
                    </label>
                    <div class="flex items-center justify-between gap-3 p-4 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-800 rounded-2xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center">
                                <i class="{{ $data->file_icon }} text-xl {{ $data->file_type === 'pdf' ? 'text-rose-600' : 'text-blue-600' }}"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate max-w-[240px]">{{ $data->file_name }}</p>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">{{ strtoupper($data->file_type) }} • {{ $data->file_size_formatted }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('master-materials.preview', $data->id) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:hover:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 text-xs font-semibold rounded-lg transition-colors">
                                <i class="ti ti-eye text-sm"></i>
                                Preview
                            </a>
                            <a href="{{ route('master-materials.download', $data->id) }}" class="inline-flex items-center gap-1 px-3 py-2 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800/40 dark:hover:bg-gray-800/70 text-gray-700 dark:text-gray-200 text-xs font-semibold rounded-lg transition-colors">
                                <i class="ti ti-download text-sm"></i>
                                Download
                            </a>
                        </div>
                    </div>
                </div>
                 <div class="md:col-span-2" id="materi-video">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                       Link Video <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="url" 
                        name="url_link" 
                        value="{{old('url_link', $data->value)}}"
                        class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl text-sm outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                    >
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Format: Link berupa youtube</p>
                </div>
                @endif

                {{-- File Upload --}}
                <div class="md:col-span-2" id="new-file">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Ganti File (Opsional)
                    </label>
                    <input 
                        type="file" 
                        name="file" 
                        accept=".pdf,.doc,.docx"
                        class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl text-sm outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                    >
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Format: PDF, DOC, DOCX. Maksimal 10MB.</p>
                </div>

                @if ($data->materi_type == 'Video')
                    @php
                        $url = $data->value;

                        if (str_contains($url, 'youtu.be')) {
                            $url = 'https://www.youtube.com/embed/' . explode('?', last(explode('/', $url)))[0];
                        }

                        if (str_contains($url, 'youtube.com/watch')) {
                            parse_str(parse_url($url, PHP_URL_QUERY), $q);
                            $url = 'https://www.youtube.com/embed/' . ($q['v'] ?? '');
                        }
                    @endphp
                    <iframe id="frame-video" src="{{$url}}"  class="w-full aspect-video rounded-xl"
                        frameborder="0"
                        allowfullscreen></iframe>
                @endif
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
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const materiFileDiv = document.getElementById('materi-file');
            const materiVideoDiv = document.getElementById('materi-video');
            const materiTypeRadios = document.getElementsByName('materi_type');
            const materiFileNew = document.getElementById('new-file');
            const frameVideo = document.getElementById('frame-video');

          function toggleMateriFields() {
                const selectedType = Array.from(materiTypeRadios).find(r => r.checked).value;

                if (selectedType === 'File') {
                    materiFileDiv.style.display = 'block';
                    materiVideoDiv.style.display = 'none';
                    materiFileNew.style.display = 'block';
                    if (frameVideo) frameVideo.style.display = 'none'; // Tambahkan pengecekan

                    document.querySelector('[name="url_link"]').disabled = true;
                    document.querySelector('[name="file"]').disabled = false;
                } else {
                    materiFileDiv.style.display = 'none';
                    materiVideoDiv.style.display = 'block';
                    materiFileNew.style.display = 'none';
                    if (frameVideo) frameVideo.style.display = 'block'; // Tambahkan pengecekan

                    document.querySelector('[name="url_link"]').disabled = false;
                    document.querySelector('[name="file"]').disabled = true;
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




