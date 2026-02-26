@extends('layouts.app')

@section('title', $package->title)

@section('content')
    <div class="space-y-8 pb-20" x-data="{
        modalMarkRead: false,
        modelUrl: '',
        materi: '',
        confirmMarkRead(url, materi) {
            this.modelUrl = url;
            this.materi = materi;
            this.modalMarkRead = true;
        }
    }">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('user.my-packages.index') }}" class="hover:text-indigo-600 transition-colors">Package Saya</a>
            <i class="ti ti-chevron-right text-xs"></i>
            <span class="text-gray-900 dark:text-white">{{ $package->title }}</span>
        </nav>

        {{-- Package Header --}}
        <x-card>
            <div class="space-y-6">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $package->title }}</h1>
                            @if ($package->masterType)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                    {{ $package->masterType->name_type }}
                                </span>
                            @endif
                        </div>
                        @if ($package->description)
                            <div class="prose prose-sm dark:prose-invert max-w-none">
                                {!! $package->description !!}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Package Stats --}}
                @php
                    $totalMateri = $subjects->sum(fn($s) => $s->materials->count());
                    $totalDibaca = collect($subjectProgress)->sum('read');
                @endphp
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-indigo-600">{{ $totalMateri }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total Materi</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-emerald-600">{{ $totalDibaca }}/{{ $totalMateri }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Materi Dibaca</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-violet-600">{{ $package->userJoins->count() }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total Peserta</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-slate-600 dark:text-slate-300">
                            {{ $package->created_at->format('M Y') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Dibuat</p>
                    </div>
                </div>
            </div>
        </x-card>

        {{-- Pretest (ujian tipe pretest tampil di atas materi) --}}
        @php
            $exams = \App\Models\Exam::with('subjects')->where('package_id', $package->id)->get();
            $pretestExams = $exams->filter(fn($e) => $e->type === 'pretest');
            $questionCountsByExam = \App\Models\MappingQuestion::whereIn('id_exam', $exams->pluck('id'))->selectRaw('id_exam, count(*) as cnt')->groupBy('id_exam')->pluck('cnt', 'id_exam');
        @endphp

        @if ($pretestExams->count() > 0)
            <x-card title="Pretest">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Kerjakan pretest untuk mengukur kemampuan awal sebelum mempelajari materi.</p>
                <div class="space-y-4">
                    @foreach ($pretestExams as $examItem)
                        @php
                            $totalQuestions = $questionCountsByExam->get($examItem->id) ?? $examItem->planned_questions_count ?? 0;
                            $attempts = $examAttemptsByExam->get($examItem->id, collect());
                        @endphp
                        <div
                            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-500/10 dark:to-indigo-500/10 rounded-xl border border-blue-100 dark:border-blue-800">
                            <div class="flex items-center gap-4 flex-1 min-w-0">
                                <div
                                    class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center text-white shrink-0">
                                    <i class="ti ti-clipboard-check text-xl"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex flex-wrap gap-2 items-center mb-2">
                                        <h4 class="font-bold text-gray-900 dark:text-white">{{ $examItem->title }}</h4>
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300 text-xs font-semibold rounded-full">
                                            <i class="ti ti-copyleft"></i> Pretest
                                        </span>
                                        @if ($examItem->subjects->isNotEmpty())
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300 text-xs font-semibold rounded-full">
                                                <i class="ti ti-school"></i> {{ $examItem->subjects->pluck('name')->join(', ') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex flex-wrap items-center gap-4 text-xs text-gray-600 dark:text-gray-400">
                                        <span class="inline-flex items-center gap-1">
                                            <i class="ti ti-help-circle"></i>
                                            {{ $totalQuestions }} Soal
                                        </span>
                                        @if ($examItem->duration)
                                            <span class="inline-flex items-center gap-1">
                                                <i class="ti ti-clock"></i>
                                                {{ $examItem->duration }} Menit
                                            </span>
                                        @endif
                                        @if ($examItem->passing_grade)
                                            <span class="inline-flex items-center gap-1">
                                                <i class="ti ti-target"></i>
                                                Passing Grade: {{ $examItem->passing_grade }}%
                                            </span>
                                        @endif
                                    </div>
                                    @if ($examItem->description)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ Str::limit(strip_tags($examItem->description), 80) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                @if ($attempts->isNotEmpty())
                                    <x-button variant="secondary"
                                        href="{{ route('user.exams.attempts', ['package' => $package->id, 'exam' => $examItem->id]) }}"
                                        class="rounded-xl">
                                        <i class="ti ti-chart-bar mr-2"></i> Lihat Hasil ({{ $attempts->count() }})
                                    </x-button>
                                @endif
                                <x-button variant="primary"
                                    href="{{ route('user.exams.show', ['package' => $package->id, 'exam' => $examItem->id]) }}"
                                    class="rounded-xl shadow-lg shadow-indigo-500/20">
                                    <i class="ti ti-arrow-right mr-2"></i> {{ $attempts->isNotEmpty() ? 'Kerjakan Lagi' : 'Mulai Pretest' }}
                                </x-button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-card>
        @endif

        {{-- Materials List (dikelompokkan per Mata Pelajaran) --}}
        <x-card title="Materi Pembelajaran">
            @if ($subjects->sum(fn($s) => $s->materials->count()) > 0)
                <div class="space-y-8">
                    @foreach ($subjects as $subject)
                        @php
                            $progress = $subjectProgress[$subject->id] ?? [
                                'total' => 0,
                                'read' => 0,
                                'can_do_quiz' => false,
                            ];
                            $pct = $progress['total'] > 0 ? round(($progress['read'] / $progress['total']) * 100) : 0;
                        @endphp
                        {{-- Header Mata Pelajaran + Progress --}}
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    <span
                                        class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400">
                                        <i class="ti ti-book text-sm"></i>
                                    </span>
                                    {{ $subject->name }}
                                    @if ($subject->code)
                                        <span
                                            class="text-xs font-medium text-gray-500 dark:text-gray-400">({{ $subject->code }})</span>
                                    @endif
                                </h3>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                        {{ $progress['read'] }}/{{ $progress['total'] }} materi dibaca
                                    </span>
                                    <div class="w-24 h-2 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden"
                                        title="{{ $pct }}%">
                                        <div class="h-full rounded-full bg-indigo-600 transition-all duration-300"
                                            style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 ml-10">
                                {{ $subject->materials->count() }} materi</p>
                        </div>
                        {{-- Daftar Materi di bawah mata pelajaran --}}
                        <div
                            class="space-y-3 ml-0 md:ml-4 pl-0 md:pl-4 border-l-0 md:border-l-2 border-indigo-100 dark:border-indigo-900/50">
                            @forelse ($subject->materials as $material)
                                @php
                                    $statusMateri = \App\Models\StatusMateri::where('id_user', Auth::id())
                                        ->where('id_material', $material->id)
                                        ->first();
                                @endphp
                                <div
                                    class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-800 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors {{ $statusMateri && $statusMateri->status == 'completed' ? 'opacity-80' : '' }}">
                                    <div
                                        class="w-12 h-12 bg-indigo-50 dark:bg-indigo-500/10 rounded-lg flex items-center justify-center shrink-0">
                                        @if ($material->materi_type == 'File')
                                            <i
                                                class="{{ $material->file_icon }} text-xl {{ $material->file_type === 'pdf' ? 'text-rose-600' : 'text-blue-600' }}"></i>
                                        @else
                                            <i class="ti ti-video text-xl text-indigo-600"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">
                                            {{ $material->title }}</h4>
                                        <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                            @if ($material->topic)
                                                <span class="inline-flex items-center gap-1">
                                                    <i class="ti ti-tag"></i>
                                                    {{ $material->topic }}
                                                </span>
                                            @endif
                                            @if ($material->value)
                                                <span>{{ $material->file_size_formatted }}</span>
                                            @endif
                                        </div>
                                        @if ($material->description)
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-2 line-clamp-2">
                                                {{ Str::limit(strip_tags($material->description), 100) }}
                                            </p>
                                        @endif
                                    </div>

                                    @if ($material->materi_type == 'File')
                                        <div class="flex items-center gap-2 shrink-0">
                                            @if ($material->value)
                                                @if (!$statusMateri || $statusMateri->status != 'completed')
                                                    <x-button
                                                        class="inline-flex items-center gap-1 px-3 py-2 bg-green-50 hover:bg-green-100 dark:bg-green-500/10 dark:hover:bg-green-500/20 text-green-600 dark:text-green-400 text-xs font-semibold rounded-lg transition-colors"
                                                        @click="confirmMarkRead('{{ route('user.mark-as-read', $material) }}', {{ \Illuminate\Support\Js::from($material->title) }})">
                                                        <i class="ti ti-square-check"></i>
                                                        Tandai Telah Dibaca
                                                    </x-button>
                                                @else
                                                    <span
                                                        class="inline-flex items-center gap-1 px-3 py-2 bg-green-100 text-green-700 text-xs font-semibold rounded-lg">
                                                        <i class="ti ti-check"></i>
                                                        Materi Telah Dibaca
                                                    </span>
                                                @endif
                                                <a href="{{ route('master-materials.preview', $material->id) }}?v={{ $material->updated_at->timestamp }}"
                                                    target="_blank"
                                                    class="inline-flex items-center gap-1 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:hover:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 text-xs font-semibold rounded-lg transition-colors">
                                                    <i class="ti ti-eye"></i>
                                                    Baca Materi
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-400">Tidak ada file</span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2 shrink-0">
                                            @if (!$statusMateri || $statusMateri->status != 'completed')
                                                <x-button
                                                    class="inline-flex items-center gap-1 px-3 py-2 bg-green-50 hover:bg-green-100 dark:bg-green-500/10 dark:hover:bg-green-500/20 text-green-600 dark:text-green-400 text-xs font-semibold rounded-lg transition-colors"
                                                    @click="confirmMarkRead('{{ route('user.mark-as-read', $material) }}', {{ \Illuminate\Support\Js::from($material->title) }})">
                                                    <i class="ti ti-square-check"></i>
                                                    Tandai Telah DiPelajari
                                                </x-button>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1 px-3 py-2 bg-green-100 text-green-700 text-xs font-semibold rounded-lg">
                                                    <i class="ti ti-check"></i>
                                                    Materi Telah Dibaca
                                                </span>
                                            @endif
                                        </div>
                                        @if ($material->value)
                                            @php
                                                $url = $material->value;
                                                if (str_contains($url, 'youtu.be')) {
                                                    $url =
                                                        'https://www.youtube.com/embed/' .
                                                        explode('?', last(explode('/', $url)))[0];
                                                }
                                                if (str_contains($url, 'youtube.com/watch')) {
                                                    parse_str(parse_url($url, PHP_URL_QUERY), $q);
                                                    $url = 'https://www.youtube.com/embed/' . ($q['v'] ?? '');
                                                }
                                            @endphp
                                            <iframe id="frame-video" src="{{ $url }}"
                                                class="w-full aspect-video rounded-xl max-w-md" frameborder="0"
                                                allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe>
                                        @endif
                                    @endif
                                </div>
                            @empty
                                <div class="p-4 text-sm text-gray-500 dark:text-gray-400 italic">
                                    Belum ada materi untuk mata pelajaran ini.
                                </div>
                            @endforelse
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                        <i class="ti ti-file-off text-2xl text-gray-400"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                        Belum ada materi
                    </h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Package ini belum memiliki materi pembelajaran.
                    </p>
                </div>
            @endif
        </x-card>

        {{-- Exams List --}}
        @php
            $quizez = $package->quizzes;
            $subjectIds = $subjects->pluck('id');
            $lastResultPackage = \App\Models\TransQuiz::where('user_id', auth()->id())
                ->whereHas('quiz', function ($q) use ($subjectIds) {
                    $q->whereIn('subject_id', $subjectIds);
                })
                ->latest('updated_at')
                ->first();
            $quizScores = \App\Models\TransQuiz::where('user_id', auth()->id())
                ->whereIn('quiz_id', $quizez->pluck('id'))
                ->get()
                ->keyBy('quiz_id');
        @endphp

        @if ($quizez->count() > 0)
            <x-card title="Kuis / Latihan">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Kerjakan latihan per mata pelajaran untuk mengasah
                    pemahaman.</p>
                <div class="space-y-3">
                    @foreach ($quizez as $quizItem)
                        @php
                            $subjectId = $quizItem->subject->id ?? null;
                            $progress = $subjectId
                                ? $subjectProgress[$subjectId] ?? ['total' => 0, 'read' => 0, 'can_do_quiz' => false]
                                : ['total' => 0, 'read' => 0, 'can_do_quiz' => true];
                            $canDoQuiz = $progress['can_do_quiz'];
                        @endphp
                        <div
                            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-xl border {{ !$canDoQuiz ? 'border-amber-200 dark:border-amber-800/50 bg-amber-50/50 dark:bg-amber-500/5' : 'border-emerald-100 dark:border-emerald-800/50 bg-emerald-50/30 dark:bg-emerald-500/5 hover:bg-emerald-50/50 dark:hover:bg-emerald-500/10 transition-colors' }}">
                            <div class="flex gap-4 min-w-0 flex-1">
                                <div
                                    class="w-11 h-11 rounded-xl {{ $canDoQuiz ? 'bg-emerald-600' : 'bg-amber-500' }} flex items-center justify-center text-white shrink-0">
                                    <i class="ti ti-puzzle text-lg"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-semibold {{ $canDoQuiz ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-300' : 'bg-amber-500/10 text-amber-700 dark:text-amber-300' }}">
                                            {{ $quizItem->subject->name ?? 'Kuis' }}
                                        </span>
                                    </div>
                                    <h4 class="font-bold text-gray-900 dark:text-white mb-1 truncate">
                                        {{ $quizItem->title }}</h4>
                                    <div
                                        class="flex flex-wrap items-center gap-x-4 gap-y-0.5 text-xs text-gray-500 dark:text-gray-400">
                                        <span><i class="ti ti-help-circle mr-0.5"></i> {{ $quizItem->total_questions }}
                                            Soal</span>
                                        @if ($quizItem->duration)
                                            <span><i class="ti ti-clock mr-0.5"></i> {{ $quizItem->duration }} Menit</span>
                                        @endif
                                        @if ($quizItem->passing_grade)
                                            <span><i class="ti ti-target mr-0.5"></i> KKM
                                                {{ $quizItem->passing_grade }}%</span>
                                        @endif
                                    </div>
                                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                                        Nilai terakhir:
                                        <strong>{{ $quizScores->get($quizItem->id) ? number_format($quizScores->get($quizItem->id)->total_score, 1) : '-' }}</strong>
                                    </p>
                                    @if (!$canDoQuiz && $progress['total'] > 0)
                                        <p class="text-xs text-amber-700 dark:text-amber-400 mt-2 flex items-center gap-1">
                                            <i class="ti ti-info-circle"></i>
                                            Progress materi: {{ $progress['read'] }}/{{ $progress['total'] }} — selesaikan
                                            semua materi mapel ini untuk membuka kuis.
                                        </p>
                                    @endif
                                </div>
                            </div>
                            @if (!$canDoQuiz)
                                <span
                                    class="inline-flex items-center gap-1 px-3 py-2 bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300 text-xs font-semibold rounded-xl shrink-0">
                                    <i class="ti ti-lock"></i>
                                    Selesaikan semua materi mapel ini
                                </span>
                            @else
                                <div class="flex items-center gap-2 shrink-0">
                                    @if ($quizScores->get($quizItem->id))
                                        <x-button variant="secondary"
                                            href="{{ route('user.quizzes.review', ['package' => $package->id, 'quiz' => $quizItem->id, 'transQuiz' => $quizScores->get($quizItem->id)->id]) }}"
                                            class="rounded-xl">
                                            <i class="ti ti-chart-bar mr-2"></i> Lihat Hasil
                                        </x-button>
                                    @endif
                                    <x-button variant="success"
                                        href="{{ route('user.quizzes.show', ['package' => $package->id, 'quiz' => $quizItem->id, 'subject' => $quizItem->subject->id]) }}"
                                        class="rounded-xl shadow-lg shadow-emerald-500/20">
                                        <i class="ti ti-arrow-right mr-2"></i> Mulai Kuis
                                    </x-button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </x-card>
        @endif

        @php
            $posttestExams = $exams->filter(fn($e) => $e->type !== 'pretest');
        @endphp

        @if ($posttestExams->count() > 0)
            <x-card title="Try Out / Ujian">
                <div class="space-y-4">
                    @foreach ($posttestExams as $examItem)
                        @php
                            $totalQuestions = $questionCountsByExam->get($examItem->id) ?? $examItem->planned_questions_count ?? 0;
                            $attempts = $examAttemptsByExam->get($examItem->id, collect());
                        @endphp
                        <div
                            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-gradient-to-r from-indigo-50 to-violet-50 dark:from-indigo-500/10 dark:to-violet-500/10 rounded-xl border border-indigo-100 dark:border-indigo-800">
                            <div class="flex items-center gap-4 flex-1 min-w-0">
                                <div
                                    class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center text-white shrink-0">
                                    <i class="ti ti-clipboard-check text-xl"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex flex-wrap gap-2 items-center mb-2">
                                        <h4 class="font-bold text-gray-900 dark:text-white">{{ $examItem->title }}</h4>
                                        @if ($examItem->type === 'pretest')
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300 text-xs font-semibold rounded-full">
                                                <i class="ti ti-copyleft"></i> Pretest
                                            </span>
                                            @if ($examItem->subjects->isNotEmpty())
                                                <span
                                                    class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300 text-xs font-semibold rounded-full">
                                                    <i class="ti ti-school"></i> {{ $examItem->subjects->pluck('name')->join(', ') }}
                                                </span>
                                            @endif
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-300 text-xs font-semibold rounded-full">
                                                <i class="ti ti-devices-question"></i> Posttest
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex flex-wrap items-center gap-4 text-xs text-gray-600 dark:text-gray-400">
                                        <span class="inline-flex items-center gap-1">
                                            <i class="ti ti-help-circle"></i>
                                            {{ $totalQuestions }} Soal
                                        </span>
                                        @if ($examItem->duration)
                                            <span class="inline-flex items-center gap-1">
                                                <i class="ti ti-clock"></i>
                                                {{ $examItem->duration }} Menit
                                            </span>
                                        @endif
                                        @if ($examItem->passing_grade)
                                            <span class="inline-flex items-center gap-1">
                                                <i class="ti ti-target"></i>
                                                Passing Grade: {{ $examItem->passing_grade }}%
                                            </span>
                                        @endif
                                    </div>
                                    @if ($examItem->description)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ Str::limit(strip_tags($examItem->description), 80) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                @if ($attempts->isNotEmpty())
                                    <x-button variant="secondary"
                                        href="{{ route('user.exams.attempts', ['package' => $package->id, 'exam' => $examItem->id]) }}"
                                        class="rounded-xl">
                                        <i class="ti ti-chart-bar mr-2"></i> Lihat Hasil ({{ $attempts->count() }})
                                    </x-button>
                                @endif
                                <x-button variant="primary"
                                    href="{{ route('user.exams.show', ['package' => $package->id, 'exam' => $examItem->id]) }}"
                                    class="rounded-xl shadow-lg shadow-indigo-500/20">
                                    <i class="ti ti-arrow-right mr-2"></i> {{ $attempts->isNotEmpty() ? 'Kerjakan Lagi' : 'Mulai Ujian' }}
                                </x-button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-card>
        @endif

        {{-- Approve Confirmation Modal --}}
        <div x-show="modalMarkRead"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" x-cloak
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

            <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800"
                @click.away="modalMarkRead = false">
                <div class="p-8 text-center">
                    <div
                        class="w-20 h-20 bg-green-50 dark:bg-green-500/10 text-green-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="ti ti-circle-dashed-check text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Tandai Telah Dibaca</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                        Anda akan menandai materi <strong x-text="materi"></strong> sebagai telah dibaca. Tindakan ini
                        tidak dapat dibatalkan.
                    </p>
                </div>

                <div class="flex gap-3 p-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800">
                    <x-button variant="secondary" class="flex-1 rounded-xl" @click="modalMarkRead = false">
                        Batal
                    </x-button>
                    <form :action="modelUrl" method="POST" class="flex-1">
                        @csrf
                        @method('POST')
                        <x-button variant="success" type="submit"
                            class="w-full rounded-xl shadow-lg shadow-emerald-500/20">
                            Ya, Tandai Telah Dibaca
                        </x-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
