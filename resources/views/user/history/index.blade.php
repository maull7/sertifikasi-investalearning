@extends('layouts.app')

@section('title', 'Bank Question')

@section('content')
<div class="space-y-8 pb-20" x-data="{ 
    deleteModalOpen: false, 
    deleteUrl: '', 
    questionTitle: '',
    importModalOpen: false,
    confirmDelete(url, title) {
        this.deleteUrl = url;
        this.questionTitle = title;
        this.deleteModalOpen = true;
    }
}">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Riwayat Pengerjaan</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Lihat riwayat ujian Anda (pretest & posttest)</p>
        </div>
    </div>

    {{-- Tab: Posttest | Pretest --}}
    <div class="flex gap-2 border-b border-gray-200 dark:border-gray-700">
        <a href="{{ route('user.history-exams.index', ['exam_type' => 'posttest', 'package_id' => $packageId ?? '', 'exam_id' => $examId ?? '']) }}"
           class="px-4 py-3 text-sm font-semibold rounded-t-xl transition-colors {{ ($examType ?? 'posttest') === 'posttest' ? 'bg-indigo-500 text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            Posttest
        </a>
        <a href="{{ route('user.history-exams.index', ['exam_type' => 'pretest', 'package_id' => $packageId ?? '', 'exam_id' => $examId ?? '']) }}"
           class="px-4 py-3 text-sm font-semibold rounded-t-xl transition-colors {{ ($examType ?? '') === 'pretest' ? 'bg-indigo-500 text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            Pretest
        </a>
    </div>

    {{-- Filter --}}
    <div class="flex flex-col lg:flex-row gap-4">
        <form action="{{ route('user.history-exams.index') }}" method="GET" class="flex-1 flex flex-col md:flex-row gap-3 items-end">
            <input type="hidden" name="exam_type" value="{{ $examType ?? 'posttest' }}">
            <div class="w-full md:w-64">
                <x-select name="package_id" label="Filter Sesuai paket" inline class="h-12">
                    <option value="">-- Semua paket --</option>
                    @foreach($packages as $data)
                        <option value="{{ $data->id }}" {{ (int) ($packageId ?? 0) === $data->id ? 'selected' : '' }}>{{ $data->title }}</option>
                    @endforeach
                </x-select>
            </div>
            <div class="w-full md:w-64">
                <x-select name="exam_id" label="Filter Ujian" inline class="h-12">
                    <option value="">-- Semua ujian --</option>
                    @foreach($exams as $data)
                        <option value="{{ $data->id }}" {{ (int) ($examId ?? 0) === $data->id ? 'selected' : '' }}>{{ $data->title }}</option>
                    @endforeach
                </x-select>
            </div>
            <div class="flex gap-2">
                <x-button type="submit" variant="primary" class="h-12 px-6 rounded-xl">Terapkan</x-button>
            </div>
        </form>
    </div>


    {{-- Main Data Card --}}
    <x-card :padding="false" title="Riwayat Ujian Anda">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Paket</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Ujian / Exam</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Tipe</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Tipe Ujian</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Total Soal</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Terjawab</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Total Poin</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Aksi</th>
                        
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse ($history as $data)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                           <td class="py-4 px-8">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                    {{ $data->Package->title ?? '-' }}
                                </span>
                            </td>

                          
                            <td class="py-4 px-8">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-sm text-gray-900 dark:text-white">
                                        {{ $data->Exam->title ?? '-' }}
                                    </span>
                                    <span class="text-xs text-gray-400">
                                        {{ $data->created_at->format('d M Y') }}
                                    </span>
                                </div>
                            </td>

                            <td class="py-4 px-8 text-center">
                                <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold
                                    bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                    {{ strtoupper($data->Package->masterType->name_type ?? '-') }}
                                </span>
                            </td>

                            <td class="py-4 px-8 text-center">
                                @php $examType = $data->Exam->type ?? null; @endphp
                                @if($examType === 'pretest')
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold bg-sky-100 text-sky-800 dark:bg-sky-500/20 dark:text-sky-300">Pretest</span>
                                @elseif($examType === 'posttest')
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold bg-violet-100 text-violet-800 dark:bg-violet-500/20 dark:text-violet-300">Posttest</span>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>

                           <td class="py-4 px-8 text-center">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    {{ $data->total_questions }}
                                </span>
                            </td>

                            <td class="py-4 px-8 text-center">
                                <span class="inline-flex items-center gap-1 text-sm font-semibold
                                    text-emerald-600 dark:text-emerald-400">
                                    {{ $data->questions_answered }}
                                    <span class="text-xs text-gray-400">
                                        / {{ $data->total_questions }}
                                    </span>
                                </span>
                            </td>

                            <td class="py-4 px-8 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                                    bg-yellow-100 text-yellow-800 dark:bg-yellow-500/10 dark:text-yellow-300">
                                    {{ $data->total_score }} poin
                                </span>
                            </td>

                            <td class="py-4 px-8 text-center">
                                <div class="flex items-center justify-end gap-2">
                                    <x-button variant="primary" size="sm" href="{{ route('user.history-exams.detail', $data->id) }}" class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                        <i class="ti ti-eye text-base"></i>
                                    </x-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-24">
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
                                                Belum ada soal. Mulai tambahkan soal baru.
                                            @endif
                                        </p>
                                    </div>
                                    <div class="mt-6">
                                        @if(request('package_id') || request('exam_id'))
                                            <x-button variant="secondary" href="{{ route('user.history-exams.index', ['exam_type' => $examType ?? 'posttest']) }}">
                                                Reset Filter
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

        @if($history->hasPages())
            <div class="px-8 py-6 border-t border-gray-50 dark:border-gray-800">
                {{ $history->links() }}
            </div>
        @endif
    </x-card>

</div>
@endsection

