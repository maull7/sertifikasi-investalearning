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
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Riwayat Jawaban user</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Lihat riwayat ujian</p>
        </div>
        <div class="flex gap-2">
            <x-button 
                icon="ti ti-arrow-left"
                type="submit"
                href="{{ route('show-grades.index') }}" 
                variant="primary" 
                class="h-12 px-6 rounded-xl"
            >
                Kembali ke Riwayat Ujian
            </x-button>
        </div>
      
    </div>

    {{-- Main Data Card --}}
    <x-card :padding="false" title="Riwayat Ujian Anda">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Soal</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Jawaban Anda</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Jawaban Benar</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">Status</th>
                        <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">Poin Diperoleh</th>
                        
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse ($historyDetail as $data)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                            <td class="py-4 px-8">
                                @if ($data->Question->question_type == 'Text')   
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-300">
                                    {{ $data->Question->question ?? '-' }}
                                </span>
                                @else
                                     <a href="{{asset('storage/' . $data->Question->question)}}"
                                                 class="inline-flex px-3 py-1 items-center gap-1 text-xs text-indigo-600 dark:text-indigo-400"
                                                target="_blank"
                                                 >
                                                <i class="ti ti-photo"></i> Lihat Gambar
                                    </a>
                                </span>
                                @endif
                            </td>
                          
                            <td class="py-4 px-8 text-center">
                                <span class="inline-flex items-center justify-center p-2 rounded-lg font-bold text-sm bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-300">
                                    {{ strtoupper($data->user_answer) }}
                                </span>
                            </td>
                            <td class="py-4 px-8 text-center">
                                <span class="inline-flex items-center justify-center p-1 rounded-lg font-bold text-sm bg-green-100 text-green-800 dark:bg-green-500/10 dark:text-green-300">
                                    {{ strtoupper($data->correct_answer) }}
                                </span>
                            </td>
                            <td class="py-4 px-8 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg font-bold text-sm ">
                                    {{ $data->user_answer == $data->correct_answer ? 'BENAR' : 'SALAH' }}
                                </span>
                            </td>
                            <td class="py-4 px-8 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg font-bold text-sm">
                                    {{ strtoupper($data->score_obtained) }}
                                </span>
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
                                                Belum ada soal. Mulai tambahkan soal baru.
                                            @endif
                                        </p>
                                    </div>
                                    <div class="mt-6">
                                        @if(request('search'))
                                            <x-button variant="secondary"  href="{{ route('bank-questions.index') }}">
                                                Reset Pencarian
                                            </x-button>
                                        @else
                                            <x-button variant="secondary"  href="{{ route('bank-questions.create') }}">
                                                <i class="ti ti-plus mr-2"></i> Tambah Sekarang
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

        @if($historyDetail->hasPages())
            <div class="px-8 py-6 border-t border-gray-50 dark:border-gray-800">
                {{ $historyDetail->links() }}
            </div>
        @endif
    </x-card>
</div>
@endsection

