@extends('layouts.app')

@section('title', 'Bank Question')

@section('content')
<div class="space-y-8 pb-20" x-data="{ 
    questionTitle: '',
}">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Riwayat Ujian</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Lihat riwayat ujian user</p>
        </div>
      
    </div>

    {{-- Search & Filter Section --}}
    <div class="flex flex-col lg:flex-row gap-4">
        <form 
            action="{{ route('show-grades.index') }}" 
            method="GET" 
            class="flex-1 flex flex-col md:flex-row gap-3 items-end"
        >
            <!-- Select -->
            <div class="w-full md:w-64">
                <x-select 
                    name="package_id" 
                    label="Filter Sesuai paket" 
                    inline
                    class="h-12"
                >
                    @foreach($packages as $data)
                        <option value="{{ $data->id }}" {{ (int) ($packageId ?? 0) === $data->id ? 'selected' : '' }}>
                            {{ $data->title }}
                        </option>
                    @endforeach
                </x-select>
            </div>

            <!-- Select -->
            <div class="w-full md:w-64">
                <x-select 
                    name="exam_id" 
                    label="Filter Sesuai Ujian" 
                    inline
                    class="h-12"
                >
                    @foreach($exams as $data)
                        <option value="{{ $data->id }}" {{ (int) ($examId ?? 0) === $data->id ? 'selected' : '' }}>
                            {{ $data->title }}
                        </option>
                    @endforeach
                </x-select>
            </div>

            <!-- Button -->
            <div class="flex gap-2">
                <x-button 
                    type="submit" 
                    variant="primary" 
                    class="h-12 px-6 rounded-xl"
                >
                    Terapkan
                </x-button>
            </div>
        </form>
    </div>

    {{-- Main Data Card --}}
    <x-card :padding="false" title="Riwayat Ujian Peserta">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Nama Peserta</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Paket</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Ujian / Exam</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Tipe</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Total Soal</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Terjawab</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Total Poin</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Aksi</th>
                        
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse ($list as $data)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ">
                                    {{ $data->User->name ?? '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-8">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-300">
                                    {{ $data->Package->title ?? '-' }}
                                </span>
                            </td>
                          
                            <td class="py-4 px-8 text-center">
                                <span class="inline-flex items-center justify-center rounded-lg font-bold text-sm ">
                                    {{ strtoupper($data->Exam->title) }}
                                </span>
                            </td>
                            <td class="py-4 px-8 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg font-bold text-sm">
                                    {{ strtoupper($data->Type->name_type) }}
                                </span>
                            </td>
                            <td class="py-4 px-8 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg font-bold text-sm">
                                    {{ strtoupper($data->total_questions) }}
                                </span>
                            </td>
                            <td class="py-4 px-8 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg font-bold text-sm">
                                    {{ strtoupper($data->questions_answered) }}
                                </span>
                            </td>
                            <td class="py-4 px-8 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg font-bold text-sm">
                                    {{ strtoupper($data->total_score) }}
                                </span>
                            </td>
                            <td class="py-4 px-8 text-center">
                                <div class="flex items-center justify-end gap-2">
                                    <x-button variant="primary" size="sm" href="{{ route('show-grades.detail', $data->id) }}" class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                        <i class="ti ti-eye text-base"></i>
                                    </x-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-24">
                                <div class="flex flex-col items-center justify-center text-center max-w-[280px] mx-auto">
                                    <div class="space-y-1">
                                        <h4 class="text-base font-bold text-gray-900 dark:text-white">
                                            @if(request('search')) 
                                                Hasil tidak ditemukan 
                                            @else 
                                                Belum Ada Data 
                                            @endif
                                        </h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                            @if(request('search'))
                                                Tidak ada hasil untuk kata kunci "{{ request('search') }}".
                                            @else
                                                Belum ada data riwayat ujian.
                                            @endif
                                        </p>
                                    </div>
                                    <div class="mt-6">
                                        @if(request('search'))
                                            <x-button variant="secondary"  href="{{ route('show-grades.index') }}">
                                                Reset Pencarian
                                            </x-button>
                                        
                                           
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($list->hasPages())
            <div class="px-8 py-6 border-t border-gray-50 dark:border-gray-800">
                {{ $list->links() }}
            </div>
        @endif
    </x-card>


</div>
@endsection

