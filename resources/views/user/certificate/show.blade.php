@extends('layouts.app')

@section('title', 'Lihat Sertifikat')

@section('content')
    <div class="space-y-6 pb-20">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Sertifikat</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">Preview Sertifikat</p>
            </div>

            <div class="flex gap-2">
                <x-button variant="primary" href="{{ route('certificates.download', $certificate) }}" class="rounded-xl">
                    <i class="ti ti-download mr-2"></i> Download PDF
                </x-button>
                <x-button variant="secondary" href="{{ route('certificates.index') }}" class="rounded-xl">
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
                        <div>
                            <img src="{{asset('img/favicon.png')}}" alt="Logo"
                                class="w-32 h-24">
                        </div>
                        <div class="text-center">
                            <h2 class="text-4xl text-sky-600 underline underline-offset-8 uppercase font-serif">INVESTALEARNING</h2>
                            <span class="text-xs text-sky-600 font-bold font-serif">Be Champion With Us</span>
                        </div>
                        <div>
                            <img src="{{asset('img/favicon.png')}}" alt="Logo"
                                class="w-32 h-24">
                        </div>
                      
                    </div>

                    <div class="mt-4 text-center space-y-3">
                        <div class="text-gray-900 dark:text-white font-bold">
                            <h2 class="text-3xl md:text-4xl uppercase">Certificate of Training</h2>
                             <span>
                                No.
                                {{ 
                                    $certificate->certificate_number
                                    ? $certificate->certificate_number . '/BIB/Training/' .
                                    \Carbon\Carbon::parse($certificate->training_date_start)->format('d F') .
                                    ' - ' .
                                    \Carbon\Carbon::parse($certificate->training_date_end)->format('d F Y')
                                    : '---/BIB/Training/Month/Years'
                                }}
                            </span>
                        </div>
                        <p class="text-xl font-semibold uppercase text-gray-900 dark:text-gray-400">
                           GIVEN TO
                        </p>
                    </div>

                    <div class="mt-4 text-center">
                        <p class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">
                            {{ $certificate->user?->name ?? '-' }}
                        </p>
                        <div class="mx-auto max-w-1/2 space-y-1">
                            <span class="block  h-1 bg-blue-500 mt-2"></span>
                            <p class="text-gray-700 text-center font-semibold">As Participant In Examination Prepation Training of "{{$certificate->type?->name_type ?? '-' }}" Jakarta @if($certificate->training_date_start && $certificate->training_date_end) {{ \Carbon\Carbon::parse($certificate->training_date_start)->format('d F') }} - {{ \Carbon\Carbon::parse($certificate->training_date_end)->format('d F Y') }} @elseif($certificate->training_date_start) {{ \Carbon\Carbon::parse($certificate->training_date_start)->format('d F Y') }} @else Jakarta, 12 - 14 Month Years @endif</p>

                        </div>
                    </div>

                    <div class="mt-24 flex items-center justify-between">
                        <div class="text-center font-medium">
                            <div class="h-0.5 w-48 bg-sky-400 dark:bg-gray-700 mb-2"></div>
                                <p class="text-xl text-gray-700">Lucas Bonardo </p>
                                <p class="text-xl text-gray-700">Director</p>
                             <div class="h-0.5 w-48 bg-sky-400 dark:bg-gray-700 mb-2"></div>
                        </div>
                        <div class="text-center font-medium">
                            <div class="h-0.5 w-48 bg-sky-400 dark:bg-gray-700 mb-2"></div>
                                <p class="text-xl text-gray-700">Lisbeth Rosaria </p>
                                <p class="text-xl text-gray-700">Senior Vice President</p>
                             <div class="h-0.5 w-48 bg-sky-400 dark:bg-gray-700 mb-2"></div>
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
                        <div>
                            <img src="{{asset('img/favicon.png')}}" alt="Logo"
                                class="w-32 h-24">
                        </div>
                        <div class="text-center">
                            <h2 class="text-4xl text-sky-600 underline underline-offset-8 uppercase font-serif">INVESTALEARNING</h2>
                            <span class="text-xs text-sky-600 font-bold font-serif">Be Champion With Us</span>
                        </div>
                        <div>
                            <img src="{{asset('img/favicon.png')}}" alt="Logo"
                                class="w-32 h-24">
                        </div>
                      
                    </div>

                 {{-- Judul Halaman 2 --}}
                    <div class="mt-6 flex justify-center">
                        <div class="max-w-2xl text-center space-y-2">
                            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white leading-snug">
                                JENJANG KUALIFIKASI 4 BIDANG PASAR MODAL SUBBIDANG
                                PERANTARA PEDAGANG EFEK PEMASARAN
                            </h2>
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-left text-gray-800 dark:text-white leading-snug font-semibold">
                        <p>Sesuai keputusan Menteri ketenagakerjaan Republik Indonesia Nomor 20 Tahun 2024 Tentang Penetapan Standar Kompotensi Kerja nasional Indonesia Kategori Aktivitas Keuangan Dan Asuransi Golongan Pokok Aktivitas Penunjang Jasa Keuangan, Bukan Asuransi Dan Dana Pensiun Bidang Pasar Modal. </p>
                        <p>Dan Keputusan Anggota Dewan Komisioner Otoritas Jasa Keuangan Nomor Kep-11/D.02/2024 Tentang Kerangka Kualitas Nasional Indonesia Bidang Pasar Modal.</p>
                    </div>


                    {{-- Isi: Daftar Pengajar & Topik (lurus satu kolom) --}}
                    <div class="mt-10 space-y-8">
                       
                        <div>

                            <div class="overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-800">
                                <table class="w-full border-collapse bg-white dark:bg-gray-900">
                                    <thead>
                                        <tr class="bg-gray-50 dark:bg-gray-800">
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">
                                                NO
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">
                                                KODE
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">
                                                Deskripsi
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">
                                                Topik
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                        @forelse($certificate->package?->materials ?? [] as $material)
                                            <tr>
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white align-top">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white align-top">
                                                    {{ $material->subject->code }}
                                                </td>

                                                <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400 align-top">
                                                    {{ $material->description ?? '-' }}
                                                </td>

                                                <td class="px-4 py-3 text-xs text-indigo-600 dark:text-indigo-300 align-top">
                                                    {{ $material->topic ?? '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                                    Belum ada materi pada paket ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    {{-- Footer kecil --}}
                    <div class="mt-10 flex items-center justify-between text-xs text-gray-900 dark:text-gray-500 font-semibold font-sans">
                        <span>NAMA FASILITATOR : @forelse ($certificate->teachers ?? [] as $teacher )
                            {{$teacher->name ,}}
                        @empty
                            <span>Nama Faslitator belum tersedia.</span>
                        @endforelse</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


