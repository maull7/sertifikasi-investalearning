@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- Header Page --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Overview</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Selamat datang {{ Auth::user()->name }}, berikut statistik platform anda.</p>
        </div>
      
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
       @php
            $stats = [
                [
                    'label' => 'Total Paket',
                    'value' => $data['package'],
                    'trend' => '+8.3%',
                    'icon'  => 'ti ti-book'
                ],
                [
                    'label' => 'Total Jenis Soal',
                    'value' => $data['types'],
                    'trend' => '+2.1%',
                    'icon'  => 'ti ti-bleach-no-chlorine'
                ],
                [
                    'label' => 'Total Materi',
                    'value' => $data['material'],
                    'trend' => '+12.7%',
                    'icon'  => 'ti ti-checklist'
                ],
                [
                    'label' => 'Total User',
                    'value' => $data['user'],
                    'trend' => '+5.4%',
                    'icon'  => 'users'
                ],
            ];
        @endphp


        @foreach($stats as $stat)
        <x-card :padding="true">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-600">
                    <i class="ti ti-{{ $stat['icon'] }} text-xl"></i>
                </div>
                <span @class([
                    'text-xs font-bold px-2 py-1 rounded-lg',
                    'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10' => str_contains($stat['trend'], '+'),
                    'bg-rose-50 text-rose-600 dark:bg-rose-500/10' => str_contains($stat['trend'], '-'),
                ])>
                    {{ $stat['trend'] }}
                </span>
            </div>
            <p class="text-sm font-semibold text-gray-400">{{ $stat['label'] }}</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stat['value'] }}</h3>
        </x-card>
        @endforeach
    </div>

    {{-- Ringkasan Ujian & User --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
        <x-card>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Pass Rate Ujian</p>
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                {{ number_format($passRate ?? 0, 1) }}%
            </p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                {{ $totalExamsPassed ?? 0 }} dari {{ $totalExamsTaken ?? 0 }} attempt dinyatakan lulus.
            </p>
        </x-card>
        <x-card>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">User Baru</p>
            <div class="flex items-baseline gap-4">
                <div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">7 hari terakhir</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $newUsers7 ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">30 hari terakhir</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $newUsers30 ?? 0 }}</p>
                </div>
            </div>
        </x-card>
        <x-card>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">User Belum Aktivasi</p>
            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">
                {{ $pendingActivation ?? 0 }}
            </p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                <a href="{{ route('user.not.active') }}" class="text-indigo-600 dark:text-indigo-400 font-semibold hover:underline">
                    Lihat daftar user belum aktif
                </a>
            </p>
        </x-card>
    </div>

    {{-- Charts & Activity Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Chart Analytics Card --}}
<x-card class="lg:col-span-2">
    <x-slot:header>
        <div class="flex items-center justify-between w-full gap-4 flex-wrap">
            <h3 class="font-bold text-gray-900 dark:text-white">Analisis Peserta Ujian</h3>
            <div class="flex gap-2 flex-wrap">
                {{-- Filter Type --}}
                <select id="filterType" class="text-xs border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900 rounded-lg focus:ring-indigo-500 dark:text-gray-300 outline-none px-3 py-1.5">
                    <option value="">Semua Jenis</option>
                    @foreach($typesData as $type)
                        <option value="{{ $type->id }}">{{ $type->name_type }}</option>
                    @endforeach
                </select>

                {{-- Filter Package --}}
                <select id="filterPackage" class="text-xs border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900 rounded-lg focus:ring-indigo-500 dark:text-gray-300 outline-none px-3 py-1.5">
                    <option value="">Semua Paket</option>
                    @foreach($packagesData as $pkg)
                        <option value="{{ $pkg->id }}">{{ $pkg->title }}</option>
                    @endforeach
                </select>

                {{-- Filter Period --}}
                <select id="filterPeriod" class="text-xs border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900 rounded-lg focus:ring-indigo-500 dark:text-gray-300 outline-none px-3 py-1.5">
                    <option value="7">7 Hari Terakhir</option>
                    <option value="14">14 Hari Terakhir</option>
                    <option value="30">30 Hari Terakhir</option>
                </select>
            </div>
        </div>
    </x-slot:header>

    <div class="h-[320px] relative">
        {{-- Loading State --}}
        <div id="chartLoading" class="absolute inset-0 flex items-center justify-center bg-gray-50/50 dark:bg-gray-900/20 rounded-2xl hidden">
            <div class="text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto mb-2"></div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Memuat data...</p>
            </div>
        </div>

        {{-- Chart Canvas --}}
        <canvas id="participantChart"></canvas>
    </div>

    {{-- Summary Stats (berbasis nilai) --}}
    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800 grid grid-cols-3 gap-4">
        <div class="text-center">
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Rata-rata Nilai</p>
            <p id="avgScore" class="text-lg font-bold text-gray-900 dark:text-white">0</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Nilai Tertinggi</p>
            <p id="maxScore" class="text-lg font-bold text-gray-900 dark:text-white">0</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Jumlah User</p>
            <p id="totalUsers" class="text-lg font-bold text-gray-900 dark:text-white">0</p>
        </div>
    </div>
