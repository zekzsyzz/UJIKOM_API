@extends('layouts.app')

@section('title', 'Tambah User - Panel Admin')
@section('header-title', 'Tambah Pengguna Baru')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Form Header -->
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="p-2.5 bg-blue-100 text-blue-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Formulir Pendaftaran</h3>
                <p class="text-xs text-slate-500 font-medium">Lengkapi formulir di bawah ini untuk menambahkan pengguna ke dalam sistem.</p>
            </div>
        </div>

        <form action="{{ route('admin.user.store') }}" method="POST" class="p-6 md:p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    @error('name') <span class="text-red-500 text-xs mt-1 ml-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@domain.com"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    @error('email') <span class="text-red-500 text-xs mt-1 ml-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- No HP -->
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Nomor HP <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Kata Sandi</label>
                    <input type="password" name="password" required placeholder="••••••••"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    @error('password') <span class="text-red-500 text-xs mt-1 ml-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Hak Akses</label>
                    <select name="role" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer text-slate-700">
                        <option value="peminjam">Peminjam</option>
                        <option value="petugas">Petugas</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 mt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.user.index') }}" 
                    class="px-6 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-all">
                    Kembali
                </a>
                <button type="submit" 
                    class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection