<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Peminjaman Alat</title>
    <!-- Memuat Tailwind CSS melalui CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Tambahan font Inter untuk kesan lebih elegan -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="relative min-h-screen flex items-center justify-center overflow-hidden">

    <!-- 1. Background Image -->
    <!-- Ganti URL src di bawah ini dengan path gambar lokal Anda nanti (contoh: asset('images/bg-login.jpg')) -->
    <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=2070&auto=format&fit=crop" 
         alt="Background Laboratorium" 
         class="absolute inset-0 w-full h-full object-cover z-0 scale-105" />
    
    <!-- 2. Overlay Blur & Tone -->
    <!-- backdrop-blur-md menurunkan ketajaman gambar, bg-slate-900/60 memberikan warna gelap yang kalem -->
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md z-0"></div>

    <!-- 3. Card Container (Glassmorphism) -->
    <!-- bg-white/80 dan backdrop-blur-lg membuat efek kaca yang mewah -->
    <div class="relative z-10 w-full max-w-md bg-white/80 p-10 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] border border-white/50 backdrop-blur-lg mx-4 transition-all">
        
        <div class="text-center mb-8">
            <h3 class="text-3xl font-bold text-slate-800 tracking-tight">Login Sistem</h3>
            <p class="text-slate-600 text-sm mt-2 font-medium">Manajemen Peminjaman Alat</p>
        </div>

        <!-- Alert Error Session -->
        @if(session('error'))
            <div class="mb-5 p-4 bg-red-50/90 border border-red-200 text-red-600 rounded-xl text-sm flex items-center gap-2 backdrop-blur-sm shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Alert Error Validasi -->
        @if($errors->any())
            <div class="mb-5 p-4 bg-red-50/90 border border-red-200 text-red-600 rounded-xl text-sm backdrop-blur-sm shadow-sm">
                <div class="flex items-center gap-2 mb-2 font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Terdapat kesalahan:
                </div>
                <ul class="list-disc pl-7 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Input Email -->
            <div>
                <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 bg-white/60 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-800 focus:bg-white transition-all shadow-sm"
                    placeholder="Masukkan email Anda">
            </div>

            <!-- Input Password -->
            <div>
                <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 bg-white/60 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-800 focus:bg-white transition-all shadow-sm"
                    placeholder="••••••••">
            </div>

            <!-- Tombol Submit -->
            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-slate-800 text-white font-semibold py-3.5 rounded-xl hover:bg-slate-900 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0">
                    Masuk
                </button>
            </div>
        </form>
    </div>

</body>
</html>