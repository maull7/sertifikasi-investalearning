@extends('layouts.guest')

@section('title', 'Verifikasi Sertifikat')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-950 px-4">
        <div class="max-w-lg w-full">
            <x-card class="shadow-sm border-gray-200 dark:border-gray-800">
                <div class="flex items-center gap-3 mb-4">
                    <img src="https://investalearning.com/public/assets/logo/investalearning2.jpeg" alt="Logo"
                        class="h-10 w-auto rounded-lg border border-gray-200 bg-white p-1">
                    <div>
                        <p class="text-xs uppercase tracking-[0.18em] text-gray-400">InvestaLearning</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Verifikasi Sertifikat</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Status</p>
                        <p class="mt-1 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                            Sertifikat Valid
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Pemilik</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $certificate->user?->name ?? '-' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $certificate->user?->email ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Nomor Sertifikat</p>
                            <p class="mt-1 font-mono text-xs text-gray-900 dark:text-white">
                                CERT-{{ str_pad((string) $certificate->id, 6, '0', STR_PAD_LEFT) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Diterbitkan: {{ $certificate->created_at?->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Jenis</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $certificate->type?->name_type ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Paket</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $certificate->package?->title ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <p class="mt-4 text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                        Halaman ini digunakan untuk memverifikasi keaslian sertifikat yang diterbitkan oleh InvestaLearning.
                        Pastikan data di atas sesuai dengan informasi yang dimiliki peserta.
                    </p>
                </div>
            </x-card>
        </div>
    </div>
@endsection

