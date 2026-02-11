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
                        @if($package->masterType)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                {{ $package->masterType->name_type }}
                            </span>
                        @endif
                    </div>
                    @if($package->description)
                        <div class="prose prose-sm dark:prose-invert max-w-none">
                            {!! $package->description !!}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Package Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                <div class="text-center">
                    <p class="text-2xl font-bold text-indigo-600">{{ $subjects->sum(fn($s) => $s->materials->count()) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Materi</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-emerald-600">{{ $package->userJoins->count() }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Peserta</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-violet-600">{{ $package->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Status</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-rose-600">{{ $package->created_at->format('M Y') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Dibuat</p>
                </div>
            </div>
        </div>
    </x-card>

    {{-- Materials List (dikelompokkan per Mata Pelajaran) --}}
    <x-card title="Materi Pembelajaran">
        @if($subjects->sum(fn($s) => $s->materials->count()) > 0)
            <div class="space-y-8">
                @foreach($subjects as $subject)
                    @if($subject->materials->count() > 0)
                        {{-- Header Mata Pelajaran --}}
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400">
                                    <i class="ti ti-book text-sm"></i>
                                </span>
                                {{ $subject->name }}
                                @if($subject->code)
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">({{ $subject->code }})</span>
                                @endif
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 ml-10">{{ $subject->materials->count() }} materi</p>
                        </div>
                        {{-- Daftar Materi di bawah mata pelajaran --}}
                        <div class="space-y-3 ml-0 md:ml-4 pl-0 md:pl-4 border-l-0 md:border-l-2 border-indigo-100 dark:border-indigo-900/50">
                            @foreach($subject->materials as $material)
                                @php
                                    $statusMateri = \App\Models\StatusMateri::where('id_user', Auth::id())
                                        ->where('id_material', $material->id)
                                        ->first();
                                @endphp
                                <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-800 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors {{ $statusMateri && $statusMateri->status == 'completed' ? 'opacity-80' : '' }}">
                                    <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-500/10 rounded-lg flex items-center justify-center shrink-0">
                                        @if ($material->materi_type == 'File')
                                            <i class="{{ $material->file_icon }} text-xl {{ $material->file_type === 'pdf' ? 'text-rose-600' : 'text-blue-600' }}"></i>
                                        @else
                                            <i class="ti ti-video text-xl text-indigo-600"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $material->title }}</h4>
                                        <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                            @if($material->topic)
                                                <span class="inline-flex items-center gap-1">
                                                    <i class="ti ti-tag"></i>
                                                    {{ $material->topic }}
                                                </span>
                                            @endif
                                            @if($material->value)
                                                <span>{{ $material->file_size_formatted }}</span>
                                            @endif
                                        </div>
                                        @if($material->description)
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-2 line-clamp-2">
                                                {{ Str::limit(strip_tags($material->description), 100) }}
                                            </p>
                                        @endif
                                    </div>

                                    @if ($material->materi_type == 'File')
                                        <div class="flex items-center gap-2 shrink-0">
                                            @if($material->value)
                                                @if(!$statusMateri || $statusMateri->status != 'completed')
                                                    <x-button 
                                                        class="inline-flex items-center gap-1 px-3 py-2 bg-green-50 hover:bg-green-100 dark:bg-green-500/10 dark:hover:bg-green-500/20 text-green-600 dark:text-green-400 text-xs font-semibold rounded-lg transition-colors"
                                                        @click="confirmMarkRead('{{ route('user.mark-as-read', $material) }}', {{ \Illuminate\Support\Js::from($material->title) }})"
                                                    >
                                                        <i class="ti ti-square-check"></i>
                                                        Tandai Telah Dibaca
                                                    </x-button>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-3 py-2 bg-green-100 text-green-700 text-xs font-semibold rounded-lg">
                                                        <i class="ti ti-check"></i>
                                                        Materi Telah Dibaca
                                                    </span>
                                                @endif
                                                <a href="{{ route('master-materials.preview', $material->id) }}?v={{ $material->updated_at->timestamp }}" target="_blank" 
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
                                            @if(!$statusMateri || $statusMateri->status != 'completed')
                                                <x-button 
                                                    class="inline-flex items-center gap-1 px-3 py-2 bg-green-50 hover:bg-green-100 dark:bg-green-500/10 dark:hover:bg-green-500/20 text-green-600 dark:text-green-400 text-xs font-semibold rounded-lg transition-colors"
                                                    @click="confirmMarkRead('{{ route('user.mark-as-read', $material) }}', {{ \Illuminate\Support\Js::from($material->title) }})"
                                                >
                                                    <i class="ti ti-square-check"></i>
                                                    Tandai Telah DiPelajari
                                                </x-button>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-3 py-2 bg-green-100 text-green-700 text-xs font-semibold rounded-lg">
                                                    <i class="ti ti-check"></i>
                                                    Materi Telah Dibaca
                                                </span>
                                            @endif
                                        </div>
                                        @if($material->value)
                                            @php
                                                $url = $material->value;
                                                if (str_contains($url, 'youtu.be')) {
                                                    $url = 'https://www.youtube.com/embed/' . explode('?', last(explode('/', $url)))[0];
                                                }
                                                if (str_contains($url, 'youtube.com/watch')) {
                                                    parse_str(parse_url($url, PHP_URL_QUERY), $q);
                                                    $url = 'https://www.youtube.com/embed/' . ($q['v'] ?? '');
                                                }
                                            @endphp
                                            <iframe 
                                                id="frame-video" 
                                                src="{{ $url }}"  
                                                class="w-full aspect-video rounded-xl max-w-md"
                                                frameborder="0"
                                                allowfullscreen
                                                referrerpolicy="strict-origin-when-cross-origin"
                                            ></iframe>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
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
        $quizez = \App\Models\Quiz::where('package_id', $package->id)->get();
        $lastResultPackage = \App\Models\TransQuestion::where('id_user', auth()->id())
                            ->whereHas('quiz', function($q) use ($package) {
                                $q->where('package_id', $package->id);
                            })
                            ->latest()
                            ->first();
    @endphp

      @if($quizez->count() > 0)
        <x-card title="Kuis / Latihan">
            <div class="space-y-4">
                @foreach($quizez as $quizItem)
                    @php
                        $totalQuestions = \App\Models\MappingQuestion::where('id_quiz', $quizItem->id)->count();
                    @endphp
                    <div class="flex items-center justify-between p-4 
                        bg-gradient-to-r from-emerald-50 to-teal-50 
                        dark:from-emerald-500/10 dark:to-teal-500/10 
                        rounded-xl border border-emerald-100 dark:border-emerald-800"
                    >

                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center text-white">
                                <i class="ti ti-brain text-xl"></i>
                            </div>

                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white mb-1">
                                    {{ $quizItem->title }}
                                </h4>

                                <div class="flex items-center gap-4 text-xs text-gray-600 dark:text-gray-400">
                                    <span class="inline-flex items-center gap-1">
                                        <i class="ti ti-help-circle"></i>
                                        {{ $totalQuestions }} Soal
                                    </span>

                                    @if($quizItem->duration)
                                        <span class="inline-flex items-center gap-1">
                                            <i class="ti ti-clock"></i>
                                            {{ $quizItem->duration }} Menit
                                        </span>
                                    @endif

                                    @if($quizItem->passing_grade)
                                        <span class="inline-flex items-center gap-1">
                                            <i class="ti ti-target"></i>
                                            Passing Grade: {{ $quizItem->passing_grade }}%
                                        </span>
                                    @endif
                                </div>
                                @if($quizItem->description)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ Str::limit(strip_tags($quizItem->description)) }}
                                        </p>
                                @endif
                               
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                           Nilai Terakhir Mengerjakan: {{ $lastResultPackage ? $lastResultPackage->total_score : 'Belum pernah mengikuti kuis' }}
                                        </p>
                            
                            </div>
                        </div>

                        <x-button variant="secondary"
                            href="{{ route('user.quizzes.show', ['package' => $package->id, 'quiz' => $quizItem->id]) }}"
                            class="rounded-xl shadow-lg shadow-emerald-500/20">
                            <i class="ti ti-arrow-right mr-2"></i> Mulai Kuis
                        </x-button>
                    </div>
                @endforeach
            </div>
        </x-card>
    @endif

    @php
        $exams = \App\Models\Exam::where('package_id', $package->id)->get();
    @endphp
    
    @if($exams->count() > 0)
        <x-card title="Try Out / Ujian">
            <div class="space-y-4">
                @foreach($exams as $examItem)
                    @php
                        $totalQuestions = \App\Models\MappingQuestion::where('id_exam', $examItem->id)->count();
                    @endphp
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-indigo-50 to-violet-50 dark:from-indigo-500/10 dark:to-violet-500/10 rounded-xl border border-indigo-100 dark:border-indigo-800">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center text-white">
                                <i class="ti ti-clipboard-check text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white mb-1">{{ $examItem->title }}</h4>
                                <div class="flex items-center gap-4 text-xs text-gray-600 dark:text-gray-400">
                                    <span class="inline-flex items-center gap-1">
                                        <i class="ti ti-help-circle"></i>
                                        {{ $totalQuestions }} Soal
                                    </span>
                                    @if($examItem->duration)
                                        <span class="inline-flex items-center gap-1">
                                            <i class="ti ti-clock"></i>
                                            {{ $examItem->duration }} Menit
                                        </span>
                                    @endif
                                    @if($examItem->passing_grade)
                                        <span class="inline-flex items-center gap-1">
                                            <i class="ti ti-target"></i>
                                            Passing Grade: {{ $examItem->passing_grade }}%
                                        </span>
                                    @endif
                                </div>
                                @if($examItem->description)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ Str::limit(strip_tags($examItem->description), 80) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <x-button variant="primary" href="{{ route('user.exams.show', ['package' => $package->id, 'exam' => $examItem->id]) }}" class="rounded-xl shadow-lg shadow-indigo-500/20">
                            <i class="ti ti-arrow-right mr-2"></i> Mulai Ujian
                        </x-button>
                    </div>
                @endforeach
            </div>
        </x-card>
    @endif

     {{-- Approve Confirmation Modal --}}
    <div x-show="modalMarkRead" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        
        <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800"
            @click.away="modalMarkRead = false">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-green-50 dark:bg-green-500/10 text-green-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i class="ti ti-circle-dashed-check text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Tandai Telah Dibaca</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    Anda akan menandai materi <span x-text="materialName"></span> sebagai telah dibaca. Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            
            <div class="flex gap-3 p-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800">
                <x-button variant="secondary" class="flex-1 rounded-xl" @click="modalMarkRead = false">
                    Batal
                </x-button>
                <form :action="modelUrl" method="POST" class="flex-1">
                    @csrf
                    @method('POST')
                    <x-button variant="success" type="submit" class="w-full rounded-xl shadow-lg shadow-emerald-500/20">
                        Ya, Tandai Telah Dibaca
                    </x-button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

