@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="space-y-8 pb-20" x-data="{ activeTab: 'personal' }">
    
    {{-- Header Section (Cover) --}}
    <div class="relative h-48 md:h-60 rounded-[2.5rem] overflow-hidden bg-indigo-600">
        <div class="absolute inset-0 opacity-10">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"></path>
            </svg>
        </div>
       
    </div>

    {{-- Profile Header Card --}}
    <div class="-mt-20 relative px-4 sm:px-8">
        <div class="flex flex-col md:flex-row items-end gap-6">
            <div class="relative group">
                <x-avatar 
                    :src="'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6366f1&color=fff'" 
                    size="xl" 
                    shape="3xl" 
                />
            </div>
            
            <div class="flex-1 mb-2">
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                </div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                    {{ $user->email }}
                </p>
            </div>

           
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 mt-6">
        

        {{-- Sisi Kanan: Form Pengaturan --}}
        <div class="lg:col-span-8">
            <x-card :padding="false">
                {{-- Tab Navigation --}}
                <div class="flex items-center gap-6 px-8 border-b border-gray-100 dark:border-gray-800">
                    <button @click="activeTab = 'personal'" 
                        :class="activeTab === 'personal' ? 'text-indigo-600 border-b-2 border-indigo-600 py-5' : 'text-gray-400 py-5'"
                        class="text-sm font-bold transition-all">
                        Data Diri
                    </button>
                    <button @click="activeTab = 'security'" 
                        :class="activeTab === 'security' ? 'text-indigo-600 border-b-2 border-indigo-600 py-5' : 'text-gray-400 py-5'"
                        class="text-sm font-bold transition-all">
                        Keamanan
                    </button>
                </div>

                {{-- Tab Content: Data Diri (editable) --}}
                <div x-show="activeTab === 'personal'" class="p-8 " x-transition>
                    @include('profile.partials.update-profile-information-form')
                </div>

                {{-- Tab Content: Keamanan (ganti password + hapus akun) --}}
                <div x-show="activeTab === 'security'" class="p-8 space-y-8" x-transition x-cloak>
                    @include('profile.partials.update-password-form')
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection