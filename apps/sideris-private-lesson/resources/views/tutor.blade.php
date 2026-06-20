<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Tutor - Sideris Private Lesson</title>
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
            <a href="{{ route('student') }}" class="flex items-center gap-4 px-4 py-3.5 text-secondary-text font-bold rounded-2xl transition-all hover:scale-[1.02]">
                <img src="{{ asset('images/home_active.svg') }}" alt="Home" class="w-6 h-6" style="filter: brightness(0) invert(75%) sepia(8%) saturate(301%) hue-rotate(182deg) brightness(87%) contrast(85%);">
                <span class="text-lg">Home</span>
            </a>
            <a href="{{route('tutor')}}" class="flex items-center gap-4 px-4 py-3.5 bg-primary text-white font-bold rounded-2xl shadow-sm transition-all hover:scale-[1.02]">
                <img src="{{ asset('images/tutor_active.svg') }}" alt="List Tutor" class="w-6 h-6">
                <span class="text-lg">List Tutor</span>
            </a>
            <a href="{{route('purchase')}}" class="flex items-center gap-4 px-4 py-3.5 text-secondary-text font-bold rounded-2xl transition-all hover:scale-[1.02]">
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
        <div class="flex flex-col lg:flex-row gap-12 max-w-7xl mx-auto">
            
            <!-- Left: Filter Form -->
            <div class="w-full lg:w-1/4 flex-shrink-0">
                <h2 class="text-3xl font-bold text-heading mb-6 tracking-tight">Cari Tutormu</h2>
                
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-50">
                    <h3 class="text-lg font-bold text-heading mb-4 border-b border-gray-100 pb-2">Mata Pelajaran</h3>
                    <form action="{{ route('tutor') }}" method="GET" id="filter-form">
                        <div class="space-y-3">
                            @php
                                $allSubjects = ['Matematika', 'Bahasa Inggris', 'Bahasa Indonesia', 'Fisika', 'Bahasa Latin'];
                            @endphp
                            @foreach ($allSubjects as $subj)
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="subjects[]" value="{{ $subj }}" class="w-5 h-5 rounded border-gray-300 text-secondary focus:ring-secondary accent-secondary" {{ in_array($subj, $selectedSubjects) ? 'checked' : '' }}>
                                <span class="text-heading font-medium group-hover:text-primary transition-colors">{{ $subj }}</span>
                            </label>
                            @endforeach
                        </div>
                        <button type="submit" class="w-full bg-primary text-white py-3 mt-6 rounded-xl font-bold shadow-lg shadow-primary/30 hover:-translate-y-1 hover:shadow-primary/50 transition-all">
                            Terapkan Filter
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Right: Tutor Cards Grid -->
            <div class="flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                    
                    @forelse ($tutors as $tutor)
                    <!-- Tutor Card -->
                    <div class="bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-xl transition-all border border-gray-50 flex flex-col group">
                        <!-- Top: Photo -->
                        <div class="h-48 overflow-hidden relative">
                            <img src="{{ $tutor->foto_profil ? asset('storage/uploads/'.$tutor->foto_profil) : asset('images/profile_1_1781900483861.png') }}" alt="{{ $tutor->user->nama }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <!-- Bottom: Info -->
                        <div class="p-6 flex flex-col flex-1">
                            <div class="flex justify-between items-start mb-3">
                                <h4 class="text-xl font-bold text-heading">{{ $tutor->user->nama }}</h4>
                            </div>
                            <!-- Subject Tag (Oval) -->
                            <div class="mb-4">
                                <span class="bg-secondary text-heading px-4 py-1.5 rounded-full font-bold text-sm inline-block">{{ $tutor->mata_pelajaran }}</span>
                            </div>
                            
                            <!-- Description -->
                            <p class="text-secondary-text mb-6 line-clamp-3 text-sm leading-relaxed flex-1">
                                Pengajar profesional dan berpengalaman untuk mata pelajaran {{ $tutor->mata_pelajaran }}.
                            </p>
                            
                            <!-- Price & Button -->
                            <div class="mt-auto">
                                <div class="mb-4">
                                    <span class="text-xl font-black text-primary">Rp {{ number_format($tutor->harga_per_jam, 0, ',', '.') }}</span>
                                    <span class="text-secondary-text text-sm font-medium"> / jam</span>
                                </div>
                                <button onclick="openOrderModal({{ $tutor->id_tutor }}, '{{ $tutor->user->nama }}', {{ $tutor->harga_per_jam }}, {{ $tutor->jam_tersedia }})" class="w-full bg-primary text-white py-3 rounded-xl font-bold shadow-lg shadow-primary/30 hover:-translate-y-1 hover:shadow-primary/50 transition-all">
                                    Pesan Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-1 md:col-span-2 xl:col-span-3 text-center py-12">
                        <p class="text-secondary-text font-medium text-lg">Tidak ada tutor yang sesuai dengan filter.</p>
                    </div>
                    @endforelse

                </div>
            </div>
        </div>
    </main>

    <!-- Modal Form (Pop-up) Order -->
    <div id="order-modal" class="fixed inset-0 bg-black/60 z-50 hidden flex flex-col items-center justify-center p-4 backdrop-blur-sm transition-opacity">
        <div class="bg-white w-full max-w-md rounded-[2rem] p-8 md:p-10 shadow-2xl relative">
            <button onclick="closeOrderModal()" class="absolute top-6 right-6 text-gray-400 hover:text-heading hover:rotate-90 transition-all focus:outline-none bg-gray-50 rounded-full p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <div class="text-center mb-8">
                <h3 class="text-3xl font-bold text-heading tracking-tight">Pesan Sesi</h3>
                <p class="text-secondary-text mt-2 font-medium" id="modal_tutor_name">Nama Tutor</p>
            </div>
            
            <form action="{{ route('tutor.order') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="id_tutor" id="modal_id_tutor">
                
                <div>
                    <label class="block text-sm font-bold text-heading mb-2">Tanggal Les</label>
                    <input type="date" name="tanggal_les" min="{{ now()->toDateString() }}" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all font-medium text-heading" required>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-heading mb-2">Jam Sesi</label>
                    <div class="relative">
                        <select name="jam_pilihan_murid" id="modal_jam_tersedia" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all appearance-none font-medium text-heading bg-white" required>
                            <!-- Populated dynamically -->
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-heading mb-2">Durasi (Jam)</label>
                    <input type="number" name="durasi_jam" id="modal_durasi_jam" min="1" max="8" value="1" oninput="calculateTotal()" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" required>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                    <span class="text-sm font-bold text-secondary-text uppercase tracking-widest">Total Harga</span>
                    <span class="text-2xl font-black text-primary" id="modal_total_harga">Rp 0</span>
                </div>

                <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-bold shadow-lg shadow-primary/30 hover:-translate-y-1 hover:shadow-primary/50 transition-all mt-6 text-lg tracking-wide">
                    Konfirmasi Pesanan
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

        let currentTutorPrice = 0;
        function openOrderModal(id, name, price, availableHours) {
            document.getElementById('modal_id_tutor').value = id;
            document.getElementById('modal_tutor_name').innerText = 'Bersama: ' + name;
            currentTutorPrice = price;
            
            let select = document.getElementById('modal_jam_tersedia');
            select.innerHTML = '';
            if (typeof availableHours === 'string') {
                try {
                    availableHours = JSON.parse(availableHours);
                } catch(e) {
                    availableHours = [];
                }
            }
            if (Array.isArray(availableHours)) {
                availableHours.forEach(hour => {
                    let opt = document.createElement('option');
                    opt.value = hour;
                    opt.innerHTML = hour;
                    select.appendChild(opt);
                });
            }
            
            document.getElementById('modal_durasi_jam').value = 1;
            calculateTotal();
            
            const modal = document.getElementById('order-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function calculateTotal() {
            let durasi = document.getElementById('modal_durasi_jam').value;
            let total = durasi * currentTutorPrice;
            document.getElementById('modal_total_harga').innerText = 'Rp ' + total.toLocaleString('id-ID');
        }

        function closeOrderModal() {
            const modal = document.getElementById('order-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</body>
</html>
