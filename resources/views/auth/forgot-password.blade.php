@extends('layouts.auth')

@section('title', 'Lupa Password - InvestaLearning')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center p-6 bg-gray-50 dark:bg-gray-950 relative" x-data>
    <div class="absolute top-8 right-8">
        <button 
            @click="$store.theme.toggle()" 
            class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 hover:text-indigo-600 transition-all shadow-sm active:scale-95"
        >
            <i class="ti text-xl" :class="$store.theme.theme === 'light' ? 'ti-moon' : 'ti-sun'"></i>
        </button>
    </div>

    <div class="mb-8 flex flex-col items-center gap-4">
        <a href="/">
            <div class="w-16 h-16 p-2 bg-slate-100 dark:bg-slate-700 rounded-[2rem] flex items-center justify-center text-white shadow-xl shadow-indigo-500/20 rotate-3 hover:rotate-0 transition-transform duration-500">
                <img src="{{ asset('img/favicon.png') }}" alt="">
            </div>
        </a>
        <h1 class="text-xl font-bold text-gray-900 dark:text-white ">
            Investalearning <span class="text-indigo-600">Forgot Password</span>
        </h1>
    </div>

    <div class="w-full max-w-md">
        <x-card class="shadow-sm border-gray-200 dark:border-gray-800">
            <div class="space-y-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Lupa Password</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">
                        Masukkan email akun Anda. Kami akan mengirimkan link untuk mengatur ulang password.
                    </p>
                </div>

                @if (session('status'))
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <x-input 
                        label="Email" 
                        name="email" 
                        type="email" 
                        icon="mail"
                        placeholder="yourname@example.com" 
                        required 
                        autofocus
                        :value="old('email')"
                    />

                    <div class="space-y-4 pt-2">
                        <x-button type="submit" variant="primary" class="w-full shadow-indigo-500/20">
                            Kirim Link Reset Password
                        </x-button>

                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-indigo-600 font-medium transition-colors">
                                Kembali ke <span class="font-semibold underline underline-offset-4">Halaman Login</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </x-card>

        <p class="text-center mt-8 text-xs text-gray-400 font-semibold uppercase tracking-widest opacity-60">
            &copy; {{ date('Y') }} InvestaLearning. All rights reserved.
        </p>
    </div>
</div>
@endsection
