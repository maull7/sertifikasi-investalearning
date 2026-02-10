@extends('layouts.exam')

@section('title', 'Review Hasil Kuis - ' . $quiz->title)

@section('content')
<div class="space-y-8 pb-20 p-4 md:p-8 max-w-screen-2xl mx-auto">
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('user.my-packages.index') }}" class="hover:text-emerald-600 transition-colors">Package Saya</a>
        <i class="ti ti-chevron-right text-xs"></i>
        <a href="{{ route('user.my-packages.show', $package) }}" class="hover:text-emerald-600 transition-colors">{{ $package->title }}</a>
        <i class="ti ti-chevron-right text-xs"></i>
        <span class="text-gray-900 dark:text-white font-semibold">{{ $quiz->title }}</span>
    </nav>

    <x-card>
        <div class="space-y-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-300">
                            <i class="ti ti-key text-lg"></i>
                        </span>
                        <span>Review Hasil Kuis</span>
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kunci jawaban, penjelasan, dan solusi untuk semua soal</p>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                <div class="text-center">
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $trans->total_questions }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Soal</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $trans->total_score }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Nilai</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold {{ $trans->status === 'lulus' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                        {{ ucfirst($trans->status) }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Status</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-violet-600 dark:text-violet-400">{{ $quiz->passing_grade ?? 0 }}%</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Passing Grade</p>
                </div>
            </div>
        </div>
    </x-card>

    <div class="space-y-6">
        @forelse($detailResults as $detail)
            @php
                $question = $detail->Question;
                $questionNumber = ($detailResults->currentPage() - 1) * $detailResults->perPage() + 1;
                $isCorrect = strtoupper(trim($detail->user_answer ?? '')) === strtoupper(trim($detail->correct_answer ?? ''));
            @endphp
            <x-card>
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                                Soal {{ $questionNumber }} / {{ $detailResults->total() }}
                            </span>
                            @if($isCorrect)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                                    <i class="ti ti-check mr-1"></i> Benar
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300">
                                    <i class="ti ti-x mr-1"></i> Salah
                                </span>
                            @endif
                        </div>
                        <div class="text-sm font-semibold {{ $isCorrect ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                            {{ number_format($detail->score_obtained, 2) }} poin
                        </div>
                    </div>

                    <div class="p-5 md:p-6 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800">
                        <h3 class="text-xs font-semibold tracking-[0.16em] uppercase text-gray-400 dark:text-gray-500 mb-3">Pertanyaan</h3>
                        @if($question->question_type === 'Text')
                            <div class="prose prose-sm dark:prose-invert max-w-none">
                                <p class="text-base font-medium text-gray-900 dark:text-white">{!! $question->question !!}</p>
                            </div>
                        @elseif($question->question_type === 'Image')
                            <div class="space-y-3">
                                <img src="{{ str_starts_with($question->question, 'http') ? $question->question : asset('storage/' . ltrim($question->question, '/')) }}"
                                     alt="Soal" class="max-w-full h-auto rounded-2xl border border-gray-200 dark:border-gray-700 mx-auto bg-white dark:bg-gray-900"
                                     style="max-height: 420px; object-fit: contain; display: block;" loading="lazy">
                            </div>
                        @endif
                    </div>

                    {{-- Pilihan jawaban: minimal sampai D, maksimal sampai E — hanya opsi yang ada isinya --}}
                    <div class="space-y-3">
                        <h4 class="text-xs font-semibold tracking-[0.16em] uppercase text-gray-400 dark:text-gray-500 mb-2">Pilihan Jawaban</h4>
                        @php
                            $allLetters = ['A', 'B', 'C', 'D', 'E'];
                            $lastIndex = -1;
                            foreach ($allLetters as $i => $letter) {
                                $key = 'option_' . strtolower($letter);
                                if (trim((string) ($question->$key ?? '')) !== '') {
                                    $lastIndex = $i;
                                }
                            }
                            $availableOptions = $lastIndex < 0 ? ['A', 'B', 'C', 'D'] : array_slice($allLetters, 0, $lastIndex + 1);
                        @endphp
                        @foreach($availableOptions as $option)
                            @php
                                $optionKey = 'option_' . strtolower($option);
                                $optionText = $question->$optionKey ?? '';
                                $isUserAnswer = strtoupper(trim($detail->user_answer ?? '')) === $option;
                                $isCorrectAnswer = strtoupper(trim($detail->correct_answer ?? '')) === $option;
                            @endphp
                            <div class="flex items-center gap-4 p-4 rounded-2xl border-2 transition-all
                                @if($isCorrectAnswer) border-emerald-500 bg-emerald-50 dark:bg-emerald-500/10
                                @elseif($isUserAnswer && !$isCorrectAnswer) border-rose-500 bg-rose-50 dark:bg-rose-500/10
                                @else border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 @endif">
                                <div class="w-9 h-9 rounded-full border flex items-center justify-center text-xs font-bold
                                    @if($isCorrectAnswer) bg-emerald-500 text-white border-emerald-500
                                    @elseif($isUserAnswer && !$isCorrectAnswer) bg-rose-500 text-white border-rose-500
                                    @else bg-gray-50 text-gray-700 dark:bg-gray-800 dark:text-gray-200 border-gray-300 dark:border-gray-600 @endif">
                                    <span>{{ $option }}</span>
                                </div>
                                <div class="flex-1"><span class="text-sm text-gray-900 dark:text-gray-100">{{ $optionText }}</span></div>
                                @if($isCorrectAnswer)
                                    <div class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400 text-xs font-semibold">
                                        <i class="ti ti-check"></i> Kunci Jawaban
                                    </div>
                                @endif
                                @if($isUserAnswer && !$isCorrectAnswer)
                                    <div class="flex items-center gap-1 text-rose-600 dark:text-rose-400 text-xs font-semibold">
                                        <i class="ti ti-x"></i> Jawaban Anda
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 space-y-4 pt-4 border-t border-dashed border-gray-200 dark:border-gray-800">
                        <div class="flex items-center gap-2 text-xs font-semibold tracking-[0.16em] uppercase text-emerald-600 dark:text-emerald-400">
                            <i class="ti ti-key text-base"></i>
                            <span>Kunci Jawaban & Pembahasan</span>
                        </div>
                        <div class="grid gap-4 md:grid-cols-3">
                            <div class="md:col-span-1">
                                <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200/70 dark:border-emerald-500/40">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-emerald-700 dark:text-emerald-300 mb-1">Kunci Jawaban</p>
                                    <p class="text-lg font-bold text-emerald-700 dark:text-emerald-200">{{ $detail->correct_answer ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="space-y-2 md:col-span-2">
                                @if(!empty($question->explanation))
                                    <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-gray-500 dark:text-gray-400 mb-1">Penjelasan</p>
                                        <div class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-200">{!! $question->explanation !!}</div>
                                    </div>
                                @endif
                                @if(!empty($question->solution))
                                    <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/40">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-emerald-600 dark:text-emerald-300 mb-1">Solusi</p>
                                        <div class="prose prose-sm dark:prose-invert max-w-none text-gray-800 dark:text-gray-100">{!! $question->solution !!}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        @empty
            <x-card>
                <div class="flex flex-col items-center justify-center py-12">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                        <i class="ti ti-help-off text-2xl text-gray-400"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Belum ada hasil kuis</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada data hasil kuis yang ditemukan.</p>
                </div>
            </x-card>
        @endforelse

        @if($detailResults->hasPages())
            <div class="flex items-center justify-between pt-6 border-t border-gray-100 dark:border-gray-800">
                @if($detailResults->onFirstPage())
                    <x-button variant="secondary" disabled class="rounded-xl opacity-40 cursor-not-allowed">
                        <i class="ti ti-arrow-left mr-2"></i> Sebelumnya
                    </x-button>
                @else
                    <x-button variant="secondary" href="{{ $detailResults->previousPageUrl() }}" class="rounded-xl">
                        <i class="ti ti-arrow-left mr-2"></i> Sebelumnya
                    </x-button>
                @endif
                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <span>Soal</span>
                    <span class="px-2 py-1 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100 font-semibold">
                        {{ $detailResults->currentPage() }} / {{ $detailResults->lastPage() }}
                    </span>
                </div>
                @if($detailResults->onLastPage())
                    <x-button variant="secondary" disabled class="rounded-xl opacity-40 cursor-not-allowed">
                        Selanjutnya <i class="ti ti-arrow-right ml-2"></i>
                    </x-button>
                @else
                    <x-button variant="secondary" href="{{ $detailResults->nextPageUrl() }}" class="rounded-xl">
                        Selanjutnya <i class="ti ti-arrow-right ml-2"></i>
                    </x-button>
                @endif
            </div>
        @endif
    </div>

    <div class="flex justify-center">
        <x-button variant="primary" href="{{ route('user.my-packages.show', $package) }}" class="rounded-xl">
            <i class="ti ti-arrow-left mr-2"></i> Kembali ke Package
        </x-button>
    </div>
</div>
@endsection
