@extends('layouts.app')

@section('title', 'Kelola Alat - Panel Petugas')
@section('header-title', 'Daftar Pengajuan Peminjaman Alat')

@section('content')
{{-- Pembungkus utama dibuat full width & mengambil sisa area --}}
<div class="w-full flex-1 min-h-screen bg-slate-50 p-6 md:p-8">
    
    

    <!-- Toolbar (Pencarian) -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ url()->current() }}" method="GET" class="w-full md:w-96 relative group">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari peminjam / status..."
                class="w-full pl-10 pr-20 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm placeholder-slate-400">
            <button type="submit" class="absolute right-1.5 top-1.5 bottom-1.5 bg-slate-800 hover:bg-slate-900 text-white px-4 text-xs font-semibold rounded-lg transition-colors shadow-sm">
                Cari
            </button>
        </form>
    </div>

    <!-- Tabel Data Peminjaman -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden w-full">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200 text-slate-400 text-xs uppercase tracking-wider font-semibold">
                        <th class="py-4 px-6">Peminjam</th>
                        <th class="py-4 px-6">Tanggal Pinjam</th>
                        <th class="py-4 px-6">Rencana Kembali</th>
                        <th class="py-4 px-6">Detail Alat</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($peminjamans as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors align-top">
                            <!-- Peminjam -->
                            <td class="py-4 px-6 font-semibold text-slate-800">
                                {{ $item->user->name ?? 'User Dihapus' }}
                            </td>

                            <!-- Tanggal Pinjam -->
                            <td class="py-4 px-6 text-slate-600">
                                {{ $item->tgl_pinjam }}
                            </td>

                            <!-- Rencana Kembali -->
                            <td class="py-4 px-6 text-slate-600">
                                {{ $item->tgl_kembali_plan }}
                            </td>

                            <!-- Detail Alat -->
                            <td class="py-4 px-6">
                                <ul class="list-disc list-inside space-y-1 text-xs text-slate-600">
                                    @foreach($item->detailPinjams as $detail)
                                        <li>
                                            <span class="font-semibold text-slate-700">{{ $detail->alat->nama_alat ?? 'Alat Dihapus' }}</span>
                                            (Jumlah: {{ $detail->jumlah }})
                                        </li>
                                    @endforeach
                                </ul>
                            </td>

                            <!-- Aksi / Status -->
                            <td class="py-4 px-6 text-center">
                                @if($item->status == 'diajukan')
                                    <form action="{{ route('petugas.peminjaman.setujui', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Setujui peminjaman alat ini?')"
                                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all shadow-sm">
                                            Setujui
                                        </button>
                                    </form>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold text-blue-600 bg-blue-50 border border-blue-200">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400 text-sm font-medium">
                                Tidak ada pengajuan peminjaman baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection