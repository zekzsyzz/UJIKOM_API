<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SIMALAT</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
            <!-- Header -->
            <div class="text-center">
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">Buat Akun Baru</h2>
                <p class="mt-2 text-sm text-slate-500">Daftar untuk mulai meminjam alat</p>
            </div>

            <!-- Session Error / Flash Message -->
            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-100 text-rose-700 p-4 rounded-xl text-xs space-y-1">
                    <p class="font-semibold">Terjadi kesalahan:</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Register -->
            <form class="mt-8 space-y-4" action="{{ route('register') }}" method="POST">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder-slate-400"
                        placeholder="Masukkan nama lengkap">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder-slate-400"
                        placeholder="nama@email.com">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Password</label>
                        <input type="password" name="password" required 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                            placeholder="Minimal 8 karakter">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                            placeholder="Ulangi password">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nomor Handphone</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder-slate-400"
                        placeholder="08xxxxxxxxxx">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Alamat</label>
                    <textarea name="alamat" rows="2" required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder-slate-400"
                        placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                </div>

                <button type="submit" 
                    class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition-all shadow-sm shadow-blue-500/30 mt-2">
                    Daftar Sekarang
                </button>
            </form>

            <!-- Footer Link ke Login -->
            <p class="text-center text-sm text-slate-500 mt-4">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700">Masuk di sini</a>
            </p>
        </div>
    </div>
</body>
</html>