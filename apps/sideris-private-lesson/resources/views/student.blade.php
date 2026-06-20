<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Sideris Private Lesson</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-secondary-text min-h-screen font-sans antialiased flex flex-col md:flex-row overflow-x-hidden">

    <!-- Mobile Header -->
    <div class="md:hidden bg-white p-4 px-6 flex justify-between items-center border-b border-gray-100 sticky top-0 z-20">
        <h1 class="text-xl font-bold text-primary">SPL<span class="text-secondary">.</span></h1>
        <button id="mobile-menu-btn" class="text-heading focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
        </button>
    </div>

    <!-- Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-20 hidden md:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 ease-in-out z-30 w-72 bg-white border-r border-gray-100 flex flex-col h-screen shadow-sm">
        <!-- Sidebar Header -->
        <div class="p-8 pb-6 border-b border-gray-50">
            <h1 class="text-2xl font-bold text-primary leading-tight">Sideris Private Lesson</h1>
            <p class="text-md font-bold text-heading mt-2">Student Dashboard</p>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-3">
            <a href="{{ route('student') }}" class="flex items-center gap-4 px-4 py-3.5 bg-primary text-white font-bold rounded-2xl shadow-sm transition-all hover:scale-[1.02]">
                <img src="{{ asset('images/home_active.svg') }}" alt="Home" class="w-6 h-6">
                <span class="text-lg">Home</span>
            </a>
            <a href="{{ route('tutor') }}" class="flex items-center gap-4 px-4 py-3.5 text-secondary-text font-bold rounded-2xl transition-all hover:scale-[1.02]">
                <img src="{{ asset('images/tutor_inactive.svg') }}" alt="List Tutor" class="w-6 h-6">
                <span class="text-lg">List Tutor</span>
            </a>
            <a href="{{ route('purchase') }}" class="flex items-center gap-4 px-4 py-3.5 text-secondary-text font-bold rounded-2xl transition-all hover:scale-[1.02]">
                <img src="{{ asset('images/purchase_inactive.svg') }}" alt="My Purchase" class="w-6 h-6">
                <span class="text-lg">My Purchase</span>
            </a>
        </nav>

        <!-- Sidebar Footer (Logout) -->
        <div class="p-4 mt-auto border-t border-gray-50">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-4 px-4 py-3.5 text-secondary-text font-bold rounded-2xl transition-all hover:scale-[1.02]">
                    <img src="{{ asset('images/logout.svg') }}" alt="Logout Icon" class="w-6 h-6 opacity-60">
                    <span class="text-lg">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 lg:p-12 overflow-y-auto bg-background">
        <!-- Header Section -->
        <header class="mb-12">
            <h2 class="text-4xl lg:text-5xl font-bold text-primary tracking-tight">Halo, {{ session('user')['nama'] ?? 'Student' }} !</h2>
            <p class="text-2xl font-bold text-secondary mt-3">Yuk Lanjut Belajar !</p>
        </header>

        <!-- Jadwal Mendatang (Upcoming Schedule) -->
        <section class="w-full">
            <h3 class="text-heading font-bold text-2xl mb-6">Jadwal Mendatang</h3>
            
            @if($jadwal)
            <!-- Card -->
            <div class="bg-primary text-white rounded-[2rem] p-8 flex flex-col md:flex-row items-start md:items-center justify-between shadow-xl shadow-primary/20 relative overflow-hidden">
                

                <div class="flex items-center gap-6 relative z-10">
                    <img src="{{ $jadwal->tutor->foto_profil ? asset('storage/uploads/'.$jadwal->tutor->foto_profil) : asset('images/profile_1_1781900483861.png') }}" alt="Tutor Profile" class="w-20 h-20 rounded-full object-cover border-4 border-secondary shadow-md">
                    <div>
                        <h4 class="text-2xl font-bold">{{ $jadwal->tutor->user->nama }}</h4>
                        <p class="text-white/90 font-bold mt-1 text-lg">{{ $jadwal->tutor->mata_pelajaran }}</p>
                    </div>
                </div>
                
                <div class="mt-6 md:mt-0 flex flex-row md:flex-col lg:flex-row gap-8 lg:gap-12 relative z-10 w-full md:w-auto border-t border-white/20 md:border-none pt-6 md:pt-0">
                    <div class="flex-1 md:text-right lg:text-left">
                        <p class="text-sm text-white/70 font-bold uppercase tracking-widest mb-1">Tanggal</p>
                        <p class="font-bold text-xl">{{ \Carbon\Carbon::parse($jadwal->tanggal_les)->format('d F Y') }}</p>
                    </div>
                    <div class="flex-1 md:text-right lg:text-left">
                        <p class="text-sm text-white/70 font-bold uppercase tracking-widest mb-1">Waktu</p>
                        <p class="font-bold text-xl">{{ \Carbon\Carbon::parse($jadwal->jam_pilihan_murid)->format('H:i') }} WIB</p>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm text-center">
                <p class="text-secondary-text font-medium text-lg">Belum ada jadwal les mendatang. Yuk pesan tutor sekarang!</p>
                <a href="{{ route('tutor') }}" class="inline-block mt-4 bg-primary text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-primary/30 hover:-translate-y-1 hover:shadow-primary/50 transition-all">Pesan Tutor</a>
            </div>
            @endif
        </section>
    </main>

    <script>
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        if (mobileMenuBtn && sidebar && overlay) {
            mobileMenuBtn.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', toggleSidebar);
        }
    </script>
</body>
</html>
