@extends('layouts.app')

@section('title', 'Laporan Peminjaman - Dashboard Petugas')
@section('header-title', 'Laporan Peminjaman & Pengembalian Alat')

@section('content')
    <!-- FILTER LAPORAN -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 rounded-t-2xl">
            <h3 class="text-lg font-bold text-slate-800">Filter Laporan</h3>
        </div>
        
        <form action="{{ route('petugas.laporan.index') }}" method="GET" class="p-6 grid grid-cols-1 md:grid-cols-4 gap-5 items-end">
            <!-- Status Peminjaman -->
            <div>
                <label class="block text-[11px] uppercase font-bold text-slate-500 mb-2 tracking-wider">Status Peminjaman</label>
                <select name="status" class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all shadow-sm text-slate-700">
                    <option value="">Semua Status</option>
                    <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="telat" {{ request('status') == 'telat' ? 'selected' : '' }}>Telat</option>
                </select>
            </div>
            
            <!-- Dari Tanggal -->
            <div>
                <label class="block text-[11px] uppercase font-bold text-slate-500 mb-2 tracking-wider">Dari Tanggal (Pinjam)</label>
                <input type="date" name="dari_tanggal" value="{{ request('dari_tanggal') }}" 
                    class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all shadow-sm text-slate-700">
            </div>
            
            <!-- Sampai Tanggal -->
            <div>
                <label class="block text-[11px] uppercase font-bold text-slate-500 mb-2 tracking-wider">Sampai Tanggal (Pinjam)</label>
                <input type="date" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}" 
                    class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all shadow-sm text-slate-700">
            </div>
            
            <!-- Tombol Aksi -->
            <div class="flex gap-3 h-[42px]">
                <button type="submit" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 text-sm font-semibold rounded-xl transition-all shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter
                </button>
                <a href="{{ route('petugas.laporan.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-xl transition-all flex items-center justify-center border border-slate-200" title="Reset Filter">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </a>
            </div>
        </form>
    </div>

    <!-- TABEL HASIL & TOMBOL CETAK -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden w-full">
        <!-- Header Tabel -->
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h3 class="text-lg font-bold text-slate-800">Hasil Rekap Laporan</h3>
            <a href="{{ route('petugas.laporan.cetak', request()->all()) }}" target="_blank" 
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 text-sm font-semibold rounded-xl transition-all shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak / Print Laporan</span>
            </a>
        </div>

        <!-- Tabel Data -->
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider font-semibold">
                        <th class="py-4 px-6 w-16">No</th>
                        <th class="py-4 px-6 w-40">Peminjam</th>
                        <th class="py-4 px-6 w-32">Tgl Pinjam</th>
                        <th class="py-4 px-6 w-32">Rencana Kembali</th>
                        <th class="py-4 px-6 w-28">Status</th>
                        <th class="py-4 px-6 min-w-[200px]">Detail Alat</th>
                        <th class="py-4 px-6 w-40">Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($laporans as $index => $item)
                        <tr class="hover:bg-slate-50/80 transition-colors align-top">
                            <td class="py-5 px-6 font-medium text-slate-600">
                                {{ $index + 1 }}
                            </td>
                            
                            <td class="py-5 px-6 font-semibold text-slate-800">
                                {{ $item->user->name ?? '-' }}
                            </td>
                            
                            <td class="py-5 px-6 text-slate-600">
                                {{ $item->tgl_pinjam }}
                            </td>
                            
                            <td class="py-5 px-6 text-slate-600">
                                {{ $item->tgl_kembali_plan }}
                            </td>
                            
                            <td class="py-5 px-6">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border 
                                    {{ $item->status == 'dikembalikan' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                                    {{ $item->status == 'dipinjam' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                    {{ $item->status == 'telat' ? 'bg-rose-50 text-rose-700 border-rose-200' : '' }}
                                    {{ $item->status == 'diajukan' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>

                            <td class="py-5 px-6">
                                <ul class="list-disc list-inside space-y-1.5 text-xs text-slate-600">
                                    @foreach($item->detailPinjams as $detail)
                                        <li class="flex items-start gap-1">
                                            <span class="font-semibold text-slate-700">{{ $detail->alat->nama_alat ?? '-' }}</span>
                                            <span class="text-slate-400 whitespace-nowrap">({{ $detail->jumlah }})</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>

                            <td class="py-5 px-6 font-bold {{ ($item->pengembalian->denda ?? 0) > 0 ? 'text-rose-600' : 'text-slate-600' }}">
                                Rp {{ number_format($item->pengembalian->denda ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3 border border-slate-100">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <p class="font-semibold text-slate-500">Tidak ada data laporan yang sesuai filter.</p>
                                    <p class="text-xs mt-1">Silakan sesuaikan parameter pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection