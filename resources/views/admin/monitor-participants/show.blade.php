@extends('layouts.app')

@section('title', 'Monitor - ' . $user->name)

@section('content')
<div class="space-y-8 pb-20">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs font-medium text-gray-400">
        <a href="{{ route('monitor-participants.index') }}" class="hover:text-indigo-600 transition-colors">Monitor Peserta</a>
        <i class="ti ti-chevron-right"></i>
        <a href="{{ route('monitor-participants.package', $package) }}" class="hover:text-indigo-600 transition-colors">{{ $package->title }}</a>
        <i class="ti ti-chevron-right"></i>
        <span class="text-gray-500 dark:text-gray-500">Detail</span>
    </nav>

    {{-- Header: Nama Peserta + Paket --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-indigo-500/25">
                {{ strtoupper(mb_substr($user->name, 0, 2)) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $user->name }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $user->email }}</p>
                <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 mt-2">
                    <i class="ti ti-package mr-1"></i> {{ $package->title }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <x-button variant="primary" href="{{ route('monitor-participants.export', $userJoin) }}" class="rounded-xl">
                <i class="ti ti-download mr-2"></i> Export Laporan
            </x-button>
            <x-button variant="secondary" href="{{ route('monitor-participants.package', $package) }}" class="rounded-xl">
                <i class="ti ti-arrow-left mr-2"></i> Kembali ke Daftar Peserta
            </x-button>
        </div>
    </div>

    {{-- Filter periode & tipe ujian --}}
    <form action="{{ route('monitor-participants.show', $userJoin) }}" method="GET" class="flex flex-wrap items-end gap-4 p-4 rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900">
        <div>
            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Periode</label>
            <select name="period" class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm py-2.5 px-4 outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="all" {{ ($period ?? 'all') === 'all' ? 'selected' : '' }}>Semua</option>
                <option value="7d" {{ ($period ?? '') === '7d' ? 'selected' : '' }}>7 hari terakhir</option>
                <option value="30d" {{ ($period ?? '') === '30d' ? 'selected' : '' }}>30 hari terakhir</option>
                <option value="3m" {{ ($period ?? '') === '3m' ? 'selected' : '' }}>3 bulan terakhir</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Tipe Ujian</label>
            <select name="exam_type" class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm py-2.5 px-4 outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="" {{ ($examType ?? '') === '' ? 'selected' : '' }}>Semua</option>
                <option value="pretest" {{ ($examType ?? '') === 'pretest' ? 'selected' : '' }}>Pretest</option>
                <option value="posttest" {{ ($examType ?? '') === 'posttest' ? 'selected' : '' }}>Posttest</option>
            </select>
        </div>
        <x-button type="submit" variant="secondary" class="rounded-xl">
            <i class="ti ti-filter mr-2"></i> Terapkan
        </x-button>
    </form>

    {{-- Rata-rata Tryout --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center">
                    <i class="ti ti-clipboard-list text-2xl text-amber-600 dark:text-amber-400"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rata-rata Nilai Tryout</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-0.5">
                        @if($tryoutAverage !== null)
                            {{ number_format($tryoutAverage, 1, ',', '') }}
                        @else
                            <span class="text-gray-400 dark:text-gray-500">—</span>
                        @endif
                    </p>
                    @if($tryoutCount > 0)
                        <p class="text-xs text-gray-500 dark:text-gray-400">dari {{ $tryoutCount }} tryout</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Mata Pelajaran + Nilai Kuis --}}
    <x-card :padding="false" title="Daftar Mata Pelajaran & Nilai Terakhir Kuis">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Mata Pelajaran</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Nilai Terakhir Kuis</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse ($subjectStats as $row)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors">
                            <td class="py-4 px-8">
                                <span class="font-medium text-gray-900 dark:text-white">{{ $row['subject']->name }}</span>
                            </td>
                            <td class="py-4 px-8 text-center">
                                @if($row['last_quiz_score'] !== null)
                                    <span class="inline-flex items-center justify-center min-w-[3rem] px-3 py-1.5 rounded-xl text-sm font-bold
                                        {{ $row['last_quiz_score'] >= 70 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' }}">
                                        {{ number_format($row['last_quiz_score'], 1, ',', '') }}
                                    </span>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 text-sm">—</span>
                                @endif
                            </td>
                            <td class="py-4 px-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                @if($row['last_quiz_at'])
                                    {{ $row['last_quiz_at']->format('d M Y, H:i') }}
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-16 text-center">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada mata pelajaran di paket ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    {{-- Chart Nilai Tryout & Kuis --}}
    @if(count($tryoutChartData) > 0 || count($quizChartData) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @if(count($tryoutChartData) > 0)
                <x-card title="Grafik Nilai Tryout">
                    <div class="w-full h-64">
                        <canvas id="tryoutChart"></canvas>
                    </div>
                </x-card>
            @endif
            @if(count($quizChartData) > 0)
                <x-card title="Grafik Nilai Kuis per Mapel">
                    <div class="w-full h-64">
                        <canvas id="quizChart"></canvas>
                    </div>
                </x-card>
            @endif
        </div>
    @endif

    {{-- Riwayat Nilai Tryout --}}
    <x-card :padding="false" title="Riwayat Nilai Tryout">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Tryout / Ujian</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Nilai</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Terjawab</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Status</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">Tanggal</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse ($tryoutHistory as $t)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors">
                            <td class="py-4 px-8 font-medium text-gray-900 dark:text-white">{{ $t->exam?->title ?? 'Tryout' }}</td>
                            <td class="py-4 px-8 text-center">
                                <span class="inline-flex items-center justify-center min-w-[3rem] px-3 py-1.5 rounded-xl text-sm font-bold
                                    {{ ($t->total_score ?? 0) >= 70 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' }}">
                                    {{ number_format((float) ($t->total_score ?? 0), 1, ',', '') }}
                                </span>
                            </td>
                            <td class="py-4 px-8 text-center text-sm text-gray-600 dark:text-gray-400">{{ $t->questions_answered ?? 0 }}/{{ $t->total_questions ?? 0 }}</td>
                            <td class="py-4 px-8 text-center">
                                @php $status = $t->status ?? ''; @endphp
                                <span class="inline-flex px-2 py-1 rounded-lg text-xs font-semibold {{ $status === 'lulus' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ $status === 'lulus' ? 'Lulus' : 'Tidak Lulus' }}
                                </span>
                            </td>
                            <td class="py-4 px-8 text-right text-sm text-gray-500 dark:text-gray-400">{{ $t->created_at?->format('d M Y, H:i') ?? '—' }}</td>
                            <td class="py-4 px-8 text-right">
                                <x-button variant="secondary" size="sm" href="{{ route('monitor-participants.tryout-detail', [$userJoin, $t]) }}" class="rounded-lg">
                                    <i class="ti ti-eye mr-1"></i> Lihat Jawaban
                                </x-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada riwayat tryout.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tryoutHistory->hasPages())
            <div class="px-8 py-6 border-t border-gray-50 dark:border-gray-800">
                {{ $tryoutHistory->links() }}
            </div>
        @endif
    </x-card>

    {{-- Riwayat Nilai Kuis --}}
    <x-card :padding="false" title="Riwayat Nilai Kuis">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Mata Pelajaran</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Kuis</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Nilai</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse ($quizHistory as $q)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors">
                            <td class="py-4 px-8 font-medium text-gray-900 dark:text-white">{{ $q->quiz?->subject?->name ?? '—' }}</td>
                            <td class="py-4 px-8 text-sm text-gray-600 dark:text-gray-400">{{ $q->quiz?->title ?? 'Kuis' }}</td>
                            <td class="py-4 px-8 text-center">
                                <span class="inline-flex items-center justify-center min-w-[3rem] px-3 py-1.5 rounded-xl text-sm font-bold
                                    {{ ($q->total_score ?? 0) >= 70 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' }}">
                                    {{ number_format((float) ($q->total_score ?? 0), 1, ',', '') }}
                                </span>
                            </td>
                            <td class="py-4 px-8 text-right text-sm text-gray-500 dark:text-gray-400">{{ $q->created_at?->format('d M Y, H:i') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-16 text-center">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada riwayat kuis.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($quizHistory->hasPages())
            <div class="px-8 py-6 border-t border-gray-50 dark:border-gray-800">
                {{ $quizHistory->links() }}
            </div>
        @endif
    </x-card>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tryoutData = @json($tryoutChartData ?? []);
    const quizData = @json($quizChartData ?? []);

    if (tryoutData.length > 0) {
        const ctxTryout = document.getElementById('tryoutChart');
        if (ctxTryout) {
            new Chart(ctxTryout, {
                type: 'bar',
                data: {
                    labels: tryoutData.map(d => d.label),
                    datasets: [{
                        label: 'Nilai Tryout',
                        data: tryoutData.map(d => d.score),
                        backgroundColor: 'rgba(99, 102, 241, 0.6)',
                        borderColor: 'rgb(99, 102, 241)',
                        borderWidth: 1,
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            grid: { color: 'rgba(0,0,0,0.06)' },
                            ticks: { color: '#6b7280' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#6b7280', maxRotation: 45 }
                        }
                    }
                }
            });
        }
    }

    if (quizData.length > 0) {
        const ctxQuiz = document.getElementById('quizChart');
        if (ctxQuiz) {
            new Chart(ctxQuiz, {
                type: 'bar',
                data: {
                    labels: quizData.map(d => d.label),
                    datasets: [{
                        label: 'Nilai Kuis',
                        data: quizData.map(d => d.score),
                        backgroundColor: 'rgba(16, 185, 129, 0.6)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 1,
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            grid: { color: 'rgba(0,0,0,0.06)' },
                            ticks: { color: '#6b7280' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#6b7280', maxRotation: 45 }
                        }
                    }
                }
            });
        }
    }
});
</script>
@endpush
@endsection
