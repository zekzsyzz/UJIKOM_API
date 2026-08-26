<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin')</title>
    <!-- Memuat Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Inter untuk kesan modern -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Kustomisasi scrollbar agar lebih rapi */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <aside class="w-72 bg-slate-900 text-slate-300 flex flex-col hidden md:flex border-r border-slate-800 z-20 shadow-2xl">
            <!-- Header Sidebar (Logo) -->
            <div class="h-20 flex items-center px-6 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-600/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <span class="text-xl font-bold tracking-wider text-white">SIM<span class="text-blue-500">ALAT</span></span>
                </div>
            </div>

            <!-- Navigasi -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <!-- Link: Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600/10 text-blue-500 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
                
                <!-- Link: Kelola User -->
                <a href="{{ route('admin.user.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.user*') ? 'bg-blue-600/10 text-blue-500 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Kelola User
                </a>

                <!-- Link: Kelola Alat -->
                <a href="{{ route('admin.alat.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.alat*') ? 'bg-blue-600/10 text-blue-500 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Kelola Alat
                </a>

                <!-- Link: Kelola Kategori -->
                <a href="{{ route('admin.kategori.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.kategori*') ? 'bg-blue-600/10 text-blue-500 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    Kelola Kategori
                </a>

                <!-- Link: Kelola Peminjaman -->
                <a href="{{ route('admin.peminjaman.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.peminjaman*') ? 'bg-blue-600/10 text-blue-500 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Kelola Peminjaman
                </a>
                <a href="{{ route('admin.pengembalian.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.pengembalian*') ? 'bg-blue-600/10 text-blue-500 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Kelola Pengembalian
                </a>
            </nav>

            <!-- FOOTER SIDEBAR (User Profile & Interactive Dropdown) -->
            <div class="relative p-4 mt-auto border-t border-slate-800">
                
                <!-- Popup Card Profil (Hidden by default) -->
                <div id="profileDropdown" class="hidden absolute bottom-full left-4 right-4 mb-3 bg-slate-800 border border-slate-700 rounded-2xl shadow-xl p-4 transition-all duration-200 transform">
                    <div class="flex items-center gap-3 mb-4 pb-4 border-b border-slate-700">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-500 to-emerald-400 flex items-center justify-center text-white font-bold text-lg shadow-inner">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-white font-bold truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email ?? 'admin@sistem.com' }}</p>
                        </div>
                    </div>
                    
                    <!-- Tombol Logout dalam Card -->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-red-500/10 hover:bg-red-500/20 text-red-500 font-semibold py-2.5 rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Keluar Sistem
                        </button>
                    </form>
                </div>

                <!-- Tombol Trigger Profil di Sidebar -->
                <button onclick="toggleProfile()" class="w-full flex items-center gap-3 p-2 hover:bg-slate-800 rounded-xl transition-colors group">
                    <div class="w-10 h-10 rounded-full bg-blue-600/20 border border-blue-500/30 flex items-center justify-center text-blue-400 font-bold group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="text-left flex-1 overflow-hidden">
                        <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">{{ auth()->user()->role ?? 'Admin' }}</p>
                    </div>
                    <!-- Ikon panah atas (indikator bisa di-klik) -->
                    <svg class="w-4 h-4 text-slate-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                </button>

            </div>
        </aside>

        <!-- MAIN CONTENT CONTAINER -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50/50">

            <!-- NAVBAR ATAS (Lebih Bersih & Elegan) -->
            <header class="bg-white/80 backdrop-blur-md h-20 flex items-center justify-between px-8 z-10 border-b border-slate-200">
                <div class="flex items-center gap-4">
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight">
                        @yield('header-title', 'Dashboard')
                    </h1>
                </div>
                
                <!-- Ruang kosong di kanan (karena logout sudah pindah ke sidebar) -->
                <!-- Anda bisa mengisi ini nanti dengan Notifikasi atau Breadcrumbs -->
                <div class="hidden sm:flex items-center gap-2 text-sm font-medium text-slate-500 bg-slate-100 px-4 py-2 rounded-full">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </div>
            </header>

            <!-- KONTEN UTAMA HALAMAN -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 md:p-8">
                @yield('content')
            </main>

        </div>

    </div>

    <!-- Script Sederhana untuk Toggle Profile Card -->
    <script>
        function toggleProfile() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Menutup dropdown jika user klik di luar area profile
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const profileArea = event.target.closest('.relative.p-4.mt-auto');
            
            if (!profileArea && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>