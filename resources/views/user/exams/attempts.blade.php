@extends('layouts.app')

@section('title', 'Riwayat Ujian - ' . $exam->title)

@section('content')
    <div class="space-y-8 pb-20 p-4 md:p-8 max-w-4xl mx-auto">
        <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('user.my-packages.index') }}" class="hover:text-indigo-600 transition-colors">Package Saya</a>
            <i class="ti ti-chevron-right text-xs"></i>
            <a href="{{ route('user.my-packages.show', $package) }}" class="hover:text-indigo-600 transition-colors">{{ $package->title }}</a>
            <i class="ti ti-chevron-right text-xs"></i>
            <span class="text-gray-900 dark:text-white font-semibold">{{ $exam->title }}</span>
        </nav>

        <x-card>
            <div class="mb-6">
                <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="ti ti-history text-indigo-600"></i>
                    Riwayat Pengerjaan
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pilih salah satu riwayat untuk melihat nilai dan pembahasan jawaban.</p>
            </div>

            @if ($attempts->isEmpty())
                <div class="py-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ti ti-clipboard-off text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400">Belum ada riwayat pengerjaan untuk ujian ini.</p>
                    <x-button variant="primary" href="{{ route('user.exams.show', ['package' => $package->id, 'exam' => $exam->id]) }}" class="mt-4 rounded-xl">
                        <i class="ti ti-pencil mr-2"></i> Mulai Ujian
                    </x-button>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="py-3 px-4 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">#</th>
                                <th class="py-3 px-4 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Waktu</th>
                                <th class="py-3 px-4 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 text-center">Soal</th>
                                <th class="py-3 px-4 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 text-center">Nilai</th>
                                <th class="py-3 px-4 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 text-center">Status</th>
                                <th class="py-3 px-4 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($attempts as $index => $trans)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="py-4 px-4 text-sm font-medium text-gray-700 dark:text-gray-300">{{ $index + 1 }}</td>
                                    <td class="py-4 px-4 text-sm text-gray-600 dark:text-gray-400">{{ $trans->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="py-4 px-4 text-center text-sm text-gray-700 dark:text-gray-300">{{ $trans->questions_answered }} / {{ $trans->total_questions }}</td>
                                    <td class="py-4 px-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                            {{ number_format($trans->total_score, 1) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        @if ($trans->status === 'lulus')
                                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">Lulus</span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300">Tidak Lulus</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <x-button variant="primary" size="sm" href="{{ route('user.exams.review', ['package' => $package->id, 'exam' => $exam->id, 'trans' => $trans->id]) }}" class="rounded-lg">
                                            <i class="ti ti-eye mr-1"></i> Lihat Hasil
                                        </x-button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-card>

        <div class="flex justify-center">
            <x-button variant="secondary" href="{{ route('user.my-packages.show', $package->id) }}" class="rounded-xl">
                <i class="ti ti-arrow-left mr-2"></i> Kembali ke Package
            </x-button>
        </div>
    </div>
@endsection
