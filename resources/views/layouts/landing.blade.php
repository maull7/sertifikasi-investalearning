<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <meta name="theme-color" content="#4f46e5">
    <meta name="color-scheme" content="light dark">

    <title>{{ $title ?? '' }} | InvestaLearning</title>
    <meta name="description" content="{{ $description ?? 'Platform belajar investasi dan sertifikasi pasar modal terpercaya.' }}">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('img/favicon.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    {{-- Preconnect --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;500;600;700;800;1,400&display=swap" rel="stylesheet">

    {{-- Icons: non-blocking load --}}
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.35.0/tabler-icons.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.35.0/tabler-icons.min.css"></noscript>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }

        :focus-visible {
            outline: 2px solid #4f46e5;
            outline-offset: 2px;
            border-radius: 4px;
        }

        .skip-link {
            position: absolute;
            top: -100%;
            left: 0;
            z-index: 9999;
            padding: 0.5rem 1rem;
            background: #4f46e5;
            color: white;
            font-weight: 600;
            text-decoration: none;
            border-radius: 0 0 4px 0;
            transition: top 0.1s;
        }
        .skip-link:focus { top: 0; }
    </style>

    {{-- Theme Initializer --}}
    <script>
        (function() {
            var t = localStorage.getItem('theme') || 'light';
            document.documentElement.classList.toggle('dark', t === 'dark');
        })();
    </script>

    {{-- Alpine Stores --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                theme: localStorage.getItem('theme') || 'light',
                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    document.documentElement.classList.toggle('dark', this.theme === 'dark');
                }
            });
        });
    </script>
</head>

<body
    class="h-full text-gray-900 dark:text-gray-100 transition-colors duration-300 antialiased font-sans"
    style="font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;">

    {{-- Skip to main content --}}
    <a href="#main-content" class="skip-link">Langsung ke konten utama</a>

    {{-- Background dekoratif (aria-hidden agar tidak dibaca screen reader) --}}
    <div class="fixed inset-0 -z-10 bg-[#f8fafc] dark:bg-gray-950" aria-hidden="true">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/60 via-transparent to-cyan-50/40 dark:from-indigo-950/20 dark:via-transparent dark:to-cyan-950/10"></div>
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#e2e8f0_0.5px,transparent_0.5px),linear-gradient(to_bottom,#e2e8f0_0.5px,transparent_0.5px)] dark:bg-[linear-gradient(to_right,#1e293b_0.5px,transparent_0.5px),linear-gradient(to_bottom,#1e293b_0.5px,transparent_0.5px)] bg-[size:24px_24px] opacity-50 dark:opacity-30"></div>
    </div>

    <div class="min-h-screen">
        <header class="sticky top-0 z-30 border-b border-gray-200/80 dark:border-gray-800/80 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl" role="banner">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 md:h-18">
                    <a
                        href="{{ route('user.landing') }}"
                        class="flex items-center gap-2.5 text-gray-900 dark:text-white font-bold text-lg tracking-tight hover:opacity-90 transition-opacity"
                        aria-label="InvestaLearning - Halaman Utama">
                        <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-cyan-500 text-white shadow-lg shadow-indigo-500/25" aria-hidden="true">
                            <i class="ti ti-school text-xl"></i>
                        </span>
                        <span>InvestaLearning</span>
                    </a>
                </div>
            </div>
        </header>

        <main id="main-content" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-6 pb-20" tabindex="-1">
            @yield('content')
        </main>
    </div>

    <x-toast />
    @stack('scripts')
</body>

</html>
