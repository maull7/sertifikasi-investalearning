@extends('layouts.app')

@section('title', 'Pengajar')

@section('content')
    <div class="space-y-8 pb-20"
        x-data="{ 
        deleteModalOpen: false,
        deleteUrl: '',
        subjectName: '' }">
        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Pengajar</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                    Kelola data pengajar yang terdaftar di sistem.
                </p>
            </div>

            <x-button variant="primary" href="{{ route('teacher.create') }}" class="rounded-xl shadow-lg shadow-indigo-500/20">
                <i class="ti ti-plus mr-2"></i> Tambah Pengajar
            </x-button>
        </div>

        {{-- Main Data Card --}}
        <x-card :padding="false" title="Daftar Pengajar">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider whitespace-nowrap">
                                Pengajar
                            </th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider whitespace-nowrap">
                                NIP
                            </th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider whitespace-nowrap">
                                Email
                            </th>
                            <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right whitespace-nowrap">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($teachers as $teacher)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors">
                                <td class="py-3 px-4 sm:px-6">
                                    <span class="font-semibold text-sm text-gray-900 dark:text-white">
                                        {{ $teacher->name }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 sm:px-6">
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        {{ $teacher->nip ?: '-' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 sm:px-6">
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        {{ $teacher->email ?: '-' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 sm:px-6">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-button variant="secondary" size="sm" href="{{ route('teacher.edit', $teacher) }}"
                                            class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                            <i class="ti ti-pencil text-base"></i>
                                        </x-button>

                                        
                                           
                                            <x-button
                                                @click="deleteModalOpen = true; deleteUrl='{{ route('teacher.destroy', $teacher->id) }}'; subjectName='{{ $teacher->name }}';"
                                                variant="danger" size="sm"
                                                class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                                <i class="ti ti-trash text-base"></i>
                                            </x-button>
                                        
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-24">
                                    <div class="flex flex-col items-center justify-center text-center max-w-[280px] mx-auto">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                            <i class="ti ti-chalkboard text-2xl text-gray-400"></i>
                                        </div>
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                                            Belum ada pengajar
                                        </h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Tambahkan pengajar untuk mulai mengelola kelas dan ujian.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($teachers->hasPages())
                <div class="px-4 sm:px-6 lg:px-8 py-6 border-t border-gray-50 dark:border-gray-800">
                    {{ $teachers->links() }}
                </div>
            @endif
        </x-card>
        <div x-show="deleteModalOpen" 
           class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
           x-cloak
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="opacity-0 scale-95"
           x-transition:enter-end="opacity-100 scale-100"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="opacity-100 scale-100"
           x-transition:leave-end="opacity-0 scale-95">
           
           <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800"
               @click.away="deleteModalOpen = false">
               <div class="p-8 text-center">
                   <div class="w-20 h-20 bg-rose-50 dark:bg-rose-500/10 text-rose-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                       <i class="ti ti-trash-x text-4xl"></i>
                   </div>
                   <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Hapus Data Pengajar ?</h3>
                   <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                       Anda akan menghapus data pengajar <span class="font-bold text-gray-900 dark:text-white" x-text="subjectName"></span>. Tindakan ini tidak dapat dibatalkan.
                   </p>
               </div>
               
               <div class="flex gap-3 p-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800">
                   <x-button variant="secondary" class="flex-1 rounded-xl" @click="deleteModalOpen = false">
                       Batal
                   </x-button>
                   <form :action="deleteUrl" method="POST" class="flex-1">
                       @csrf
                       @method('DELETE')
                       <x-button variant="danger" type="submit" class="w-full rounded-xl shadow-lg shadow-rose-500/20">
                           Ya, Hapus
                       </x-button>
                   </form>
               </div>
           </div>
       </div>
    </div>
@endsection



