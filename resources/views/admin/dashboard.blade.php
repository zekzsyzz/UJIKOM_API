@extends('layouts.app')

@section('title', 'Dashboard Admin - Sistem Peminjaman')
@section('header-title', 'Ringkasan Aktivitas Sistem')

@section('content')
    <!-- 1. Card Selamat Datang (Modern Gradient) -->
    <div class="mb-8 relative overflow-hidden bg-gradient-to-r from-slate-800 to-slate-900 rounded-2xl p-6 md:p-8 shadow-lg border border-slate-700">
        <!-- Dekorasi Latar Belakang -->
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center gap-5">
            <!-- Avatar Inisial -->
            <div class="w-14 h-14 rounded-full bg-white/10 flex items-center justify-center text-2xl font-bold text-white border border-white/20 backdrop-blur-sm shadow-inner flex-shrink-0">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-white mb-1">
                    Selamat datang, {{ auth()->user()->name }}! 🚀
                </h2>
                <p class="text-slate-300 text-sm flex items-center gap-2">
                    Anda login dengan hak akses 
                    <span class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 uppercase tracking-wider">
                        {{ auth()->user()->role }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- 2. Log Aktivitas (Activity Feed Style) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Header Log dengan Indikator Live -->
        <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Log Aktivitas Terbaru
            </h3>
            
            <!-- Animasi Pulsing "Live" -->
            <div class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 border border-emerald-100 rounded-full shadow-sm">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Live Updates</span>
            </div>
        </div>

        <!-- List Log (Menggantikan Tabel) -->
        <div class="p-0">
            <ul class="divide-y divide-slate-100">
                @forelse($logs as $log)
                    <li class="p-4 sm:p-6 hover:bg-slate-50 transition-colors duration-200 group">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            
                            <!-- Ikon/Avatar User di Log -->
                            <div class="hidden sm:flex flex-shrink-0 w-10 h-10 rounded-full bg-blue-50 text-blue-600 items-center justify-center font-bold text-sm border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                {{ substr($log->user->name ?? 'S', 0, 1) }}
                            </div>
                            
                            <!-- Konten Aktivitas -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-900 truncate">
                                    {{ $log->user->name ?? 'Sistem' }}
                                </p>
                                <p class="text-sm text-slate-600 mt-0.5 break-words">
                                    {{ $log->aktivitas }}
                                </p>
                            </div>

                            <!-- Label Waktu -->
                            <div class="flex-shrink-0 sm:text-right mt-2 sm:mt-0">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-medium text-slate-500 bg-slate-100 border border-slate-200 group-hover:bg-white group-hover:shadow-sm transition-all duration-200">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $log->created_at }}
                                </span>
                            </div>
                        </div>
                    </li>
                @empty
                    <!-- State Kosong yang Menarik -->
                    <li class="p-10 text-center flex flex-col items-center justify-center">
                        <div class="w-16 h-16 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                        <p class="text-slate-800 font-semibold text-lg">Belum Ada Aktivitas</p>
                        <p class="text-slate-500 text-sm mt-1">Sistem sedang memantau pembaruan log terbaru...</p>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection