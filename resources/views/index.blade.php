<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Investalearning - Platform Belajar Saham</title>
    <link rel="shortcut icon" href="{{asset('img/favicon.png')}}" type="image/x-icon">
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
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px) translateX(0px); }
            50% { transform: translateY(-30px) translateX(10px); }
        }
        @keyframes pulse-glow {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.1); }
        }
        @keyframes slide-up {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-15px) scale(1.05); }
        }
        @keyframes morph {
            0%, 100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
            25% { border-radius: 58% 42% 75% 25% / 76% 46% 54% 24%; }
            50% { border-radius: 50% 50% 33% 67% / 55% 27% 73% 45%; }
            75% { border-radius: 33% 67% 58% 42% / 63% 68% 32% 37%; }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-slow { animation: floatSlow 8s ease-in-out infinite; }
        .animate-pulse-glow { animation: pulse-glow 3s ease-in-out infinite; }
        .animate-slide-up { animation: slide-up 0.6s ease-out forwards; }
        .animate-rotate-slow { animation: rotate 20s linear infinite; }
        .animate-bounce-slow { animation: bounce-slow 4s ease-in-out infinite; }
        .animate-morph { animation: morph 10s ease-in-out infinite; }
        
        .shape-blob {
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            background: linear-gradient(45deg, #0891b2, #06b6d4);
            filter: blur(40px);
            opacity: 0.3;
        }
        
        .shape-circle {
            border-radius: 50%;
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            filter: blur(60px);
            opacity: 0.2;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .shape-triangle {
            width: 0;
            height: 0;
            border-left: 50px solid transparent;
            border-right: 50px solid transparent;
            border-bottom: 87px solid rgba(6, 182, 212, 0.2);
            filter: blur(2px);
        }
        
        .shape-square {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, rgba(6, 182, 212, 0.2), rgba(8, 145, 178, 0.2));
            filter: blur(2px);
        }
        
        .shape-hexagon {
            width: 80px;
            height: 46px;
            background: rgba(6, 182, 212, 0.2);
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
            border-bottom: 23px solid rgba(6, 182, 212, 0.2);
        }
        
        .shape-hexagon:after {
            top: 100%;
            border-top: 23px solid rgba(6, 182, 212, 0.2);
        }
        
        /* Mobile Responsive Adjustments */
        @media (max-width: 640px) {
            .shape-blob, .shape-circle {
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
            .shape-blob, .shape-circle {
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
<body class="bg-gray-50 overflow-x-hidden relative">
    <div class="">

    </div>
    <!-- Floating Background Shapes -->
    <div class=" inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="shape-blob absolute top-20 right-20 w-96 h-96 animate-float animate-morph"></div>
        <div class="shape-blob absolute bottom-40 left-20 w-80 h-80 animate-float-slow animate-morph" style="animation-delay: 2s;"></div>
        <div class="shape-circle absolute top-1/2 right-1/3 w-64 h-64 animate-pulse-glow" style="animation-delay: 1s;"></div>
        <div class="shape-circle absolute bottom-20 right-40 w-72 h-72 animate-float" style="animation-delay: 3s;"></div>
        
        <!-- Geometric Shapes -->
        <div class="shape-triangle absolute top-1/4 left-1/4 animate-float animate-rotate-slow" style="animation-delay: 1.5s;"></div>
        <div class="shape-square absolute top-3/4 right-1/4 animate-bounce-slow animate-rotate-slow" style="animation-delay: 2.5s;"></div>
        <div class="shape-hexagon absolute bottom-1/3 left-1/3 animate-float-slow" style="animation-delay: 3.5s;"></div>
        
        <!-- Additional Circles -->
        <div class="absolute top-40 left-1/2 w-40 h-40 bg-cyan-400 rounded-full opacity-10 blur-2xl animate-pulse-glow" style="animation-delay: 4s;"></div>
        <div class="absolute bottom-1/4 right-1/2 w-56 h-56 bg-cyan-300 rounded-full opacity-10 blur-2xl animate-float" style="animation-delay: 5s;"></div>
    </div>

    <!-- Navbar -->
    <nav x-data="{ mobileMenuOpen: false }" class="fixed inset-x-0 top-0 z-50 bg-white/90 backdrop-blur-lg border-b border-cyan-200 shadow-lg">
        <div class="px-3 sm:px-4 md:px-8 lg:px-16">
            <div class="flex justify-between items-center h-14 sm:h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-4 sm:space-x-8 md:space-x-16">
                    <h2 class="text-lg xs:text-xl sm:text-2xl md:text-3xl font-bold gradient-text hover:scale-105 transition-transform duration-300 cursor-pointer">Investalearning</h2>

                    <div class="hidden md:flex items-center space-x-4 lg:space-x-8">
                        <a href="#home" class="text-lg text-gray-700 hover:text-cyan-800 transition-all duration-300 hover:scale-110 relative group">
                            Home
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-cyan-700 group-hover:w-full transition-all duration-300"></span>
                        </a>
                        <a href="#courses" class="text-lg text-gray-700 hover:text-cyan-800 transition-all duration-300 hover:scale-110 relative group">
                            Courses
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-cyan-700 group-hover:w-full transition-all duration-300"></span>
                        </a>
                        <a href="#about" class="text-lg text-gray-700 hover:text-cyan-800 transition-all duration-300 hover:scale-110 relative group">
                            About
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-cyan-700 group-hover:w-full transition-all duration-300"></span>
                        </a>
                        <a href="#contact" class="text-lg text-gray-700 hover:text-cyan-800 transition-all duration-300 hover:scale-110 relative group">
                            Contact
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-cyan-700 group-hover:w-full transition-all duration-300"></span>
                        </a>
                    </div>
                </div>

                <!-- Desktop Buttons -->
                <div class="hidden md:flex items-center space-x-2 lg:space-x-4">
                    <a href="{{route('register')}}" class="bg-gradient-to-r from-cyan-700 to-cyan-600 text-white px-3 lg:px-6 py-2 text-sm lg:text-base hover:from-cyan-800 hover:to-cyan-700 rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg">Register</a>
                    <a href="{{route('login')}}" class="bg-white text-cyan-800 px-3 lg:px-6 py-2 text-sm lg:text-base border-2 border-cyan-300 rounded-lg hover:bg-cyan-700 hover:text-white transition-all duration-300 hover:scale-105 hover:shadow-lg">Login</a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 hover:text-cyan-800 focus:outline-none transition-transform duration-300 hover:scale-110">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2"
             class="md:hidden bg-white/95 backdrop-blur-lg border-t border-gray-300">
            <div class="px-4 py-3 space-y-3">
                <a href="#home" class="block text-lg text-gray-700 hover:text-cyan-800 py-2 transition hover:translate-x-2">Home</a>
                <a href="#courses" class="block text-lg text-gray-700 hover:text-cyan-800 py-2 transition hover:translate-x-2">Courses</a>
                <a href="#about" class="block text-lg text-gray-700 hover:text-cyan-800 py-2 transition hover:translate-x-2">About</a>
                <a href="#contact" class="block text-lg text-gray-700 hover:text-cyan-800 py-2 transition hover:translate-x-2">Contact</a>
                <div class="pt-4 space-y-2">
                    <a href="{{route('register')}}" class="block w-full text-center bg-gradient-to-r from-cyan-700 to-cyan-600 text-white px-4 py-2 hover:from-cyan-800 hover:to-cyan-700 rounded-lg transition">Register</a>
                    <a href="{{route('login')}}" class="block w-full text-center bg-white text-cyan-800 px-4 py-2 border-2 border-gray-300 rounded-lg hover:bg-cyan-700 hover:text-white transition">Login</a>
                </div>
            </div>
        </div>
    </nav>   
    <!-- Hero Section -->
    <section id="home" class="relative bg-gradient-to-br from-gray-50 via-cyan-50 to-gray-100 py-16 sm:py-20 md:py-32 mt-14 sm:mt-16 overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute top-10 left-5 sm:left-10 w-48 sm:w-72 h-48 sm:h-72 bg-cyan-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float"></div>
        <div class="absolute top-40 right-10 sm:right-20 w-64 sm:w-96 h-64 sm:h-96 bg-cyan-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float-slow"></div>
        <div class="absolute bottom-10 left-1/3 w-56 sm:w-80 h-56 sm:h-80 bg-cyan-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-glow"></div>
        
        <div class="px-3 xs:px-4 sm:px-8 lg:px-16 relative z-10">
            <div class="grid md:grid-cols-2 gap-8 sm:gap-12 items-center">
                <!-- Left Content -->
                <div class="space-y-4 sm:space-y-6 md:space-y-8 animate-slide-up">
                    <h1 class="text-2xl xs:text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
                       Platform Sertifikasi<br>
                        Pasar Modal<br>
                        <span class="gradient-text">Indonesia</span>
                    </h1>
                    
                    <p class="text-sm xs:text-base sm:text-lg md:text-xl text-gray-600 max-w-lg">
                       Tingkatkan kompetensi investasi Anda dengan sertifikasi profesional yang diakui OJK dan BNSP
                    </p>

                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <button class="group bg-gradient-to-r from-cyan-700 to-cyan-600 text-white px-6 sm:px-8 py-3 sm:py-4 text-sm sm:text-base rounded-lg hover:from-cyan-800 hover:to-cyan-700 transition-all duration-300 font-semibold flex items-center justify-center gap-2 hover:scale-105 hover:shadow-2xl">
                            Daftar Sekarang
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Stats -->
                    <div class="flex gap-4 xs:gap-6 sm:gap-8 pt-4 sm:pt-6">
                        <div class="text-center">
                            <h3 class="text-xl xs:text-2xl sm:text-3xl font-bold gradient-text">50K+</h3>
                            <p class="text-gray-600 text-xs xs:text-sm">Peserta</p>
                        </div>
                        <div class="text-center">
                            <h3 class="text-xl xs:text-2xl sm:text-3xl font-bold gradient-text">200+</h3>
                            <p class="text-gray-600 text-xs xs:text-sm">Kursus</p>
                        </div>
                        <div class="text-center">
                            <h3 class="text-xl xs:text-2xl sm:text-3xl font-bold gradient-text">95%</h3>
                            <p class="text-gray-600 text-xs xs:text-sm">Keberhasilan</p>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Card Mockup -->
                <div class="relative animate-slide-up" style="animation-delay: 0.2s;">
                    <!-- Decorative Shapes -->
                    <div class="absolute -top-6 -right-6 w-24 sm:w-32 h-24 sm:h-32 bg-cyan-400 rounded-full blur-3xl opacity-40 animate-pulse-glow"></div>
                    <div class="absolute -bottom-6 -left-6 w-24 sm:w-32 h-24 sm:h-32 bg-cyan-300 rounded-full blur-3xl opacity-40 animate-float"></div>
                    
                    <!-- Main Card -->
                    <div class="bg-white/80 backdrop-blur-lg rounded-xl sm:rounded-2xl shadow-2xl p-4 xs:p-5 sm:p-6 md:p-8 relative z-10 border border-cyan-100 hover:shadow-cyan-200/50 transition-all duration-500 hover:scale-[1.02]">
                        <!-- Header -->
                        <div class="mb-4 sm:mb-6 text-center">
                            <h3 class="text-lg xs:text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                                Siap Memulai Perjalanan Investasi Profesional?
                            </h3>
                            <p class="text-gray-600 text-xs xs:text-sm md:text-base">
                                Bergabunglah dengan ribuan investor Indonesia yang telah meningkatkan kompetensi
                                melalui <span class="font-semibold gradient-text">Investalearning</span>
                            </p>
                        </div>

                        <!-- Highlight Section -->
                        <div class="mb-4 sm:mb-6 text-center p-3 sm:p-4 bg-gradient-to-r from-cyan-50 to-blue-50 rounded-lg sm:rounded-xl">
                            <p class="text-xs sm:text-sm text-gray-500 mb-1">Platform Edukasi Investasi</p>
                            <h2 class="text-xl xs:text-2xl sm:text-3xl md:text-4xl font-bold gradient-text">Terpercaya & Profesional</h2>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1">Belajar • Analisa • Bertumbuh</p>
                        </div>

                        <!-- Benefit List -->
                        <div class="space-y-2 sm:space-y-3 mb-4 sm:mb-6">
                            <div class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 border-2 border-cyan-700 rounded-lg bg-cyan-50 hover:bg-cyan-100 transition-all duration-300 hover:scale-105">
                                <span class="w-2 h-2 bg-cyan-700 rounded-full animate-pulse flex-shrink-0"></span>
                                <span class="font-semibold text-gray-900 text-xs xs:text-sm sm:text-base">
                                    Materi Investasi Terstruktur
                                </span>
                            </div>

                            <div class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 border border-gray-300 rounded-lg hover:border-cyan-700 hover:bg-cyan-50 transition-all duration-300 hover:scale-105">
                                <span class="w-2 h-2 bg-gray-400 rounded-full flex-shrink-0"></span>
                                <span class="font-medium text-gray-700 text-xs xs:text-sm sm:text-base">
                                    Mentor Berpengalaman & Praktis
                                </span>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <button class="w-full bg-gradient-to-r from-gray-900 to-gray-800 text-white py-3 sm:py-4 text-sm sm:text-base rounded-lg hover:from-cyan-700 hover:to-cyan-600 transition-all duration-300 font-semibold hover:scale-105 hover:shadow-xl">
                            Daftar Sekarang
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </section>

   
    <!-- Features Section -->
    <section id="courses" class="relative bg-gradient-to-b from-cyan-50 to-white py-10 sm:py-12 md:py-20 overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute top-20 right-0 w-48 sm:w-64 h-48 sm:h-64 bg-cyan-200 rounded-full blur-3xl opacity-20 animate-pulse-glow"></div>
        
        <div class="px-3 xs:px-4 sm:px-8 lg:px-16 relative z-10">
            <div class="text-center mb-8 sm:mb-12 animate-slide-up">
                <h2 class="text-2xl xs:text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-3 sm:mb-4">
                    Fitur <span class="gradient-text">Unggulan</span>
                </h2>
                <p class="text-gray-600 text-sm xs:text-base sm:text-lg max-w-2xl mx-auto px-4">
                    Platform lengkap untuk kebutuhan belajar investasi Anda
                </p>
            </div>
            
            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
                <div class="group text-center p-4 xs:p-5 sm:p-6 md:p-8 bg-white rounded-xl sm:rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:scale-105 hover:-translate-y-2 border border-cyan-100 animate-slide-up">
                    <div class="w-12 h-12 xs:w-14 xs:h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-cyan-100 to-cyan-200 rounded-xl flex items-center justify-center mx-auto mb-3 sm:mb-4 group-hover:rotate-12 transition-transform duration-500 group-hover:scale-110">
                        <svg class="w-6 h-6 xs:w-7 xs:h-7 sm:w-8 sm:h-8 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-base xs:text-lg sm:text-xl font-bold text-gray-900 mb-2 group-hover:text-cyan-700 transition-colors">Kursus Expert</h3>
                    <p class="text-gray-600 text-xs xs:text-sm sm:text-base">Belajar dari profesional industri dengan pengalaman trading bertahun-tahun.</p>
                </div>

                <div class="group text-center p-4 xs:p-5 sm:p-6 md:p-8 bg-white rounded-xl sm:rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:scale-105 hover:-translate-y-2 border border-cyan-100 animate-slide-up" style="animation-delay: 0.1s;">
                    <div class="w-12 h-12 xs:w-14 xs:h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-cyan-100 to-cyan-200 rounded-xl flex items-center justify-center mx-auto mb-3 sm:mb-4 group-hover:rotate-12 transition-transform duration-500 group-hover:scale-110">
                        <svg class="w-6 h-6 xs:w-7 xs:h-7 sm:w-8 sm:h-8 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base xs:text-lg sm:text-xl font-bold text-gray-900 mb-2 group-hover:text-cyan-700 transition-colors">Data Real-Time</h3>
                    <p class="text-gray-600 text-xs xs:text-sm sm:text-base">Akses data pasar langsung dan buat keputusan investasi yang tepat.</p>
                </div>

                <div class="group text-center p-4 xs:p-5 sm:p-6 md:p-8 bg-white rounded-xl sm:rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:scale-105 hover:-translate-y-2 border border-cyan-100 animate-slide-up" style="animation-delay: 0.2s;">
                    <div class="w-12 h-12 xs:w-14 xs:h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-cyan-100 to-cyan-200 rounded-xl flex items-center justify-center mx-auto mb-3 sm:mb-4 group-hover:rotate-12 transition-transform duration-500 group-hover:scale-110">
                        <svg class="w-6 h-6 xs:w-7 xs:h-7 sm:w-8 sm:h-8 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base xs:text-lg sm:text-xl font-bold text-gray-900 mb-2 group-hover:text-cyan-700 transition-colors">Komunitas Support</h3>
                    <p class="text-gray-600 text-xs xs:text-sm sm:text-base">Bergabung dengan ribuan investor yang berbagi strategi dan wawasan.</p>
                </div>
            </div>
        </div>
    </section>

     <section id="about" class="bg-gradient-to-br from-gray-50 to-white py-10 sm:py-12 md:py-20">
        <div class="px-3 xs:px-4 sm:px-8 lg:px-16">
            <div class="grid md:grid-cols-2 gap-8 sm:gap-12 items-center">
                <div class="space-y-4 sm:space-y-6">
                    <span class="text-cyan-700 font-semibold text-xs sm:text-sm uppercase tracking-wide">About Us</span>
                    <h2 class="text-2xl xs:text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900">
                        Empowering investors through education
                    </h2>
                    <p class="text-sm xs:text-base sm:text-lg text-gray-600 leading-relaxed">
                        Investalearning was founded with a simple mission: to democratize financial education and make investing accessible to everyone. We believe that anyone can become a successful investor with the right knowledge and tools.
                    </p>
                    <p class="text-sm xs:text-base sm:text-lg text-gray-600 leading-relaxed">
                        Our platform combines comprehensive courses, expert insights, and practical trading simulations to help you build confidence in your investment decisions. Whether you're just starting or looking to refine your strategy, we're here to guide you every step of the way.
                    </p>
                    <div class="grid grid-cols-3 gap-3 xs:gap-4 sm:gap-6 pt-4 sm:pt-6">
                        <div class="text-center">
                            <h3 class="text-xl xs:text-2xl sm:text-3xl font-bold text-cyan-700">50K+</h3>
                            <p class="text-xs sm:text-sm text-gray-600 mt-1">Active Students</p>
                        </div>
                        <div class="text-center">
                            <h3 class="text-xl xs:text-2xl sm:text-3xl font-bold text-cyan-700">200+</h3>
                            <p class="text-xs sm:text-sm text-gray-600 mt-1">Expert Courses</p>
                        </div>
                        <div class="text-center">
                            <h3 class="text-xl xs:text-2xl sm:text-3xl font-bold text-cyan-700">95%</h3>
                            <p class="text-xs sm:text-sm text-gray-600 mt-1">Success Rate</p>
                        </div>
                    </div>
                </div>
                <div class="none md:relative">
                    <img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=600&h=800&fit=crop" 
                         alt="About" 
                         class="rounded-2xl shadow-2xl w-full h-[500px] object-cover">
                    <div class="absolute -bottom-6 -left-6 bg-cyan-700 text-white p-6 rounded-xl shadow-xl max-w-xs">
                        <p class="text-sm font-semibold mb-1">Join Our Community</p>
                        <p class="text-2xl font-bold">Start Learning Today</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Section -->
    <section x-data="{ isPlaying: false, showOverlay: true }" class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-gray-900 via-cyan-950 to-gray-900 py-10 sm:py-16">
        
        <!-- Animated Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0 animate-pulse-glow" style="background-image: radial-gradient(circle at 2px 2px, rgba(6, 182, 212, 0.5) 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>

        <!-- Floating Shapes -->
        <div class="absolute top-20 left-5 sm:left-10 w-24 sm:w-32 h-24 sm:h-32 bg-cyan-500 rounded-full opacity-20 blur-3xl animate-float"></div>
        <div class="absolute bottom-20 right-5 sm:right-10 w-32 sm:w-40 h-32 sm:h-40 bg-cyan-400 rounded-full opacity-20 blur-3xl animate-float-slow"></div>
        <div class="absolute top-1/2 left-1/4 w-16 sm:w-24 h-16 sm:h-24 bg-cyan-300 rounded-full opacity-20 blur-2xl animate-pulse-glow"></div>

        <!-- Content Container -->
        <div class="relative z-10 w-full max-w-7xl mx-auto px-3 xs:px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            
            <!-- Header Info -->
            <div class="text-center mb-6 sm:mb-8 animate-slide-up" x-show="showOverlay" x-transition>
                <h1 class="text-2xl xs:text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-3 sm:mb-4 px-4">
                    Pelajari Investasi Saham dari <span class="text-cyan-400">Ahlinya</span>
                </h1>
                <p class="text-gray-300 text-sm xs:text-base sm:text-lg md:text-xl px-4">
                    Tutorial lengkap untuk pemula hingga profesional
                </p>
            </div>

            <!-- Video Container -->
            <div class="relative rounded-2xl overflow-hidden shadow-2xl bg-black border-2 border-cyan-500/30 hover:border-cyan-500/60 transition-all duration-500 animate-slide-up" 
                 @click="showOverlay = false"
                 style="animation-delay: 0.2s;">
                
                <!-- YouTube Iframe -->
                <div class="relative" style="padding-bottom: 56.25%; height: 0;">
                    <iframe 
                        class="absolute top-0 left-0 w-full h-full"
                        src="https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=0&rel=0&modestbranding=1"
                        title="YouTube video player" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                        allowfullscreen>
                    </iframe>
                </div>

                <!-- Overlay Gradient -->
                <div x-show="showOverlay" 
                     x-transition
                     class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/60 flex items-center justify-center cursor-pointer">
                    <button @click="isPlaying = true; showOverlay = false" 
                            class="group w-24 h-24 bg-gradient-to-br from-cyan-600 to-cyan-500 hover:from-cyan-700 hover:to-cyan-600 rounded-full flex items-center justify-center transform hover:scale-125 transition-all duration-500 shadow-2xl hover:shadow-cyan-500/50 animate-pulse-glow">
                        <svg class="w-12 h-12 text-white ml-1 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Video Info Below -->
            <div class="mt-6 sm:mt-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-3 sm:gap-4 animate-slide-up" style="animation-delay: 0.3s;">
                <div class="text-white">
                    <h3 class="text-base xs:text-lg sm:text-xl font-bold mb-2">Strategi Investasi Saham untuk Pemula</h3>
                    <p class="text-gray-400 text-xs xs:text-sm">Investalearning • 1.2M views • 2 days ago</p>
                </div>
                <div class="flex gap-2 sm:gap-3 flex-wrap">
                    <button class="group bg-gray-800 hover:bg-cyan-600 text-white px-4 xs:px-5 sm:px-6 py-2 xs:py-2.5 sm:py-3 text-xs xs:text-sm sm:text-base rounded-lg flex items-center gap-2 transition-all duration-300 hover:scale-105 hover:shadow-lg">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                        </svg>
                        Like
                    </button>
                    <button class="group bg-gray-800 hover:bg-cyan-600 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-all duration-300 hover:scale-105 hover:shadow-lg">
                        <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                        </svg>
                        Share
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section id="mission" class="relative py-10 sm:py-12 md:py-20 bg-gradient-to-br from-cyan-50 via-white to-cyan-50 overflow-hidden">
        <!-- Background Shapes -->
        <div class="absolute top-0 left-0 w-48 sm:w-64 md:w-96 h-48 sm:h-64 md:h-96 bg-cyan-200 rounded-full blur-3xl opacity-20 animate-pulse-glow"></div>
        <div class="absolute bottom-0 right-0 w-48 sm:w-64 md:w-96 h-48 sm:h-64 md:h-96 bg-cyan-300 rounded-full blur-3xl opacity-20 animate-float-slow"></div>
        
        <div class="px-3 xs:px-4 sm:px-8 lg:px-16 relative z-10">
            <div class="text-center mb-8 sm:mb-12 md:mb-16 animate-slide-up">
                <span class="text-cyan-700 font-semibold text-xs sm:text-sm uppercase tracking-wide">Misi Kami</span>
                <h2 class="text-2xl xs:text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mt-3 sm:mt-4 px-4">
                    Yang Mendorong Kami <span class="gradient-text">Maju</span>
                </h2>
                <p class="text-sm xs:text-base sm:text-lg md:text-xl text-gray-600 mt-3 sm:mt-4 max-w-3xl mx-auto px-4">
                    Kami berkomitmen mengubah kehidupan melalui literasi finansial dan edukasi investasi
                </p>
            </div>

            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
                <div class="group bg-gradient-to-br from-white to-cyan-50 p-4 xs:p-5 sm:p-6 md:p-8 rounded-xl sm:rounded-2xl border-2 border-cyan-100 hover:border-cyan-300 hover:shadow-2xl transition-all duration-500 hover:scale-105 hover:-translate-y-2 animate-slide-up">
                    <div class="w-12 h-12 xs:w-14 xs:h-14 sm:w-14 sm:h-14 bg-gradient-to-br from-cyan-700 to-cyan-600 rounded-xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 group-hover:rotate-12 transition-all duration-500 shadow-lg">
                        <svg class="w-5 h-5 xs:w-6 xs:h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg xs:text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4 group-hover:text-cyan-700 transition-colors">Memberdayakan</h3>
                    <p class="text-gray-600 text-xs xs:text-sm sm:text-base leading-relaxed">
                        Membekali individu dengan pengetahuan dan kepercayaan diri untuk mengontrol masa depan finansial mereka melalui investasi strategis.
                    </p>
                </div>

                <div class="group bg-gradient-to-br from-white to-cyan-50 p-4 xs:p-5 sm:p-6 md:p-8 rounded-xl sm:rounded-2xl border-2 border-cyan-100 hover:border-cyan-300 hover:shadow-2xl transition-all duration-500 hover:scale-105 hover:-translate-y-2 animate-slide-up" style="animation-delay: 0.1s;">
                    <div class="w-12 h-12 xs:w-14 xs:h-14 sm:w-14 sm:h-14 bg-gradient-to-br from-cyan-700 to-cyan-600 rounded-xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 group-hover:rotate-12 transition-all duration-500 shadow-lg">
                        <svg class="w-5 h-5 xs:w-6 xs:h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg xs:text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4 group-hover:text-cyan-700 transition-colors">Mengedukasi</h3>
                    <p class="text-gray-600 text-xs xs:text-sm sm:text-base leading-relaxed">
                        Menyediakan kursus komprehensif yang dipimpin ahli, membuat konsep finansial kompleks dapat diakses semua orang, dari pemula hingga trader advanced.
                    </p>
                </div>

                <div class="group bg-gradient-to-br from-white to-cyan-50 p-4 xs:p-5 sm:p-6 md:p-8 rounded-xl sm:rounded-2xl border-2 border-cyan-100 hover:border-cyan-300 hover:shadow-2xl transition-all duration-500 hover:scale-105 hover:-translate-y-2 animate-slide-up" style="animation-delay: 0.2s;">
                    <div class="w-12 h-12 xs:w-14 xs:h-14 sm:w-14 sm:h-14 bg-gradient-to-br from-cyan-700 to-cyan-600 rounded-xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 group-hover:rotate-12 transition-all duration-500 shadow-lg">
                        <svg class="w-5 h-5 xs:w-6 xs:h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg xs:text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4 group-hover:text-cyan-700 transition-colors">Berkembang</h3>
                    <p class="text-gray-600 text-xs xs:text-sm sm:text-base leading-relaxed">
                        Mendukung komunitas kami dalam mencapai tujuan investasi mereka melalui pembelajaran berkelanjutan, alat praktis, dan bimbingan ahli.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Certification Section -->
    <section id="certification" class="relative py-10 sm:py-12 md:py-20 bg-gradient-to-br from-cyan-950 via-gray-900 to-cyan-950 text-white overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute top-20 left-5 sm:left-10 w-48 sm:w-64 md:w-96 h-48 sm:h-64 md:h-96 bg-cyan-500 rounded-full blur-3xl opacity-10 animate-float"></div>
        <div class="absolute bottom-20 right-5 sm:right-10 w-48 sm:w-64 md:w-96 h-48 sm:h-64 md:h-96 bg-cyan-400 rounded-full blur-3xl opacity-10 animate-float-slow"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full h-full opacity-5">
            <div class="animate-rotate-slow w-full h-full" style="background: radial-gradient(circle, transparent 20%, #06b6d4 21%, #06b6d4 23%, transparent 24%), radial-gradient(circle, transparent 20%, #06b6d4 21%, #06b6d4 23%, transparent 24%); background-size: 80px 80px; background-position: 0 0, 40px 40px;"></div>
        </div>
        
        <div class="px-3 xs:px-4 sm:px-8 lg:px-16 relative z-10">
            <!-- Header -->
            <div class="text-center mb-8 sm:mb-12 md:mb-16 animate-slide-up">
                <span class="text-cyan-400 font-semibold text-xs sm:text-sm uppercase tracking-wide">
                    Sertifikasi
                </span>
                <h2 class="text-2xl xs:text-3xl sm:text-4xl md:text-5xl font-bold mt-3 sm:mt-4 px-4">
                    Dapatkan Sertifikat dan <span class="text-cyan-400">Menonjol</span>
                </h2>
                <p class="text-sm xs:text-base sm:text-lg md:text-xl text-gray-300 mt-3 sm:mt-4 max-w-3xl mx-auto px-4">
                    Raih sertifikasi yang diakui untuk memvalidasi pengetahuan dan keterampilan investasi Anda
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 sm:gap-12 items-center">
                <!-- Left Content -->
                <div class="space-y-4 sm:space-y-6 animate-slide-up">
                    <div class="group flex items-start gap-3 sm:gap-4 p-3 xs:p-4 bg-white/5 backdrop-blur-sm rounded-xl hover:bg-white/10 transition-all duration-300 hover:translate-x-2">
                        <div class="w-10 h-10 xs:w-12 xs:h-12 bg-gradient-to-br from-cyan-600 to-cyan-500 rounded-lg flex items-center justify-center flex-shrink-0 mt-1 group-hover:scale-110 group-hover:rotate-12 transition-all duration-500">
                            <svg class="w-5 h-5 xs:w-6 xs:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base xs:text-lg sm:text-xl font-bold mb-2">
                                Sertifikat Diakui Industri
                            </h3>
                            <p class="text-gray-300 text-xs xs:text-sm sm:text-base">
                                Terima sertifikat yang diakui oleh institusi finansial terkemuka dan employer di seluruh dunia.
                            </p>
                        </div>
                    </div>

                    <div class="group flex items-start gap-3 sm:gap-4 p-3 xs:p-4 bg-white/5 backdrop-blur-sm rounded-xl hover:bg-white/10 transition-all duration-300 hover:translate-x-2" style="animation-delay: 0.1s;">
                        <div class="w-10 h-10 xs:w-12 xs:h-12 bg-gradient-to-br from-cyan-600 to-cyan-500 rounded-lg flex items-center justify-center flex-shrink-0 mt-1 group-hover:scale-110 group-hover:rotate-12 transition-all duration-500">
                            <svg class="w-5 h-5 xs:w-6 xs:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base xs:text-lg sm:text-xl font-bold mb-2">
                                Penilaian Komprehensif
                            </h3>
                            <p class="text-gray-300 text-xs xs:text-sm sm:text-base">
                                Selesaikan ujian ketat yang menguji pemahaman Anda tentang prinsip investasi dan strategi pasar.
                            </p>
                        </div>
                    </div>

                    <div class="group flex items-start gap-3 sm:gap-4 p-3 xs:p-4 bg-white/5 backdrop-blur-sm rounded-xl hover:bg-white/10 transition-all duration-300 hover:translate-x-2" style="animation-delay: 0.2s;">
                        <div class="w-10 h-10 xs:w-12 xs:h-12 bg-gradient-to-br from-cyan-600 to-cyan-500 rounded-lg flex items-center justify-center flex-shrink-0 mt-1 group-hover:scale-110 group-hover:rotate-12 transition-all duration-500">
                            <svg class="w-5 h-5 xs:w-6 xs:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base xs:text-lg sm:text-xl font-bold mb-2">
                                Kemajuan Karir
                            </h3>
                            <p class="text-gray-300 text-xs xs:text-sm sm:text-base">
                                Tingkatkan profil profesional Anda dan buka pintu ke peluang baru di sektor finansial.
                            </p>
                        </div>
                    </div>

                    <div class="pt-4 sm:pt-6">
                        <a href="#"
                            class="group inline-flex items-center gap-2 bg-gradient-to-r from-cyan-600 to-cyan-500 text-white px-5 xs:px-6 sm:px-8 py-3 sm:py-4 text-sm sm:text-base rounded-lg hover:from-cyan-700 hover:to-cyan-600 transition-all duration-300 font-semibold hover:scale-105 hover:shadow-2xl hover:shadow-cyan-500/50">
                            Lihat Program Sertifikasi
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Right Card -->
                <div class="relative animate-slide-up" style="animation-delay: 0.3s;">
                    <div class="bg-gradient-to-br from-white to-gray-100 p-4 xs:p-5 sm:p-6 md:p-8 rounded-xl sm:rounded-2xl shadow-2xl border-2 sm:border-4 border-cyan-500 hover:border-cyan-400 transition-all duration-500 hover:scale-105 relative overflow-hidden">
                        <!-- Card Background Pattern -->
                        <div class="absolute inset-0 opacity-5">
                            <div style="background-image: radial-gradient(circle at 2px 2px, #06b6d4 1px, transparent 0); background-size: 30px 30px;"></div>
                        </div>
                        
                        <div class="text-center mb-4 sm:mb-6 relative z-10">
                            <div class="w-16 h-16 xs:w-18 xs:h-18 sm:w-20 sm:h-20 bg-gradient-to-br from-cyan-700 to-cyan-600 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4 animate-pulse-glow shadow-xl">
                                <svg class="w-8 h-8 xs:w-9 xs:h-9 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.5c-2.28 0-4.39-.636-6.16-1.922L12 14z"></path>
                                </svg>
                            </div>

                            <h3 class="text-lg xs:text-xl sm:text-2xl font-bold text-gray-900">
                                Profesional Investasi Bersertifikat
                            </h3>
                            <p class="text-gray-600 mt-2 text-xs xs:text-sm sm:text-base">
                                Sertifikasi resmi yang diterbitkan oleh Investalearning
                            </p>
                        </div>

                        <div class="space-y-3 sm:space-y-4 text-gray-700 mb-6 sm:mb-8 relative z-10">
                            <div class="flex justify-between p-2.5 sm:p-3 bg-gray-50 rounded-lg text-xs xs:text-sm sm:text-base">
                                <span>Level</span>
                                <span class="font-semibold gradient-text">Professional</span>
                            </div>
                            <div class="flex justify-between p-2.5 sm:p-3 bg-gray-50 rounded-lg text-xs xs:text-sm sm:text-base">
                                <span>Durasi</span>
                                <span class="font-semibold gradient-text">12 Minggu</span>
                            </div>
                            <div class="flex justify-between p-2.5 sm:p-3 bg-gray-50 rounded-lg text-xs xs:text-sm sm:text-base">
                                <span>Penilaian</span>
                                <span class="font-semibold gradient-text">Ujian Akhir</span>
                            </div>
                            <div class="flex justify-between p-2.5 sm:p-3 bg-gray-50 rounded-lg text-xs xs:text-sm sm:text-base">
                                <span>Sertifikat</span>
                                <span class="font-semibold gradient-text">Digital & Terverifikasi</span>
                            </div>
                        </div>

                        <div class="relative z-10">
                            <a href="#"
                                class="block w-full text-center bg-gradient-to-r from-gray-900 to-gray-800 text-white py-3 sm:py-4 text-sm sm:text-base rounded-lg font-semibold hover:from-cyan-700 hover:to-cyan-600 transition-all duration-300 hover:scale-105 hover:shadow-xl">
                                Dapatkan Sertifikat Sekarang
                            </a>
                        </div>
                    </div>

                    <!-- Decorative Glow -->
                    <div class="absolute -top-6 -right-6 w-32 h-32 bg-cyan-400 rounded-full blur-3xl opacity-40 animate-pulse-glow"></div>
                    <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-cyan-300 rounded-full blur-3xl opacity-40 animate-float"></div>
                </div>
            </div>
        </div>
    </section>



    <!-- Contact Section -->
    <section id="contact" class="relative py-12 md:py-20 bg-gradient-to-br from-white via-cyan-50 to-white overflow-hidden">
        <!-- Background Shapes -->
        <div class="absolute top-20 right-20 w-96 h-96 bg-cyan-200 rounded-full blur-3xl opacity-20 animate-float"></div>
        <div class="absolute bottom-20 left-20 w-80 h-80 bg-cyan-300 rounded-full blur-3xl opacity-20 animate-float-slow"></div>
        
        <div class="px-4 sm:px-8 lg:px-16 relative z-10">
            <div class="grid md:grid-cols-2 gap-8 sm:gap-12">
                <div class="space-y-6 sm:space-y-8">
                    <div>
                        <span class="text-cyan-700 font-semibold text-xs sm:text-sm uppercase tracking-wide">Contact Us</span>
                        <h2 class="text-2xl xs:text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mt-3 sm:mt-4">
                            Let's start a conversation
                        </h2>
                        <p class="text-sm xs:text-base sm:text-lg text-gray-600 mt-3 sm:mt-4">
                            Have questions about our courses or need guidance? We're here to help you on your investment journey.
                        </p>
                    </div>

                    <div class="space-y-4 sm:space-y-6">
                        <div class="flex items-start gap-3 sm:gap-4">
                            <div class="w-10 h-10 xs:w-12 xs:h-12 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 xs:w-6 xs:h-6 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-1 text-sm xs:text-base">Email</h3>
                                <p class="text-gray-600 text-xs xs:text-sm sm:text-base">support@investalearning.com</p>
                                <p class="text-gray-600 text-xs xs:text-sm sm:text-base">hello@investalearning.com</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 sm:gap-4">
                            <div class="w-10 h-10 xs:w-12 xs:h-12 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 xs:w-6 xs:h-6 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-1 text-sm xs:text-base">Phone</h3>
                                <p class="text-gray-600 text-xs xs:text-sm sm:text-base">+62 21 1234 5678</p>
                                <p class="text-gray-600 text-xs xs:text-sm sm:text-base">+62 812 3456 7890</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 sm:gap-4">
                            <div class="w-10 h-10 xs:w-12 xs:h-12 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 xs:w-6 xs:h-6 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-1 text-sm xs:text-base">Office</h3>
                                <p class="text-gray-600 text-xs xs:text-sm sm:text-base">Jl. Sudirman No. 123<br>Jakarta Pusat, 10220<br>Indonesia</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2 xs:gap-3 sm:gap-4 pt-4">
                        <a href="#" class="w-10 h-10 xs:w-12 xs:h-12 bg-gray-100 hover:bg-cyan-700 rounded-lg flex items-center justify-center transition group">
                            <svg class="w-4 h-4 xs:w-5 xs:h-5 text-gray-600 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 xs:w-12 xs:h-12 bg-gray-100 hover:bg-cyan-700 rounded-lg flex items-center justify-center transition group">
                            <svg class="w-4 h-4 xs:w-5 xs:h-5 text-gray-600 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 xs:w-12 xs:h-12 bg-gray-100 hover:bg-cyan-700 rounded-lg flex items-center justify-center transition group">
                            <svg class="w-4 h-4 xs:w-5 xs:h-5 text-gray-600 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 xs:w-12 xs:h-12 bg-gray-100 hover:bg-cyan-700 rounded-lg flex items-center justify-center transition group">
                            <svg class="w-4 h-4 xs:w-5 xs:h-5 text-gray-600 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 xs:p-5 sm:p-6 md:p-8 rounded-xl sm:rounded-2xl">
                    <form class="space-y-4 sm:space-y-6">
                        <div>
                            <label class="block text-xs xs:text-sm font-semibold text-gray-900 mb-2">Full Name</label>
                            <input type="text" class="w-full px-3 xs:px-4 py-2 xs:py-3 text-sm xs:text-base rounded-lg border-2 border-gray-300 focus:border-cyan-700 focus:outline-none" placeholder="John Doe">
                        </div>

                        <div>
                            <label class="block text-xs xs:text-sm font-semibold text-gray-900 mb-2">Email Address</label>
                            <input type="email" class="w-full px-3 xs:px-4 py-2 xs:py-3 text-sm xs:text-base rounded-lg border-2 border-gray-300 focus:border-cyan-700 focus:outline-none" placeholder="john@example.com">
                        </div>

                        <div>
                            <label class="block text-xs xs:text-sm font-semibold text-gray-900 mb-2">Phone Number</label>
                            <input type="tel" class="w-full px-3 xs:px-4 py-2 xs:py-3 text-sm xs:text-base rounded-lg border-2 border-gray-300 focus:border-cyan-700 focus:outline-none" placeholder="+62 812 3456 7890">
                        </div>

                        <div>
                            <label class="block text-xs xs:text-sm font-semibold text-gray-900 mb-2">Message</label>
                            <textarea rows="4" class="w-full px-3 xs:px-4 py-2 xs:py-3 text-sm xs:text-base rounded-lg border-2 border-gray-300 focus:border-cyan-700 focus:outline-none resize-none" placeholder="Tell us how we can help you..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-cyan-700 text-white py-3 sm:py-4 text-sm sm:text-base rounded-lg hover:bg-cyan-800 transition font-semibold">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="relative bg-gradient-to-b from-gray-900 to-black text-white py-8 sm:py-12 md:py-20 overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute top-0 left-0 w-48 sm:w-64 h-48 sm:h-64 bg-cyan-600 rounded-full blur-3xl opacity-10 animate-pulse-glow"></div>
        <div class="absolute bottom-0 right-0 w-48 sm:w-64 h-48 sm:h-64 bg-cyan-500 rounded-full blur-3xl opacity-10 animate-float"></div>
        
        <div class="px-3 xs:px-4 sm:px-8 lg:px-16 relative z-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 xs:gap-7 sm:gap-8 mb-6 sm:mb-8">
                <div class="space-y-3 sm:space-y-4 sm:col-span-2 md:col-span-1">
                    <h2 class="text-lg xs:text-xl sm:text-2xl font-bold text-cyan-400">Investalearning</h2>
                    <p class="text-gray-400 text-xs xs:text-sm leading-relaxed">
                        Empowering investors through comprehensive education and expert guidance.
                    </p>
                    <div class="flex flex-wrap gap-2 sm:gap-3">
                        <a href="#" class="w-8 h-8 xs:w-10 xs:h-10 bg-gray-800 hover:bg-cyan-700 rounded-lg flex items-center justify-center transition">
                            <svg class="w-3.5 h-3.5 xs:w-4 xs:h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-8 h-8 xs:w-10 xs:h-10 bg-gray-800 hover:bg-cyan-700 rounded-lg flex items-center justify-center transition">
                            <svg class="w-3.5 h-3.5 xs:w-4 xs:h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-8 h-8 xs:w-10 xs:h-10 bg-gray-800 hover:bg-cyan-700 rounded-lg flex items-center justify-center transition">
                            <svg class="w-3.5 h-3.5 xs:w-4 xs:h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-8 h-8 xs:w-10 xs:h-10 bg-gray-800 hover:bg-cyan-700 rounded-lg flex items-center justify-center transition">
                            <svg class="w-3.5 h-3.5 xs:w-4 xs:h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="font-semibold text-sm xs:text-base sm:text-lg mb-2.5 xs:mb-3 sm:mb-4">Quick Links</h3>
                    <ul class="space-y-1.5 sm:space-y-2 text-gray-400 text-xs xs:text-sm sm:text-base">
                        <li><a href="#" class="hover:text-cyan-400 transition inline-block py-0.5">About Us</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition inline-block py-0.5">Our Courses</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition inline-block py-0.5">Pricing</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition inline-block py-0.5">Blog</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition inline-block py-0.5">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-sm xs:text-base sm:text-lg mb-2.5 xs:mb-3 sm:mb-4">Resources</h3>
                    <ul class="space-y-1.5 sm:space-y-2 text-gray-400 text-xs xs:text-sm sm:text-base">
                        <li><a href="#" class="hover:text-cyan-400 transition inline-block py-0.5">Help Center</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition inline-block py-0.5">Community</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition inline-block py-0.5">Certifications</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition inline-block py-0.5">Free Resources</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition inline-block py-0.5">FAQ</a></li>
                    </ul>
                </div>

                <div class="sm:col-span-2 md:col-span-1">
                    <h3 class="font-semibold text-sm xs:text-base sm:text-lg mb-2.5 xs:mb-3 sm:mb-4">Newsletter</h3>
                    <p class="text-gray-400 text-xs xs:text-sm mb-3 sm:mb-4 leading-relaxed">Subscribe to get the latest investment tips and market insights.</p>
                    <div class="flex gap-2">
                        <input type="email" placeholder="Your email" class="flex-1 min-w-0 px-2.5 xs:px-3 sm:px-4 py-2 text-xs xs:text-sm rounded-lg bg-gray-800 border border-gray-700 focus:border-cyan-700 focus:outline-none">
                        <button class="bg-cyan-700 px-2.5 xs:px-3 sm:px-4 py-2 rounded-lg hover:bg-cyan-800 transition flex-shrink-0">
                            <svg class="w-4 h-4 xs:w-5 xs:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-4 xs:pt-5 sm:pt-6 md:pt-8 flex flex-col md:flex-row justify-between items-center gap-3 sm:gap-4">
                <p class="text-gray-400 text-xs xs:text-sm text-center md:text-left order-2 md:order-1">© 2026 Investalearning. All rights reserved.</p>
                <div class="flex flex-wrap justify-center gap-2 xs:gap-3 sm:gap-4 md:gap-6 text-xs xs:text-sm text-gray-400 order-1 md:order-2">
                    <a href="#" class="hover:text-cyan-400 transition whitespace-nowrap">Privacy Policy</a>
                    <a href="#" class="hover:text-cyan-400 transition whitespace-nowrap">Terms of Service</a>
                    <a href="#" class="hover:text-cyan-400 transition whitespace-nowrap">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="scrollTop" class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 md:bottom-8 md:right-8 bg-gradient-to-br from-cyan-600 to-cyan-500 text-white w-10 h-10 xs:w-12 xs:h-12 rounded-full flex items-center justify-center shadow-2xl hover:scale-110 transition-all duration-300 z-50 opacity-0 pointer-events-none hover:shadow-cyan-500/50">
        <svg class="w-5 h-5 xs:w-6 xs:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
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
            anchor.addEventListener('click', function (e) {
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