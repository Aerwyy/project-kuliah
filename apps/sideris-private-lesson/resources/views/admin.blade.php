<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Sideris Private Lesson</title>
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

    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-20 hidden md:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 ease-in-out z-30 w-72 bg-white border-r border-gray-100 flex flex-col h-screen shadow-sm">
        <div class="p-8 pb-6 border-b border-gray-50">
            <h1 class="text-2xl font-bold text-primary leading-tight">Sideris Private Lesson</h1>
            <p class="text-md font-bold text-heading mt-2">Admin Dashboard</p>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-3">
            <a href="{{ route('admin') }}" class="flex items-center gap-4 px-4 py-3.5 bg-primary text-white font-bold rounded-2xl shadow-sm transition-all hover:scale-[1.02]">
                <img src="{{ asset('images/home_active.svg') }}" alt="Home" class="w-6 h-6">
                <span class="text-lg">Home</span>
            </a>
            <a href="{{ route('admin.user') }}" class="flex items-center gap-4 px-4 py-3.5 text-secondary-text font-bold rounded-2xl transition-all hover:scale-[1.02]">
                <img src="{{ asset('images/tutor_inactive.svg') }}" alt="User" class="w-6 h-6">
                <span class="text-lg">User</span>
            </a>
            <a href="{{ route('admin.assignment') }}" class="flex items-center gap-4 px-4 py-3.5 text-secondary-text font-bold rounded-2xl transition-all hover:scale-[1.02]">
                <img src="{{ asset('images/assignment_inactive.svg') }}" alt="Assignment" class="w-6 h-6">
                <span class="text-lg">Assignment</span>
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
        <header class="mb-10">
            <h2 class="text-4xl lg:text-5xl font-bold text-primary tracking-tight">Halo, Admin !</h2>
        </header>

        <!-- Stats Boxes -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50 hover:-translate-y-1 transition-transform">
                <p class="text-secondary-text font-bold text-sm uppercase tracking-widest mb-3">Total User</p>
                <p class="text-5xl font-black text-heading">{{ number_format($totalUser) }}</p>
            </div>
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50 hover:-translate-y-1 transition-transform">
                <p class="text-secondary-text font-bold text-sm uppercase tracking-widest mb-3">Total Student</p>
                <p class="text-5xl font-black text-heading">{{ number_format($totalStudent) }}</p>
            </div>
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50 hover:-translate-y-1 transition-transform">
                <p class="text-secondary-text font-bold text-sm uppercase tracking-widest mb-3">Total Tutor</p>
                <p class="text-5xl font-black text-heading">{{ number_format($totalTutor) }}</p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-50 overflow-hidden">
            <div class="p-8 border-b border-gray-100">
                <h3 class="text-2xl font-bold text-heading">Riwayat Transaksi Terbaru</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-secondary text-heading text-sm uppercase tracking-widest font-bold">
                            <th class="p-5 px-8">ID Pesanan</th>
                            <th class="p-5 px-8">Nama Pengguna</th>
                            <th class="p-5 px-8">Nama Tutor</th>
                            <th class="p-5 px-8">Tanggal</th>
                            <th class="p-5 px-8 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="text-heading">
                        @forelse ($transaksiTerbaru as $trx)
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                            <td class="p-5 px-8 font-bold text-primary">#INV-{{ \Carbon\Carbon::parse($trx->tanggal_les)->format('Ymd') }}{{ $trx->id_transaksi }}</td>
                            <td class="p-5 px-8 font-bold">{{ $trx->murid->nama }}</td>
                            <td class="p-5 px-8 font-medium">{{ $trx->tutor->user->nama }}</td>
                            <td class="p-5 px-8 text-secondary-text font-medium">{{ \Carbon\Carbon::parse($trx->tanggal_les)->format('d M Y') }}</td>
                            <td class="p-5 px-8 text-right font-black">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-5 px-8 text-center text-secondary-text font-medium">Belum ada transaksi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
