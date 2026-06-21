<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sideris Private Lesson</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-background text-secondary-text min-h-screen font-sans selection:bg-secondary/30 selection:text-heading">
    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
        <div class="w-full px-4 sm:px-8 lg:px-16">
            <div class="flex justify-between items-center py-6">
                <div class="flex-shrink-0">
                    <a href="#" class="text-4xl font-black text-primary tracking-tight">SPL<span class="text-secondary">.</span></a>
                </div>
                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-24">
                    <a href="#home" class="text-primary text-2xl font-extrabold hover:text-primary transition-colors">HOME</a>
                    <a href="#about" class="text-primary text-2xl font-extrabold hover:text-primary transition-colors">ABOUT</a>
                    <a href="#testimony" class="text-primary text-2xl font-extrabold hover:text-primary transition-colors">TESTIMONY</a>
                    <a href="#faq" class="text-primary text-2xl font-extrabold hover:text-primary transition-colors">FAQ</a>
                </div>
                <div class="hidden md:block">
                    <a href="{{ route('login') }}" class="bg-primary text-white px-8 py-2.5 rounded-xl font-bold shadow-lg shadow-primary/30 hover:-translate-y-1 hover:shadow-primary/50 transition-all flex items-center gap-2">
                        <span>LOGIN</span>
                        <img src="{{ asset('images/login_icon.svg') }}" alt="Login Icon" class="w-5 h-5 brightness-0 invert">
                    </a>
                </div>
                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-heading hover:text-primary focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile Menu Container -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 px-4 py-4 space-y-4 shadow-lg absolute w-full left-0 top-[100%]">
            <a href="#home" class="block text-primary text-xl font-extrabold hover:text-primary transition-colors">HOME</a>
            <a href="#about" class="block text-primary text-xl font-extrabold hover:text-primary transition-colors">ABOUT</a>
            <a href="#testimony" class="block text-primary text-xl font-extrabold hover:text-primary transition-colors">TESTIMONY</a>
            <a href="#faq" class="block text-primary text-xl font-extrabold hover:text-primary transition-colors">FAQ</a>
            <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 bg-primary text-white px-8 py-3 rounded-xl font-bold mt-4 shadow-lg shadow-primary/30">
                <span>Login</span>
                <img src="{{ asset('images/login_icon.svg') }}" alt="Login Icon" class="w-5 h-5 brightness-0 invert">
            </a>
        </div>
    </nav>

    <main>
        <!-- Home Section -->
        <section id="home" class="pt-32 pb-20 lg:pt-48 lg:pb-32 bg-bg-light overflow-hidden">
            <div class="w-full px-4 sm:px-8 lg:px-16 relative">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                    <!-- Text (Left) -->
                    <div class="space-y-8 z-10">
                        <h1 class="text-5xl lg:text-7xl font-black text-heading leading-tight">
                            Temukan Tutor <br> <span class="text-primary">Terbaik </span>Untuk <span class="text-primary">Masa Depanmu</span>
                        </h1>
                        <p class="text-xl font-bold text-heading leading-relaxed max-w-lg">
                            Platform belajar privat terpercaya dengan tutor berpengalaman. Kami menghubungkan siswa dengan pengajar profesional untuk mencapai potensi maksimal mereka dalam lingkungan belajar yang suportif
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('login') }}" class="bg-primary text-white px-8 py-4 rounded-xl font-bold shadow-xl shadow-primary/30 hover:-translate-y-1 hover:shadow-primary/50 transition-all text-lg">
                                Mulai Belajar Sekarang
                            </a>
                        </div>
                    </div>
                    <!-- Image (Right) -->
                    <div class="relative z-10 w-full h-[400px] lg:h-[600px] flex justify-center items-center">
                        <!-- Decorative Blob -->
                        <div class="absolute inset-0 bg-primary/20 rounded-[100px] blur-3xl scale-90 rotate-12"></div>
                        <img src="{{ asset('images/hero_illustration_1781900460657.png') }}" alt="Student studying" class="relative w-full h-full object-contain drop-shadow-2xl">
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="py-20 lg:py-32 bg-white">
            <div class="w-full px-4 sm:px-8 lg:px-16">
                <div class="mb-12">
                    <h2 class="text-primary font-bold tracking-widest uppercase text-2xl"><span class="text-heading">Tentang</span> Sideris Private Lesson</h2>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <!-- Image (Left) -->
                    <div class="relative w-full max-w-lg mx-auto aspect-square order-1 flex justify-center items-center">
                        <div class="absolute inset-0 bg-secondary/20 rounded-[100px] blur-3xl scale-90 -rotate-12"></div>
                        <img src="{{ asset('images/about_illustration_1781900474820.png') }}" alt="Teacher explaining" class="relative w-full h-full object-cover rounded-3xl drop-shadow-xl hover:scale-105 transition-transform duration-500">
                    </div>
                    <!-- Text (Right) -->
                    <div class="space-y-6 order-2">
                        <p class="text-lg font-bold text-heading leading-relaxed">
                            <span class="font-bold text-primary">Sideris Private Lesson</span> hadir sebagai solusi terdepan untuk menghubungkan siswa dengan <span class="font-bold text-primary">tutor privat berkualitas</span>. Kami percaya bahwa setiap individu memiliki cara belajar yang unik, dan pendekatan personal adalah kunci kesuksesan akademis. Melalui platform kami, Anda dapat dengan mudah menemukan pengajar yang sesuai dengan kebutuhan spesifik, gaya belajar, dan jadwal Anda. 
                        </p>
                        <p class="text-lg font-bold text-heading leading-relaxed">
                            Dengan <span class="font-bold text-primary">standar kurasi yang ketat</span>, kami memastikan setiap tutor tidak hanya menguasai materi, tetapi juga memiliki kemampuan mengajar yang inspiratif. Misi kami adalah mendemokratisasi akses ke pendidikan privat berkualitas tinggi yang aman, terpercaya, dan fleksibel.
                        </p>
                        <div class="pt-6">
                            <ul class="space-y-4">
                                <li class="flex items-center gap-4 bg-bg-light p-4 rounded-xl hover:-translate-y-1 transition-transform border border-gray-50">
                                    <svg class="w-8 h-8 text-primary flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.11 0 2-.9 2-2V5c0-1.1-.89-2-2-2zm-9 14l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                    <span class="text-heading font-bold text-lg">Kurasi Tutor Ketat & Professional</span>
                                </li>
                                <li class="flex items-center gap-4 bg-bg-light p-4 rounded-xl hover:-translate-y-1 transition-transform border border-gray-50">
                                    <svg class="w-8 h-8 text-primary flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.11 0 2-.9 2-2V5c0-1.1-.89-2-2-2zm-9 14l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                    <span class="text-heading font-bold text-lg">Platform Aman & Transparan</span>
                                </li>
                                <li class="flex items-center gap-4 bg-bg-light p-4 rounded-xl hover:-translate-y-1 transition-transform border border-gray-50">
                                    <svg class="w-8 h-8 text-primary flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.11 0 2-.9 2-2V5c0-1.1-.89-2-2-2zm-9 14l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                    <span class="text-heading font-bold text-lg">Dukungan Pelanggan Responsif</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimony Section -->
        <section id="testimony" class="py-20 lg:py-32 bg-bg-light">
            <div class="w-full px-4 sm:px-8 lg:px-16">
                <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                    <h2 class="text-primary font-bold tracking-widest uppercase text-md">Testimonials</h2>
                    <h3 class="text-4xl lg:text-5xl font-black text-heading">
                        Apa Kata Mereka?
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Card 1 -->
                    <div class="bg-white rounded-3xl p-8 shadow-sm hover:shadow-xl transition-all border border-gray-50 flex flex-col h-full group">
                        <!-- Profile and Info (Horizontal layout as requested) -->
                        <div class="flex items-center gap-4 mb-6">
                            <img src="{{ asset('images/profile_1_1781900483861.png') }}" alt="Sarah Jenkins" class="w-24 h-24 rounded-full object-cover shadow-md border-4 border-secondary group-hover:scale-110 transition-transform">
                            <div>
                                <h4 class="font-bold text-primary text-xl">Sarah Jenkins</h4>
                                <p class="text-secondary text-sm font-bold">Student</p>
                            </div>
                        </div>

                        <p class="text-heading-text leading-relaxed flex-grow text-lg italic">
                            "Belajar di Sideris itu beda banget, tutornya sabar banget jelasin materi yang awalnya susah jadi masuk akal. Sekarang kalau di kelas aku jadi lebih pede buat aktif bertanya dan nggak takut lagi sama ujian!"
                        </p>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white rounded-3xl p-8 shadow-sm hover:shadow-xl transition-all border border-gray-50 flex flex-col h-full group relative">
                        <!-- Profile and Info -->
                        <div class="flex items-center gap-4 mb-6 relative z-10">
                            <img src="{{ asset('images/profile_2_1781900494566.png') }}" alt="Michael Chang" class="w-24 h-24 rounded-full object-cover shadow-md border-4 border-secondary group-hover:scale-110 transition-transform">
                            <div>
                                <h4 class="font-bold text-primary text-xl">Michael Chang</h4>
                                <p class="text-secondary text-sm font-bold">Parent</p>
                            </div>
                        </div>

                        <p class="text-secondary-text leading-relaxed flex-grow text-lg italic relative z-10">
                            "Sebagai orang tua, saya sangat puas dengan Sideris. Komunikasi tutornya sangat terbuka dan mereka benar-benar memantau perkembangan belajar anak saya secara personal. Hasilnya, nilai sekolah meningkat dan anak saya jadi jauh lebih semangat belajar di rumah."
                        </p>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white rounded-3xl p-8 shadow-sm hover:shadow-xl transition-all border border-gray-50 flex flex-col h-full group">
                        <!-- Profile and Info -->
                        <div class="flex items-center gap-4 mb-6">
                            <img src="{{ asset('images/profile_3_1781900505665.png') }}" alt="Emily Rodriguez" class="w-24 h-24 rounded-full object-cover shadow-md border-4 border-secondary group-hover:scale-110 transition-transform">
                            <div>
                                <h4 class="font-bold text-primary text-xl">Emily Rodriguez</h4>
                                <p class="text-secondary text-sm font-bold">Tutor</p>
                            </div>
                        </div>

                        <p class="text-secondary-text leading-relaxed flex-grow text-lg italic">
                            "Mengajar di Sideris memberikan saya ruang untuk menerapkan metode belajar yang kreatif dan personal. Melihat progres murid-murid saya dari yang awalnya ragu menjadi sangat kompeten adalah kepuasan terbesar bagi saya sebagai seorang pendidik."
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section id="faq" class="py-20 lg:py-32 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 space-y-4">
                    <h2 class="text-primary font-bold tracking-widest uppercase text-sm">FAQ</h2>
                    <h3 class="text-4xl lg:text-5xl font-black text-heading">
                        Frequently Asked Questions
                    </h3>
                </div>

                <div class="space-y-4">
                    <!-- FAQ 1 -->
                    <details class="group bg-secondary rounded-2xl p-6 [&_summary::-webkit-details-marker]:hidden cursor-pointer shadow-sm border border-gray-100 hover:border-primary/30 transition-colors" open>
                        <summary class="flex justify-between items-center font-bold text-white text-xl">
                            Bagaimana mendaftar sebagai murid?
                            <span class="transition group-open:rotate-180 text-secondary bg-white/20 rounded-full p-2">
                                <svg fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                            </span>
                        </summary>
                        <p class="text-white mt-4 leading-relaxed text-lg border-t border-white/20 pt-4">
                                Memulai sangat mudah! Cukup klik tombol "Mulai Belajar", isi formulir konsultasi singkat kami, dan kami akan mencocokkan Anda dengan tutor yang tepat dalam waktu 24 jam berdasarkan kebutuhan dan jadwal spesifik Anda.
                            </p>
                    </details>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-white text-secondary-text py-12 border-t border-gray-100">
        <div class="w-full px-4 sm:px-8 lg:px-16">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Left: Social Links Column -->
                <div class="flex flex-col space-y-4 items-center md:items-start">
                    <a href="#" class="flex items-center gap-3 text-heading font-bold hover:text-primary transition-colors">
                        <img src="{{ asset('images/instagram.svg') }}" alt="Instagram" class="w-6 h-6">
                        <span>Instagram</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 text-heading font-bold hover:text-primary transition-colors">
                        <img src="{{ asset('images/youtube.svg') }}" alt="YouTube" class="w-6 h-6">
                        <span>YouTube</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 text-heading font-bold hover:text-primary transition-colors">
                        <img src="{{ asset('images/discord.svg') }}" alt="Discord" class="w-6 h-6">
                        <span>Discord</span>
                    </a>
                </div>
                
                <!-- Center: Copyright -->
                <div class="flex flex-col justify-end text-center pb-2">
                    <p class="text-secondary-text">&copy; 2026 Sideris Private Lesson. All rights reserved.</p>
                </div>
                
                <!-- Right: Logo -->
                <div class="text-center md:text-right">
                    <a href="#" class="text-4xl font-black text-primary tracking-tight">SPL<span class="text-secondary">.</span></a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobile-menu').classList.add('hidden');
            });
        });
    </script>
</body>
</html>
