<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kelas Saham & Investasi | Investalearning</title>
    <meta name="description"
        content="Belajar investasi saham dari dasar hingga siap sertifikasi. Kelas Pasar Modal & MSDM. Materi lengkap, ujian, dan sertifikat diakui.">
    <meta name="referrer" content="strict-origin-when-cross-origin" />
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    screens: {
                        'xs': '375px',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        @keyframes floatSlow {

            0%,
            100% {
                transform: translateY(0px) translateX(0px);
            }

            50% {
                transform: translateY(-30px) translateX(10px);
            }
        }

        @keyframes pulse-glow {

            0%,
            100% {
                opacity: 0.3;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(1.1);
            }
        }

        @keyframes slide-up {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @keyframes bounce-slow {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-15px) scale(1.05);
            }
        }

        @keyframes wa-float {

            0%,
            100% {
                transform: translateY(0) scale(1);
                box-shadow: 0 10px 40px -10px rgba(37, 211, 102, 0.5);
            }

            50% {
                transform: translateY(-8px) scale(1.05);
                box-shadow: 0 20px 50px -10px rgba(37, 211, 102, 0.6);
            }
        }

        .animate-wa-float {
            animation: wa-float 2.5s ease-in-out infinite;
        }

        @keyframes morph {

            0%,
            100% {
                border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            }

            25% {
                border-radius: 58% 42% 75% 25% / 76% 46% 54% 24%;
            }

            50% {
                border-radius: 50% 50% 33% 67% / 55% 27% 73% 45%;
            }

            75% {
                border-radius: 33% 67% 58% 42% / 63% 68% 32% 37%;
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-float-slow {
            animation: floatSlow 8s ease-in-out infinite;
        }

        .animate-pulse-glow {
            animation: pulse-glow 3s ease-in-out infinite;
        }

        .animate-slide-up {
            animation: slide-up 0.6s ease-out forwards;
        }

        .animate-rotate-slow {
            animation: rotate 20s linear infinite;
        }

        .animate-bounce-slow {
            animation: bounce-slow 4s ease-in-out infinite;
        }

        .animate-morph {
            animation: morph 10s ease-in-out infinite;
        }

        .shape-blob {
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            background: linear-gradient(45deg, #4f46e5, #6366f1);
            filter: blur(40px);
            opacity: 0.3;
        }

        .shape-circle {
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            filter: blur(60px);
            opacity: 0.2;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .gradient-text {
            background: linear-gradient(135deg, #3730a3 0%, #4f46e5 50%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        }

        .gradient-mesh {
            background: radial-gradient(ellipse 80% 50% at 50% -20%, rgba(79, 70, 229, 0.12), transparent), radial-gradient(ellipse 60% 40% at 100% 0%, rgba(99, 102, 241, 0.06), transparent);
        }

        .shape-triangle {
            width: 0;
            height: 0;
            border-left: 50px solid transparent;
            border-right: 50px solid transparent;
            border-bottom: 87px solid rgba(79, 70, 229, 0.2);
            filter: blur(2px);
        }

        .shape-square {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, rgba(79, 70, 229, 0.2), rgba(99, 102, 241, 0.2));
            filter: blur(2px);
        }

        .shape-hexagon {
            width: 80px;
            height: 46px;
            background: rgba(79, 70, 229, 0.2);
            position: relative;
            filter: blur(2px);
        }

        .shape-hexagon:before,
        .shape-hexagon:after {
            content: "";
            position: absolute;
            width: 0;
            border-left: 40px solid transparent;
            border-right: 40px solid transparent;
        }

        .shape-hexagon:before {
            bottom: 100%;
            border-bottom: 23px solid rgba(79, 70, 229, 0.2);
        }

        .shape-hexagon:after {
            top: 100%;
            border-top: 23px solid rgba(79, 70, 229, 0.2);
        }

        /* Mobile Responsive Adjustments */
        @media (max-width: 640px) {

            .shape-blob,
            .shape-circle {
                width: 200px !important;
                height: 200px !important;
                opacity: 0.15;
            }

            .shape-triangle,
            .shape-square,
            .shape-hexagon {
                display: none;
            }

            h1 {
                font-size: 1.75rem !important;
                line-height: 2rem !important;
            }

            h2 {
                font-size: 1.5rem !important;
                line-height: 2rem !important;
            }

            h3 {
                font-size: 1.25rem !important;
            }

            .gradient-text {
                font-size: inherit;
            }
        }

        @media (max-width: 375px) {

            .shape-blob,
            .shape-circle {
                width: 150px !important;
                height: 150px !important;
                opacity: 0.1;
            }

            body {
                font-size: 0.875rem;
            }

            h1 {
                font-size: 1.5rem !important;
                line-height: 1.75rem !important;
            }

            h2 {
                font-size: 1.25rem !important;
                line-height: 1.75rem !important;
            }

            h3 {
                font-size: 1.125rem !important;
            }
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 overflow-x-hidden antialiased">
    <!-- Navbar - minimal & elegan -->
    <nav x-data="{ mobileMenuOpen: false }"
        class="fixed inset-x-0 top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200/80">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 lg:h-18">
                <a href="#home" class="flex items-center gap-2">
                    <img src="{{ asset('img/favicon.png') }}" alt="" class="w-8 h-8 rounded-lg">
                    <span class="text-xl font-bold text-slate-900">Investa<span
                            class="gradient-text">learning</span></span>
                </a>
                <div class="hidden md:flex items-center gap-8">
                    <a href="#home"
                        class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition">Beranda</a>
                    <a href="#courses"
                        class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition">Kelas</a>
                    <a href="#books"
                        class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition">Buku</a>
                    <a href="#video"
                        class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition">Video</a>
                    <a href="#about"
                        class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition">Tentang</a>
                    <a href="#contact"
                        class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition">Kontak</a>
                </div>
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('login') }}"
                        class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition">Masuk</a>
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-600/25 hover:bg-indigo-700 transition">Daftar</a>
                </div>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
        <div x-show="mobileMenuOpen" x-transition
            class="md:hidden border-t border-slate-200 bg-white px-4 py-4 space-y-2">
            <a href="#home" class="block py-2 text-sm font-medium text-slate-600">Beranda</a>
            <a href="#courses" class="block py-2 text-sm font-medium text-slate-600">Kelas</a>
            <a href="#books" class="block py-2 text-sm font-medium text-slate-600">Buku</a>
            <a href="#video" class="block py-2 text-sm font-medium text-slate-600">Video</a>
            <a href="#about" class="block py-2 text-sm font-medium text-slate-600">Tentang</a>
            <a href="#contact" class="block py-2 text-sm font-medium text-slate-600">Kontak</a>
            <a href="{{ route('register') }}"
                class="block w-full text-center py-3 rounded-xl bg-indigo-600 text-white text-sm font-semibold mt-2">Daftar</a>
        </div>
    </nav>
    <!-- Hero - promosi kelas saham -->
    <section id="home" class="relative min-h-[90vh] flex items-center pt-24 pb-20 gradient-mesh">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div class="animate-slide-up">
                    <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wider mb-4">Kelas Investasi &
                        Sertifikasi</p>
                    <h1
                        class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-[1.1] tracking-tight">
                        Kelas Saham<br>
                        <span class="gradient-text">Profesional</span><br>
                        untuk Indonesia
                    </h1>
                    <p class="mt-6 text-lg text-slate-600 max-w-xl leading-relaxed">
                        Belajar investasi dari dasar hingga siap sertifikasi. Pilih kelas, daftar, akses materi lengkap,
                        ujian, dan raih sertifikat yang diakui.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-3.5 text-base font-semibold text-white shadow-xl shadow-indigo-600/25 hover:bg-indigo-700 transition">
                            Daftar & Mulai Belajar
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                        <a href="#courses"
                            class="inline-flex items-center gap-2 rounded-xl border-2 border-slate-200 px-6 py-3.5 text-base font-semibold text-slate-700 hover:border-indigo-300 hover:bg-indigo-50/50 transition">Lihat
                            Kelas</a>
                    </div>
                    <div class="mt-12 flex gap-10 text-slate-500">
                        <div><span class="block text-2xl font-bold text-slate-900">50K+</span><span
                                class="text-sm">Peserta</span></div>
                        <div><span class="block text-2xl font-bold text-slate-900">Kelas</span><span
                                class="text-sm">Pasar Modal & MSDM</span></div>
                        <div><span class="block text-2xl font-bold text-slate-900">Sertifikat</span><span
                                class="text-sm">Terverifikasi</span></div>
                    </div>
                </div>
                <div class="animate-slide-up lg:pl-8" style="animation-delay: 0.15s">
                    <div
                        class="rounded-3xl bg-white/90 backdrop-blur border border-slate-200/80 shadow-2xl shadow-slate-200/50 p-6 sm:p-8">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Pilih jenis kelas
                        </p>
                        <p class="text-slate-900 font-bold text-lg mb-6">Langsung ke daftar kelas sesuai minat Anda</p>
                        <div class="space-y-3">
                            @forelse ($jenis as $item)
                                <a href="#jenis-{{ $item->id }}"
                                    class="flex items-center gap-3 p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-indigo-50 hover:border-indigo-100 transition group">
                                    <span class="w-2 h-2 rounded-full bg-indigo-500 group-hover:bg-indigo-600"></span>
                                    <span
                                        class="font-semibold text-slate-800 group-hover:text-indigo-700">{{ $item->name_type }}</span>
                                    <svg class="w-4 h-4 ml-auto text-slate-400 group-hover:text-indigo-500 group-hover:translate-x-1 transition"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @empty
                                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50"><span
                                        class="font-semibold text-slate-700">Pasar Modal</span></div>
                                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50"><span
                                        class="font-semibold text-slate-700">MSDM</span></div>
                            @endforelse
                        </div>
                        <a href="{{ route('register') }}"
                            class="mt-6 block w-full text-center rounded-xl bg-slate-900 text-white py-3.5 text-sm font-semibold hover:bg-slate-800 transition">Daftar
                            Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Kelas Tersedia -->
    <section id="courses" class="relative py-16 sm:py-20 md:py-24 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16 animate-slide-up">
                <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wider mb-2">Kelas Tersedia</p>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-slate-900 mb-4">
                    Pilih <span class="gradient-text">Kelas</span> Investasi Anda
                </h2>
                <p class="text-slate-600 text-base sm:text-lg max-w-2xl mx-auto">
                    Materi lengkap, ujian, dan sertifikat. Klik jenis kelas di atas untuk langsung ke daftar.
                </p>
            </div>

            @foreach ($jenis as $type)
                @php $paketList = $paketPerJenis[$type->id] ?? collect(); @endphp
                <div class="scroll-mt-28 my-12 md:my-16" id="jenis-{{ $type->id }}">
                    <h3 class="text-xl font-semibold text-slate-500 uppercase tracking-wider mb-6 text-center">
                        {{ $type->name_type }}</h3>

                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($paketList as $item)
                            <div
                                class="group relative overflow-hidden rounded-2xl border border-slate-100 bg-slate-50/30 p-6 sm:p-8 shadow-sm hover:shadow-xl hover:border-indigo-100 transition-all duration-300 animate-slide-up">
                                <div class="space-y-4">
                                    {{-- Header: judul + badge jenis --}}
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1 min-w-0">
                                            <h3
                                                class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                                {{ $item->title }}
                                            </h3>
                                            @if ($item->masterType)
                                                <span
                                                    class="inline-flex items-center mt-2 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                                                    {{ $item->masterType->name_type }}
                                                </span>
                                            @endif
                                        </div>
                                        <div
                                            class="w-12 h-12 shrink-0 rounded-xl bg-indigo-50 flex items-center justify-center group-hover:bg-indigo-100 transition-colors">
                                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>

                                    {{-- Deskripsi --}}
                                    @if ($item->description)
                                        <p class="text-sm text-gray-600 line-clamp-3">
                                            {{ Str::limit(strip_tags($item->description), 120) }}
                                        </p>
                                    @endif

                                    {{-- Info: materi & peserta --}}
                                    <div class="flex items-center gap-4 text-xs text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                </path>
                                            </svg>
                                            {{ $item->materials->count() }} Materi
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                                </path>
                                            </svg>
                                            {{ $item->userJoins->count() }} Peserta
                                        </span>
                                    </div>

                                    {{-- CTA --}}
                                    <div class="pt-2">
                                        <a href="{{ route('register') }}"
                                            class="block w-full text-center rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 hover:bg-indigo-700 transition-colors">
                                            Daftar untuk Akses
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-8 text-gray-500">
                                <p class="text-sm sm:text-base">Belum ada paket untuk jenis ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- section buku --}}
    <section id="books" class="relative py-16 sm:py-20 md:py-24 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16 animate-slide-up">
                <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wider mb-2">Buku Tersedia</p>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-slate-900 mb-4">
                    <span class="gradient-text">Buku</span> Tersedia
                </h2>

            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($books as $item)
                    <div class="group relative rounded-2xl overflow-hidden shadow-lg">

                        {{-- Cover Full --}}
                        <div class="relative h-80">
                            @if ($item->cover_image)
                                <img src="{{ asset('storage/' . $item->cover_image) }}" alt="{{ $item->title }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                                    Tidak ada cover
                                </div>
                            @endif

                            {{-- Overlay --}}
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent 
                        opacity-0 group-hover:opacity-100 transition duration-500 flex items-end">

                                <div
                                    class="p-6 text-white translate-y-6 group-hover:translate-y-0 transition duration-500">

                                    <p class="text-xs uppercase tracking-wider text-indigo-300 mb-2">
                                        {{ $item->author ?? 'Unknown Author' }}
                                    </p>

                                    <h3 class="text-xl font-bold mb-2">
                                        {{ $item->title }}
                                    </h3>

                                    @if ($item->description)
                                        <p class="text-sm text-gray-200 line-clamp-3">
                                            {{ Str::limit(strip_tags($item->description), 120) }}
                                        </p>
                                    @endif

                                </div>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500">
                        Belum ada buku tersedia.
                    </div>
                @endforelse
            </div>
        </div>

        </div>
    </section>

    <section id="about" class="py-16 sm:py-20 md:py-24 bg-slate-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div>
                    <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wider mb-3">Tentang Kami</p>
                    <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-6">
                        Edukasi investasi yang <span class="gradient-text">terpercaya</span>
                    </h2>
                    <p class="text-slate-600 leading-relaxed mb-4">
                        Investalearning hadir untuk membuat literasi finansial dan kelas investasi saham dapat diakses
                        siapa saja. Dari pemula hingga yang ingin siap sertifikasi—materi terstruktur, ujian, dan
                        sertifikat yang diakui.
                    </p>
                    <p class="text-slate-600 leading-relaxed">
                        Platform kami menggabungkan kelas lengkap, latihan, dan penilaian yang memudahkan Anda membangun
                        kompetensi investasi secara bertahap.
                    </p>
                    <div class="mt-10 flex gap-10">
                        <div><span class="block text-2xl font-bold text-slate-900">50K+</span><span
                                class="text-sm text-slate-500">Peserta</span></div>
                        <div><span class="block text-2xl font-bold text-slate-900">Kelas</span><span
                                class="text-sm text-slate-500">Aktif</span></div>
                        <div><span class="block text-2xl font-bold text-slate-900">Sertifikat</span><span
                                class="text-sm text-slate-500">Terverifikasi</span></div>
                    </div>
                </div>
                <div class="rounded-2xl overflow-hidden shadow-xl border border-slate-200/80">
                    <img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=600&h=400&fit=crop"
                        alt="Belajar investasi" class="w-full h-80 object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- Section YouTube / Video -->
    <section id="video" class="py-16 sm:py-20 md:py-24 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wider mb-2">Video</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
                    Pelajari investasi saham dari <span class="gradient-text">ahlinya</span>
                </h2>
                <p class="text-slate-600 max-w-2xl mx-auto">
                    Simak pengenalan singkat tentang kelas dan manfaat belajar investasi bersama Investalearning.
                </p>
            </div>
            <div
                class="relative rounded-2xl overflow-hidden shadow-xl border border-slate-200 bg-slate-900 aspect-video">
                <iframe class="absolute inset-0 w-full h-full"
                    src="https://www.youtube.com/embed/{{ config('services.youtube.video_id', 'dQw4w9WgXcQ') }}?rel=0&modestbranding=1"
                    title="Video Investalearning"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen>
                </iframe>
            </div>
            <p class="mt-4 text-center text-sm text-slate-500">
                Daftar sekarang untuk akses materi video lengkap di dalam kelas.
            </p>
        </div>
    </section>

    <!-- CTA Sertifikat -->
    <section class="py-16 sm:py-20 bg-slate-900 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4">Selesaikan kelas, raih sertifikat</h2>
            <p class="text-slate-300 mb-8 max-w-xl mx-auto">Selesaikan materi dan ujian untuk mendapatkan sertifikat
                yang dapat memvalidasi kompetensi investasi Anda.</p>
            <a href="{{ route('register') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-indigo-500 px-6 py-3.5 text-base font-semibold text-white hover:bg-indigo-600 transition">
                Daftar Sekarang
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
        </div>
    </section>

    <!-- Kontak -->
    <section id="contact" class="py-16 sm:py-20 md:py-24 bg-slate-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 lg:gap-16">
                <div>
                    <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wider mb-2">Kontak</p>
                    <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
                        Butuh bantuan? <span class="gradient-text">Hubungi kami</span>
                    </h2>
                    <p class="text-slate-600 mb-8">
                        Punya pertanyaan seputar kelas atau pendaftaran? Tim kami siap membantu perjalanan investasi
                        Anda.
                    </p>

                    @php
                        $waNumber = preg_replace('/[^0-9]/', '', config('services.wa_cs.number', '6281234567890'));
                        $waUrl =
                            'https://wa.me/' .
                            $waNumber .
                            '?text=' .
                            urlencode('Halo, saya ingin bertanya tentang kelas Investalearning.');
                    @endphp
                    <div class="space-y-4">
                        <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
                            class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 bg-white hover:border-indigo-200 hover:shadow-md transition">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-emerald-600" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900">WhatsApp CS</p>
                                <p class="text-sm text-slate-500">Chat langsung untuk pertanyaan kelas & pendaftaran
                                </p>
                            </div>
                        </a>
                        <p class="text-sm text-slate-500">Email: support@investalearning.com</p>
                    </div>
                    <div class="mt-8">
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition">Daftar
                            Sekarang <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg></a>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm">
                    <p class="font-semibold text-slate-900 mb-4">Atau isi form berikut</p>
                    <form class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama</label>
                            <input type="text"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition"
                                placeholder="Nama Anda">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                            <input type="email"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition"
                                placeholder="email@contoh.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Pesan</label>
                            <textarea rows="3"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition resize-none"
                                placeholder="Tulis pertanyaan Anda..."></textarea>
                        </div>
                        <button type="submit"
                            class="w-full rounded-xl bg-slate-900 text-white py-3 text-sm font-semibold hover:bg-slate-800 transition">Kirim
                            Pesan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white py-12 md:py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('img/favicon.png') }}" alt="" class="w-8 h-8 rounded-lg">
                    <span class="text-lg font-bold">Investa<span class="text-indigo-400">learning</span></span>
                </div>
                <div class="flex flex-wrap justify-center gap-6 text-sm text-slate-400">
                    <a href="#home" class="hover:text-white transition">Beranda</a>
                    <a href="#courses" class="hover:text-white transition">Kelas</a>
                    <a href="#video" class="hover:text-white transition">Video</a>
                    <a href="#about" class="hover:text-white transition">Tentang</a>
                    <a href="#contact" class="hover:text-white transition">Kontak</a>
                    <a href="{{ route('register') }}" class="hover:text-white transition">Daftar</a>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-slate-800 text-center text-sm text-slate-500">
                © {{ date('Y') }} Investalearning. Semua hak dilindungi.
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp Chat CS -->
    @php
        $waNumber = preg_replace('/[^0-9]/', '', config('services.wa_cs.number', '6281234567890'));
        $waUrl = 'https://wa.me/' . $waNumber . '?text=' . urlencode('Halo CS InvestaLearning, saya ingin bertanya.');
    @endphp
    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Chat ke CS via WhatsApp"
        class="fixed bottom-4 left-4 sm:bottom-6 sm:left-6 md:bottom-8 md:left-8 z-50 flex items-center gap-2 bg-[#25D366] text-white px-4 py-3 rounded-full shadow-xl hover:bg-[#20BD5A] transition-colors duration-300 animate-wa-float hover:animate-none hover:scale-105 group">
        <svg class="w-6 h-6 sm:w-7 sm:h-7 shrink-0" viewBox="0 0 24 24" fill="currentColor">
            <path
                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
        </svg>
        <span class="text-sm font-semibold hidden sm:inline">Chat CS</span>
    </a>

    <!-- Scroll to Top Button -->
    <button id="scrollTop"
        class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 md:bottom-8 md:right-8 bg-gradient-to-br from-indigo-600 to-indigo-500 text-white w-10 h-10 xs:w-12 xs:h-12 rounded-full flex items-center justify-center shadow-2xl hover:scale-110 transition-all duration-300 z-50 opacity-0 pointer-events-none hover:shadow-indigo-500/50">
        <svg class="w-5 h-5 xs:w-6 xs:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18">
            </path>
        </svg>
    </button>
</body>

</html>
<script>
    // Scroll to Top Button
    const scrollTopBtn = document.getElementById('scrollTop');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            scrollTopBtn.style.opacity = '1';
            scrollTopBtn.style.pointerEvents = 'auto';
        } else {
            scrollTopBtn.style.opacity = '0';
            scrollTopBtn.style.pointerEvents = 'none';
        }
    });

    scrollTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Smooth Scroll for Anchor Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Intersection Observer for Scroll Animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe elements with slide-up animation
    document.querySelectorAll('.animate-slide-up').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
        observer.observe(el);
    });
</script>
