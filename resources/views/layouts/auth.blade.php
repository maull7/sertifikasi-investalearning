<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <meta name="theme-color" content="#4f46e5">
    <meta name="color-scheme" content="light dark">
    <meta name="robots" content="noindex, nofollow">

    <title>@yield('title', 'Masuk') | InvestaLearning</title>
    <meta name="description" content="@yield('meta_description', 'Masuk ke akun InvestaLearning Anda.')">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('img/favicon.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    {{-- Preconnect --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Icons: non-blocking --}}
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
</head>

<body class="h-full bg-white dark:bg-gray-950 antialiased selection:bg-indigo-100 dark:selection:bg-indigo-900/30">

    <a href="#main-content" class="skip-link">Langsung ke konten utama</a>

    <main id="main-content" tabindex="-1">
        @yield('content')
    </main>

    <x-toast />

    @stack('scripts')
</body>
</html>
