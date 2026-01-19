@extends('layouts.auth')

@section('title', 'Register - User InvestaLearning')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center p-6 bg-gray-50 dark:bg-gray-950 relative" x-data>
    
    {{-- THEME TOGGLE --}}
    <div class="absolute top-8 right-8">
        <button 
            @click="$store.theme.toggle()" 
            class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 hover:text-indigo-600 transition-all shadow-sm active:scale-95"
        >
            <i class="ti text-xl" :class="$store.theme.theme === 'light' ? 'ti-moon' : 'ti-sun'"></i>
        </button>
    </div>

    {{-- BRANDING --}}
    <div class="mb-8 flex flex-col items-center gap-4">
        <a href="/">
            <div class="w-16 h-16 bg-indigo-600 rounded-[2rem] flex items-center justify-center text-white shadow-xl shadow-indigo-500/20 rotate-3 hover:rotate-0 transition-transform duration-500">
                <i class="ti ti-brand-laravel text-4xl"></i>
            </div>
        </a>
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">
            Investalearning <span class="text-indigo-600">Registrasi</span>
        </h1>
    </div>

    <div class="w-full max-w-4xl">
        <x-card class="shadow-sm border-gray-200 dark:border-gray-800">
            <div class="space-y-6">
                
                {{-- HEADER --}}
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Buat Akun Baru</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Mulai perjalanan Anda bersama kami hari ini.</p>
                </div>

                <form action="{{ route('register') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- ROW 1: Nama & Email --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-input 
                            label="Nama Lengkap" 
                            name="name" 
                            type="text" 
                            icon="user"
                            placeholder="John Doe" 
                            required 
                            autofocus
                        />

                        <x-input 
                            label="Email" 
                            name="email" 
                            type="email" 
                            icon="mail"
                            placeholder="yourname@example.com" 
                            required 
                        />
                    </div>

                    {{-- ROW 2: Phone & Jenis Kelamin --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-input 
                            label="Nomor Telepon" 
                            name="phone" 
                            type="text" 
                            icon="phone"
                            placeholder="081234567890" 
                            required
                        />

                        
                        <div>
                            <x-select 
                                label="Pilih Jenis Kelamin" 
                                name="jenis_kelamin" 
                                required
                            >
                                <option value="Laki-laki" {{ old('jenis_kelamin') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </x-select>
                            
                            @error('jenis_kelamin')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- ROW 3: Profesi & Tanggal Lahir --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-input 
                            label="Profesi" 
                            name="profesi" 
                            type="text" 
                            icon="briefcase"
                            placeholder="Guru, Siswa, Profesional, dll" 
                        />

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Tanggal Lahir
                            </label>
                            <input 
                                type="date" 
                                name="tanggal_lahir" 
                                value="{{ old('tanggal_lahir') }}"
                                class="w-full rounded-2xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-4 py-3"
                            />
                            @error('tanggal_lahir')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- ROW 4: Institusi & Alamat --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-input 
                            label="Institusi" 
                            name="institusi" 
                            type="text" 
                            icon="building"
                            placeholder="Nama sekolah / kampus / instansi" 
                        />

                        <div>
                            <x-textarea
                                label="Alamat" 
                                name="alamat" 
                                placeholder="Alamat lengkap" 
                                rows="3"
                                required
                            >
                                {{old('alamat')}}
                            </x-textarea>
                          
                        </div>
                    </div>

                    {{-- ROW 5: Password & Konfirmasi Password --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-input 
                            label="Password" 
                            name="password" 
                            type="password" 
                            icon="lock"
                            placeholder="••••••••" 
                            required 
                        />

                        <x-input 
                            label="Konfirmasi Password" 
                            name="password_confirmation" 
                            type="password" 
                            icon="lock-check"
                            placeholder="••••••••" 
                            required 
                        />
                    </div>

                  

                    {{-- ACTION BUTTONS --}}
                    <div class="space-y-4 pt-2">
                        <x-button type="submit" variant="primary" class="w-full shadow-indigo-500/20">
                            Daftar Sekarang
                        </x-button>

                        <div class="text-center">
                            <a href="{{route('login')}}" class="text-sm text-gray-500 hover:text-indigo-600 font-medium transition-colors">
                                Sudah punya akun? <span class="font-semibold underline underline-offset-4">Masuk di sini</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </x-card>

        {{-- FOOTER --}}
        <p class="text-center mt-8 text-xs text-gray-400 font-semibold uppercase tracking-widest opacity-60">
            &copy; {{ date('Y') }} InvestaLearning. All rights reserved.
        </p>
    </div>
</div>
@endsection