@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Dashboard
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Selamat datang kembali, <span class="font-semibold">{{ $user->name }}</span>
            </p>
        </div>
        <x-button href="{{ route('profile.edit') }}" variant="secondary" class="rounded-xl">
            <i class="ti ti-user-edit mr-2"></i> Ubah Profil
        </x-button>
    </div>

    {{-- Stats --}}
    @php
        $stats = [
            [
                'label' => 'Paket Diikuti',
                'value' => $totalPackages ?? 0,
                'icon'  => 'book',
                'color' => 'indigo',
            ],
            // [
            //     'label' => 'Materi DiPelajari',
            //     'value' => $completedMaterials ?? 0,
            //     'icon'  => 'checklist',
            //     'color' => 'emerald',
            // ],
            [
                'label' => 'Ujian Diikuti',
                'value' => $totalExams ?? 0,
                'icon'  => 'file-text',
                'color' => 'amber',
            ],
            // [
            //     'label' => 'Progress Rata-rata',
            //     'value' => ($avgProgress ?? 0) . '%',
            //     'icon'  => 'chart-line',
            //     'color' => 'rose',
            // ],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($stats as $stat)
            <x-card>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center
                        bg-{{ $stat['color'] }}-50 dark:bg-{{ $stat['color'] }}-500/10
                        text-{{ $stat['color'] }}-600">
                        <i class="ti ti-{{ $stat['icon'] }} text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400 font-semibold">{{ $stat['label'] }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $stat['value'] }}
                        </p>
                    </div>
                </div>
            </x-card>
        @endforeach
    </div>

    {{-- Chart Nilai Peserta (Diri Sendiri) --}}
    <x-card title="Grafik Nilai Saya">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-1 space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Filter Jenis</label>
                    <select id="filterType"
                        class="w-full rounded-xl border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 px-3 py-2">
                        <option value="">Semua Jenis</option>
                        @foreach(($types ?? []) as $type)
                            <option value="{{ $type->id }}">{{ $type->name_type }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Filter Paket</label>
                    <select id="filterPackage"
                        class="w-full rounded-xl border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 px-3 py-2">
                        <option value="">Semua Paket</option>
                        @foreach(($packagesForFilter ?? []) as $pkg)
                            <option value="{{ $pkg->id }}">{{ $pkg->title }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-[11px] text-gray-400">Paket yang tampil hanya paket yang kamu ikuti.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Filter Ujian</label>
                    <select id="filterExam"
                        class="w-full rounded-xl border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 px-3 py-2">
                        <option value="">Semua Ujian</option>
                        @foreach(($examsForFilter ?? []) as $exam)
                            <option value="{{ $exam->id }}">{{ $exam->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Periode</label>
                    <select id="filterPeriod"
                        class="w-full rounded-xl border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 px-3 py-2">
                        <option value="">Semua</option>
                        <option value="7">7 hari</option>
                        <option value="30" selected>30 hari</option>
                        <option value="90">90 hari</option>
                    </select>
                </div>
                <div class="pt-2">
                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                        <span>Rata-rata</span>
                        <span id="avgScore" class="font-semibold text-gray-900 dark:text-gray-100">-</span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                        <span>Nilai Tertinggi</span>
                        <span id="maxScore" class="font-semibold text-gray-900 dark:text-gray-100">-</span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                        <span>Total Attempt</span>
                        <span id="totalAttempts" class="font-semibold text-gray-900 dark:text-gray-100">-</span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="w-full overflow-x-auto">
                    <div class="min-w-[700px] lg:min-w-0 h-80 relative">
                        <div class="mb-3 flex flex-wrap items-center gap-2 text-xs">
                            <span class="px-2.5 py-1 rounded-full bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-200 font-semibold">
                                Jenis: <span id="currentTypeText" class="font-normal">Semua Jenis</span>
                            </span>
                            <span class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-semibold">
                                Paket: <span id="currentPackageText" class="font-normal">Semua Paket</span>
                            </span>
                            <span class="px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-200 font-semibold">
                                Ujian: <span id="currentExamText" class="font-normal">Semua Ujian</span>
                            </span>
                            <span class="px-2.5 py-1 rounded-full bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-200 font-semibold">
                                Periode: <span id="currentPeriodText" class="font-normal">30 hari</span>
                            </span>
                        </div>
                        <div id="chartLoading"
                            class="hidden absolute inset-0 z-10 bg-white/70 dark:bg-gray-950/60 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <div class="text-sm text-gray-500 dark:text-gray-400 font-semibold">Memuat grafik...</div>
                        </div>
                        <canvas id="myScoreChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </x-card>

    {{-- Paket Aktif --}}
    <x-card title="Paket yang Sedang Diikuti">
        @forelse($packageFollow ?? [] as $package)
            <div class="flex items-center justify-between py-3 border-b last:border-0 border-gray-100 dark:border-gray-800">
                <div>
                    <p class="font-semibold text-gray-900 dark:text-white">
                        {{ $package->package->title }}
                    </p>
                    <p class="text-xs text-gray-400">
                         {{ $package->package->description }}
                    </p>
                </div>
                <x-button size="sm" href="{{ route('user.my-packages.show', $package->package->id) }}" variant="primary" class="rounded-lg">
                    Lanjutkan
                </x-button>
            </div>
        @empty
            <div class="py-10 text-center">
                <i class="ti ti-books text-4xl text-gray-300 mb-3"></i>
                <p class="text-sm text-gray-500">
                    Anda belum mengikuti paket apa pun
                </p>
            </div>
        @endforelse
    </x-card>
    <x-card title="Paket Terbaru">
        @forelse($packageActive ?? [] as $package)
            <div class="flex items-center justify-between py-3 border-b last:border-0 border-gray-100 dark:border-gray-800">
                <div>
                    <p class="font-semibold text-gray-900 dark:text-white">
                        {{ $package->title }}
                    </p>
                    <p class="text-xs text-gray-400">
                         {{ $package->description }}
                    </p>
                </div>
                <x-button size="sm" href="{{ route('user.my-packages.index') }}" variant="primary" class="rounded-lg">
                    Lihat Paket
                </x-button>
            </div>
        @empty
            <div class="py-10 text-center">
                <i class="ti ti-books text-4xl text-gray-300 mb-3"></i>
                <p class="text-sm text-gray-500">
                   Belum ada paket terbaru
                </p>
            </div>
        @endforelse
    </x-card>

</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('myScoreChart');
            if (!canvas) { return; }

            const routeUrl = "{{ route('user.dashboard.chart-data') }}";
            const filterType = document.getElementById('filterType');
            const filterPackage = document.getElementById('filterPackage');
            const filterExam = document.getElementById('filterExam');
            const filterPeriod = document.getElementById('filterPeriod');
            const loadingEl = document.getElementById('chartLoading');

            const avgScoreEl = document.getElementById('avgScore');
            const maxScoreEl = document.getElementById('maxScore');
            const totalAttemptsEl = document.getElementById('totalAttempts');
            const currentTypeTextEl = document.getElementById('currentTypeText');
            const currentPackageTextEl = document.getElementById('currentPackageText');
            const currentExamTextEl = document.getElementById('currentExamText');
            const currentPeriodTextEl = document.getElementById('currentPeriodText');

            let chartInstance = null;
            let currentTypeText = 'Semua Jenis';
            let currentPackageText = 'Semua Paket';
            let currentExamText = 'Semua Ujian';
            let currentPeriodText = '30 hari';

            function toggleLoading(isLoading) {
                if (!loadingEl) { return; }
                loadingEl.classList.toggle('hidden', !isLoading);
            }

            function buildQuery() {
                const params = new URLSearchParams();
                if (filterType && filterType.value) { params.set('type_id', filterType.value); }
                if (filterPackage && filterPackage.value) { params.set('package_id', filterPackage.value); }
                if (filterExam && filterExam.value) { params.set('exam_id', filterExam.value); }
                if (filterPeriod && filterPeriod.value) { params.set('period_days', filterPeriod.value); }
                return params.toString() ? `?${params.toString()}` : '';
            }

            function updateFilterInfo() {
                if (filterType) {
                    const opt = filterType.options[filterType.selectedIndex];
                    currentTypeText = (opt && opt.text) ? opt.text : 'Semua Jenis';
                }
                if (filterPackage) {
                    const opt = filterPackage.options[filterPackage.selectedIndex];
                    currentPackageText = (opt && opt.text) ? opt.text : 'Semua Paket';
                }
                if (filterExam) {
                    const opt = filterExam.options[filterExam.selectedIndex];
                    currentExamText = (opt && opt.text) ? opt.text : 'Semua Ujian';
                }
                if (filterPeriod) {
                    const opt = filterPeriod.options[filterPeriod.selectedIndex];
                    currentPeriodText = (opt && opt.text) ? opt.text : 'Semua';
                }
                if (currentTypeTextEl) { currentTypeTextEl.textContent = currentTypeText; }
                if (currentPackageTextEl) { currentPackageTextEl.textContent = currentPackageText; }
                if (currentExamTextEl) { currentExamTextEl.textContent = currentExamText; }
                if (currentPeriodTextEl) { currentPeriodTextEl.textContent = currentPeriodText; }
            }

            async function fetchChartData() {
                toggleLoading(true);
                try {
                    updateFilterInfo();
                    const res = await fetch(routeUrl + buildQuery(), {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    updateChart(Array.isArray(data) ? data : []);
                } catch (e) {
                    updateChart([]);
                } finally {
                    toggleLoading(false);
                }
            }

            function updateSummary(items) {
                const scores = items.map(i => Number(i.score || 0)).filter(v => !Number.isNaN(v));
                const total = scores.length;
                const max = total ? Math.max(...scores) : 0;
                const avg = total ? (scores.reduce((a, b) => a + b, 0) / total) : 0;

                if (avgScoreEl) { avgScoreEl.textContent = total ? avg.toFixed(1) : '-'; }
                if (maxScoreEl) { maxScoreEl.textContent = total ? max.toFixed(1) : '-'; }
                if (totalAttemptsEl) { totalAttemptsEl.textContent = total ? String(total) : '-'; }
            }

            function updateChart(items) {
                updateSummary(items);

                const ctx = canvas.getContext('2d');
                if (chartInstance) { chartInstance.destroy(); }

                // bawa metadata ke tiap titik (exam, package, type)
                const points = items.map(i => ({
                    x: i.label,
                    y: Number(i.score || 0),
                    exam: i.exam || '-',
                    package: i.package || '-',
                    type: i.type || '-',
                }));

                chartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        datasets: [{
                            label: `Nilai • ${currentTypeText} • ${currentPackageText} • ${currentExamText} • ${currentPeriodText}`,
                            data: points,
                            borderColor: '#22c55e',
                            backgroundColor: 'rgba(34, 197, 94, 0.12)',
                            borderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            tension: 0.05, // sedikit "monitor-like"
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    title: (tooltipItems) => {
                                        const item = tooltipItems?.[0]?.raw;
                                        if (!item) { return tooltipItems?.[0]?.label ?? ''; }

                                        const exam = item.exam ? `Ujian: ${item.exam}` : null;
                                        const pkg = item.package ? `Paket: ${item.package}` : null;
                                        const type = item.type ? `Jenis: ${item.type}` : null;
                                        const period = currentPeriodText ? `Periode: ${currentPeriodText}` : null;
                                        return [item.x ?? tooltipItems?.[0]?.label ?? '', exam, pkg, type, period].filter(Boolean);
                                    },
                                    label: (ctx) => `Nilai: ${ctx.parsed.y ?? '-'}`
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                suggestedMax: 100,
                                grid: { color: 'rgba(148, 163, 184, 0.18)' },
                                ticks: { precision: 0 }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { maxRotation: 0, autoSkip: true }
                            }
                        }
                    }
                });
            }

            if (filterType) { filterType.addEventListener('change', fetchChartData); }
            if (filterPackage) { filterPackage.addEventListener('change', fetchChartData); }
            if (filterExam) { filterExam.addEventListener('change', fetchChartData); }
            if (filterPeriod) { filterPeriod.addEventListener('change', fetchChartData); }

            updateFilterInfo();
            fetchChartData();
        });
    </script>
@endpush
