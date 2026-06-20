<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Dashboard</title>
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
            <a href="#" class="flex items-center gap-4 px-4 py-3.5 bg-primary text-white font-bold rounded-2xl shadow-sm transition-all hover:scale-[1.02]">
                <img src="{{ asset('images/tutor_active.svg') }}" alt="User" class="w-6 h-6">
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

    <main class="flex-1 p-8 lg:p-12 overflow-y-auto bg-background">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <h2 class="text-4xl lg:text-5xl font-bold text-primary tracking-tight">Kelola Pengguna</h2>
            <button onclick="document.getElementById('add-user-modal').classList.remove('hidden')" class="bg-primary text-white font-bold px-8 py-3.5 rounded-xl shadow-lg shadow-primary/30 hover:-translate-y-1 hover:shadow-primary/50 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah User
            </button>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-secondary text-heading text-sm uppercase tracking-widest font-bold">
                            <th class="p-5 px-8">Nama Pengguna</th>
                            <th class="p-5 px-8">Peran</th>
                            <th class="p-5 px-8">Tanggal Daftar</th>
                            <th class="p-5 px-8 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-heading">
                        @foreach ($users as $user)
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                            <td class="p-5 px-8 flex items-center gap-4">
                                <img src="{{ $user->foto_profil ? asset('storage/uploads/' . $user->foto_profil) : asset('images/profile_1_1781900483861.png') }}" alt="Foto Profil" class="w-12 h-12 rounded-full object-cover border-2 border-gray-100 shadow-sm flex-shrink-0">
                                <span class="font-bold text-lg">{{ $user->nama }}</span>
                            </td>
                            <td class="p-5 px-8">
                                @if ($user->role === 'admin')
                                    <span class="bg-purple-100 text-purple-700 px-4 py-1.5 rounded-full text-sm font-bold">Admin</span>
                                @elseif ($user->role === 'tutor')
                                    <span class="bg-green-100 text-green-700 px-4 py-1.5 rounded-full text-sm font-bold">Tutor</span>
                                @else
                                    <span class="bg-blue-100 text-blue-700 px-4 py-1.5 rounded-full text-sm font-bold">Student</span>
                                @endif
                            </td>
                            <td class="p-5 px-8 text-secondary-text font-medium">{{ \Carbon\Carbon::parse($user->created_at ?? now())->format('d M Y') }}</td>
                            <td class="p-5 px-8 text-center space-x-2">
                                <button onclick="openEditModal({{ $user->id_user }}, '{{ $user->nama }}', '{{ $user->email }}')" class="text-secondary-text hover:text-primary font-bold px-2 py-1">Edit</button>
                                <form action="{{ route('admin.user.delete', $user->id_user) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 font-bold px-2 py-1">Hapus</button>
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
    <div id="add-user-modal" class="fixed inset-0 bg-black/60 z-50 hidden flex flex-col items-center justify-center p-4 backdrop-blur-sm transition-opacity">
        <div class="bg-white w-full max-w-lg max-h-[90vh] overflow-y-auto rounded-[2rem] p-8 md:p-10 shadow-2xl relative">
            <button onclick="document.getElementById('add-user-modal').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-heading hover:rotate-90 transition-all focus:outline-none bg-gray-50 rounded-full p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <div class="text-center mb-8">
                <h3 class="text-3xl font-bold text-heading tracking-tight">Tambah User Baru</h3>
                <p class="text-secondary-text mt-2 font-medium">Lengkapi form berikut untuk mendaftarkan pengguna.</p>
            </div>
            
            <form action="{{ route('admin.user.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-heading mb-2">Full Name</label>
                    <input type="text" name="nama" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Enter full name" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-heading mb-2">Email Address</label>
                    <input type="email" name="email" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Enter email address" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-heading mb-2">Role</label>
                    <div class="relative">
                        <select name="role" onchange="toggleTutorFields(this)" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all appearance-none font-medium text-heading bg-white" required>
                            <option value="" disabled selected>Pilih Peran Pengguna</option>
                            <option value="murid">Student</option>
                            <option value="tutor">Tutor</option>
                            <option value="admin">Admin</option>
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
                <div id="tutor_fields" class="hidden space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-heading mb-2">Mata Pelajaran (Khusus Tutor)</label>
                        <div class="relative">
                            <select name="mata_pelajaran" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all appearance-none font-medium text-heading bg-white">
                                <option value="" disabled selected>Pilih Mata Pelajaran</option>
                                <option value="Matematika">Matematika</option>
                                <option value="Bahasa Inggris">Bahasa Inggris</option>
                                <option value="Bahasa Indonesia">Bahasa Indonesia</option>
                                <option value="Fisika">Fisika</option>
                                <option value="Bahasa Latin">Bahasa Latin</option>
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-heading mb-2">Harga Per Jam (Rp)</label>
                        <input type="number" name="harga_per_jam" min="0" step="1000" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Contoh: 150000">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-heading mb-2">Password</label>
                        <input type="password" name="password" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Password" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-heading mb-2">Confirm</label>
                        <input type="password" name="password_confirmation" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Confirm" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-heading mb-2">Foto Profil (Opsional)</label>
                    <input type="file" name="foto_profil" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all bg-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                </div>
                <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-bold shadow-lg shadow-primary/30 hover:-translate-y-1 hover:shadow-primary/50 transition-all mt-6 text-lg tracking-wide">
                    Simpan User
                </button>
            </form>
        </div>
    </div>

    <!-- Edit Modal Form (Pop-up) -->
    <div id="edit-user-modal" class="fixed inset-0 bg-black/60 z-50 hidden flex flex-col items-center justify-center p-4 backdrop-blur-sm transition-opacity">
        <div class="bg-white w-full max-w-lg max-h-[90vh] overflow-y-auto rounded-[2rem] p-8 md:p-10 shadow-2xl relative">
            <button onclick="document.getElementById('edit-user-modal').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-heading hover:rotate-90 transition-all focus:outline-none bg-gray-50 rounded-full p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <div class="text-center mb-8">
                <h3 class="text-3xl font-bold text-heading tracking-tight">Edit User</h3>
                <p class="text-secondary-text mt-2 font-medium">Perbarui informasi pengguna ini.</p>
            </div>
            
            <form id="edit-form" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-bold text-heading mb-2">Full Name</label>
                    <input type="text" name="nama" id="edit_nama" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-heading mb-2">Email Address</label>
                    <input type="email" name="email" id="edit_email" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-heading mb-2">Password (Kosongkan jika tidak ingin diubah)</label>
                    <input type="password" name="password" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="New Password">
                </div>
                <div>
                    <label class="block text-sm font-bold text-heading mb-2">Foto Profil Baru (Opsional)</label>
                    <input type="file" name="foto_profil" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all bg-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                </div>
                <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-bold shadow-lg shadow-primary/30 hover:-translate-y-1 hover:shadow-primary/50 transition-all mt-6 text-lg tracking-wide">
                    Update User
                </button>
            </form>
        </div>
    </div>

    <script>
        function toggleTutorFields(roleSelect) {
            const tutorFields = document.getElementById('tutor_fields');
            if (roleSelect.value === 'tutor') {
                tutorFields.classList.remove('hidden');
                tutorFields.querySelector('select[name="mata_pelajaran"]').required = true;
                tutorFields.querySelector('input[name="harga_per_jam"]').required = true;
            } else {
                tutorFields.classList.add('hidden');
                tutorFields.querySelector('select[name="mata_pelajaran"]').required = false;
                tutorFields.querySelector('input[name="harga_per_jam"]').required = false;
            }
        }

        function openEditModal(id, nama, email) {
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_email').value = email;
            
            // Set form action dynamically
            const form = document.getElementById('edit-form');
            form.action = `/admin/user/${id}`;
            
            document.getElementById('edit-user-modal').classList.remove('hidden');
        }

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
