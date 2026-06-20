<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment - Admin Dashboard</title>
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
            <a href="{{ route('admin') }}" class="flex items-center gap-4 px-4 py-3.5 text-secondary-text font-bold rounded-2xl transition-all hover:scale-[1.02]">
                <img src="{{ asset('images/home_active.svg') }}" alt="Home" class="w-6 h-6" style="filter: brightness(0) invert(75%) sepia(8%) saturate(301%) hue-rotate(182deg) brightness(87%) contrast(85%);">
                <span class="text-lg">Home</span>
            </a>
            <a href="{{ route('admin.user') }}" class="flex items-center gap-4 px-4 py-3.5 text-secondary-text font-bold rounded-2xl transition-all hover:scale-[1.02]">
                <img src="{{ asset('images/tutor_inactive.svg') }}" alt="User" class="w-6 h-6">
                <span class="text-lg">User</span>
            </a>
            <a href="#" class="flex items-center gap-4 px-4 py-3.5 bg-primary text-white font-bold rounded-2xl shadow-sm transition-all hover:scale-[1.02]">
                <img src="{{ asset('images/assignment_active.svg') }}" alt="Assignment" class="w-6 h-6">
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
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <h2 class="text-4xl lg:text-5xl font-bold text-primary tracking-tight">Assignment</h2>
            <button id="open-modal-btn" class="bg-primary text-white font-bold px-8 py-3.5 rounded-xl shadow-lg shadow-primary/30 hover:-translate-y-1 hover:shadow-primary/50 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Assignment
            </button>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-secondary text-heading text-sm uppercase tracking-widest font-bold">
                            <th class="p-5 px-8">Nama Murid</th>
                            <th class="p-5 px-8">Nama Tutor</th>
                            <th class="p-5 px-8">Judul Sesi</th>
                            <th class="p-5 px-8">Tanggal Sesi</th>
                            <th class="p-5 px-8 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-heading">
                        @foreach ($assignments as $assignment)
                        @php
                            $durasi = $assignment->transaksi->total_harga / max($assignment->transaksi->tutor->harga_per_jam, 1);
                        @endphp
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                            <td class="p-5 px-8 font-bold">{{ $assignment->transaksi->murid->nama }}</td>
                            <td class="p-5 px-8 font-medium">{{ $assignment->transaksi->tutor->user->nama }}</td>
                            <td class="p-5 px-8">
                                <div>
                                    <p class="font-bold">{{ $assignment->judul_pertemuan }}</p>
                                    <span class="bg-secondary text-heading px-3 py-0.5 rounded-full text-sm font-bold inline-block mt-1">{{ $assignment->transaksi->tutor->mata_pelajaran }} · {{ $durasi }} jam</span>
                                </div>
                            </td>
                            <td class="p-5 px-8 text-secondary-text font-medium">{{ \Carbon\Carbon::parse($assignment->transaksi->tanggal_les)->format('d M Y') }}, {{ $assignment->transaksi->jam_pilihan_murid }}</td>
                            <td class="p-5 px-8 text-center space-x-2">
                                <form action="{{ route('admin.assignment.delete', $assignment->id_jadwal) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus assignment ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 font-bold px-2 py-1 transition-colors">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal Form (Pop-up) -->
    <div id="assignment-modal" class="fixed inset-0 bg-black/60 z-50 hidden items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white w-full max-w-lg max-h-[90vh] overflow-y-auto rounded-[2rem] p-8 md:p-10 shadow-2xl relative">
            <button id="close-modal-btn" class="absolute top-6 right-6 text-gray-400 hover:text-heading hover:rotate-90 transition-all focus:outline-none bg-gray-50 rounded-full p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div class="text-center mb-8">
                <h3 class="text-3xl font-bold text-heading tracking-tight">Tambah Assignment</h3>
                <p class="text-secondary-text mt-2 font-medium">Buat sesi belajar baru untuk student.</p>
            </div>

            <form action="{{ route('admin.assignment.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-heading mb-2">Judul Sesi</label>
                    <input type="text" name="judul_pertemuan" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Contoh: Integral & Diferensial" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-heading mb-2">Pilih Student</label>
                    <div class="relative">
                        <select name="id_transaksi" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all appearance-none font-medium text-heading bg-white" required>
                            <option value="" disabled selected>Pilih student</option>
                            @foreach ($transaksiList as $t)
                                @php
                                    $durasi = $t->total_harga / max($t->tutor->harga_per_jam, 1);
                                @endphp
                                <option value="{{ $t->id_transaksi }}">{{ $t->murid->nama }} — {{ $t->tutor->mata_pelajaran }} · {{ $durasi }} jam</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-bold shadow-lg shadow-primary/30 hover:-translate-y-1 hover:shadow-primary/50 transition-all mt-4 text-lg tracking-wide">
                    Simpan Assignment
                </button>
            </form>
        </div>
    </div>

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

        const modal = document.getElementById('assignment-modal');
        document.getElementById('open-modal-btn').addEventListener('click', () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
        document.getElementById('close-modal-btn').addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    </script>
</body>
</html>
