@extends('layouts.auth')

@section('title', 'Login - InvestaLearning')

@section('content')
    <div class="min-h-screen flex flex-col justify-center items-center p-6 bg-gray-50 dark:bg-gray-950 relative" x-data>

        <div class="absolute top-8 right-8">
            <button @click="$store.theme.toggle()"
                class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 hover:text-indigo-600 transition-all shadow-sm active:scale-95">
                <i class="ti text-xl" :class="$store.theme.theme === 'light' ? 'ti-moon' : 'ti-sun'"></i>
            </button>
        </div>

        <div class="mb-8 flex flex-col items-center gap-4">
            <a href="/">
                <div
                    class="w-16 h-16 p-2 bg-slate-100 dark:bg-slate-700 rounded-[2rem] flex items-center justify-center text-white shadow-xl shadow-indigo-500/20 rotate-3 hover:rotate-0 transition-transform duration-500">
                    <img src="{{ asset('img/favicon.png') }}" alt="">
                </div>
            </a>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white ">
                Investalearning <span class="text-indigo-600">Authentication</span>
            </h1>
        </div>

        <div class="w-full max-w-md">
            <x-card class="shadow-sm border-gray-200 dark:border-gray-800">
                <div class="space-y-6">

                    <div class="text-center">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Selamat Datang Kembali
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Silakan masuk untuk mengakses akun
                            Anda.</p>
                        <div class="mt-4 bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg">
                            <p class="text-xs text-yellow-600 dark:text-yellow-400 font-medium">
                                Jika tidak bisa login menggunakan akun Google, pastikan email Google Anda sudah terdaftar di
                                sistem kami, lalu coba login dengan memasukan email dan password yang terdaftar.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('login') }}" method="POST" class="space-y-6">
                        @csrf

                        <x-input label="Email" name="email" type="email" icon="mail"
                            placeholder="yourname@example.com" required autofocus />

                        <div class="space-y-1">
                            <x-input label="Password" name="password" type="password" icon="lock" placeholder="••••••••"
                                required />
                            <div class="flex justify-end">
                                <a href="{{ route('password.request') }}"
                                    class="text-[11px] font-semibold text-indigo-600 hover:text-indigo-700 uppercase tracking-widest transition-colors">Lupa
                                    Password?</a>
                            </div>
                        </div>

                        <div class="space-y-4 pt-2">
                            {{-- reCAPTCHA (production, bila enabled dan key terisi) --}}
                            @if (
                                !app()->environment('local') &&
                                    config('services.recaptcha.enabled', true) &&
                                    config('services.recaptcha.site_key') &&
                                    config('services.recaptcha.secret_key'))
                                <div>
                                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}">
                                    </div>
                                    @error('g-recaptcha-response')
                                        <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            <x-button type="submit" variant="primary" class="w-full shadow-indigo-500/20">
                                Log in
                            </x-button>

                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span
                                        class="bg-white dark:bg-gray-800 px-2 text-gray-500 dark:text-gray-400">atau</span>
                                </div>
                            </div>

                            <a href="{{ route('login.google') }}"
                                class="flex items-center justify-center gap-2 w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                <svg class="h-5 w-5" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                    <path fill="currentColor"
                                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                    <path fill="currentColor"
                                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                    <path fill="currentColor"
                                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                                </svg>
                                Masuk dengan Google
                            </a>

                            <div class="text-center">
                                <a href="{{ route('register') }}"
                                    class="text-sm text-gray-500 hover:text-indigo-600 font-medium transition-colors">
                                    Belum punya akun? <span class="font-semibold underline underline-offset-4">Daftar
                                        Sekarang</span>
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
    @if (!app()->environment('local') && config('services.recaptcha.enabled', true) && config('services.recaptcha.site_key'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endsection
