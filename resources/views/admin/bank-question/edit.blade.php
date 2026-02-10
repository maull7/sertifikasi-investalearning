@extends('layouts.app')

@section('title', 'Edit Soal')

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .ql-editor {
        min-height: 150px;
        font-size: 14px;
    }
    .ql-container {
        font-family: 'Inter', sans-serif;
    }
     .dark .ql-toolbar {
        background: #111827;
        border-color: #1f2937 !important;
    }
    .dark .ql-container {
        background: #111827;
        border-color: #1f2937 !important;
    }
    .dark .ql-editor.ql-blank::before {
        color: #6b7280;
    }
    .dark .ql-stroke {
        stroke: #9ca3af !important;
    }
    .dark .ql-fill {
        fill: #9ca3af !important;
    }
    .dark .ql-picker-label {
        color: #9ca3af !important;
    }
</style>


@section('content')
<div class="max-w-5xl mx-auto space-y-8 pb-20">
    
    {{-- Header & Breadcrumb --}}
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Edit Soal</h1>
            <nav class="flex items-center gap-2 text-xs font-medium text-gray-400">
                <a href="{{ route('bank-questions.index') }}" class="hover:text-indigo-600 transition-colors">Bank Question</a>
                <i class="ti ti-chevron-right"></i>
                <span class="text-gray-500 dark:text-gray-500">Edit</span>
            </nav>
        </div>
        <x-button variant="secondary" href="{{ route('bank-questions.index') }}" class="rounded-xl">
            <i class="ti ti-arrow-left mr-2"></i> Kembali
        </x-button>
    </div>

    {{-- Form Card --}}
    <x-card title="Edit Data Soal">
        <form action="{{ route('bank-questions.update', $data->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
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

                {{-- Type Select --}}
                <div class="md:col-span-2">
                    <x-select 
                        label="Mata Pelajaran" 
                        name="subject_id" 
                        required
                    >
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', $data->subject_id) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>  
                        @endforeach
                    </x-select>
                </div>

                {{-- Question Mode --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Jenis Soal <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-4 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 cursor-pointer">
                            <input type="radio" name="question_type" value="Text" class="accent-indigo-600" {{ old('question_type', $data->question_type ?? 'Text') === 'Text' ? 'checked' : '' }}>
                            <div class="flex items-center gap-2">
                                <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center">
                                    <i class="ti ti-text-size text-indigo-600 dark:text-indigo-400"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">Text</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Soal berupa tulisan</p>
                                </div>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-4 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 cursor-pointer">
                            <input type="radio" name="question_type" value="Image" class="accent-indigo-600" {{ old('question_type', $data->question_type ?? '') === 'Image' ? 'checked' : '' }}>
                            <div class="flex items-center gap-2">
                                <div class="w-9 h-9 rounded-xl bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center">
                                    <i class="ti ti-photo text-rose-600 dark:text-rose-400"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">Image</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Soal berupa gambar</p>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('question_type')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Rich Text Editor for Question --}}
                <div class="md:col-span-2 mb-16" id="question-text-section">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Soal <span class="text-rose-500">*</span>
                    </label>
                    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                        <div id="editor-question" class="dark:text-white">{!! old('question', $data->question) !!}</div>
                    </div>
                    <input type="hidden" name="question" id="question" value="{{ old('question', $data->question) }}">
                    @error('question')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Question Image Upload --}}
                <div class="md:col-span-2" id="question-image-section">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Gambar Soal <span class="text-rose-500">*</span>
                    </label>
                    
                    {{-- Existing Image --}}
                    @if(($data->question_type ?? 'Text') === 'Image' && $data->question)
                    <div id="existing-image" class="mb-4">
                        <div class="relative rounded-xl overflow-hidden border border-gray-200 dark:border-gray-800">
                            <img src="{{ asset('storage/' . $data->question) }}" alt="Current Image" class="w-full h-48 object-cover">
                            <div class="absolute top-2 right-2">
                                <button type="button" onclick="removeExistingImage()" class="w-8 h-8 bg-rose-500 hover:bg-rose-600 text-white rounded-lg flex items-center justify-center transition-colors">
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Gambar saat ini. Klik X untuk menghapus atau upload gambar baru untuk menggantinya.</p>
                    </div>
                    @endif

                    {{-- Upload New Image --}}
                    <div class="relative">
                        <input 
                            type="file" 
                            name="question_file" 
                            id="question_file"
                            accept="image/jpeg,image/jpg,image/png,image/webp"
                            class="hidden"
                            onchange="handleImageSelect(this)"
                        >
                        <label for="question_file" class="flex items-center justify-center gap-3 px-6 py-6 bg-gray-50 dark:bg-gray-900/50 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-2xl cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-all group">
                            <div class="text-center">
                                <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-500/10 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                    <i class="ti ti-photo text-2xl text-indigo-600 dark:text-indigo-400"></i>
                                </div>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                    Klik untuk upload gambar baru
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    JPG, PNG, WEBP (Max: 2MB)
                                </p>
                            </div>
                        </label>
                    </div>
                    <div id="image-preview" class="hidden mt-4">
                        <div class="relative rounded-xl overflow-hidden border border-gray-200 dark:border-gray-800">
                            <img id="preview-img" src="" alt="Preview" class="w-full h-48 object-cover">
                            <button type="button" onclick="removeImage()" class="absolute top-2 right-2 w-8 h-8 bg-rose-500 hover:bg-rose-600 text-white rounded-lg flex items-center justify-center transition-colors">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>
                    </div>
                    @error('question_file')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Options --}}
                <div>
                    <x-input 
                        label="Opsi A" 
                        name="option_a" 
                        placeholder="Masukkan opsi A" 
                        value="{{ old('option_a', $data->option_a) }}"
                        required 
                    />
                </div>

                <div>
                    <x-input 
                        label="Opsi B" 
                        name="option_b" 
                        placeholder="Masukkan opsi B" 
                        value="{{ old('option_b', $data->option_b) }}"
                        required 
                    />
                </div>

                <div>
                    <x-input 
                        label="Opsi C" 
                        name="option_c" 
                        placeholder="Masukkan opsi C" 
                        value="{{ old('option_c', $data->option_c) }}"
                        required 
                    />
                </div>

                <div>
                    <x-input 
                        label="Opsi D" 
                        name="option_d" 
                        placeholder="Masukkan opsi D" 
                        value="{{ old('option_d', $data->option_d) }}"
                        required 
                    />
                </div>
                <div>
                    <x-input 
                        label="Opsi E" 
                        name="option_e" 
                        placeholder="Masukkan opsi E" 
                        value="{{ old('option_e', $data->option_e ?? '') }}" 
                    />
                </div>

                {{-- Answer --}}
                <div class="md:col-span-2">
                    <x-select 
                        label="Jawaban Benar" 
                        name="answer" 
                        required
                    >
                        <option value="">Pilih Jawaban</option>
                        <option value="a" {{ old('answer', $data->answer) == 'a' ? 'selected' : '' }}>A</option>
                        <option value="b" {{ old('answer', $data->answer) == 'b' ? 'selected' : '' }}>B</option>
                        <option value="c" {{ old('answer', $data->answer) == 'c' ? 'selected' : '' }}>C</option>
                        <option value="d" {{ old('answer', $data->answer) == 'd' ? 'selected' : '' }}>D</option>
                        <option value="e" {{ old('answer', $data->answer) == 'e' ? 'selected' : '' }}>E</option>
                    </x-select>
                </div>

                {{-- Rich Text Editor for Solution --}}
                <div class="md:col-span-2 mb-16">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Pembahasan <span class="text-rose-500">*</span>
                    </label>
                    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                        <div id="editor-solution" class="dark:text-white">{!! old('solution', $data->solution) !!}</div>
                    </div>
                    <input type="hidden" name="solution" id="solution" value="{{ old('solution', $data->solution) }}">
                    @error('solution')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Rich Text Editor for Explanation --}}
                <div class="md:col-span-2 mb-16">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Penjelasan <span class="text-rose-500">*</span>
                    </label>
                    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                        <div id="editor-explanation" class="dark:text-white">{!! old('explanation', $data->explanation) !!}</div>
                    </div>
                    <input type="hidden" name="explanation" id="explanation" value="{{ old('explanation', $data->explanation) }}">
                    @error('explanation')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-50 dark:border-gray-800">
                <x-button type="reset" variant="secondary" class="rounded-xl" onclick="resetForm()">
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
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const questionTextSection = document.getElementById('question-text-section');
        const questionImageSection = document.getElementById('question-image-section');
        const questionTypeInputs = document.querySelectorAll('input[name="question_type"]');

        const applyQuestionMode = () => {
            const selected = document.querySelector('input[name="question_type"]:checked')?.value || 'Text';
            if (selected === 'Image') {
                questionTextSection.classList.add('hidden');
                questionImageSection.classList.remove('hidden');
            } else {
                questionTextSection.classList.remove('hidden');
                questionImageSection.classList.add('hidden');
                removeImage();
            }
        };

        // Initialize Quill Editors
        const quillQuestion = new Quill('#editor-question', {
            theme: 'snow',
            placeholder: 'Tulis soal di sini...',
            modules: { toolbar: [[{ 'header': [1, 2, 3, false] }], ['bold', 'italic', 'underline'], [{ 'list': 'ordered'}, { 'list': 'bullet' }], ['clean']] }
        });

        const quillSolution = new Quill('#editor-solution', {
            theme: 'snow',
            placeholder: 'Tulis pembahasan di sini...',
            modules: { toolbar: [[{ 'header': [1, 2, 3, false] }], ['bold', 'italic', 'underline'], [{ 'list': 'ordered'}, { 'list': 'bullet' }], ['clean']] }
        });

        const quillExplanation = new Quill('#editor-explanation', {
            theme: 'snow',
            placeholder: 'Tulis penjelasan di sini...',
            modules: { toolbar: [[{ 'header': [1, 2, 3, false] }], ['bold', 'italic', 'underline'], [{ 'list': 'ordered'}, { 'list': 'bullet' }], ['clean']] }
        });

        // Set initial content
        if (document.getElementById('question').value) quillQuestion.root.innerHTML = document.getElementById('question').value;
        if (document.getElementById('solution').value) quillSolution.root.innerHTML = document.getElementById('solution').value;
        if (document.getElementById('explanation').value) quillExplanation.root.innerHTML = document.getElementById('explanation').value;

        // Update hidden inputs
        quillQuestion.on('text-change', () => document.getElementById('question').value = quillQuestion.root.innerHTML);
        quillSolution.on('text-change', () => document.getElementById('solution').value = quillSolution.root.innerHTML);
        quillExplanation.on('text-change', () => document.getElementById('explanation').value = quillExplanation.root.innerHTML);

        // Update on form submit
        document.querySelector('form').addEventListener('submit', function(e) {
            document.getElementById('question').value = quillQuestion.root.innerHTML;
            document.getElementById('solution').value = quillSolution.root.innerHTML;
            document.getElementById('explanation').value = quillExplanation.root.innerHTML;
        });

        window.resetForm = function() {
            quillQuestion.setContents([]);
            quillSolution.setContents([]);
            quillExplanation.setContents([]);
            document.getElementById('question').value = '';
            document.getElementById('solution').value = '';
            document.getElementById('explanation').value = '';
            removeImage();
        }

        questionTypeInputs.forEach((el) => el.addEventListener('change', applyQuestionMode));
        applyQuestionMode();
    });

    // Image handling
    function handleImageSelect(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('image-preview').classList.remove('hidden');
                // Hide existing image if present
                const existingImage = document.getElementById('existing-image');
                if (existingImage) {
                    existingImage.style.opacity = '0.5';
                }
            }
            reader.readAsDataURL(file);
        }
    }

    function removeImage() {
        const el = document.getElementById('question_file');
        if (el) {
            el.value = '';
        }
        document.getElementById('image-preview').classList.add('hidden');
        // Show existing image again if present
        const existingImage = document.getElementById('existing-image');
        if (existingImage) {
            existingImage.style.opacity = '1';
        }
    }

    function removeExistingImage() {
        if (confirm('Hapus gambar saat ini?')) {
            document.getElementById('existing-image').remove();
        }
    }
</script>
@endpush



