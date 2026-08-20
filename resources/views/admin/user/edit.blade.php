@extends('layouts.app')

@section('title', 'Edit User - Panel Admin')
@section('header-title', 'Edit Data Pengguna')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Form Header -->
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="p-2.5 bg-amber-100 text-amber-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Perbarui Informasi</h3>
                <p class="text-xs text-slate-500 font-medium">Ubah data identitas atau hak akses pengguna di bawah ini.</p>
            </div>
        </div>

        <form action="{{ route('admin.user.update', $user->id) }}" method="POST" class="p-6 md:p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                </div>

                <!-- No HP -->
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Nomor HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Kata Sandi Baru 
                        <span class="text-[11px] text-slate-400 font-normal ml-1">(Kosongkan jika tidak diubah)</span>
                    </label>
                    <input type="password" name="password" placeholder="••••••••"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Hak Akses</label>
                    <select name="role" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer">
                        <option value="peminjam" {{ $user->role == 'peminjam' ? 'selected' : '' }}>Peminjam</option>
                        <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 mt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.user.index') }}" 
                    class="px-6 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-all">
                    Batal
                </a>
                <button type="submit" 
                    class="px-6 py-2.5 text-sm font-semibold text-white bg-amber-500 rounded-xl hover:bg-amber-600 transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection