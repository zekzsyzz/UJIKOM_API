@extends('layouts.app')

@section('title', 'Data Diri Profil')
@section('header-title', 'Informasi Data Diri')

@section('content')
<div class="w-full flex-1 min-h-screen bg-slate-50 p-6 md:p-8 max-w-4xl mx-auto">
    
    <!-- Notifikasi Sukses -->
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-100 text-emerald-700 p-4 rounded-xl shadow-sm">
            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-semibold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden p-6 md:p-8 space-y-6">
        
        <!-- Header Profil -->
        <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-slate-100">
            @if($user->foto_profil)
                <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Foto Profil" class="w-24 h-24 rounded-full object-cover border-4 border-blue-50 shadow-md">
            @else
                <div class="w-24 h-24 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-3xl shadow-md">
                    {{ substr($user->name, 0, 1) }}
                </div>
            @endif
            <div class="text-center sm:text-left">
                <h2 class="text-xl font-bold text-slate-900">{{ $user->name }}</h2>
                <p class="text-sm text-slate-500">{{ $user->email }}</p>
                <span class="inline-block mt-2 px-3 py-1 bg-blue-50 text-blue-600 font-semibold text-xs rounded-full uppercase tracking-wider border border-blue-100">
                    {{ $user->role }}
                </span>
            </div>
        </div>

        <!-- Detail Informasi -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nomor Handphone</p>
                <p class="text-sm font-semibold text-slate-800">{{ $user->no_hp ?? '-' }}</p>
            </div>
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Hak Akses (Role)</p>
                <p class="text-sm font-semibold text-slate-800 uppercase">{{ $user->role }}</p>
            </div>
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 md:col-span-2">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Alamat Lengkap</p>
                <p class="text-sm font-semibold text-slate-800">{{ $user->alamat ?? '-' }}</p>
            </div>
        </div>

        <!-- Tombol Aksi ke Edit -->
        <div class="pt-4 flex items-center justify-end border-t border-slate-100">
            <a href="{{ route('profil.edit') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition-all shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Data Diri
            </a>
        </div>
        <div class="pt-6 border-t border-slate-200 flex items-center justify-between bg-rose-50/50 p-4 rounded-xl border border-rose-100">
            <div>
                <h4 class="text-sm font-bold text-rose-900">Keluar dari Aplikasi</h4>
                <p class="text-xs text-rose-600">Sesi Anda akan diakhiri dan diarahkan kembali ke halaman login.</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin keluar?')"
                    class="bg-rose-500 hover:bg-rose-600 text-white font-semibold px-4 py-2 rounded-xl text-xs transition-all shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection