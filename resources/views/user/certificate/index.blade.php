@extends('layouts.app')

@section('title', 'My Sertifikat')

@section('content')
    <div class="space-y-6 pb-20">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Sertifikat</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">Data Sertifikat Anda</p>
            </div>

        </div>

        <x-card :padding="false" title="Daftar Sertifikat Anda">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider whitespace-nowrap">Peserta</th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider whitespace-nowrap">Jenis</th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider whitespace-nowrap">Paket</th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider whitespace-nowrap">Dibuat</th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($certificates as $certificate)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors">
                                <td class="py-3 px-4 sm:px-6">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-sm text-gray-900 dark:text-white">
                                            {{ $certificate->user?->name ?? '-' }}
                                        </span>
                                        <span class="text-xs text-gray-400">
                                            {{ $certificate->user?->email ?? '' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 sm:px-6">
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        {{ $certificate->type?->name_type ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 sm:px-6">
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        {{ $certificate->package?->title ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 sm:px-6">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $certificate->created_at?->format('d M Y H:i') }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 sm:px-6 text-center">
                                    <x-button variant="primary" size="sm" href="{{ route('user.certificate.show', $certificate) }}"
                                        class="rounded-lg h-9 w-9 p-0 inline-flex items-center justify-center">
                                        <i class="ti ti-eye text-base"></i>
                                    </x-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-24">
                                    <div class="flex flex-col items-center justify-center text-center max-w-[320px] mx-auto">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                            <i class="ti ti-certificate text-2xl text-gray-400"></i>
                                        </div>
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                                            Belum ada sertifikat
                                        </h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Buat sertifikat pertama untuk peserta.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($certificates->hasPages())
                <div class="px-4 sm:px-6 lg:px-8 py-6 border-t border-gray-50 dark:border-gray-800">
                    {{ $certificates->links() }}
                </div>
            @endif
        </x-card>
    </div>
@endsection



