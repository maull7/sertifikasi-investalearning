@extends('layouts.app')

@section('title', 'Detail Soal')

@section('content')
    <style>
        .prose ol {
            list-style-type: decimal;
            padding-left: 1.25rem;
        }

        .prose ul {
            list-style-type: disc;
            padding-left: 1.25rem;
        }
    </style>
    <div class="max-w-5xl mx-auto space-y-8 pb-20">

        {{-- Header & Breadcrumb --}}
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Detail Soal</h1>
                <nav class="flex items-center gap-2 text-xs font-medium text-gray-400">
                    <a href="{{ route('bank-questions.index') }}" class="hover:text-indigo-600 transition-colors">Bank
                        Question</a>
                    <i class="ti ti-chevron-right"></i>
                    <span class="text-gray-500 dark:text-gray-500">Detail</span>
                </nav>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="secondary" href="{{ route('bank-questions.edit', $data->id) }}" class="rounded-xl">
                    <i class="ti ti-pencil mr-2"></i> Edit
                </x-button>
                <x-button variant="secondary" href="{{ route('bank-questions.index') }}" class="rounded-xl">
                    <i class="ti ti-arrow-left mr-2"></i> Kembali
                </x-button>
            </div>
        </div>

        {{-- Detail Card --}}
        <x-card title="Informasi Soal">
            <div class="space-y-6">

                {{-- Tipe Soal --}}
                <div
                    class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4 pb-6 border-b border-gray-50 dark:border-gray-800">
                    <div class="md:w-1/3">
                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mapel
                            Soal</span>
                    </div>
                    <div class="md:w-2/3">
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-300">
                            {{ $data->subject->name ?? '-' }}
                        </span>
                    </div>
                </div>

                {{-- Jenis Soal --}}
                <div
                    class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4 pb-6 border-b border-gray-50 dark:border-gray-800">
                    <div class="md:w-1/3">
                        <span
                            class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jenis</span>
                    </div>
                    <div class="md:w-2/3">
                        @if (($data->question_type ?? 'Text') === 'Image')
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300">
                                <i class="ti ti-photo mr-2"></i> Image
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                <i class="ti ti-text-size mr-2"></i> Text
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Soal --}}
                <div class="flex flex-col gap-2 pb-6 border-b border-gray-50 dark:border-gray-800">
                    <div>
                        <span
                            class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Soal</span>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-2xl p-6">
                        <div class="prose prose-sm dark:prose-invert max-w-none text-gray-900 dark:text-white">
                            @if (($data->question_type ?? 'Text') === 'Text')
                                {!! $data->question !!}
                            @else
                                <span class="text-sm text-gray-500 dark:text-gray-400">Soal berupa gambar.</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Gambar Soal --}}
                @if (($data->question_type ?? 'Text') === 'Image' && $data->question)
                    <div class="flex flex-col gap-2 pb-6 border-b border-gray-50 dark:border-gray-800">
                        <div>
                            <span
                                class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Gambar
                                Soal</span>
                        </div>
                        <div class="rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-800">
                            <img src="{{ asset('storage/' . $data->question) }}" alt="Question Image"
                                class="w-full h-auto object-contain max-h-96">
                        </div>
                    </div>
                @endif

                {{-- Opsi Jawaban --}}
                <div class="pb-6 border-b border-gray-50 dark:border-gray-800">
                    <span
                        class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-4">Opsi
                        Jawaban</span>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div
                            class="flex items-center gap-3 p-4 rounded-2xl {{ strtolower($data->answer) === 'a' ? 'bg-green-50 dark:bg-green-500/10 border-2 border-green-500' : 'bg-gray-50 dark:bg-gray-900/50' }}">
                            <div
                                class="w-10 h-10 rounded-xl {{ strtolower($data->answer) === 'a' ? 'bg-green-500 text-white' : 'bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }} flex items-center justify-center font-bold text-sm">
                                A
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $data->option_a }}</span>
                            @if (strtolower($data->answer) === 'a')
                                <i class="ti ti-check text-green-500 ml-auto text-xl"></i>
                            @endif
                        </div>

                        <div
                            class="flex items-center gap-3 p-4 rounded-2xl {{ strtolower($data->answer) === 'b' ? 'bg-green-50 dark:bg-green-500/10 border-2 border-green-500' : 'bg-gray-50 dark:bg-gray-900/50' }}">
                            <div
                                class="w-10 h-10 rounded-xl {{ strtolower($data->answer) === 'b' ? 'bg-green-500 text-white' : 'bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }} flex items-center justify-center font-bold text-sm">
                                B
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $data->option_b }}</span>
                            @if (strtolower($data->answer) === 'b')
                                <i class="ti ti-check text-green-500 ml-auto text-xl"></i>
                            @endif
                        </div>

                        <div
                            class="flex items-center gap-3 p-4 rounded-2xl {{ strtolower($data->answer) === 'c' ? 'bg-green-50 dark:bg-green-500/10 border-2 border-green-500' : 'bg-gray-50 dark:bg-gray-900/50' }}">
                            <div
                                class="w-10 h-10 rounded-xl {{ strtolower($data->answer) === 'c' ? 'bg-green-500 text-white' : 'bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }} flex items-center justify-center font-bold text-sm">
                                C
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $data->option_c }}</span>
                            @if (strtolower($data->answer) === 'c')
                                <i class="ti ti-check text-green-500 ml-auto text-xl"></i>
                            @endif
                        </div>

                        <div
                            class="flex items-center gap-3 p-4 rounded-2xl {{ strtolower($data->answer) === 'd' ? 'bg-green-50 dark:bg-green-500/10 border-2 border-green-500' : 'bg-gray-50 dark:bg-gray-900/50' }}">
                            <div
                                class="w-10 h-10 rounded-xl {{ strtolower($data->answer) === 'd' ? 'bg-green-500 text-white' : 'bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }} flex items-center justify-center font-bold text-sm">
                                D
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $data->option_d }}</span>
                            @if (strtolower($data->answer) === 'd')
                                <i class="ti ti-check text-green-500 ml-auto text-xl"></i>
                            @endif
                        </div>
                        <div
                            class="flex items-center gap-3 p-4 rounded-2xl {{ strtolower($data->answer) === 'e' ? 'bg-green-50 dark:bg-green-500/10 border-2 border-green-500' : 'bg-gray-50 dark:bg-gray-900/50' }}">
                            <div
                                class="w-10 h-10 rounded-xl {{ strtolower($data->answer) === 'e' ? 'bg-green-500 text-white' : 'bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }} flex items-center justify-center font-bold text-sm">
                                E
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $data->option_e }}</span>
                            @if (strtolower($data->answer) === 'e')
                                <i class="ti ti-check text-green-500 ml-auto text-xl"></i>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Pembahasan --}}
                <div class="flex flex-col gap-2 pb-6 border-b border-gray-50 dark:border-gray-800">
                    <div>
                        <span
                            class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pembahasan</span>
                    </div>
                    <div
                        class="bg-blue-50 dark:bg-blue-500/10 rounded-2xl p-6 border border-blue-100 dark:border-blue-500/20">
                        <div class="prose prose-sm dark:prose-invert max-w-none text-gray-900 dark:text-white">
                            {!! $data->solution !!}
                        </div>
                    </div>
                </div>

                {{-- Penjelasan --}}
                <div class="flex flex-col gap-2 pb-6 border-b border-gray-50 dark:border-gray-800">
                    <div>
                        <span
                            class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Penjelasan</span>
                    </div>
                    <div
                        class="bg-amber-50 dark:bg-amber-500/10 rounded-2xl p-6 border border-amber-100 dark:border-amber-500/20">
                        <div class="prose prose-sm dark:prose-invert max-w-none text-gray-900 dark:text-white">
                            {!! $data->explanation !!}
                        </div>
                    </div>
                </div>

                {{-- Timestamps --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <span
                            class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-2">Dibuat</span>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $data->created_at->format('d M Y, H:i') }}
                            WIB</p>
                    </div>

                    <div>
                        <span
                            class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-2">Terakhir
                            Diperbarui</span>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $data->updated_at->format('d M Y, H:i') }}
                            WIB</p>
                    </div>
                </div>

            </div>
        </x-card>
    </div>
@endsection
