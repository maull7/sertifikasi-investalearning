<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? '|' }} InvestaLearning</title>

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;500;600;700;800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.35.0/tabler-icons.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Theme Initializer: Mencegah efek putih sesaat (flicker) --}}
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    {{-- Alpine Stores (tanpa sidebar store) --}}
    <script>
        document.addEventListener('alpine:init', () => {
            // Theme Store (Hanya Light & Dark)
            Alpine.store('theme', {
                theme: localStorage.getItem('theme') || 'light',

                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    this.updateTheme();
                },

                updateTheme() {
                    if (this.theme === 'dark') {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            });
        });
    </script>
</head>

<body
    class="h-full text-gray-900 dark:text-gray-100 transition-colors duration-300 antialiased font-sans"
    style="font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;">
    {{-- Background: subtle gradient + grid --}}
    <div class="fixed inset-0 -z-10 bg-[#f8fafc] dark:bg-gray-950">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/60 via-transparent to-cyan-50/40 dark:from-indigo-950/20 dark:via-transparent dark:to-cyan-950/10"></div>
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#e2e8f0_0.5px,transparent_0.5px),linear-gradient(to_bottom,#e2e8f0_0.5px,transparent_0.5px)] dark:bg-[linear-gradient(to_right,#1e293b_0.5px,transparent_0.5px),linear-gradient(to_bottom,#1e293b_0.5px,transparent_0.5px)] bg-[size:24px_24px] opacity-50 dark:opacity-30"></div>
    </div>

    <div class="min-h-screen">
        {{-- Header minimal: logo + theme toggle (no dashboard menu) --}}
        <header class="sticky top-0 z-30 border-b border-gray-200/80 dark:border-gray-800/80 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 md:h-18">
                    <a href="{{ route('user.landing') }}" class="flex items-center gap-2.5 text-gray-900 dark:text-white font-bold text-lg tracking-tight hover:opacity-90 transition-opacity">
                        <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-cyan-500 text-white shadow-lg shadow-indigo-500/25">
                            <i class="ti ti-school text-xl"></i>
                        </span>
                        <span>InvestaLearning</span>
                    </a>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-6 pb-20">
            @yield('content')
        </main>
    </div>

    <x-toast />
    @stack('scripts')
</body>

</html>
