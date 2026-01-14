@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- Header Page --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Overview</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Selamat datang {{ Auth::user()->name }}, berikut statistik platform anda.</p>
        </div>
      
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
       @php
            $stats = [
                [
                    'label' => 'Total Paket',
                    'value' => $data['package'],
                    'trend' => '+8.3%',
                    'icon'  => 'ti ti-book'
                ],
                [
                    'label' => 'Total Jenis Soal',
                    'value' => $data['types'],
                    'trend' => '+2.1%',
                    'icon'  => 'ti ti-bleach-no-chlorine'
                ],
                [
                    'label' => 'Total Materi',
                    'value' => $data['material'],
                    'trend' => '+12.7%',
                    'icon'  => 'ti ti-checklist'
                ],
                [
                    'label' => 'Total User',
                    'value' => $data['user'],
                    'trend' => '+5.4%',
                    'icon'  => 'users'
                ],
            ];
        @endphp


        @foreach($stats as $stat)
        <x-card :padding="true">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-600">
                    <i class="ti ti-{{ $stat['icon'] }} text-xl"></i>
                </div>
                <span @class([
                    'text-xs font-bold px-2 py-1 rounded-lg',
                    'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10' => str_contains($stat['trend'], '+'),
                    'bg-rose-50 text-rose-600 dark:bg-rose-500/10' => str_contains($stat['trend'], '-'),
                ])>
                    {{ $stat['trend'] }}
                </span>
            </div>
            <p class="text-sm font-semibold text-gray-400">{{ $stat['label'] }}</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stat['value'] }}</h3>
        </x-card>
        @endforeach
    </div>

    {{-- Charts & Activity Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Chart Analytics Card --}}
        <x-card class="lg:col-span-2">
            <x-slot:header>
                <div class="flex items-center justify-between w-full">
                    <h3 class="font-bold text-gray-900 dark:text-white">Revenue Analytics</h3>
                    <select class="text-xs border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900 rounded-lg focus:ring-indigo-500 dark:text-gray-300 outline-none px-2 py-1">
                        <option>Last 7 Days</option>
                        <option>Last 30 Days</option>
                    </select>
                </div>
            </x-slot:header>

            <div class="h-[320px] flex items-center justify-center text-gray-400 border-2 border-dashed border-gray-100 dark:border-gray-800 rounded-2xl bg-gray-50/50 dark:bg-gray-900/20">
                <div class="text-center">
                    <i class="ti ti-chart-area-line text-4xl mb-2 opacity-20"></i>
                    <p class="text-sm font-medium">Chart Visualization Placeholder</p>
                </div>
            </div>
        </x-card>

        {{-- Recent Customers Card --}}
        <x-card title="Recent Student">
            <div class="space-y-6">
                @for($i = 1; $i <= 5; $i++)
                <div class="flex items-center gap-4">
                    <x-avatar name="User {{ $i }}" size="sm" />
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-900 dark:text-white truncate">Student #{{ $i }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">student.{{ $i }}@example.com</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900 dark:text-white">88.20</p>
                        <p class="text-[10px] text-emerald-500 font-bold">LULUS</p>
                    </div>
                </div>
                @endfor
            </div>

          
        </x-card>

    </div>
</div>
@endsection