@extends('layouts.app')

@section('title', $package->title)

@section('content')
<div class="space-y-8 pb-20">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('user.packages.index') }}" class="hover:text-indigo-600 transition-colors">Package</a>
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
                    <p class="text-2xl font-bold text-indigo-600">{{ $package->materials->count() }}</p>
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

            {{-- Action Button --}}
            <div class="pt-4 border-t border-gray-100 dark:border-gray-800">
                @if($isJoined)
                    <x-button variant="secondary" href="{{ route('user.my-packages.show', $package) }}" class="w-full md:w-auto rounded-xl">
                        <i class="ti ti-eye mr-2"></i> Lihat Package Saya
                    </x-button>
                @else
                    <form action="{{ route('user.packages.join', $package) }}" method="POST" class="inline">
                        @csrf
                        <x-button variant="primary" type="submit" class="w-full md:w-auto rounded-xl shadow-lg shadow-indigo-500/20">
                            <i class="ti ti-plus mr-2"></i> Daftar Paket Sekarang
                        </x-button>
                    </form>
                @endif
            </div>
        </div>
    </x-card>

    {{-- Materials Preview (dikelompokkan per Mata Pelajaran) --}}
    @if($subjects->sum(fn($s) => $s->materials->count()) > 0)
        <x-card title="Materi dalam Package">
            <div class="space-y-8">
                @foreach($subjects as $subject)
                    @if($subject->materials->count() > 0)
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2 mb-3 pb-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400">
                                    <i class="ti ti-book text-xs"></i>
                                </span>
                                {{ $subject->name }}
                                @if($subject->code)
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">({{ $subject->code }})</span>
                                @endif
                            </h3>
                            <div class="space-y-3 pl-0 md:pl-5 border-l-0 md:border-l-2 border-indigo-100 dark:border-indigo-900/50">
                                @foreach($subject->materials as $material)
                                    <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-800">
                                        <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-500/10 rounded-lg flex items-center justify-center shrink-0">
                                            @if($material->materi_type == 'File')
                                                <i class="{{ $material->file_icon }} text-lg {{ $material->file_type === 'pdf' ? 'text-rose-600' : 'text-blue-600' }}"></i>
                                            @else
                                                <i class="ti ti-video text-lg text-indigo-600"></i>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-900 dark:text-white truncate">{{ $material->title }}</h4>
                                            @if($material->topic)
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $material->topic }}</p>
                                            @endif
                                        </div>
                                        @if($material->value)
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $material->file_size_formatted }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </x-card>
    @endif
</div>
@endsection











