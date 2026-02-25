@extends('layouts.app')

@section('title', 'Mapping Soal - Tambah Baru')

@section('content')
    <div class="space-y-8 pb-20">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                    Mapping Soal - Tambah Baru
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                    Pilih ujian lalu tentukan soal dari bank soal (manual atau acak).
                </p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="secondary" href="{{ route('mapping-questions.index') }}" class="rounded-xl">
                    <i class="ti ti-arrow-left mr-2"></i> Kembali ke Daftar
                </x-button>
            </div>
        </div>

        {{-- Pilih Ujian --}}
        <x-card>
            <form action="{{ route('mapping-questions.create') }}" method="GET" class="space-y-4">
                <div class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Pilih
                            Ujian</label>
                        <select name="exam_id"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Pilih Ujian --</option>
                            @foreach ($exams as $exam)
                                <option value="{{ $exam->id }}"
                                    {{ (int) ($examId ?? 0) === $exam->id ? 'selected' : '' }}>
                                    {{ $exam->title }}@if ($exam->package)
                                        ({{ $exam->package->title }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @if ($selectedExam)
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Anda memilih ujian <span
                                    class="font-semibold text-gray-900 dark:text-white">{{ $selectedExam->title }}</span>.

                            </p>
                            <div class="font-semibold text-gray-900 dark:text-white">
                                Deskripsi: {{ $selectedExam->description }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <x-button type="submit" variant="primary" class="rounded-xl">
                            Pilih
                        </x-button>
                    </div>
                </div>
            </form>
        </x-card>

        @if ($selectedExam)
            <div class="space-y-8" x-data="{
                deleteModalOpen: false,
                deleteUrl: '',
                questionTitle: '',
                autoCount: {{ (int) ($selectedExam->total_questions ?? 10) }},
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

                {{-- Kiri: Pilih Soal --}}
                <div class="space-y-4">
                    <x-card :padding="false" title="Pilih Soal dari Bank">
                        <div
                            class="border-b border-gray-100 dark:border-gray-800 px-6 py-4 flex flex-col md:flex-row gap-4 md:items-center md:justify-between">
                            <div class="flex-1 flex flex-col md:flex-row gap-3 md:items-center">
                                {{-- Filter Jenis Soal --}}
                                <form action="{{ route('mapping-questions.create') }}" method="GET"
                                    class="flex-1 flex flex-col md:flex-row gap-3">
                                    <input type="hidden" name="type" value="exam">
                                    <input type="hidden" name="exam_id" value="{{ $selectedExam->id }}">
                                    <input type="hidden" name="mapped_page" value="{{ request('mapped_page') }}">
                                    <input type="hidden" name="subject_id" value="{{ $subjectId }}">
                                    <input type="hidden" name="sort_by" value="{{ $sortBy ?? 'created_at' }}">
                                    <input type="hidden" name="sort_order" value="{{ $sortOrder ?? 'desc' }}">
                                    <div class="w-full md:w-60">
                                        <x-select name="subject_id" label="Filter Soal Dengan Mapel" inline>
                                            <option value="">Semua Mapel</option>
                                            @foreach ($subjects as $subject)
                                                <option value="{{ $subject->id }}"
                                                    {{ (int) $subjectId === $subject->id ? 'selected' : '' }}>
                                                    {{ $subject->name }}
                                                </option>
                                            @endforeach
                                        </x-select>
                                    </div>
                                    <div class="flex items-end gap-2">
                                        <x-button type="submit" variant="primary" class="rounded-xl">
                                            Terapkan
                                        </x-button>
                                        <x-button type="button" variant="secondary" class="rounded-xl"
                                            onclick="window.location='{{ route('mapping-questions.create', ['exam_id' => $selectedExam->id]) }}'">
                                            <i class="ti ti-eraser mr-1"></i> Hapus Filter

                                        </x-button>

                                    </div>
                                </form>
                            </div>
                            {{-- Random --}}
                            <div class="flex flex-col gap-3">
                                <form action="{{ route('mapping-questions.random', $selectedExam) }}" method="POST"
                                    class="flex items-end gap-2">
                                    @csrf
                                    <input type="hidden" name="subject_id" value="{{ $subjectId }}">
                                    <div>
                                        <label
                                            class="block text-[11px] font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">
                                            Tambah Acak
                                        </label>
                                        <div class="flex items-center gap-2">
                                            <input type="number" name="total" min="1" max="1000"
                                                value="{{ (int) ($selectedExam->total_questions ?? 5) }}"
                                                class="w-20 px-2 py-1.5 text-xs rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                                            <x-button type="submit" variant="secondary" size="sm" class="rounded-lg">
                                                <i class="ti ti-dice-3 mr-1 text-sm"></i> Acak
                                            </x-button>
                                        </div>
                                    </div>
                                </form>
                                <div>
                                    <label
                                        class="block text-[11px] font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">
                                        Pilih Otomatis
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <input type="number" min="1" max="1000" x-model.number="autoCount"
                                            class="w-20 px-2 py-1.5 text-xs rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                                        <x-button type="button" variant="secondary" size="sm" class="rounded-lg"
                                            @click="autoSelect()">
                                            <i class="ti ti-checklist mr-1 text-sm"></i> Pilih
                                        </x-button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('mapping-questions.store', $selectedExam) }}" method="POST">
                            @csrf
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr
                                            class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                                            <th class="py-3 px-6 w-10"></th>
                                            @php
                                                $sortBy = $sortBy ?? 'created_at';
                                                $sortOrder = $sortOrder ?? 'desc';
                                                $sortUrl = fn($col) => route('mapping-questions.create', array_filter([
                                                    'exam_id' => $selectedExam->id ?? null,
                                                    'subject_id' => $subjectId ?? null,
                                                    'mapped_page' => request('mapped_page'),
                                                    'page' => request('page'),
                                                    'sort_by' => $col,
                                                    'sort_order' => ($sortBy === $col && $sortOrder === 'asc') ? 'desc' : 'asc',
                                                ]));
                                            @endphp
                                            <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">
                                                <a href="{{ $sortUrl('mapel') }}"
                                                    class="inline-flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                                    Mapel
                                                    @if ($sortBy === 'mapel')
                                                        <i class="ti {{ $sortOrder === 'asc' ? 'ti-sort-ascending' : 'ti-sort-descending' }} text-sm"></i>
                                                    @else
                                                        <i class="ti ti-arrows-sort text-sm opacity-50"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">
                                                <a href="{{ $sortUrl('soal') }}"
                                                    class="inline-flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                                    Soal
                                                    @if ($sortBy === 'soal')
                                                        <i class="ti {{ $sortOrder === 'asc' ? 'ti-sort-ascending' : 'ti-sort-descending' }} text-sm"></i>
                                                    @else
                                                        <i class="ti ti-arrows-sort text-sm opacity-50"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">
                                                <a href="{{ $sortUrl('jenis') }}"
                                                    class="inline-flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                                    Jenis
                                                    @if ($sortBy === 'jenis')
                                                        <i class="ti {{ $sortOrder === 'asc' ? 'ti-sort-ascending' : 'ti-sort-descending' }} text-sm"></i>
                                                    @else
                                                        <i class="ti ti-arrows-sort text-sm opacity-50"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th
                                                class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">
                                                <a href="{{ $sortUrl('jawaban') }}"
                                                    class="inline-flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors justify-center w-full">
                                                    Jawaban
                                                    @if ($sortBy === 'jawaban')
                                                        <i class="ti {{ $sortOrder === 'asc' ? 'ti-sort-ascending' : 'ti-sort-descending' }} text-sm"></i>
                                                    @else
                                                        <i class="ti ti-arrows-sort text-sm opacity-50"></i>
                                                    @endif
                                                </a>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800" x-ref="questionTbody">
                                        @forelse ($questions as $q)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                                                <td class="py-3 px-6 align-top">
                                                    <input type="checkbox" name="question_ids[]"
                                                        value="{{ $q->id }}"
                                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                </td>
                                                <td class="py-3 px-6 align-top">
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-300">
                                                        {{ $q->subject->name ?? '-' }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-6 align-top">
                                                    <div class="max-w-md space-y-1">
                                                        <div
                                                            class="text-sm text-gray-900 dark:text-white font-medium line-clamp-2">
                                                            @if (($q->question_type ?? 'Text') === 'Text')
                                                                {!! \Illuminate\Support\Str::limit(strip_tags($q->question), 120) !!}
                                                            @else
                                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                                    Soal berupa gambar.
                                                                </span>
                                                            @endif
                                                        </div>
                                                        @if (($q->question_type ?? 'Text') === 'Image')
                                                            <a href="{{ asset('storage/' . $q->question) }}"
                                                                class="inline-flex items-center gap-1 text-[11px] text-indigo-600 dark:text-indigo-400"
                                                                target="_blank">
                                                                <i class="ti ti-photo"></i> Lihat Gambar
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="py-3 px-6 align-top">
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold 
                                                    {{ ($q->question_type ?? 'Text') === 'Image'
                                                        ? 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300'
                                                        : 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300' }}">
                                                        <i class="ti {{ ($q->question_type ?? 'Text') === 'Image' ? 'ti-photo' : 'ti-text-size' }} mr-1"></i>
                                                        {{ $q->question_type ?? 'Text' }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-6 text-center align-top">
                                                    <span
                                                        class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 font-bold text-xs">
                                                        {{ strtoupper($q->answer) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="py-10">
                                                    <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                                                        Tidak ada soal tersedia / semua sudah ter-mapping ke ujian ini.
                                                    </p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if ($questions instanceof \Illuminate\Pagination\LengthAwarePaginator && $questions->hasPages())
                                <div class="px-6 py-4 border-t border-gray-50 dark:border-gray-800">
                                    {{ $questions->links() }}
                                </div>
                            @endif

                            <div class="px-6 py-4 border-t border-gray-50 dark:border-gray-800 flex justify-end">
                                <x-button type="submit" variant="primary"
                                    class="rounded-xl shadow-lg shadow-indigo-500/20">
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
                                    <tr
                                        class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                                        <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">
                                            Mapel</th>
                                        <th class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">
                                            Soal</th>
                                        <th
                                            class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                    @forelse ($mapped as $map)
                                        @php($q = $map->questionBank)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                                            <td class="py-3 px-6 align-top">
                                                <div class="flex flex-col gap-1">
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-300">
                                                        {{ $q->subject->name ?? '-' }}
                                                    </span>
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold 
                                                    {{ ($q->question_type ?? 'Text') === 'Image'
                                                        ? 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300'
                                                        : 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300' }}">
                                                        <i
                                                            class="ti {{ ($q->question_type ?? 'Text') === 'Image' ? 'ti-photo' : 'ti-text-size' }} mr-1"></i>
                                                        {{ $q->question_type ?? 'Text' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="py-3 px-6 align-top">
                                                <div class="max-w-md space-y-1">
                                                    <div
                                                        class="text-sm text-gray-900 dark:text-white font-medium line-clamp-2">
                                                        @if (($q->question_type ?? 'Text') === 'Text')
                                                            {!! \Illuminate\Support\Str::limit(strip_tags($q->question), 120) !!}
                                                        @else
                                                            <div class="flex flex-col gap-2">
                                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                                    Soal berupa gambar.
                                                                </span>
                                                                <a href="{{ asset('storage/' . $q->question) }}"
                                                                    class="inline-flex items-center gap-1 text-[11px] text-indigo-600 dark:text-indigo-400"
                                                                    target="_blank">
                                                                    <i class="ti ti-photo"></i> Lihat Gambar
                                                                </a>
                                                            </div>
                                                        @endif

                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 px-6 align-top">
                                                <div class="flex items-center justify-end gap-2">
                                                    <x-button variant="info" size="sm"
                                                        href="{{ route('mapping-questions.show', [$selectedExam, $map]) }}"
                                                        class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                                        <i class="ti ti-eye text-base"></i>
                                                    </x-button>
                                                    <x-button variant="danger" size="sm" type="button"
                                                        @click="confirmDelete('{{ route('mapping-questions.destroy', [$selectedExam, $map]) }}', '{{ \Illuminate\Support\Str::limit(strip_tags($q->question), 50) }}')"
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

                        @if ($mapped instanceof \Illuminate\Pagination\LengthAwarePaginator && $mapped->hasPages())
                            <div class="px-6 py-4 border-t border-gray-50 dark:border-gray-800">
                                {{ $mapped->links() }}
                            </div>
                        @endif
                    </x-card>

                    {{-- Delete Confirmation Modal --}}
                    <div x-show="deleteModalOpen"
                        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
                        x-cloak x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

                        <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800"
                            @click.away="deleteModalOpen = false">
                            <div class="p-8 text-center">
                                <div
                                    class="w-20 h-20 bg-rose-50 dark:bg-rose-500/10 text-rose-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                    <i class="ti ti-trash-x text-4xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Hapus Soal dari Ujian?
                                </h3>
                                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                    Anda akan menghapus soal <span class="font-bold text-gray-900 dark:text-white"
                                        x-text="questionTitle"></span> dari ujian ini. Tindakan ini tidak dapat dibatalkan.
                                </p>
                            </div>

                            <div
                                class="flex gap-3 p-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800">
                                <x-button variant="secondary" class="flex-1 rounded-xl" @click="deleteModalOpen = false">
                                    Batal
                                </x-button>
                                <form :action="deleteUrl" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <x-button variant="danger" type="submit"
                                        class="w-full rounded-xl shadow-lg shadow-rose-500/20">
                                        Ya, Hapus
                                    </x-button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
