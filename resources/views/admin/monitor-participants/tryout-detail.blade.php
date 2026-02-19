@extends('layouts.app')

@section('title', 'Detail Tryout - ' . ($exam->title ?? 'Tryout'))

@section('content')
<style>
    .prose ol { list-style-type: decimal; padding-left: 1.25rem; }
    .prose ul { list-style-type: disc; padding-left: 1.25rem; }
</style>
<div class="space-y-8 pb-20">
    <nav class="flex items-center gap-2 text-xs font-medium text-gray-400">
        <a href="{{ route('monitor-participants.index') }}" class="hover:text-indigo-600 transition-colors">Monitor Peserta</a>
        <i class="ti ti-chevron-right"></i>
        <a href="{{ route('monitor-participants.package', $package) }}" class="hover:text-indigo-600 transition-colors">{{ $package->title }}</a>
        <i class="ti ti-chevron-right"></i>
        <a href="{{ route('monitor-participants.show', $userJoin) }}" class="hover:text-indigo-600 transition-colors">{{ $user->name }}</a>
        <i class="ti ti-chevron-right"></i>
        <span class="text-gray-500 dark:text-gray-500">Detail Tryout</span>
    </nav>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $exam->title ?? 'Tryout' }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $user->name }} · {{ $trans->created_at->format('d M Y, H:i') }}</p>
        </div>
        <x-button variant="secondary" href="{{ route('monitor-participants.show', $userJoin) }}" class="rounded-xl">
            <i class="ti ti-arrow-left mr-2"></i> Kembali ke Monitor
        </x-button>
    </div>

    {{-- Ringkasan --}}
    <x-card>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $trans->total_questions }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Soal</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ number_format((float) $trans->total_score, 1, ',', '') }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Nilai</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold {{ ($trans->status ?? '') === 'lulus' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                    {{ ($trans->status ?? '') === 'lulus' ? 'Lulus' : 'Tidak Lulus' }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Status</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-violet-600 dark:text-violet-400">{{ $trans->questions_answered ?? 0 }}/{{ $trans->total_questions ?? 0 }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Terjawab</p>
            </div>
        </div>
    </x-card>

    {{-- Daftar soal + jawaban + benar/salah --}}
    <div class="space-y-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Jawaban per Soal</h2>
        @forelse($detailResults as $detail)
            @php
                $question = $detail->Question;
                $isCorrect = strtoupper(trim((string) ($detail->user_answer ?? ''))) === strtoupper(trim((string) ($detail->correct_answer ?? '')));
            @endphp
            <x-card>
                <div class="space-y-4">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                            Soal {{ $loop->iteration }} / {{ $detailResults->count() }}
                        </span>
                        @if ($isCorrect)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                                <i class="ti ti-check mr-1"></i> Benar
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300">
                                <i class="ti ti-x mr-1"></i> Salah
                            </span>
                        @endif
                        <span class="text-sm font-semibold {{ $isCorrect ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                            {{ number_format((float) ($detail->score_obtained ?? 0), 2) }} poin
                        </span>
                    </div>

                    <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Pertanyaan</p>
                        @if (($question->question_type ?? 'Text') === 'Text')
                            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-900 dark:text-white">{!! $question->question !!}</div>
                        @else
                            <img src="{{ asset('storage/' . ltrim($question->question ?? '', '/')) }}" alt="Soal" class="max-w-full h-auto rounded-xl border border-gray-200 dark:border-gray-700" loading="lazy" onerror="this.style.display='none'">
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl border-2 {{ !$isCorrect ? 'border-rose-200 dark:border-rose-500/40 bg-rose-50 dark:bg-rose-500/10' : 'border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900' }}">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">Jawaban Peserta</p>
                            <p class="font-bold text-lg">{{ strtoupper(trim((string) ($detail->user_answer ?? '-'))) }}</p>
                        </div>
                        <div class="p-4 rounded-xl border-2 border-emerald-200 dark:border-emerald-500/40 bg-emerald-50 dark:bg-emerald-500/10">
                            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1">Jawaban Benar</p>
                            <p class="font-bold text-lg text-emerald-700 dark:text-emerald-300">{{ strtoupper(trim((string) ($detail->correct_answer ?? '-'))) }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @php
                            $allLetters = ['A', 'B', 'C', 'D', 'E'];
                            $availableOptions = [];
                            foreach ($allLetters as $letter) {
                                $key = 'option_' . strtolower($letter);
                                if (trim((string) ($question->$key ?? '')) !== '') {
                                    $availableOptions[] = $letter;
                                }
                            }
                            if (empty($availableOptions)) {
                                $availableOptions = ['A', 'B', 'C', 'D'];
                            }
                        @endphp
                        @foreach ($availableOptions as $option)
                            @php
                                $optionKey = 'option_' . strtolower($option);
                                $optionText = $question->$optionKey ?? '';
                                $isUserAnswer = strtoupper(trim((string) ($detail->user_answer ?? ''))) === $option;
                                $isCorrectAnswer = strtoupper(trim((string) ($detail->correct_answer ?? ''))) === $option;
                            @endphp
                            <div class="flex items-center gap-2 p-3 rounded-xl border-2
                                @if ($isCorrectAnswer) border-emerald-500 bg-emerald-50 dark:bg-emerald-500/10
                                @elseif($isUserAnswer) border-rose-500 bg-rose-50 dark:bg-rose-500/10
                                @else border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 @endif">
                                <span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold
                                    @if ($isCorrectAnswer) bg-emerald-500 text-white
                                    @elseif($isUserAnswer) bg-rose-500 text-white
                                    @else bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 @endif">{{ $option }}</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ Str::limit($optionText, 60) }}</span>
                                @if ($isCorrectAnswer)<span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 ml-auto">Kunci</span>@endif
                                @if ($isUserAnswer && !$isCorrectAnswer)<span class="text-xs font-semibold text-rose-600 dark:text-rose-400 ml-auto">Peserta</span>@endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </x-card>
        @empty
            <x-card>
                <p class="text-center text-gray-500 dark:text-gray-400">Tidak ada data jawaban untuk tryout ini.</p>
            </x-card>
        @endforelse
    </div>
</div>
@endsection
