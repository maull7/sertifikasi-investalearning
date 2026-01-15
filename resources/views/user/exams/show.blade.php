@extends('layouts.exam')

@section('title', $exam->title)

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-950" x-data="examData()" x-init="init()">
    {{-- Fixed Header dengan Timer --}}
    <div class="sticky top-0 z-50 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $exam->title }}</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                        Soal <span x-text="currentPage"></span> / <span x-text="totalQuestions"></span>
                    </span>
                </div>
                
                {{-- Timer Countdown --}}
                <div class="flex items-center gap-3" x-show="examDuration > 0">
                    <div class="flex items-center gap-2 px-4 py-2 rounded-xl" 
                         :class="timeRemaining <= 300 ? 'bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400' : 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'">
                        <i class="ti ti-clock text-lg"></i>
                        <span class="text-lg font-bold" x-text="formatTime(timeRemaining)"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Question Card --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-800 p-6 md:p-8">
        <div x-show="loading" class="flex items-center justify-center py-12">
            <div class="text-center">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mb-4"></div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Memuat soal...</p>
            </div>
        </div>

        <div x-show="!loading && currentQuestion" x-cloak>
            <div class="space-y-6">
                {{-- Question Number & Type --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                            Soal  <span x-text="currentPage"></span> dari  <span x-text="totalQuestions"></span>
                        </span>
                        <span x-show="currentQuestion.type" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-700 dark:bg-gray-800 dark:text-gray-300" x-text="currentQuestion.type"></span>
                    </div>
                </div>

                {{-- Question Content --}}
                <div class="space-y-4">
                    <div class="p-6 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-800">
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-3">Pertanyaan</h3>
                        
                        {{-- Text Question --}}
                        <div x-show="currentQuestion.question_type === 'Text'" class="prose prose-sm dark:prose-invert max-w-none">
                            <p class="text-base font-medium text-gray-900 dark:text-white" x-html="currentQuestion.question"></p>
                        </div>

                        {{-- Image Question --}}
                        <div x-show="currentQuestion.question_type === 'Image'" class="space-y-3">
                            <img :src="currentQuestion.question_image_url" 
                                 alt="Soal" 
                                 class="max-w-full h-auto rounded-lg border border-gray-200 dark:border-gray-700 mx-auto"
                                 onerror="this.onerror=null;this.src='{{ asset('images/placeholder.png') }}';"
                                 style="max-height: 500px; object-fit: contain; display: block;"
                                 loading="lazy">
                        </div>
                    </div>

                    {{-- Options --}}
                    <div class="space-y-3">
                        <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-3">Pilih Jawaban</h4>
                        <template x-for="(option, index) in ['A', 'B', 'C', 'D']" :key="index">
                            <label class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border-2 cursor-pointer transition-all hover:border-indigo-300 dark:hover:border-indigo-600"
                                   :class="selectedAnswer === option ? 'border-indigo-600 bg-indigo-50 dark:bg-indigo-500/10' : 'border-gray-200 dark:border-gray-700'">
                                <input type="radio" 
                                       :name="'question_' + currentQuestion.id" 
                                       :value="option"
                                       x-model="selectedAnswer"
                                       @change="saveAnswer()"
                                       class="w-5 h-5 text-indigo-600 focus:ring-indigo-500">
                                <div class="flex-1">
                                    <span class="font-bold text-gray-900 dark:text-white mr-2" x-text="option + '.'"></span>
                                    <span class="text-gray-700 dark:text-gray-300" x-text="currentQuestion['option_' + option.toLowerCase()]"></span>
                                </div>
                            </label>
                        </template>
                    </div>
                </div>

                {{-- Navigation Buttons --}}
                <div class="flex items-center justify-between pt-6 border-t border-gray-100 dark:border-gray-800">
                    <button 
                        type="button"
                        @click="previousQuestion()"
                        :disabled="currentPage === 1"
                        :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100 dark:hover:bg-gray-800'"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl font-semibold transition-colors">
                        <i class="ti ti-arrow-left"></i> Sebelumnya
                    </button>

                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400" x-text="currentPage + ' / ' + totalQuestions"></span>
                        <button 
                            type="button"
                            @click="showNavigator = !showNavigator"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                            <i class="ti" :class="showNavigator ? 'ti-layout-navbar' : 'ti-layout-grid'"></i>
                            <span x-text="showNavigator ? 'Sembunyikan Nomor' : 'Lihat Nomor Soal'"></span>
                        </button>
                    </div>

                    <button 
                        type="button"
                        @click="nextQuestion()"
                        :disabled="currentPage >= totalQuestions"
                        :class="currentPage >= totalQuestions ? 'opacity-50 cursor-not-allowed' : 'hover:bg-indigo-700'"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-xl font-semibold transition-colors shadow-lg shadow-indigo-500/20">
                        Selanjutnya <i class="ti ti-arrow-right"></i>
                    </button>
                </div>

                {{-- Pagination Bullets --}}
                <div class="mt-4" x-show="showNavigator" x-cloak>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="page in totalQuestions" :key="page">
                            <button
                                type="button"
                                @click="goToPage(page)"
                                class="w-9 h-9 flex items-center justify-center rounded-full text-xs font-bold border transition-all"
                                :class="{
                                    'bg-indigo-600 text-white border-indigo-600': page === currentPage,
                                    'bg-emerald-50 text-emerald-700 border-emerald-500': page !== currentPage && answers[page],
                                    'bg-gray-50 text-gray-500 border-gray-300 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700': !answers[page] && page !== currentPage
                                }">
                                <span x-text="page"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="!loading && !currentQuestion && !error" x-cloak class="flex flex-col items-center justify-center py-12">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                <i class="ti ti-help-off text-2xl text-gray-400"></i>
            </div>
            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                Belum ada soal
            </h4>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Ujian ini belum memiliki soal.
            </p>
        </div>

        <div x-show="error" x-cloak class="flex flex-col items-center justify-center py-12">
            <div class="w-16 h-16 bg-rose-100 dark:bg-rose-500/10 rounded-full flex items-center justify-center mb-4">
                <i class="ti ti-alert-circle text-2xl text-rose-600"></i>
            </div>
            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                Terjadi Kesalahan
            </h4>
            <p class="text-sm text-gray-500 dark:text-gray-400" x-text="error"></p>
            <button 
                type="button"
                @click="loadQuestion(1)" 
                class="mt-4 inline-flex items-center gap-2 px-6 py-3 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl font-semibold transition-colors hover:bg-gray-100 dark:hover:bg-gray-700">
                Coba Lagi
            </button>
        </div>
        </div>
    </div>
</div>

<script>
function examData() {
    return {
        loading: true,
        currentQuestion: null,
        currentPage: 1,
        totalQuestions: {{ $totalQuestions }},
        selectedAnswer: null,
        error: null,
        answers: {},
        showNavigator: false,
        examDuration: {{ $exam->duration ? ($exam->duration * 60) : 0 }}, // detik total dari durasi ujian
        timeRemaining: 0,
        timerInterval: null,

        init() {
            const STORAGE_KEY = 'exam_{{ $exam->id }}';

            // Load dari localStorage jika ada
            const saved = localStorage.getItem(STORAGE_KEY);
            if (saved) {
                try {
                    const parsed = JSON.parse(saved);
                    this.answers = parsed.answers || {};
                    this.currentPage = parsed.currentPage || 1;

                    // restore sisa waktu jika masih ada
                    if (typeof parsed.timeRemaining === 'number' && parsed.timeRemaining > 0) {
                        this.timeRemaining = parsed.timeRemaining;
                    } else {
                        this.timeRemaining = this.examDuration;
                    }

                    if (this.examDuration > 0 && this.timeRemaining > 0) {
                        this.startTimer();
                    } else if (this.examDuration > 0) {
                        this.timeRemaining = 0;
                        this.onTimeUp();
                    }
                } catch (e) {
                    console.error('Error loading saved data:', e);
                }
            } else {
                // Start fresh timer
                if (this.examDuration > 0) {
                    this.timeRemaining = this.examDuration;
                    this.startTimer();
                }
            }
            
            this.loadQuestion(this.currentPage);
        },

        startTimer() {
            if (this.timerInterval) {
                clearInterval(this.timerInterval);
            }

            this.timerInterval = setInterval(() => {
                if (this.timeRemaining > 0) {
                    this.timeRemaining--;
                    this.saveToStorage();
                    
                    if (this.timeRemaining <= 0) {
                        this.onTimeUp();
                    }
                }
            }, 1000);
        },

        onTimeUp() {
            if (this.timerInterval) {
                clearInterval(this.timerInterval);
            }
            
            alert('Waktu ujian telah habis! Jawaban Anda akan otomatis tersimpan.');
            // Bisa tambahkan logic untuk auto-submit di sini
        },

        formatTime(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            
            if (hours > 0) {
                return `${hours}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
            }
            return `${minutes}:${String(secs).padStart(2, '0')}`;
        },

        saveToStorage() {
            const STORAGE_KEY = 'exam_{{ $exam->id }}';
            localStorage.setItem(STORAGE_KEY, JSON.stringify({
                currentPage: this.currentPage,
                answers: this.answers,
                timeRemaining: this.timeRemaining,
            }));
        },

        async loadQuestion(page) {
            this.loading = true;
            this.error = null;
            this.currentPage = page;
            this.selectedAnswer = this.answers[page] || null;

            try {
                const response = await fetch(`{{ route('user.exams.questions', ['package' => $package->id, 'exam' => $exam->id]) }}?page=${page}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });

                if (!response.ok) {
                    throw new Error('Gagal memuat soal');
                }

                const data = await response.json();
                
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
                this.saveToStorage();
            }
        },

        previousQuestion() {
            if (this.currentPage > 1) {
                this.saveAnswer();
                this.loadQuestion(this.currentPage - 1);
            }
        },

        nextQuestion() {
            if (this.currentPage < this.totalQuestions) {
                this.saveAnswer();
                this.loadQuestion(this.currentPage + 1);
            }
        },

        goToPage(page) {
            if (page >= 1 && page <= this.totalQuestions) {
                this.saveAnswer();
                this.loadQuestion(page);
            }
        },

        saveAnswer() {
            if (this.selectedAnswer && this.currentQuestion) {
                this.answers[this.currentPage] = this.selectedAnswer;
                this.saveToStorage();
            }
        }
    }
}
</script>
@endsection

