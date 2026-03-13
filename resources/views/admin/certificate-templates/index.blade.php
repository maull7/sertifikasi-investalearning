@extends('layouts.app')

@section('title', 'Desain Sertifikat')

@section('content')
    <div class="space-y-6 pb-20">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                    Desain Sertifikat per Paket
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">
                    Atur background, tanda tangan, dan konten belakang sertifikat untuk setiap paket pelatihan.
                </p>
            </div>
        </div>

        <x-card :padding="false" title="Daftar Paket">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">
                                Paket
                            </th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">
                                Jenis
                            </th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">
                                Status Template
                            </th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($packages as $package)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors">
                                <td class="py-3 px-4 sm:px-6 align-top">
                                    <div class="font-semibold text-sm text-gray-900 dark:text-white">
                                        {{ $package->title }}
                                    </div>
                                </td>
                                <td class="py-3 px-4 sm:px-6 align-top">
                                    <span class="text-xs text-gray-600 dark:text-gray-300">
                                        {{ $package->masterType?->name_type ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 sm:px-6 align-top text-center">
                                    @if($package->certificateTemplate)
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                                            Sudah diatur
                                        </span>
                                    @else
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">
                                            Belum diatur
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 sm:px-6 align-top text-right">
                                    <x-button variant="secondary" size="sm"
                                              href="{{ route('certificate-templates.edit', $package) }}"
                                              class="rounded-xl">
                                        <i class="ti ti-pencil mr-1 text-sm"></i>
                                        Kelola Desain
                                    </x-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-16">
                                    <div class="flex flex-col items-center justify-center text-center max-w-[320px] mx-auto">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                            <i class="ti ti-certificate text-2xl text-gray-400"></i>
                                        </div>
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">
                                            Belum ada paket
                                        </h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                            Tambahkan paket terlebih dahulu di menu Master Paket.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
@endsection

