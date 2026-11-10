@extends('layouts.app')

@section('title', 'Edit Profil')
@section('header-title', 'Form Edit Data Diri')

@section('content')
<div class="w-full flex-1 min-h-screen bg-slate-50 p-6 md:p-8 max-w-4xl mx-auto">
    
    <!-- Tampilkan Error Validasi -->
    @if ($errors->any())
        <div class="mb-6 bg-rose-50 border border-rose-100 text-rose-700 p-4 rounded-xl text-xs space-y-1">
            <p class="font-semibold">Terjadi kesalahan input:</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden p-6 md:p-8 space-y-6">
        <div>
            <h3 class="text-lg font-bold text-slate-900">Ubah Informasi Profil</h3>
            <p class="text-sm text-slate-500">Perbarui informasi data diri, foto profil, atau password Anda.</p>
        </div>

        <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Foto Profil -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Foto Profil</label>
                <div class="flex items-center gap-4">
                    @if($user->foto_profil)
                        <img src="{{ asset($user->foto_profil) }}" alt="Foto Profil" class="w-14 h-14 rounded-full object-cover border border-slate-200">
                    @else
                        <div class="w-14 h-14 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-lg">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                    <input type="file" name="foto_profil" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nomor Handphone</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" required
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Alamat</label>
                <textarea name="alamat" rows="3" required
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">{{ old('alamat', $user->alamat) }}</textarea>
            </div>

            <!-- Bagian Ubah Password -->
            <div class="pt-4 border-t border-slate-100 space-y-4">
                <h4 class="text-sm font-bold text-slate-900">Ubah Password (Opsional)</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Password Baru</label>
                        <input type="password" name="password" placeholder="Minimal 8 karakter"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-4 flex items-center justify-between border-t border-slate-100">
                <a href="{{ route('profil.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm transition-all">
                    Kembali
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>
@endsection