@extends('layouts.app')

@section('title', 'Bank Question')

@section('content')
    <div class="space-y-8 pb-20" x-data="{
        deleteModalOpen: false,
        deleteUrl: '',
        questionTitle: '',
        importModalOpen: false,
        confirmDelete(url, title) {
            this.deleteUrl = url;
            this.questionTitle = title;
            this.deleteModalOpen = true;
        }
    }">
        <nav class="flex items-center gap-2 text-xs font-medium text-gray-400">
            <a href="{{ route('user.history-exams.index') }}"
                class="hover:text-indigo-600 transition-colors text-gray-500">Paket
                Diikuti</a>
            <i class="ti ti-chevron-right"></i>
        </nav>


        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Daftar Paket yang anda ikuti
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Pilih salah satu paket untu melihat hasil
                    nilai
                </p>
            </div>
        </div>
        {{-- Filter --}}



        {{-- Main Data Card --}}
        <x-card :padding="false" title="Daftar Paket">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Paket</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">
                                Jenis Paket</th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Deskripsi
                            </th>
                            <th class="py-4 px-8 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Aksi
                            </th>


                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse ($packageApprove as $data)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                                <td class="py-4 px-8">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                        {{ $data->package->title ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-8 text-center">
                                    {{ $data->package->masterType->name_type ?? '-' }}
                                </td>
                                <td class="py-4 px-8">
                                    {{ $data->package->description ?? '-' }}
                                </td>
                                <td class="py-4 px-8 text-center">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-button variant="primary" size="sm"
                                            href="{{ route('user.history-exams.show', $data) }}"
                                            class="rounded-lg h-9 w-9 p-0 flex items-center justify-center"
                                            title="Lihat pembahasan">
                                            <i class="ti ti-eye text-base"></i>
                                        </x-button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-24">
                                    <div
                                        class="flex flex-col items-center justify-center text-center max-w-[280px] mx-auto">
                                        <div class="space-y-1">

                                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                                Belum ada paket yang anda ikuti, silahkan ikuti paket untuk melihat hasil
                                                nilai anda
                                            </p>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($packageApprove->hasPages())
                <div class="px-8 py-6 border-t border-gray-50 dark:border-gray-800">
                    {{ $packageApprove->links() }}
                </div>
            @endif
        </x-card>

    </div>
@endsection