</x-card>


        {{-- Recent Customers Card --}}
        <x-card title="Recent User Exam">
            <div class="space-y-6">
                @forelse ($recents as $recent)     
                    <div class="flex items-center gap-4">
                        <x-avatar name="User {{ $recent->User->name }}" size="sm" />
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $recent->User->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $recent->User->email }}</p>
                        </div>
                        <div class="text-right">
                        
                            <p class="text-[10px] text-black font-bold">{{ $recent->Exam->title ?? $recent->quiz->title ?? 'Exam/Quiz Tidak Ditemukan' }}</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $recent->total_score }}</p>
                            <p class="text-[10px] text-emerald-500 font-bold">{{ $recent->status }}</p>
                            <a href="{{ route('show-grades.detail', $recent->id) }}"
                               class="mt-1 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">No recent user exams found.</p>
                @endforelse
                
            </div>

          
        </x-card>

        {{-- Recent Certificates --}}
        <x-card title="Sertifikat Terbaru" class="mt-6">
            <div class="space-y-4">
                @forelse($recentCertificates ?? [] as $cert)
                    <div class="flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                {{ $cert->user?->name ?? 'User' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                {{ $cert->package?->title ?? '-' }} • {{ $cert->type?->name_type ?? '-' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-[11px] text-gray-400">
                                {{ $cert->created_at?->format('d M Y') }}
                            </p>
                            <a href="{{ route('certificates.show', $cert) }}"
                               class="mt-1 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                Lihat
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada sertifikat yang dibuat.</p>
                @endforelse
            </div>
        </x-card>

    </div>
</div>
@endsection

@push('scripts')
    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('participantChart');
            if (!ctx) {
                return;
            }

            const routeUrl = "{{ route('dashboard.chart-data') }}";

            const filterType = document.getElementById('filterType');
            const filterPackage = document.getElementById('filterPackage');
            const filterPeriod = document.getElementById('filterPeriod');
            const loadingEl = document.getElementById('chartLoading');

            const avgScoreEl = document.getElementById('avgScore');
            const maxScoreEl = document.getElementById('maxScore');
            const totalUsersEl = document.getElementById('totalUsers');

            let chartInstance = null;

            function toggleLoading(show) {
                if (!loadingEl) {
                    return;
                }

                if (show) {
                    loadingEl.classList.remove('hidden');
                } else {
                    loadingEl.classList.add('hidden');
                }
            }

            function buildQuery(params) {
                const query = new URLSearchParams();
                Object.entries(params).forEach(([key, value]) => {
                    if (value !== null && value !== undefined && value !== '') {
                        query.append(key, value);
                    }
                });

                const qs = query.toString();
                return qs ? `${routeUrl}?${qs}` : routeUrl;
            }

            async function fetchChartData() {
                toggleLoading(true);

                const params = {
                    type_id: filterType ? filterType.value : '',
                    package_id: filterPackage ? filterPackage.value : '',
                    period: filterPeriod ? filterPeriod.value : ''
                };

                const url = buildQuery(params);

                try {
                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Gagal mengambil data chart');
                    }

                    const chartData = await response.json();

                    // Tampilkan di console untuk debugging
                    console.log('chartData:', chartData);

                    updateChart(chartData);
                    updateSummary(chartData);
                } catch (error) {
                    console.error(error);
                } finally {
                    toggleLoading(false);
                }
            }

            function updateChart(chartData) {
                if (!Array.isArray(chartData)) {
                    return;
                }

                const labels = chartData.map(item => item.label);
                const data = chartData.map(item => item.score);

                if (chartInstance) {
                    chartInstance.data.labels = labels;
                    chartInstance.data.datasets[0].data = data;
                    chartInstance.update();
                    return;
                }

                chartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Nilai Tertinggi per User',
                                data: data,
                                borderColor: '#4f46e5',
                                backgroundColor: 'rgba(79, 70, 229, 0.15)',
                                borderWidth: 2,
                                tension: 0.3,
                                fill: true,
                                pointRadius: 3,
                                pointBackgroundColor: '#4f46e5'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    color: '#9ca3af',
                                    maxRotation: 0,
                                    autoSkip: true,
                                    maxTicksLimit: 7
                                },
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: '#9ca3af',
                                    precision: 0
                                },
                                grid: {
                                    color: 'rgba(156, 163, 175, 0.2)'
                                }
                            }
                        }
                    }
                });
            }

            function updateSummary(chartData) {
                if (!Array.isArray(chartData) || chartData.length === 0) {
                    if (avgScoreEl) {
                        avgScoreEl.textContent = '0';
                    }
                    if (maxScoreEl) {
                        maxScoreEl.textContent = '0';
                    }
                    if (totalUsersEl) {
                        totalUsersEl.textContent = '0';
                    }
                    return;
                }

                let totalScore = 0;
                let maxScore = 0;

                chartData.forEach(item => {
                    const score = Number(item.score) || 0;
                    totalScore += score;
                    if (score > maxScore) {
                        maxScore = score;
                    }
                });

                const avg = totalScore / chartData.length;

                if (avgScoreEl) {
                    avgScoreEl.textContent = avg.toFixed(1);
                }
                if (maxScoreEl) {
                    maxScoreEl.textContent = maxScore.toFixed(1);
                }
                if (totalUsersEl) {
                    totalUsersEl.textContent = chartData.length;
                }
            }

            if (filterType) {
                filterType.addEventListener('change', fetchChartData);
            }
            if (filterPackage) {
                filterPackage.addEventListener('change', fetchChartData);
            }
            if (filterPeriod) {
                filterPeriod.addEventListener('change', fetchChartData);
            }

            // Initial load
            fetchChartData();
        });
    </script>
@endpush
