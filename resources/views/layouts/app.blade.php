<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <meta name="theme-color" content="#4f46e5">
    <meta name="color-scheme" content="light dark">

    <title>{{ $title ?? 'Dashboard' }} | InvestaLearning</title>
    <meta name="description" content="{{ $description ?? 'Platform belajar investasi dan sertifikasi pasar modal terpercaya.' }}">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('img/favicon.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    {{-- Preconnect untuk resource eksternal --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    {{-- Fonts: display=swap untuk performa --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Icons: preload untuk LCP --}}
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.35.0/tabler-icons.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.35.0/tabler-icons.min.css"></noscript>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Mencegah FOUC (Flash of Unstyled Content) */
        [x-cloak] { display: none !important; }

        /* Custom scrollbar untuk sidebar */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Fokus visible untuk aksesibilitas keyboard */
        :focus-visible {
            outline: 2px solid #4f46e5;
            outline-offset: 2px;
            border-radius: 4px;
        }

        /* Skip to main content link */
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

    {{-- Theme Initializer: Mencegah flicker --}}
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

            Alpine.store('sidebar', {
                isExpanded: window.innerWidth >= 1280,
                isMobileOpen: false,
                isHovered: false,
                toggleExpanded() { this.isExpanded = !this.isExpanded; },
                toggleMobileOpen() { this.isMobileOpen = !this.isMobileOpen; },
                setHovered(val) {
                    if (window.innerWidth >= 1280 && !this.isExpanded) this.isHovered = val;
                }
            });
        });
    </script>
</head>

<body
    class="h-full bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-300 antialiased"
    x-data
    x-init="@if(request()->routeIs('monitor-participants.*')) $store.sidebar.isExpanded = false @endif"
    @resize.window="if (window.innerWidth < 1280) { $store.sidebar.isExpanded = false; }">

    {{-- Skip to main content (Aksesibilitas) --}}
    <a href="#main-content" class="skip-link">Langsung ke konten utama</a>

    <div class="min-h-screen flex">

        {{-- Mobile Overlay --}}
        <div
            x-show="$store.sidebar.isMobileOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="$store.sidebar.toggleMobileOpen()"
            class="fixed inset-0 z-[55] bg-gray-900/50 backdrop-blur-sm xl:hidden"
            role="presentation"
            aria-hidden="true">
        </div>

        <x-sidebar />

        {{-- Main Area --}}
        <div
            class="flex-1 flex flex-col min-h-screen transition-all duration-300 ease-in-out"
            :class="{
                'xl:ml-[290px]': $store.sidebar.isExpanded || $store.sidebar.isHovered,
                'xl:ml-[80px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
            }">

            <x-header />

            <main id="main-content" class="flex-1 p-4 md:p-8" tabindex="-1">
                <div class="max-w-screen-2xl mx-auto">
                    @yield('content')
                </div>
            </main>

            <footer class="p-6 text-center" role="contentinfo">
                <p class="text-xs text-gray-500">
                    &copy; {{ date('Y') }}
                    <span class="font-bold">InvestaLearning</span>.
                    Hak cipta dilindungi.
                </p>
            </footer>
        </div>
    </div>

    <x-toast />

    @stack('scripts')
</body>

</html>
