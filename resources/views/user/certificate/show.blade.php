@extends('layouts.app')

@section('title', 'Lihat Sertifikat')

@section('content')
    <div class="space-y-6 pb-20">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Sertifikat</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">Preview sertifikat (dummy)</p>
            </div>

            <div class="flex gap-2">
                <x-button variant="primary" href="{{ route('certificates.download', $certificate) }}" class="rounded-xl">
                    <i class="ti ti-download mr-2"></i> Download PDF
                </x-button>
                <x-button variant="secondary" href="{{ route('user.certificate.index') }}" class="rounded-xl">
                    <i class="ti ti-arrow-left mr-2"></i> Kembali
                </x-button>
                <x-button variant="primary" class="rounded-xl" onclick="window.print()">
                    <i class="ti ti-printer mr-2"></i> Print
                </x-button>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm p-6">
            <div class="mx-auto max-w-4xl">
                <div class="border-4 border-indigo-600/20 rounded-2xl p-10 bg-gradient-to-b from-indigo-50/40 to-white dark:from-indigo-500/10 dark:to-gray-900">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="https://investalearning.com/public/assets/logo/investalearning2.jpeg" alt="Logo"
                                class="h-12 w-auto rounded-lg border border-gray-200 bg-white p-1">
                            <div>
                                <p class="text-xs uppercase tracking-[0.18em] text-gray-400">InvestaLearning</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Sertifikasi</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Tanggal</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $certificate->created_at?->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="mt-10 text-center space-y-3">
                        <p class="text-xs uppercase tracking-[0.25em] text-indigo-600 font-bold">Certificate of Completion</p>
                        <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white">SERTIFIKAT</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Diberikan kepada peserta yang telah menyelesaikan ujian
                        </p>
                    </div>

                    <div class="mt-10 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Nama Peserta</p>
                        <p class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $certificate->user?->name ?? '-' }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">{{ $certificate->user?->email ?? '' }}</p>
                    </div>

                    <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white/70 dark:bg-gray-900/60 p-5">
                            <p class="text-[11px] uppercase tracking-[0.14em] text-gray-400 mb-1">Jenis</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $certificate->type?->name_type ?? '-' }}</p>
                        </div>
                        <div class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white/70 dark:bg-gray-900/60 p-5">
                            <p class="text-[11px] uppercase tracking-[0.14em] text-gray-400 mb-1">Paket</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $certificate->package?->title ?? '-' }}</p>
                        </div>
                        <div class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white/70 dark:bg-gray-900/60 p-5">
                            <p class="text-[11px] uppercase tracking-[0.14em] text-gray-400 mb-1">Nomor</p>
                            <p class="font-mono text-sm text-gray-900 dark:text-white">CERT-{{ str_pad((string) $certificate->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>

                    <div class="mt-12 flex items-center justify-between">
                        <div class="text-center">
                            <div class="h-px w-48 bg-gray-300 dark:bg-gray-700 mb-2"></div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Tanda Tangan</p>
                        </div>
                        <div class="text-center">
                            <div class="h-px w-48 bg-gray-300 dark:bg-gray-700 mb-2"></div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Penyelenggara</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Halaman 2: Pengajar & Topik Materi (full certificate style) --}}
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm p-6 print:break-before-page">
            <div class="mx-auto max-w-4xl">
                <div
                    class="border-4 border-indigo-600/20 rounded-2xl p-8 md:p-10 bg-gradient-to-b from-indigo-50/40 to-white dark:from-indigo-500/10 dark:to-gray-900">
                    {{-- Header logo + info --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="https://investalearning.com/public/assets/logo/investalearning2.jpeg" alt="Logo"
                                class="h-12 w-auto rounded-lg border border-gray-200 bg-white p-1">
                            <div>
                                <p class="text-xs uppercase tracking-[0.18em] text-gray-400">InvestaLearning</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Sertifikasi</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Halaman</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">2 / 2</p>
                        </div>
                    </div>

                    {{-- Judul Halaman 2 --}}
                    <div class="mt-8 text-center space-y-2">
                        <p class="text-xs uppercase tracking-[0.25em] text-indigo-600 font-bold">Certificate Details</p>
                        <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white">PENGAJAR & TOPIK
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Rangkuman pengajar yang terlibat dan materi yang tercakup pada paket ini.
                        </p>
                    </div>

                    {{-- Isi: Daftar Pengajar & Topik (lurus satu kolom) --}}
                    <div class="mt-10 space-y-8">
                        {{-- Pengajar --}}
                        <div>
                            <p class="text-[11px] uppercase tracking-[0.16em] text-gray-400 mb-3">Daftar Pengajar</p>
                            <div
                                class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white/70 dark:bg-gray-900/60 divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse($certificate->teachers as $teacher)
                                    <div class="px-4 py-3">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $teacher->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $teacher->email ?? '-' }}
                                            @if($teacher->nip)
                                                • NIP: {{ $teacher->nip }}
                                            @endif
                                        </p>
                                    </div>
                                @empty
                                    <div class="px-4 py-4">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada pengajar terpilih.
                                        </p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Topik Materi --}}
                        <div>
                            <p class="text-[11px] uppercase tracking-[0.16em] text-gray-400 mb-3">Topik Materi</p>
                            <div
                                class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white/70 dark:bg-gray-900/60 divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse($certificate->package?->materials ?? [] as $material)
                                    <div class="px-4 py-3 space-y-1.5">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $material->title ?? '-' }}
                                        </p>
                                        @if(!empty($material->description))
                                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                                {{ $material->description }}
                                            </p>
                                        @endif
                                        @if(!empty($material->topic))
                                            <p class="text-xs font-semibold text-indigo-600 dark:text-indigo-300">
                                                Topik:
                                                <span class="font-normal text-gray-700 dark:text-gray-200">{{ $material->topic }}</span>
                                            </p>
                                        @endif
                                    </div>
                                @empty
                                    <div class="px-4 py-4">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada materi pada paket
                                            ini.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Footer kecil --}}
                    <div class="mt-10 flex items-center justify-between text-xs text-gray-400 dark:text-gray-500">
                        <span>Dummy layout sertifikat • dapat disesuaikan nanti</span>
                        <span>InvestaLearning Sertifikasi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


