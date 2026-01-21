@extends('layouts.app')

@section('title', 'Mapping Soal - ' . $exam->title)

@section('content')
<div class="space-y-8 pb-20" 
     x-data="{ 
        deleteModalOpen: false,
        deleteUrl: '',
        questionTitle: '',
        autoCount: {{ (int) ($exam->total_questions ?? 10) }},
        confirmDelete(url, title) {
            this.deleteUrl = url;
            this.questionTitle = title;
            this.deleteModalOpen = true;
        },
        autoSelect() {
            const n = parseInt(this.autoCount) || 0;
            const tbody = this.$refs.questionTbody;
            if (!tbody) return;
            const boxes = tbody.querySelectorAll('input[type=checkbox]');
            boxes.forEach((box, idx) => {
                box.checked = idx < n;
            });
        }
     }">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                Mapping Soal - {{ $exam->title }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                Pilih soal dari bank soal untuk ujian ini. Bisa pilih satu-satu atau secara acak.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <x-button variant="secondary" href="{{ route('mapping-questions.index') }}" class="rounded-xl">
                <i class="ti ti-arrow-left mr-2"></i> Kembali ke Daftar
            </x-button>
        </div>
    </div>

    <div class="space-y-8">
        {{-- Kiri: Pilih Soal --}}
        <div class="space-y-4">
            <x-card :padding="false" title="Pilih Soal dari Bank">
                <div class="border-b border-gray-100 dark:border-gray-800 px-6 py-4 flex flex-col md:flex-row gap-4 md:items-center md:justify-between">
                    <div class="flex-1 flex flex-col md:flex-row gap-3 md:items-center">
                        {{-- Filter Jenis Soal --}}
                        <form action="{{ route('mapping-questions.manage', $exam) }}" method="GET" class="flex-1 flex flex-col md:flex-row gap-3">
                            <input type="hidden" name="mapped_page" value="{{ request('mapped_page') }}">
                            <div class="w-full md:w-60">
                                <x-select name="type_id" label="Filter Tipe Soal" inline>
                                    <option value="">Semua Tipe</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ (int) $typeId === $type->id ? 'selected' : '' }}>
                                            {{ $type->name_type }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="flex items-end gap-2">
                                <x-button type="submit" variant="primary" class="rounded-xl">
                                    Terapkan
                                </x-button>
                                <a href="{{ route('mapping-questions.manage', $exam) }}" class="text-xs text-gray-500 hover:text-rose-500">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>
                    {{-- Random --}}
                    <div class="flex flex-col gap-3">
                        <form action="{{ route('mapping-questions.random', $exam) }}" method="POST" class="flex items-end gap-2">
                            @csrf
                            <input type="hidden" name="type_id" value="{{ $typeId }}">
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">
                                    Tambah Acak
                                </label>
                                <div class="flex items-center gap-2">
                                    <input type="number" name="total" min="1" max="1000" value="{{ (int) ($exam->total_questions ?? 5) }}"
                                           class="w-20 px-2 py-1.5 text-xs rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                                    <x-button type="submit" variant="secondary" size="sm" class="rounded-lg">
                                        <i class="ti ti-dice-3 mr-1 text-sm"></i> Acak
                                    </x-button>
                                </div>
                            </div>
                        </form>
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">
                                Pilih Otomatis
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="number" min="1" max="1000" x-model.number="autoCount"
                                       class="w-20 px-2 py-1.5 text-xs rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                                <x-button type="button" variant="secondary" size="sm" class="rounded-lg" @click="autoSelect()">
                                    <i class="ti ti-checklist mr-1 text-sm"></i> Pilih
                                </x-button>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('mapping-questions.store', $exam) }}" method="POST">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                                    <th class="py-3 px-6 w-10">
                                        {{-- Checkbox all (optional) --}}
                                    </th>
                                    <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Tipe</th>
                                    <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Soal</th>
                                    <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Jawaban</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-800" x-ref="questionTbody">
                                @forelse ($questions as $q)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                                        <td class="py-3 px-6 align-top">
                                            <input type="checkbox" name="question_ids[]" value="{{ $q->id }}"
                                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        </td>
                                        <td class="py-3 px-6 align-top">
                                            <div class="flex flex-col gap-1">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-300">
                                                    {{ $q->type->name_type ?? '-' }}
                                                </span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold 
                                                    {{ ($q->question_type ?? 'Text') === 'Image' 
                                                        ? 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300' 
                                                        : 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300' }}">
                                                    <i class="ti {{ ($q->question_type ?? 'Text') === 'Image' ? 'ti-photo' : 'ti-text-size' }} mr-1"></i>
                                                    {{ $q->question_type ?? 'Text' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6 align-top">
                                            <div class="max-w-md space-y-1">
                                                <div class="text-sm text-gray-900 dark:text-white font-medium line-clamp-2">
                                                    @if(($q->question_type ?? 'Text') === 'Text')
                                                        {!! \Illuminate\Support\Str::limit(strip_tags($q->question), 120) !!}
                                                    @else
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            Soal berupa gambar.
                                                        </span>
                                                    @endif
                                                </div>
                                                @if(($q->question_type ?? 'Text') === 'Image')
                                                    <a href="{{asset('storage/' . $q->question)}}"
                                                            class="inline-flex items-center gap-1 text-xs text-indigo-600 dark:text-indigo-400"
                                                            target="_blank"
                                                            >
                                                            <i class="ti ti-photo"></i> Lihat Gambar
                                                        </a>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3 px-6 text-center align-top">
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 font-bold text-xs">
                                                {{ strtoupper($q->answer) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-10">
                                            <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                                                Tidak ada soal yang tersedia / semua soal sudah ter-mapping ke ujian ini.
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($questions->hasPages())
                        <div class="px-6 py-4 border-t border-gray-50 dark:border-gray-800">
                            {{ $questions->links() }}
                        </div>
                    @endif

                    <div class="px-6 py-4 border-t border-gray-50 dark:border-gray-800 flex justify-end">
                        <x-button type="submit" variant="primary" class="rounded-xl shadow-lg shadow-indigo-500/20">
                            Tambah ke Ujian
                        </x-button>
                    </div>
                </form>
            </x-card>
        </div>

        {{-- Kanan: Soal yang sudah di-mapping --}}
        <div class="space-y-4">
            <x-card :padding="false" title="Soal di Ujian Ini">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                                <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Tipe</th>
                                <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Soal</th>
                                <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                            @forelse ($mapped as $map)
                                @php($q = $map->questionBank)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                                    <td class="py-3 px-6 align-top">
                                        <div class="flex flex-col gap-1">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-300">
                                                {{ $q->type->name_type ?? '-' }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold 
                                                {{ ($q->question_type ?? 'Text') === 'Image' 
                                                    ? 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300' 
                                                    : 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300' }}">
                                                <i class="ti {{ ($q->question_type ?? 'Text') === 'Image' ? 'ti-photo' : 'ti-text-size' }} mr-1"></i>
                                                {{ $q->question_type ?? 'Text' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 align-top">
                                        <div class="max-w-md space-y-1">
                                            <div class="text-sm text-gray-900 dark:text-white font-medium line-clamp-2">
                                                @if(($q->question_type ?? 'Text') === 'Text')
                                                    {!! \Illuminate\Support\Str::limit(strip_tags($q->question), 120) !!}
                                                @else
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                        Soal berupa gambar.
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 align-top">
                                        <div class="flex items-center justify-end gap-2">
                                            <x-button variant="info" size="sm" href="{{ route('mapping-questions.show', [$exam, $map]) }}" class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                                <i class="ti ti-eye text-base"></i>
                                            </x-button>
                                            <x-button variant="danger" size="sm" type="button"
                                                @click="confirmDelete('{{ route('mapping-questions.destroy', [$exam, $map]) }}', '{{ \Illuminate\Support\Str::limit(strip_tags($q->question), 50) }}')"
                                                class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                                <i class="ti ti-trash text-base"></i>
                                            </x-button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-10">
                                        <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                                            Belum ada soal yang di-mapping ke ujian ini.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($mapped->hasPages())
                    <div class="px-6 py-4 border-t border-gray-50 dark:border-gray-800">
                        {{ $mapped->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="deleteModalOpen" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        
        <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800"
            @click.away="deleteModalOpen = false">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-rose-50 dark:bg-rose-500/10 text-rose-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i class="ti ti-trash-x text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Hapus Soal dari Ujian?</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    Anda akan menghapus soal <span class="font-bold text-gray-900 dark:text-white" x-text="questionTitle"></span> dari ujian ini. Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            
            <div class="flex gap-3 p-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800">
                <x-button variant="secondary" class="flex-1 rounded-xl" @click="deleteModalOpen = false">
                    Batal
                </x-button>
                <form :action="deleteUrl" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <x-button variant="danger" type="submit" class="w-full rounded-xl shadow-lg shadow-rose-500/20">
                        Ya, Hapus
                    </x-button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


