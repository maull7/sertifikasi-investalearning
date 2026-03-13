@extends('layouts.app')

@section('title', 'Desain Sertifikat - ' . $package->title)

@section('content')
    <div class="space-y-6 pb-20">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                    Desain Sertifikat
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">
                    Atur desain sertifikat untuk paket:
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $package->title }}</span>
                    @if($package->masterType)
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            ({{ $package->masterType->name_type }})
                        </span>
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-2">
                <x-button variant="secondary" href="{{ route('certificate-templates.index') }}" class="rounded-xl">
                    <i class="ti ti-arrow-left mr-2"></i> Kembali
                </x-button>
            </div>
        </div>

        <form action="{{ route('certificate-templates.update', $package) }}" method="POST" enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @method('PUT')

            <x-card title="Sisi Depan Sertifikat">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">
                                Background Sertifikat (Gambar)
                            </label>
                            <input type="file" name="front_background" accept="image/*"
                                   class="w-full text-sm text-gray-700 dark:text-gray-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-500/10 dark:file:text-indigo-300">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Opsional. Format: JPG/PNG, maksimum 2 MB.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-3">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                    Tanda Tangan Kiri
                                </h3>
                                <input type="file" name="left_signature_image" accept="image/*"
                                       class="w-full text-sm text-gray-700 dark:text-gray-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-500/10 dark:file:text-indigo-300">
                                <input type="text" name="left_signature_name"
                                       value="{{ old('left_signature_name', $template->left_signature_name) }}"
                                       placeholder="Nama penandatangan kiri"
                                       class="w-full px-3 py-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white">
                                <input type="text" name="left_signature_title"
                                       value="{{ old('left_signature_title', $template->left_signature_title) }}"
                                       placeholder="Jabatan penandatangan kiri"
                                       class="w-full px-3 py-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white">
                            </div>

                            <div class="space-y-3">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                    Tanda Tangan Kanan
                                </h3>
                                <input type="file" name="right_signature_image" accept="image/*"
                                       class="w-full text-sm text-gray-700 dark:text-gray-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-500/10 dark:file:text-indigo-300">
                                <input type="text" name="right_signature_name"
                                       value="{{ old('right_signature_name', $template->right_signature_name) }}"
                                       placeholder="Nama penandatangan kanan"
                                       class="w-full px-3 py-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white">
                                <input type="text" name="right_signature_title"
                                       value="{{ old('right_signature_title', $template->right_signature_title) }}"
                                       placeholder="Jabatan penandatangan kanan"
                                       class="w-full px-3 py-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Background dan tanda tangan ini akan digunakan di <span class="font-semibold">halaman depan</span>
                            sertifikat. Jika tidak diisi, sistem akan menggunakan tampilan default yang sudah ada.
                        </p>

                        @if($template->front_background_path)
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">
                                    Preview Background Saat Ini
                                </p>
                                <img src="{{ asset('storage/'.$template->front_background_path) }}"
                                     alt="Background Sertifikat"
                                     class="w-full max-w-md rounded-xl border border-gray-200 dark:border-gray-800">
                            </div>
                        @endif
                    </div>
                </div>
            </x-card>

            <x-card title="Sisi Belakang Sertifikat (Halaman Kedua)">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">
                            Judul Skema
                        </label>
                        <input type="text" name="schema_title"
                               value="{{ old('schema_title', $template->schema_title) }}"
                               placeholder="Contoh: Pelatihan Dasar Analisis Saham"
                               class="w-full px-3 py-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">
                            Penjelasan Skema
                        </label>
                        <textarea name="schema_description" rows="4"
                                  placeholder="Deskripsikan tujuan, sasaran, dan ruang lingkup pelatihan di sini..."
                                  class="w-full px-3 py-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white">{{ old('schema_description', $template->schema_description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">
                                Daftar Unit Kompetensi (UK)
                            </label>
                            <textarea name="uk_list" rows="6"
                                      placeholder="Tulis daftar UK baris per baris.&#10;Contoh:&#10;- Memahami dasar-dasar analisis teknikal&#10;- Menerapkan manajemen risiko pada portofolio saham"
                                      class="w-full px-3 py-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white">{{ old('uk_list', $template->uk_list) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Tidak otomatis dari skema. Semua isi ditulis manual di sini.
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">
                                Daftar Fasilitator
                            </label>
                            <textarea name="facilitator_list" rows="6"
                                      placeholder="Tulis daftar fasilitator baris per baris.&#10;Contoh:&#10;- Budi Santoso, CFP&#10;- Sari Anindya, Equity Analyst"
                                      class="w-full px-3 py-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white">{{ old('facilitator_list', $template->facilitator_list) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Juga tidak otomatis dari data pengajar. Semua manual sesuai kebutuhan sertifikat.
                            </p>
                        </div>
                    </div>
                </div>
            </x-card>

            <div class="flex justify-end">
                <x-button type="submit" variant="primary" class="rounded-xl">
                    <i class="ti ti-device-floppy mr-2"></i> Simpan Desain Sertifikat
                </x-button>
            </div>
        </form>
    </div>
@endsection

