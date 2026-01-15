@extends('layouts.app')

@section('title', $exam->title)

@section('content')
<div class="space-y-8 pb-20" x-data="examData()">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('user.my-packages.index') }}" class="hover:text-indigo-600 transition-colors">Package Saya</a>
        <i class="ti ti-chevron-right text-xs"></i>
        <a href="{{ route('user.my-packages.show', $package) }}" class="hover:text-indigo-600 transition-colors">{{ $package->title }}</a>
        <i class="ti ti-chevron-right text-xs"></i>
        <span class="text-gray-900 dark:text-white">{{ $exam->title }}</span>
    </nav>

    {{-- Exam Header --}}
    <x-card>
        <div class="space-y-4">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $exam->title }}</h1>
                    @if($exam->description)
                        <div class="prose prose-sm dark:prose-invert max-w-none">
                            {!! $exam->description !!}
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                <div class="text-center">
                    <p class="text-2xl font-bold text-indigo-600">{{ $totalQuestions }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Soal</p>
                </div>
                @if($exam->duration)
                    <div class="text-center">
                        <p class="text-2xl font-bold text-emerald-600">{{ $exam->duration }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Menit</p>
                    </div>
                @endif
                @if($exam->passing_grade)
                    <div class="text-center">
                        <p class="text-2xl font-bold text-violet-600">{{ $exam->passing_grade }}%</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Passing Grade</p>
                    </div>
                @endif
                <div class="text-center">
                    <p class="text-2xl font-bold text-rose-600" x-text="currentPage"></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Soal Saat Ini</p>
                </div>
            </div>
        </div>
    </x-card>

    {{-- Question Card --}}
    <x-card>
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
                  <x-button 
                    variant="secondary"
                    @click="previousQuestion()"
                    x-bind:disabled="currentPage === 1"
                    x-bind:class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''"
                    class="rounded-xl">
                    <i class="ti ti-arrow-left mr-2"></i> Sebelumnya
                </x-button>


                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400" x-text="currentPage + ' / ' + totalQuestions"></span>
                    </div>

                    <x-button 
                        variant="secondary"
                        @click="nextQuestion()"
                        x-bind:disabled="currentPage >= totalQuestions"
                        x-bind:class="currentPage >= totalQuestions ? 'opacity-50 cursor-not-allowed' : ''">
                       Selanjutnya <i class="ti ti-arrow-right mr-2"></i>
                    </x-button>
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
            <x-button variant="secondary" @click="loadQuestion(1)" class="mt-4 rounded-xl">
                Coba Lagi
            </x-button>
        </div>
    </x-card>
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

        init() {
            this.loadQuestion(1);
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

        saveAnswer() {
            if (this.selectedAnswer && this.currentQuestion) {
                this.answers[this.currentPage] = this.selectedAnswer;
            }
        }
    }
}
</script>
@endsection

