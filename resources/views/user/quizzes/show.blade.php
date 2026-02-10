@extends('layouts.exam')

@section('title', $quiz->title)

@section('content')
<div class="space-y-8 pb-20 p-4 md:p-8 max-w-screen-2xl mx-auto" x-data="quizData()" x-init="init()">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('user.my-packages.index') }}" class="hover:text-emerald-600 transition-colors">Package Saya</a>
        <i class="ti ti-chevron-right text-xs"></i>
        <a href="{{ route('user.my-packages.show', $package) }}" class="hover:text-emerald-600 transition-colors">{{ $package->title }}</a>
        <i class="ti ti-chevron-right text-xs"></i>
        <span class="text-gray-900 dark:text-white font-semibold">{{ $quiz->title }}</span>
    </nav>

    {{-- Quiz Header --}}
    <x-card>
        <div class="space-y-4">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div class="flex-1">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-300 dark:bg-emerald-500/15">
                            <i class="ti ti-puzzle text-lg"></i>
                        </span>
                        <span>{{ $quiz->title }}</span>
                    </h1>
                    @if($quiz->description)
                        <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                            {!! $quiz->description !!}
                        </div>
                    @endif
                </div>
                <div class="flex flex-col items-end gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <span class="uppercase tracking-[0.18em] text-[10px] font-semibold">Package</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white line-clamp-1">
                        {{ $package->title }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                <div class="text-center">
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $totalQuestions }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Soal</p>
                </div>
                @if($quiz->duration)
                    <div class="text-center">
                        <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400" x-text="formatTime(timeRemaining)"></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Sisa Waktu</p>
                    </div>
                @endif
                @if($quiz->passing_grade)
                    <div class="text-center">
                        <p class="text-2xl font-bold text-violet-600 dark:text-violet-400">{{ $quiz->passing_grade }}%</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Passing Grade</p>
                    </div>
                @endif
                <div class="text-center">
                    <p class="text-2xl font-bold text-rose-600 dark:text-rose-400" x-text="currentPage"></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Soal Saat Ini</p>
                </div>
            </div>
        </div>
    </x-card>

    {{-- Question Card --}}
    <x-card>
        <div x-show="loading" class="flex items-center justify-center py-12">
            <div class="text-center">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-600 mb-4"></div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Memuat soal...</p>
            </div>
        </div>

        <div x-show="!loading && currentQuestion" x-cloak>
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                            Soal <span x-text="currentPage"></span> / <span x-text="totalQuestions"></span>
                        </span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="p-5 md:p-6 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800">
                        <h3 class="text-xs font-semibold tracking-[0.16em] uppercase text-gray-400 dark:text-gray-500 mb-3">Pertanyaan</h3>
                        <div x-show="currentQuestion.question_type === 'Text'" class="prose prose-sm dark:prose-invert max-w-none">
                            <p class="text-base font-medium text-gray-900 dark:text-white" x-html="currentQuestion.question"></p>
                        </div>
                        <div x-show="currentQuestion.question_type === 'Image'" class="space-y-3">
                            <img :src="currentQuestion.question_image_url"
                                 alt="Soal"
                                 class="max-w-full h-auto rounded-2xl border border-gray-200 dark:border-gray-700 mx-auto bg-white dark:bg-gray-900"
                                 style="max-height: 420px; object-fit: contain; display: block;"
                                 loading="lazy">
                        </div>
                    </div>

                    <div class="space-y-3">
                        <h4 class="text-xs font-semibold tracking-[0.16em] uppercase text-gray-400 dark:text-gray-500 mb-2">Pilih Jawaban</h4>
                        <template x-for="(option, index) in getAvailableOptions()" :key="index">
                            <label
                                @click="selectedAnswer = option; saveAnswer()"
                                class="flex items-center gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all bg-white dark:bg-gray-900"
                                :class="selectedAnswer === option
                                    ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-500/10 shadow-[0_0_0_1px_rgba(16,185,129,0.4)]'
                                    : 'border-gray-200 dark:border-gray-700 hover:border-emerald-400 hover:bg-emerald-50/40 dark:hover:bg-gray-800'">
                                <div
                                    class="w-9 h-9 rounded-full border flex items-center justify-center text-xs font-bold"
                                    :class="selectedAnswer === option ? 'bg-emerald-500 text-white border-emerald-500' : 'bg-gray-50 text-gray-700 dark:bg-gray-800 dark:text-gray-200 border-gray-300 dark:border-gray-600'">
                                    <span x-text="option"></span>
                                </div>
                                <div class="flex-1">
                                    <span class="text-sm text-gray-900 dark:text-gray-100"
                                          x-text="currentQuestion['option_' + option.toLowerCase()] || '-'"></span>
                                </div>
                            </label>
                        </template>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row md:items-center gap-3 justify-between pt-6 border-t border-gray-100 dark:border-gray-800">
                    <x-button variant="secondary" @click="previousQuestion()"
                        x-bind:disabled="currentPage === 1"
                        x-bind:class="currentPage === 1 ? 'opacity-40 cursor-not-allowed' : ''"
                        class="rounded-xl">
                        <i class="ti ti-arrow-left mr-2"></i> Sebelumnya
                    </x-button>
                    <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <span>Soal</span>
                        <span class="px-2 py-1 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100 font-semibold">
                            <span x-text="currentPage"></span> / {{ $totalQuestions }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-button variant="secondary" @click="nextQuestion()"
                            x-bind:disabled="currentPage >= totalQuestions"
                            x-bind:class="currentPage >= totalQuestions ? 'opacity-40 cursor-not-allowed' : ''"
                            class="rounded-xl">
                            Selanjutnya <i class="ti ti-arrow-right ml-2"></i>
                        </x-button>
                        <x-button variant="primary" color="emerald" @click="submitQuiz(false)"
                            x-show="currentPage === totalQuestions"
                            class="rounded-xl">
                            Selesai Kuis
                        </x-button>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="!loading && !currentQuestion && !error" x-cloak class="flex flex-col items-center justify-center py-12">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                <i class="ti ti-help-off text-2xl text-gray-400"></i>
            </div>
            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Belum ada soal</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kuis ini belum memiliki soal.</p>
        </div>

        <div x-show="error" x-cloak class="flex flex-col items-center justify-center py-12">
            <div class="w-16 h-16 bg-rose-100 dark:bg-rose-500/10 rounded-full flex items-center justify-center mb-4">
                <i class="ti ti-alert-circle text-2xl text-rose-600"></i>
            </div>
            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Terjadi Kesalahan</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400" x-text="error"></p>
            <x-button variant="secondary" @click="loadQuestion(1)" class="mt-4 rounded-xl">Coba Lagi</x-button>
        </div>
    </x-card>

    <div x-show="submitting" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/70 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl px-8 py-6 shadow-2xl flex flex-col items-center gap-3">
            <div class="w-12 h-12 rounded-full border-4 border-emerald-500 border-t-transparent animate-spin"></div>
            <p class="text-sm text-gray-800 dark:text-gray-100 font-medium">Mengirim dan menghitung hasil kuis...</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Mohon tunggu, jangan menutup halaman ini.</p>
        </div>
    </div>
</div>

<script>
function quizData() {
    return {
        loading: true,
        currentQuestion: null,
        currentPage: 1,
        totalQuestions: {{ $totalQuestions }},
        selectedAnswer: null,
        error: null,
        answers: {},
        storageKey: 'quiz_{{ $quiz->id }}',
        timeRemaining: {{ $quiz->duration ? ($quiz->duration * 60) : 0 }},
        timer: null,
        submitting: false,

        init() {
            const submittedFlag = localStorage.getItem(this.storageKey + '_submitted');
            if (submittedFlag === 'true') {
                localStorage.removeItem(this.storageKey);
                localStorage.removeItem(this.storageKey + '_submitted');
                this.answers = {};
                this.currentPage = 1;
                this.timeRemaining = {{ $quiz->duration ? ($quiz->duration * 60) : 0 }};
            } else {
                const savedRaw = localStorage.getItem(this.storageKey);
                if (savedRaw) {
                    try {
                        const saved = JSON.parse(savedRaw);
                        if (saved.answers && typeof saved.answers === 'object') {
                            this.answers = {};
                            for (const [key, value] of Object.entries(saved.answers)) {
                                const numKey = parseInt(key, 10);
                                if (!isNaN(numKey) && value) this.answers[numKey] = value;
                            }
                        }
                        if (saved.currentPage && Number.isInteger(saved.currentPage)) this.currentPage = saved.currentPage;
                        if (typeof saved.timeRemaining === 'number' && saved.timeRemaining > 0) this.timeRemaining = saved.timeRemaining;
                    } catch (e) {
                        this.answers = {};
                        this.currentPage = 1;
                        this.timeRemaining = {{ $quiz->duration ? ($quiz->duration * 60) : 0 }};
                    }
                } else {
                    this.answers = {};
                    this.currentPage = 1;
                    this.timeRemaining = {{ $quiz->duration ? ($quiz->duration * 60) : 0 }};
                }
            }
            this.loadQuestion(this.currentPage);

            document.addEventListener('visibilitychange', () => {
                if (document.visibilityState === 'visible' && this.timeRemaining > 0 && !this.loading) {
                    this.loadQuestion(this.currentPage);
                }
            });
        },

        async loadQuestion(page) {
            this.loading = true;
            this.error = null;
            this.currentPage = page;
            this.selectedAnswer = this.answers[page] || null;
            try {
                const response = await fetch(`{{ route('user.quizzes.questions', ['package' => $package->id, 'quiz' => $quiz->id]) }}?page=${page}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                if (!response.ok) throw new Error('Gagal memuat soal');
                const data = await response.json();

                if (data.timer) {
                    this.timeRemaining = Math.max(0, data.timer.remaining_seconds);
                    if (this.timeRemaining <= 0) {
                        this.submitQuiz(true);
                        return;
                    }
                    if (this.timer) clearInterval(this.timer);
                    this.startTimer();
                }

                if (data.questions && data.questions.length > 0) {
                    this.currentQuestion = data.questions[0];
                    this.totalQuestions = data.total;
                } else {
                    this.currentQuestion = null;
                }
            } catch (err) {
                this.error = err.message || 'Terjadi kesalahan saat memuat soal';
                this.currentQuestion = null;
            } finally {
                this.loading = false;
                this.saveState();
            }
        },

        previousQuestion() {
            if (this.currentPage > 1) { this.saveAnswer(); this.loadQuestion(this.currentPage - 1); }
        },
        nextQuestion() {
            if (this.currentPage < this.totalQuestions) { this.saveAnswer(); this.loadQuestion(this.currentPage + 1); }
        },

        saveAnswer() {
            if (this.currentQuestion) {
                if (this.selectedAnswer) this.answers[this.currentPage] = this.selectedAnswer;
                else delete this.answers[this.currentPage];
                this.saveState();
            }
        },

        startTimer() {
            if (this.timer) clearInterval(this.timer);
            this.timer = setInterval(() => {
                if (this.timeRemaining > 0) { this.timeRemaining--; this.saveState(); }
                else { clearInterval(this.timer); this.submitQuiz(true); }
            }, 1000);
        },

        formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        },

        saveState() {
            localStorage.setItem(this.storageKey, JSON.stringify({
                currentPage: this.currentPage,
                answers: this.answers,
                timeRemaining: this.timeRemaining,
            }));
        },

        // Opsi pilihan: minimal sampai D, maksimal sampai E (hanya tampil yang ada isinya)
        getAvailableOptions() {
            if (!this.currentQuestion) return [];
            const options = ['A', 'B', 'C', 'D', 'E'];
            let lastIndex = -1;
            for (let i = 0; i < options.length; i++) {
                const key = 'option_' + options[i].toLowerCase();
                const val = this.currentQuestion[key];
                if (val != null && String(val).trim() !== '') lastIndex = i;
            }
            if (lastIndex < 0) return ['A', 'B', 'C', 'D'];
            return options.slice(0, lastIndex + 1);
        },

        async submitQuiz(auto = false) {
            if (this.submitting) return;
            this.submitting = true;
            this.saveAnswer();
            const answersToSend = {};
            for (const [key, value] of Object.entries(this.answers)) {
                const numKey = parseInt(key, 10);
                if (!isNaN(numKey) && value) answersToSend[numKey] = value;
            }
            try {
                const response = await fetch(`{{ route('user.quizzes.submit', ['package' => $package->id, 'quiz' => $quiz->id]) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ answers: answersToSend, auto: auto }),
                });
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.error || 'Gagal mengirim hasil kuis');
                }
                const data = await response.json();
                localStorage.setItem(this.storageKey + '_submitted', 'true');
                localStorage.removeItem(this.storageKey);
                if (data.redirect) window.location.href = data.redirect;
                else if (data.message) alert(data.message);
            } catch (e) {
                this.error = e.message || 'Gagal mengirim hasil kuis';
                this.submitting = false;
            }
        }
    };
}
</script>
@endsection
