@extends('layouts.app')

@section('title', 'Detail Soal Ujian - ' . $exam->title)

@section('content')
<div class="max-w-5xl mx-auto space-y-8 pb-20">
    
    {{-- Header & Breadcrumb --}}
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Detail Soal di Ujian</h1>
            <nav class="flex items-center gap-2 text-xs font-medium text-gray-400">
                <a href="{{ route('exams.index') }}" class="hover:text-indigo-600 transition-colors">Ujian</a>
                <i class="ti ti-chevron-right"></i>
                <a href="{{ route('mapping-questions.manage', $exam) }}" class="hover:text-indigo-600 transition-colors">Mapping Soal</a>
                <i class="ti ti-chevron-right"></i>
                <span class="text-gray-500 dark:text-gray-500">Detail</span>
            </nav>
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('mapping-questions.destroy', [$exam, $mapping]) }}" method="POST" onsubmit="return confirm('Hapus soal ini dari ujian?')">
                @csrf
                @method('DELETE')
                <x-button variant="danger" class="rounded-xl">
                    <i class="ti ti-trash mr-2"></i> Hapus dari Ujian
                </x-button>
            </form>
            <x-button variant="secondary" href="{{ route('mapping-questions.manage', $exam) }}" class="rounded-xl">
                <i class="ti ti-arrow-left mr-2"></i> Kembali
            </x-button>
        </div>
    </div>

    {{-- Info Ujian --}}
    <x-card>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold mb-1">Ujian</p>
                <p class="text-gray-900 dark:text-white font-semibold">{{ $exam->title }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold mb-1">Paket</p>
                <p class="text-gray-900 dark:text-white">{{ $exam->package->title ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold mb-1">Mapel Soal</p>
                <p class="text-gray-900 dark:text-white">
                    {{ $question->subject->name ?? '-' }}
                </p>
            </div>
        </div>
    </x-card>

    {{-- Detail Soal --}}
    <x-card title="Detail Soal">
        <div class="space-y-6">
            {{-- Jenis Soal --}}
            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4 pb-6 border-b border-gray-50 dark:border-gray-800">
                <div class="md:w-1/3">
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jenis</span>
                </div>
                <div class="md:w-2/3">
                    @if(($question->question_type ?? 'Text') === 'Image')
                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300">
                            <i class="ti ti-photo mr-2"></i> Image
                        </span>
                    @else
                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                            <i class="ti ti-text-size mr-2"></i> Text
                        </span>
                    @endif
                </div>
            </div>

            {{-- Soal --}}
            <div class="flex flex-col gap-2 pb-6 border-b border-gray-50 dark:border-gray-800">
                <div>
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Soal</span>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-2xl p-6">
                    <div class="prose prose-sm dark:prose-invert max-w-none text-gray-900 dark:text-white">
                        @if(($question->question_type ?? 'Text') === 'Text')
                            {!! $question->question !!}
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                                Soal berupa gambar, ditampilkan di bawah ini.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Gambar Soal (untuk Image) --}}
            @if(($question->question_type ?? 'Text') === 'Image' && $question->question)
            <div class="flex flex-col gap-2 pb-6 border-b border-gray-50 dark:border-gray-800">
                <div>
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Gambar Soal</span>
                </div>
                <div class="rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-800 bg-black/5 dark:bg-white/5 flex items-center justify-center">
                    <img src="{{ asset('storage/' . $question->question) }}" alt="Question Image" class="w-full h-auto object-contain max-h-[480px]">
                </div>
            </div>
            @endif

            {{-- Opsi Jawaban --}}
            <div class="pb-6 border-b border-gray-50 dark:border-gray-800">
                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-4">Opsi Jawaban</span>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php($answer = strtolower($question->answer))
                    <div class="flex items-center gap-3 p-4 rounded-2xl {{ $answer === 'a' ? 'bg-green-50 dark:bg-green-500/10 border-2 border-green-500' : 'bg-gray-50 dark:bg-gray-900/50' }}">
                        <div class="w-10 h-10 rounded-xl {{ $answer === 'a' ? 'bg-green-500 text-white' : 'bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }} flex items-center justify-center font-bold text-sm">
                            A
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $question->option_a }}</span>
                        @if($answer === 'a')
                            <i class="ti ti-check text-green-500 ml-auto text-xl"></i>
                        @endif
                    </div>

                    <div class="flex items-center gap-3 p-4 rounded-2xl {{ $answer === 'b' ? 'bg-green-50 dark:bg-green-500/10 border-2 border-green-500' : 'bg-gray-50 dark:bg-gray-900/50' }}">
                        <div class="w-10 h-10 rounded-xl {{ $answer === 'b' ? 'bg-green-500 text-white' : 'bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }} flex items-center justify-center font-bold text-sm">
                            B
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $question->option_b }}</span>
                        @if($answer === 'b')
                            <i class="ti ti-check text-green-500 ml-auto text-xl"></i>
                        @endif
                    </div>

                    <div class="flex items-center gap-3 p-4 rounded-2xl {{ $answer === 'c' ? 'bg-green-50 dark:bg-green-500/10 border-2 border-green-500' : 'bg-gray-50 dark:bg-gray-900/50' }}">
                        <div class="w-10 h-10 rounded-xl {{ $answer === 'c' ? 'bg-green-500 text-white' : 'bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }} flex items-center justify-center font-bold text-sm">
                            C
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $question->option_c }}</span>
                        @if($answer === 'c')
                            <i class="ti ti-check text-green-500 ml-auto text-xl"></i>
                        @endif
                    </div>

                    <div class="flex items-center gap-3 p-4 rounded-2xl {{ $answer === 'd' ? 'bg-green-50 dark:bg-green-500/10 border-2 border-green-500' : 'bg-gray-50 dark:bg-gray-900/50' }}">
                        <div class="w-10 h-10 rounded-xl {{ $answer === 'd' ? 'bg-green-500 text-white' : 'bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }} flex items-center justify-center font-bold text-sm">
                            D
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $question->option_d }}</span>
                        @if($answer === 'd')
                            <i class="ti ti-check text-green-500 ml-auto text-xl"></i>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Pembahasan --}}
            <div class="flex flex-col gap-2 pb-6 border-b border-gray-50 dark:border-gray-800">
                <div>
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pembahasan</span>
                </div>
                <div class="bg-blue-50 dark:bg-blue-500/10 rounded-2xl p-6 border border-blue-100 dark:border-blue-500/20">
                    <div class="prose prose-sm dark:prose-invert max-w-none text-gray-900 dark:text-white">
                        {!! $question->solution !!}
                    </div>
                </div>
            </div>

            {{-- Penjelasan --}}
            <div class="flex flex-col gap-2">
                <div>
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Penjelasan</span>
                </div>
                <div class="bg-amber-50 dark:bg-amber-500/10 rounded-2xl p-6 border border-amber-100 dark:border-amber-500/20">
                    <div class="prose prose-sm dark:prose-invert max-w-none text-gray-900 dark:text-white">
                        {!! $question->explanation !!}
                    </div>
                </div>
            </div>
        </div>
    </x-card>
</div>
@endsection


