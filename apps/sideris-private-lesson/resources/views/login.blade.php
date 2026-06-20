<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sideris Private Lesson</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-secondary-text min-h-screen font-sans antialiased flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-3xl p-8 sm:p-12 shadow-xl border border-gray-100">
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-block text-5xl font-black text-primary tracking-tight mb-2">SPL<span class="text-secondary">.</span></a>
            <h1 class="text-2xl font-bold text-heading">Selamat Datang</h1>
        </div>
        
        <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
            @if ($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm font-medium">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @csrf
            <div>
                <label for="email" class="block text-sm font-bold text-heading mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Enter your email" required>
            </div>
            
            <div>
                <label for="password" class="block text-sm font-bold text-heading mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Enter your password" required>
            </div>
            
            <button type="submit" class="w-full bg-primary text-white py-3 rounded-xl font-bold shadow-lg shadow-primary/30 hover:-translate-y-1 hover:shadow-primary/50 transition-all mt-4">
                Login
            </button>
        </form>
        
        <div class="mt-8 text-center">
            <p class="text-secondary-text">Belum punya akun? <a href="{{ route('register') }}" class="text-primary font-bold hover:underline">Daftar Sekarang</a></p>
        </div>
    </div>
</body>
</html>