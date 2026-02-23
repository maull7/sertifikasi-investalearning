@extends('layouts.app')

@section('title', 'Hasil Ujian - ' . $exam->title)

@section('content')
    <div class="space-y-8 pb-20 p-4 md:p-8 max-w-2xl mx-auto">
        <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('user.my-packages.index') }}" class="hover:text-indigo-600 transition-colors">Package Saya</a>
            <i class="ti ti-chevron-right text-xs"></i>
            <a href="{{ route('user.my-packages.show', $package) }}" class="hover:text-indigo-600 transition-colors">{{ $package->title }}</a>
            <i class="ti ti-chevron-right text-xs"></i>
            <span class="text-gray-900 dark:text-white font-semibold">{{ $exam->title }}</span>
        </nav>

        <x-card>
            <div class="text-center space-y-6">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full {{ $trans->status === 'lulus' ? 'bg-emerald-100 dark:bg-emerald-500/20' : 'bg-amber-100 dark:bg-amber-500/20' }}">
                    @if ($trans->status === 'lulus')
                        <i class="ti ti-circle-check text-4xl text-emerald-600 dark:text-emerald-400"></i>
                    @else
                        <i class="ti ti-alert-circle text-4xl text-amber-600 dark:text-amber-400"></i>
                    @endif
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Ujian Selesai</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $exam->title }}</p>
                </div>
                <div class="grid grid-cols-3 gap-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <div>
                        <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ number_format($trans->total_score, 1) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Nilai</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-700 dark:text-gray-300">{{ $trans->total_questions }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Soal</p>
                    </div>
                    <div>
                        <p class="text-lg font-bold {{ $trans->status === 'lulus' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                            {{ ucfirst($trans->status) }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Status</p>
                    </div>
                </div>
                <div class="pt-4">
                    <x-button variant="primary" href="{{ route('user.exams.review', ['package' => $package->id, 'exam' => $exam->id, 'trans' => $trans->id]) }}" class="rounded-xl shadow-lg shadow-indigo-500/20">
                        <i class="ti ti-key mr-2"></i> Lihat Pembahasan & Kunci Jawaban
                    </x-button>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Lihat jawaban Anda, kunci jawaban benar, dan penjelasan per soal.</p>
            </div>
        </x-card>

        <div class="flex justify-center">
            <x-button variant="secondary" href="{{ route('user.my-packages.show', $package->id) }}" class="rounded-xl">
                <i class="ti ti-arrow-left mr-2"></i> Kembali ke Package
            </x-button>
        </div>
    </div>
@endsection
