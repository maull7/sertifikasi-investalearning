@extends('layouts.app')

@section('title', 'Tambah Ujian Baru')

@section('content')
    <div class="max-w-5xl mx-auto space-y-8 pb-20">

        {{-- Header & Breadcrumb --}}
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Tambah Ujian</h1>
                <nav class="flex items-center gap-2 text-xs font-medium text-gray-400">
                    <a href="{{ route('exams.index') }}" class="hover:text-indigo-600 transition-colors">Ujian</a>
                    <i class="ti ti-chevron-right"></i>
                    <span class="text-gray-500 dark:text-gray-500">Tambah Baru</span>
                </nav>
            </div>
            <x-button variant="secondary" href="{{ route('exams.index') }}" class="rounded-xl">
                <i class="ti ti-arrow-left mr-2"></i> Kembali
            </x-button>
        </div>

        {{-- Form Card --}}
        <x-card title="Ujian Baru">
            <form action="{{ route('exams.store') }}" method="POST" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    @if ($errors->any())
                        <div
                            class="md:col-span-2 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-300">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Type Select --}}
                    <div class="md:col-span-2">
                        <x-select label="Tipe Ujian" name="type" required>
                            <option value="pretest" {{ old('type') == 'pretest' ? 'selected' : '' }}>Pretest</option>
                            <option value="posttest" {{ old('type') == 'posttest' ? 'selected' : '' }}>Posttest</option>
                        </x-select>
                    </div>
                    {{-- Package Select --}}
                    <div class="md:col-span-2"
                        x-data="{
                            packageId: '{{ old('package_id') }}',
                            subjects: [],
                            loading: false,
                            baseUrl: '{{ route('exams.subjects-by-package', ['package' => 0]) }}',
                            existingCounts: @json([]),
                            loadSubjects() {
                                const id = this.packageId || (this.$refs.packageSelect && this.$refs.packageSelect.value);
                                if (!id) { this.subjects = []; return; }
                                this.loading = true;
                                const url = this.baseUrl.replace(/\/0$/, '/' + id);
                                fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                                    .then(r => r.json())
                                    .then(d => {
                                        this.subjects = (d.subjects || []).map(s => ({
                                            ...s,
                                            questions_count: this.existingCounts[s.id] ?? 0
                                        }));
                                    })
                                    .catch(() => { this.subjects = []; })
                                    .finally(() => { this.loading = false; });
                            }
                        }"
                        x-init="
                            $nextTick(() => {
                                if ($refs.packageSelect) packageId = $refs.packageSelect.value;
                                loadSubjects();
                            });
                        ">
                        <x-select label="Paket" name="package_id" required
                            x-ref="packageSelect"
                            x-model="packageId"
                            @change="loadSubjects()">
                            <option value="">Pilih Paket</option>
                            @foreach ($packages as $package)
                                <option value="{{ $package->id }}"
                                    {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                    {{ $package->title }}
                                </option>
                            @endforeach
                        </x-select>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-show="loading" x-cloak>Memuat mata pelajaran...</p>
                        {{-- Daftar mapel + jumlah soal per mapel --}}
                        <div class="mt-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/30 overflow-hidden" x-show="!loading && subjects.length > 0" x-cloak x-transition>
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Jumlah soal per mata pelajaran</span>
                                <span class="ml-2 text-xs text-gray-500 dark:text-gray-400" x-text="'( ' + subjects.length + ' mapel )'"></span>
                            </div>
                            <div class="p-4 space-y-3 max-h-60 overflow-y-auto">
                                <template x-for="(s, i) in subjects" :key="s.id">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 p-3 rounded-xl bg-white dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700">
                                        <div class="flex items-center gap-2 flex-1 min-w-0">
                                            <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 text-xs font-bold shrink-0" x-text="i + 1"></span>
                                            <span class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate" x-text="s.name"></span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 shrink-0" x-show="s.code" x-text="s.code ? '(' + s.code + ')' : ''"></span>
                                        </div>
                                        <div class="flex items-center gap-2 sm:w-40">
                                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 shrink-0">Jumlah soal:</label>
                                            <input type="number" min="0" :name="'subject_questions[' + s.id + ']'" x-model.number="s.questions_count"
                                                class="w-20 px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="mt-4 rounded-xl border border-dashed border-gray-200 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-800/20 p-4 text-center" x-show="packageId && !loading && subjects.length === 0" x-cloak>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Paket ini belum memiliki mapping mata pelajaran. Atur mapel di <a href="{{ route('mapping-package.index') }}" class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline">Mapping Paket</a>.</p>
                        </div>
                    </div>

                    {{-- Title --}}
                    <div class="md:col-span-2">
                        <x-input label="Judul Ujian" name="title" placeholder="Masukkan judul ujian"
                            value="{{ old('title') }}" required />
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Deskripsi
                        </label>
                        <textarea name="description" rows="4" placeholder="Masukkan deskripsi ujian..."
                            class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl text-sm outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all dark:text-white resize-none">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Duration --}}
                    <div>
                        <x-input label="Durasi (Menit)" name="duration" type="number" min="1"
                            placeholder="Contoh: 90" value="{{ old('duration') }}" required />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Durasi ujian dalam menit</p>
                    </div>

                    {{-- Passing Grade --}}
                    <div>
                        <x-input label="Nilai Kelulusan (KKM)" name="passing_grade" type="number" min="0"
                            max="100" placeholder="Contoh: 75" value="{{ old('passing_grade') }}" required />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Nilai minimum untuk lulus (0-100)</p>
                    </div>

                    {{-- Show result after --}}
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="show_result_after" value="0">
                            <input type="checkbox" name="show_result_after" value="1"
                                {{ old('show_result_after', true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Tampilkan hasil & pembahasan setelah ujian</span>
                        </label>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Jika dicentang, peserta bisa lihat nilai dan pembahasan jawaban setelah menyelesaikan ujian.</p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-50 dark:border-gray-800">
                    <x-button type="reset" variant="secondary" class="rounded-xl">
                        Reset
                    </x-button>
                    <x-button type="submit" variant="primary" class="rounded-xl shadow-lg shadow-indigo-500/20">
                        Simpan Data
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
