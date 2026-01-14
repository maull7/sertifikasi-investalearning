@extends('layouts.app')

@section('title', 'Detail Ujian')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 pb-20">
    
    {{-- Header & Breadcrumb --}}
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Detail Ujian</h1>
            <nav class="flex items-center gap-2 text-xs font-medium text-gray-400">
                <a href="{{ route('exams.index') }}" class="hover:text-indigo-600 transition-colors">Ujian</a>
                <i class="ti ti-chevron-right"></i>
                <span class="text-gray-500 dark:text-gray-500">Detail</span>
            </nav>
        </div>
        <div class="flex items-center gap-3">
            <x-button variant="secondary" href="{{ route('exams.edit', $data->id) }}" class="rounded-xl">
                <i class="ti ti-pencil mr-2"></i> Edit
            </x-button>
            <x-button variant="secondary" href="{{ route('exams.index') }}" class="rounded-xl">
                <i class="ti ti-arrow-left mr-2"></i> Kembali
            </x-button>
        </div>
    </div>

    {{-- Detail Card --}}
    <x-card title="Informasi Ujian">
        <div class="space-y-6">
            
            {{-- Paket --}}
            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4 pb-6 border-b border-gray-50 dark:border-gray-800">
                <div class="md:w-1/3">
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Paket</span>
                </div>
                <div class="md:w-2/3">
                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                        {{ $data->package->title ?? '-' }}
                    </span>
                </div>
            </div>

            {{-- Title --}}
            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4 pb-6 border-b border-gray-50 dark:border-gray-800">
                <div class="md:w-1/3">
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Judul Ujian</span>
                </div>
                <div class="md:w-2/3">
                    <p class="text-base font-bold text-gray-900 dark:text-white">{{ $data->title }}</p>
                </div>
            </div>

            {{-- Description --}}
            @if($data->description)
            <div class="flex flex-col md:flex-row md:items-start gap-2 md:gap-4 pb-6 border-b border-gray-50 dark:border-gray-800">
                <div class="md:w-1/3">
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deskripsi</span>
                </div>
                <div class="md:w-2/3">
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $data->description }}</p>
                </div>
            </div>
            @endif

            {{-- Duration & Passing Grade --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pb-6 border-b border-gray-50 dark:border-gray-800">
                <div>
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-3">Durasi</span>
                    <div class="flex items-center gap-2">
                        <div class="w-12 h-12 bg-amber-50 dark:bg-amber-500/10 rounded-xl flex items-center justify-center">
                            <i class="ti ti-clock text-2xl text-amber-500"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data->duration }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Menit</p>
                        </div>
                    </div>
                </div>

                <div>
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-3">Jumlah Soal</span>
                    <div class="flex items-center gap-2">
                        <div class="w-12 h-12 bg-sky-50 dark:bg-sky-500/10 rounded-xl flex items-center justify-center">
                            <i class="ti ti-list-numbers text-2xl text-sky-500"></i>
                        </div>
                        <div>
                            @php($total = $data->total_questions ?? $data->mappingQuestions()->count())
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $total }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Soal</p>
                        </div>
                    </div>
                </div>

                <div>
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-3">Nilai Kelulusan</span>
                    <div class="flex items-center gap-2">
                        <div class="w-12 h-12 bg-green-50 dark:bg-green-500/10 rounded-xl flex items-center justify-center">
                            <i class="ti ti-award text-2xl text-green-500"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data->passing_grade }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Poin</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Periode --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-gray-50 dark:border-gray-800">
                <div>
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-3">Tanggal Mulai</span>
                    <div class="flex items-center gap-2">
                        <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl flex items-center justify-center">
                            <i class="ti ti-calendar-event text-2xl text-emerald-500"></i>
                        </div>
                        <div>
                            <p class="text-base font-bold text-gray-900 dark:text-white">{{ $data->start_date->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $data->start_date->format('H:i') }} WIB</p>
                        </div>
                    </div>
                </div>

                <div>
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-3">Tanggal Selesai</span>
                    <div class="flex items-center gap-2">
                        <div class="w-12 h-12 bg-rose-50 dark:bg-rose-500/10 rounded-xl flex items-center justify-center">
                            <i class="ti ti-calendar-x text-2xl text-rose-500"></i>
                        </div>
                        <div>
                            <p class="text-base font-bold text-gray-900 dark:text-white">{{ $data->end_date->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $data->end_date->format('H:i') }} WIB</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Timestamps --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-2">Dibuat</span>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $data->created_at->format('d M Y, H:i') }} WIB</p>
                </div>

                <div>
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-2">Terakhir Diperbarui</span>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $data->updated_at->format('d M Y, H:i') }} WIB</p>
                </div>
            </div>

        </div>
    </x-card>
</div>
@endsection



