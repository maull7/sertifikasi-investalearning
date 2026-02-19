@extends('layouts.app')

@section('title', 'Peserta - ' . $package->title)

@section('content')
    <div class="space-y-8 pb-20">
        <nav class="flex items-center gap-2 text-xs font-medium text-gray-400">
            <a href="{{ route('monitor-participants.index') }}" class="hover:text-indigo-600 transition-colors">Monitor
                Peserta</a>
            <i class="ti ti-chevron-right"></i>
            <span class="text-gray-500 dark:text-gray-500">Peserta</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $package->title }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $package->masterType->name_type ?? '-' }} ·
                    {{ $participants->total() }} peserta</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="primary" href="{{ route('monitor-participants.package.export', $package) }}"
                    class="rounded-xl">
                    <i class="ti ti-file-spreadsheet mr-2"></i> Export Excel
                </x-button>
                <x-button variant="secondary" href="{{ route('monitor-participants.index') }}" class="rounded-xl">
                    <i class="ti ti-arrow-left mr-2"></i> Kembali ke Daftar Paket
                </x-button>
            </div>
        </div>

        <form action="{{ route('monitor-participants.package', $package) }}" method="GET" class="relative max-w-xl">
            <i class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama atau email peserta..."
                class="w-full pl-11 pr-12 py-3 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl text-sm outline-none focus:ring-4 focus:ring-indigo-500/5 transition-all dark:text-white">
            @if (request('search'))
                <a href="{{ route('monitor-participants.package', $package) }}"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition-colors"><i
                        class="ti ti-x"></i></a>
            @endif
        </form>

        @if (!empty($rankingChartData))
            <x-card title="Perbandingan Nilai Peserta (Rata-rata Tryout)">
                <div class="w-full max-w-4xl mx-auto min-h-[240px] h-64 sm:h-72 overflow-hidden">
                    <canvas id="rankingChart"></canvas>
                </div>
            </x-card>
        @endif

        <x-card :padding="false" title="Daftar Peserta & Ranking">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider w-16">
                                Peringkat</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Nama Peserta
                            </th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Email</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">
                                Rata-rata Tryout</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse ($participants as $row)
                            @php $uj = $row['user_join']; @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                                <td class="py-4 px-8">
                                    @if ($row['rank'] <= 3)
                                        <span
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-xl text-sm font-bold
                                        {{ $row['rank'] === 1 ? 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400' : '' }}
                                        {{ $row['rank'] === 2 ? 'bg-gray-200 text-gray-700 dark:bg-gray-600 dark:text-gray-300' : '' }}
                                        {{ $row['rank'] === 3 ? 'bg-amber-50 text-amber-800 dark:bg-amber-500/10 dark:text-amber-500' : '' }}">
                                            {{ $row['rank'] }}
                                        </span>
                                    @else
                                        <span
                                            class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $row['rank'] }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-8 font-medium text-gray-900 dark:text-white">{{ $uj->user->name }}</td>
                                <td class="py-4 px-8 text-sm text-gray-600 dark:text-gray-400">{{ $uj->user->email }}</td>
                                <td class="py-4 px-8 text-center">
                                    @if (isset($row['avg_tryout']) && $row['avg_tryout'] !== null)
                                        <span
                                            class="inline-flex items-center justify-center min-w-[3rem] px-3 py-1.5 rounded-xl text-sm font-bold
                                        {{ $row['avg_tryout'] >= 70 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' }}">
                                            {{ number_format($row['avg_tryout'], 1, ',', '') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500 text-sm">—</span>
                                    @endif
                                </td>
                                <td class="py-4 px-8">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-button variant="primary" size="sm"
                                            href="{{ route('monitor-participants.show', $uj) }}" class="rounded-xl">
                                            <i class="ti ti-chart-line mr-1"></i> Detail
                                        </x-button>
                                        <x-button variant="secondary" size="sm"
                                            href="{{ route('monitor-participants.export', $uj) }}" class="rounded-xl">
                                            <i class="ti ti-download mr-1"></i> Export
                                        </x-button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-24">
                                    <div
                                        class="flex flex-col items-center justify-center text-center max-w-[280px] mx-auto">
                                        <div
                                            class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-4">
                                            <i class="ti ti-users text-3xl text-gray-400"></i>
                                        </div>
                                        <h4 class="text-base font-bold text-gray-900 dark:text-white">
                                            @if (request('search'))
                                                Hasil tidak ditemukan
                                            @else
                                                Belum ada peserta
                                            @endif
                                        </h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            @if (request('search'))
                                                Coba kata kunci lain.
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($participants->hasPages())
                <div class="px-8 py-6 border-t border-gray-50 dark:border-gray-800">
                    {{ $participants->links() }}
                </div>
            @endif
        </x-card>
    </div>

    @if (!empty($rankingChartData))
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const data = @json($rankingChartData);
                    const ctx = document.getElementById('rankingChart');
                    if (ctx && data.length > 0) {
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: data.map(d => d.label),
                                datasets: [{
                                    label: 'Rata-rata Tryout',
                                    data: data.map(d => d.score),
                                    borderColor: 'rgb(99, 102, 241)',
                                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                                    borderWidth: 2,
                                    fill: true,
                                    tension: 0.3,
                                    pointBackgroundColor: 'rgb(99, 102, 241)',
                                    pointRadius: 4,
                                    pointHoverRadius: 6,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 100,
                                        grid: {
                                            color: 'rgba(0,0,0,0.06)'
                                        },
                                        ticks: {
                                            color: '#6b7280',
                                            maxTicksLimit: 6
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false
                                        },
                                        ticks: {
                                            color: '#6b7280',
                                            maxRotation: 45,
                                            autoSkip: true,
                                            maxTicksLimit: 12
                                        }
                                    }
                                }
                            }
                        });
                    }
                });
            </script>
        @endpush
    @endif
@endsection
