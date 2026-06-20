<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Purchase - Sideris Private Lesson</title>
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
        <div class="p-8 pb-6 border-b border-gray-50">
            <h1 class="text-2xl font-bold text-primary leading-tight">Sideris Private Lesson</h1>
            <p class="text-md font-bold text-heading mt-2">Student Dashboard</p>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-3">
            <a href="{{ route('student') }}" class="flex items-center gap-4 px-4 py-3.5 text-secondary-text font-bold rounded-2xl transition-all hover:scale-[1.02]">
                <img src="{{ asset('images/home_active.svg') }}" alt="Home" class="w-6 h-6" style="filter: brightness(0) invert(75%) sepia(8%) saturate(301%) hue-rotate(182deg) brightness(87%) contrast(85%);">
                <span class="text-lg">Home</span>
            </a>
            <a href="{{ route('tutor') }}" class="flex items-center gap-4 px-4 py-3.5 text-secondary-text font-bold rounded-2xl transition-all hover:scale-[1.02]">
                <img src="{{ asset('images/tutor_inactive.svg') }}" alt="List Tutor" class="w-6 h-6">
                <span class="text-lg">List Tutor</span>
            </a>
            <a href="#" class="flex items-center gap-4 px-4 py-3.5 bg-primary text-white font-bold rounded-2xl shadow-sm transition-all hover:scale-[1.02]">
                <img src="{{ asset('images/purchase_active.svg') }}" alt="My Purchase" class="w-6 h-6">
                <span class="text-lg">My Purchase</span>
            </a>
        </nav>

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
        <h2 class="text-4xl font-bold text-primary tracking-tight mb-8">Pesanan Saya</h2>

        <div class="space-y-4">
            @forelse ($transaksi as $order)
            <!-- Order Card -->
            <div class="bg-white rounded-[2rem] p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center gap-6 shadow-sm border border-gray-50 hover:shadow-md transition-shadow">
                <!-- Left: ID Pembayaran -->
                <div class="sm:w-1/4">
                    <p class="text-xs text-secondary-text font-bold uppercase tracking-widest mb-2">ID Pembayaran</p>
                    <div class="bg-primary/10 text-primary px-4 py-2 rounded-xl font-bold text-sm tracking-wide inline-block">#INV-{{ \Carbon\Carbon::parse($order->tanggal_les)->format('Ymd') }}{{ $order->id_transaksi }}</div>
                </div>

                <!-- Middle: Tutor Info -->
                <div class="flex items-center gap-4 sm:flex-1">
                    <img src="{{ $order->tutor->foto_profil ? asset('storage/uploads/'.$order->tutor->foto_profil) : asset('images/profile_1_1781900483861.png') }}" alt="Tutor" class="w-14 h-14 rounded-full object-cover shadow-sm flex-shrink-0">
                    <div>
                        <h4 class="text-lg font-bold text-heading">{{ $order->tutor->user->nama }}</h4>
                        <span class="bg-secondary text-heading px-3 py-1 rounded-full font-bold text-sm inline-block mt-1">{{ $order->tutor->mata_pelajaran }}</span>
                    </div>
                </div>

                <!-- Right: Total & Date -->
                <div class="flex sm:flex-col lg:flex-row gap-6 lg:gap-10 sm:text-right lg:text-left sm:items-end lg:items-center w-full sm:w-auto">
                    <div>
                        <p class="text-xs text-secondary-text font-bold uppercase tracking-widest mb-1">Total Pembayaran</p>
                        <p class="font-black text-primary text-xl">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-secondary-text font-bold uppercase tracking-widest mb-1">Tanggal Les</p>
                        <p class="font-bold text-heading text-lg">{{ \Carbon\Carbon::parse($order->tanggal_les)->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm text-center">
                <p class="text-secondary-text font-medium text-lg">Kamu belum memiliki riwayat pesanan.</p>
            </div>
            @endforelse
        </div>
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
