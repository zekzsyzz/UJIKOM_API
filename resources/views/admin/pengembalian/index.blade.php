@extends('layouts.app')

@section('title', 'Riwayat Pengembalian - Panel Admin')
@section('header-title', 'Riwayat Transaksi Pengembalian')

@section('content')
    <!-- Notifikasi -->
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-100 text-emerald-700 p-4 rounded-xl shadow-sm">
            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-semibold text-sm">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 bg-rose-50 border border-rose-100 text-rose-700 p-4 rounded-xl shadow-sm">
            <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-semibold text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Toolbar (Pencarian & Tombol Navigasi) -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Search Bar Modern -->
        <form action="{{ route('admin.pengembalian.index') }}" method="GET" class="w-full md:w-96 relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari peminjam / kondisi..."
                class="w-full pl-10 pr-20 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm placeholder-slate-400">
            
            <div class="absolute inset-y-1 right-1 flex items-center gap-1">
                @if(request('search'))
                    <a href="{{ route('admin.pengembalian.index') }}" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Reset">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors shadow-sm">
                    Cari
                </button>
            </div>
        </form>

        <!-- Tombol Kembali ke Peminjaman -->
        <a href="{{ route('admin.peminjaman.index') }}" 
            class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 whitespace-nowrap">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kelola Peminjaman
        </a>
    </div>

    <!-- Tabel Data Pengembalian -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider font-semibold">
                        <th class="py-4 px-6">Peminjam</th>
                        <th class="py-4 px-6">Alat yang Dikembalikan</th>
                        <th class="py-4 px-6">Tanggal Kembali</th>
                        <th class="py-4 px-6 text-center">Kondisi</th>
                        <th class="py-4 px-6 text-right">Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($pengembalians as $kembali)
                        <tr class="hover:bg-slate-50/80 transition-colors align-top group">
                            <!-- Peminjam -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold shrink-0">
                                        {{ strtoupper(substr($kembali->peminjaman->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $kembali->peminjaman->user->name ?? 'Data Dihapus' }}</p>
                                        <p class="text-[11px] text-slate-400">ID: #{{ $kembali->id_pengembalian }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Daftar Alat (Mengambil relasi dari peminjaman) -->
                            <td class="py-4 px-6">
                                <ul class="space-y-2">
                                    @if(isset($kembali->peminjaman->detailPinjams))
                                        @foreach($kembali->peminjaman->detailPinjams as $detail)
                                        <li class="flex items-start gap-2">
                                            <svg class="w-4 h-4 text-emerald-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <div>
                                                <span class="font-semibold text-slate-700">{{ $detail->alat->nama_alat ?? 'Alat Dihapus' }}</span>
                                                <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                                    {{ $detail->jumlah }} pcs
                                                </span>
                                            </div>
                                        </li>
                                        @endforeach
                                    @else
                                        <span class="text-slate-400 italic">Data alat tidak tersedia</span>
                                    @endif
                                </ul>
                            </td>

                            <!-- Tanggal & Petugas -->
                            <td class="py-4 px-6">
                                <div class="space-y-1.5 text-xs">
                                    <!-- Info Tanggal Kembali -->
                                    <div class="flex items-center gap-1.5 text-slate-600">
                                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span>Dikembalikan: <span class="font-medium text-emerald-700">{{ \Carbon\Carbon::parse($kembali->tgl_kembali)->format('d M Y') }}</span></span>
                                    </div>
                                    
                                    <!-- Info Petugas Penerima & Role -->
                                    <div class="flex flex-wrap items-center gap-1.5 text-slate-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        <span class="flex items-center flex-wrap gap-1">
                                            Petugas: 
                                            <span class="font-medium text-slate-700">{{ $kembali->petugas->name ?? 'Sistem' }}</span>
                                            
                                            <!-- Badge Tipe Petugas (Role) -->
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-[4px] text-[9px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-100 uppercase tracking-wider">
                                                {{ $kembali->petugas->role ?? 'ADMIN' }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Kondisi Label -->
                            <td class="py-4 px-6 text-center">
                                @php
                                    $kondisiColors = [
                                        'baik' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'rusak ringan' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'rusak berat' => 'bg-orange-50 text-orange-700 border-orange-200',
                                        'hilang' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    ];
                                    $colorClass = $kondisiColors[strtolower($kembali->kondisi_kembali)] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold border {{ $colorClass }}">
                                    {{ ucwords($kembali->kondisi_kembali) }}
                                </span>
                            </td>

                            <!-- Denda -->
                            <td class="py-4 px-6 text-right font-bold text-[13px]">
                                @if($kembali->denda > 0)
                                    <span class="text-rose-600">Rp {{ number_format($kembali->denda, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-slate-400">Rp 0</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <!-- Empty State -->
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3 border border-slate-100">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    </div>
                                    <p class="font-semibold text-slate-500">Riwayat pengembalian kosong</p>
                                    <p class="text-xs mt-1">Data akan muncul setelah ada peminjam yang mengembalikan alat.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            @if(method_exists($pengembalians, 'links'))
                {{ $pengembalians->links() }}
            @endif
        </div>
    </div>
@endsection