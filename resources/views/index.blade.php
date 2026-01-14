<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Investalearning - Platform Belajar Saham</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    {{-- navbar --}}
    <nav x-data="{ mobileMenuOpen: false }" class="w-full fixed z-100 bg-white border-b border-b-cyan-200 shadow-sm">
        <div class="px-4 sm:px-8 lg:px-16">
            <div class="flex justify-between items-center h-16">
                {{-- Logo --}}
                <div class="flex items-center space-x-16">
                    <h2 class="text-2xl md:text-3xl font-bold text-cyan-800">Investalearning</h2>

                     <div class="hidden md:flex items-center space-x-8">
                        <a href="#" class="text-lg text-gray-700 hover:text-cyan-800 transition">Home</a>
                        <a href="#" class="text-lg text-gray-700 hover:text-cyan-800 transition">Courses</a>
                        <a href="#" class="text-lg text-gray-700 hover:text-cyan-800 transition">About</a>
                        <a href="#" class="text-lg text-gray-700 hover:text-cyan-800 transition">Contact</a>
                    </div>
                </div>

                {{-- Desktop Menu --}}
               

                {{-- Desktop Buttons --}}
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{route('register')}}" class="bg-cyan-700 text-white px-6 py-2 hover:bg-cyan-800 rounded-lg transition">Register</a>
                    <a href="{{route('login')}}" class="bg-white text-cyan-800 px-6 py-2 border-2 border-gray-300 rounded-lg hover:bg-cyan-700 hover:text-white transition">Login</a>
                </div>

                {{-- Mobile menu button --}}
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 hover:text-cyan-800 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2"
             class="md:hidden bg-white border-t border-gray-300">
            <div class="px-4 py-3 space-y-3">
                <a href="#" class="block text-lg text-gray-700 hover:text-cyan-800 py-2 transition">Home</a>
                <a href="#" class="block text-lg text-gray-700 hover:text-cyan-800 py-2 transition">Courses</a>
                <a href="#" class="block text-lg text-gray-700 hover:text-cyan-800 py-2 transition">About</a>
                <a href="#" class="block text-lg text-gray-700 hover:text-cyan-800 py-2 transition">Contact</a>
                <div class="pt-4 space-y-2">
                    <a href="{{ route('register') }}" class="block w-full text-center bg-cyan-700 text-white px-4 py-2 hover:bg-cyan-800 rounded-lg transition">Register</a>
                    <a href="{{ route('login') }}" class="block w-full text-center bg-white text-cyan-800 px-4 py-2 border-2 border-gray-300 rounded-lg hover:bg-cyan-700 hover:text-white transition">Login</a>
                </div>
            </div>
        </div>
    </nav>   
    {{-- Hero Section --}}
    <section class="bg-gradient-to-br from-gray-50 to-gray-100 py-12 md:py-20">
        <div class="px-4 sm:px-8 lg:px-16">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                {{-- Left Content --}}
                <div class="space-y-8">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
                       Platform Sertifikasi   <br>
                        Pasar Modal <br>
                        <span class="text-cyan-700">Indonesia</span>
                    </h1>
                    
                    <p class="text-lg md:text-xl text-gray-600 max-w-lg">
                       Tingkatkan kompetensi investasi Anda dengan sertifikasi profesional yang diakui OJK dan BNSP
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4">
                       
                        <button class="bg-cyan-700 text-white px-8 py-4 rounded-lg hover:bg-cyan-800 transition font-semibold flex items-center justify-center gap-2">
                            Daftar Sekarang
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Trusted By --}}
                   
                </div>

                {{-- Right Content - Card Mockup --}}
              <div class="relative">
                {{-- Main Card --}}
                <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 relative z-10">
                    {{-- Header --}}
                    <div class="mb-6 text-center">
                        <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                            Siap Memulai Perjalanan Investasi Profesional?
                        </h3>
                        <p class="text-gray-600 text-sm md:text-base">
                            Bergabunglah dengan ribuan investor Indonesia yang telah meningkatkan kompetensi
                            melalui <span class="font-semibold text-cyan-700">Investalearning</span>
                        </p>
                    </div>

                    {{-- Highlight Section (ganti portfolio value) --}}
                    <div class="mb-6 text-center">
                        <p class="text-sm text-gray-500 mb-1">Platform Edukasi Investasi</p>
                        <h2 class="text-4xl font-bold text-gray-900">Terpercaya & Profesional</h2>
                        <p class="text-sm text-gray-500 mt-1">Belajar • Analisa • Bertumbuh</p>
                    </div>

                    {{-- Benefit List (ganti payment methods) --}}
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-3 p-3 border-2 border-cyan-700 rounded-lg bg-cyan-50">
                            <span class="w-2 h-2 bg-cyan-700 rounded-full"></span>
                            <span class="font-semibold text-gray-900">
                                Materi Investasi Terstruktur
                            </span>
                        </div>

                        <div class="flex items-center gap-3 p-3 border border-gray-300 rounded-lg">
                            <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                            <span class="font-medium text-gray-700">
                                Mentor Berpengalaman & Praktis
                            </span>
                        </div>
                    </div>

                    {{-- CTA Button --}}
                    <button
                        class="w-full bg-gray-900 text-white py-4 rounded-lg hover:bg-gray-800 transition font-semibold">
                        Daftar Sekarang
                    </button>
                </div>
            </div>

            </div>
        </div>
    </section>

   
    {{-- Features Section --}}
    <section class="bg-cyan-50 py-12 md:py-20">
        <div class="px-4 sm:px-8 lg:px-16">
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-cyan-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Expert Courses</h3>
                    <p class="text-gray-600">Learn from industry professionals with years of trading experience.</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-cyan-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Real-Time Data</h3>
                    <p class="text-gray-600">Access live market data and make informed investment decisions.</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-cyan-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Community Support</h3>
                    <p class="text-gray-600">Join thousands of investors sharing strategies and insights.</p>
                </div>
            </div>
        </div>
    </section>

     <section id="about" class="bg-gradient-to-br from-gray-50 to-white py-12 md:py-20">
        <div class="px-4 sm:px-8 lg:px-16">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <span class="text-cyan-700 font-semibold text-sm uppercase tracking-wide">About Us</span>
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900">
                        Empowering investors through education
                    </h2>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        Investalearning was founded with a simple mission: to democratize financial education and make investing accessible to everyone. We believe that anyone can become a successful investor with the right knowledge and tools.
                    </p>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        Our platform combines comprehensive courses, expert insights, and practical trading simulations to help you build confidence in your investment decisions. Whether you're just starting or looking to refine your strategy, we're here to guide you every step of the way.
                    </p>
                    <div class="grid grid-cols-3 gap-6 pt-6">
                        <div class="text-center">
                            <h3 class="text-3xl font-bold text-cyan-700">50K+</h3>
                            <p class="text-sm text-gray-600 mt-1">Active Students</p>
                        </div>
                        <div class="text-center">
                            <h3 class="text-3xl font-bold text-cyan-700">200+</h3>
                            <p class="text-sm text-gray-600 mt-1">Expert Courses</p>
                        </div>
                        <div class="text-center">
                            <h3 class="text-3xl font-bold text-cyan-700">95%</h3>
                            <p class="text-sm text-gray-600 mt-1">Success Rate</p>
                        </div>
                    </div>
                </div>
                <div class="relative">
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

      <section x-data="{ isPlaying: false, showOverlay: true }" class="relative min-h-screen flex items-center justify-center overflow-hidden">
        
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>

        {{-- Content Container --}}
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            {{-- Header Info (Optional - bisa dihapus jika mau pure video) --}}
            <div class="text-center mb-8" x-show="showOverlay" x-transition>
                <h1 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4">
                    Pelajari Investasi Saham dari Ahlinya
                </h1>
                <p class="text-gray-600 text-lg md:text-xl">
                    Tutorial lengkap untuk pemula hingga profesional
                </p>
            </div>

            {{-- Video Container --}}
            <div class="relative rounded-2xl overflow-hidden shadow-2xl bg-black" 
                 @click="showOverlay = false">
                
                {{-- YouTube Iframe --}}
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

                {{-- Overlay Gradient (Optional) --}}
                <div x-show="showOverlay" 
                     x-transition
                     class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/60 flex items-center justify-center cursor-pointer">
                    <button @click="isPlaying = true; showOverlay = false" 
                            class="w-24 h-24 bg-cyan-600 hover:bg-cyan-700 rounded-full flex items-center justify-center transform hover:scale-110 transition shadow-2xl">
                        <svg class="w-12 h-12 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Video Info Below --}}
            <div class="mt-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="text-black">
                    <h3 class="text-xl font-bold mb-2">Strategi Investasi Saham untuk Pemula</h3>
                    <p class="text-gray-400 text-sm">Investalearning • 1.2M views • 2 days ago</p>
                </div>
                <div class="flex gap-3">
                    <button class="bg-gray-800 hover:bg-gray-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                        </svg>
                        Like
                    </button>
                    <button class="bg-gray-800 hover:bg-gray-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                        </svg>
                        Share
                    </button>
                </div>
            </div>
        </div>

        {{-- Decorative Elements --}}
        <div class="absolute top-20 left-10 w-32 h-32 bg-cyan-500 rounded-full opacity-10 blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-40 h-40 bg-cyan-600 rounded-full opacity-10 blur-3xl"></div>
    </section>

    {{-- Mission Section --}}
    <section id="mission" class="py-12 md:py-20 bg-cyan-50">
        <div class="px-4 sm:px-8 lg:px-16">
            <div class="text-center mb-16">
                <span class="text-cyan-700 font-semibold text-sm uppercase tracking-wide">Our Mission</span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mt-4">
                    What drives us forward
                </h2>
                <p class="text-xl text-gray-600 mt-4 max-w-3xl mx-auto">
                    We're committed to transforming lives through financial literacy and investment education
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-gradient-to-br from-cyan-50 to-white p-8 rounded-2xl border border-cyan-100 hover:shadow-xl transition">
                    <div class="w-14 h-14 bg-cyan-700 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Empower</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Equipping individuals with the knowledge and confidence to take control of their financial future through strategic investing.
                    </p>
                </div>

                <div class="bg-gradient-to-br from-cyan-50 to-white p-8 rounded-2xl border border-cyan-100 hover:shadow-xl transition">
                    <div class="w-14 h-14 bg-cyan-700 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Educate</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Providing comprehensive, expert-led courses that make complex financial concepts accessible to everyone, from beginners to advanced traders.
                    </p>
                </div>

                <div class="bg-gradient-to-br from-cyan-50 to-white p-8 rounded-2xl border border-cyan-100 hover:shadow-xl transition">
                    <div class="w-14 h-14 bg-cyan-700 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Excel</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Supporting our community in achieving their investment goals through continuous learning, practical tools, and expert guidance.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Certification Section --}}
    <section id="certification" class="py-12 md:py-20 bg-cyan-950 text-white">
        <div class="px-4 sm:px-8 lg:px-16">
            {{-- Header --}}
            <div class="text-center mb-16">
                <span class="text-cyan-400 font-semibold text-sm uppercase tracking-wide">
                    Certification
                </span>
                <h2 class="text-4xl md:text-5xl font-bold mt-4">
                    Get certified and stand out
                </h2>
                <p class="text-xl text-gray-300 mt-4 max-w-3xl mx-auto">
                    Earn recognized certifications that validate your investment knowledge and skills
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-center">
                {{-- Left Content --}}
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-cyan-600 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">
                                Industry-Recognized Certificates
                            </h3>
                            <p class="text-gray-300">
                                Receive certificates acknowledged by leading financial institutions and employers worldwide.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-cyan-600 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">
                                Comprehensive Assessment
                            </h3>
                            <p class="text-gray-300">
                                Complete rigorous exams that test your understanding of investment principles and market strategies.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-cyan-600 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">
                                Career Advancement
                            </h3>
                            <p class="text-gray-300">
                                Boost your professional profile and open doors to new opportunities in the financial sector.
                            </p>
                        </div>
                    </div>

                    <div class="pt-6">
                        <a href="#"
                            class="inline-flex items-center gap-2 bg-cyan-600 text-white px-8 py-4 rounded-lg hover:bg-cyan-700 transition font-semibold">
                            View Certification Programs
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Right Card --}}
                <div class="relative">
                    <div class="bg-gradient-to-br from-white to-gray-100 p-8 rounded-2xl shadow-2xl border-4 border-cyan-600">
                        <div class="text-center mb-6">
                            <div
                                class="w-20 h-20 bg-cyan-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.5c-2.28 0-4.39-.636-6.16-1.922L12 14z"></path>
                                </svg>
                            </div>

                            <h3 class="text-2xl font-bold text-gray-900">
                                Certified Investment Professional
                            </h3>
                            <p class="text-gray-600 mt-2">
                                Official certification issued by Investalearning
                            </p>
                        </div>

                        <div class="space-y-4 text-gray-700">
                            <div class="flex justify-between">
                                <span>Level</span>
                                <span class="font-semibold">Professional</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Duration</span>
                                <span class="font-semibold">12 Weeks</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Assessment</span>
                                <span class="font-semibold">Final Exam</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Certificate</span>
                                <span class="font-semibold">Digital & Verifiable</span>
                            </div>
                        </div>

                        <div class="mt-8">
                            <a href="#"
                                class="block w-full text-center bg-gray-900 text-white py-4 rounded-lg font-semibold hover:bg-gray-800 transition">
                                Get Certified Now
                            </a>
                        </div>
                    </div>

                    {{-- Decorative Glow --}}
                    <div class="absolute -top-6 -right-6 w-32 h-32 bg-cyan-400 rounded-full blur-3xl opacity-30"></div>
                    <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-cyan-300 rounded-full blur-3xl opacity-30"></div>
                </div>
            </div>
        </div>
    </section>



    {{-- Contact Section --}}
    <section id="contact" class="py-12 md:py-20 bg-white">
        <div class="px-4 sm:px-8 lg:px-16">
            <div class="grid md:grid-cols-2 gap-12">
                <div class="space-y-8">
                    <div>
                        <span class="text-cyan-700 font-semibold text-sm uppercase tracking-wide">Contact Us</span>
                        <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mt-4">
                            Let's start a conversation
                        </h2>
                        <p class="text-lg text-gray-600 mt-4">
                            Have questions about our courses or need guidance? We're here to help you on your investment journey.
                        </p>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-1">Email</h3>
                                <p class="text-gray-600">support@investalearning.com</p>
                                <p class="text-gray-600">hello@investalearning.com</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-1">Phone</h3>
                                <p class="text-gray-600">+62 21 1234 5678</p>
                                <p class="text-gray-600">+62 812 3456 7890</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-1">Office</h3>
                                <p class="text-gray-600">Jl. Sudirman No. 123<br>Jakarta Pusat, 10220<br>Indonesia</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <a href="#" class="w-12 h-12 bg-gray-100 hover:bg-cyan-700 rounded-lg flex items-center justify-center transition group">
                            <svg class="w-5 h-5 text-gray-600 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-12 h-12 bg-gray-100 hover:bg-cyan-700 rounded-lg flex items-center justify-center transition group">
                            <svg class="w-5 h-5 text-gray-600 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-12 h-12 bg-gray-100 hover:bg-cyan-700 rounded-lg flex items-center justify-center transition group">
                            <svg class="w-5 h-5 text-gray-600 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-12 h-12 bg-gray-100 hover:bg-cyan-700 rounded-lg flex items-center justify-center transition group">
                            <svg class="w-5 h-5 text-gray-600 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="bg-gray-50 p-8 rounded-2xl">
                    <form class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Full Name</label>
                            <input type="text" class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-cyan-700 focus:outline-none" placeholder="John Doe">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Email Address</label>
                            <input type="email" class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-cyan-700 focus:outline-none" placeholder="john@example.com">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Phone Number</label>
                            <input type="tel" class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-cyan-700 focus:outline-none" placeholder="+62 812 3456 7890">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Message</label>
                            <textarea rows="4" class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-cyan-700 focus:outline-none" placeholder="Tell us how we can help you..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-cyan-700 text-white py-4 rounded-lg hover:bg-cyan-800 transition font-semibold">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-white py-12 md:py-20">
        <div class="px-4 sm:px-8 lg:px-16">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div class="space-y-4">
                    <h2 class="text-2xl font-bold text-cyan-400">Investalearning</h2>
                    <p class="text-gray-400 text-sm">
                        Empowering investors through comprehensive education and expert guidance.
                    </p>
                    <div class="flex gap-3">
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-cyan-700 rounded-lg flex items-center justify-center transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-cyan-700 rounded-lg flex items-center justify-center transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-cyan-700 rounded-lg flex items-center justify-center transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-cyan-700 rounded-lg flex items-center justify-center transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="font-semibold text-lg mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-cyan-400 transition">About Us</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Our Courses</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Pricing</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Blog</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-lg mb-4">Resources</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-cyan-400 transition">Help Center</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Community</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Certifications</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Free Resources</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">FAQ</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-lg mb-4">Newsletter</h3>
                    <p class="text-gray-400 text-sm mb-4">Subscribe to get the latest investment tips and market insights.</p>
                    <div class="flex gap-2">
                        <input type="email" placeholder="Your email" class="flex-1 px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 focus:border-cyan-700 focus:outline-none text-sm">
                        <button class="bg-cyan-700 px-4 py-2 rounded-lg hover:bg-cyan-800 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-400 text-sm">© 2026 Investalearning. All rights reserved.</p>
                <div class="flex gap-6 text-sm text-gray-400">
                    <a href="#" class="hover:text-cyan-400 transition">Privacy Policy</a>
                    <a href="#" class="hover:text-cyan-400 transition">Terms of Service</a>
                    <a href="#" class="hover:text-cyan-400 transition">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    
</body>
</html>