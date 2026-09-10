@extends('layouts.app')

@section('header-title', 'Status & Riwayat Peminjaman')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    
    <!-- Tampilkan Notifikasi Sukses/Error -->
    @if(session('success'))
        <div class="bg-green-50 text-green-700 px-6 py-4 border-b border-green-100 font-medium text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 text-red-700 px-6 py-4 border-b border-red-100 font-medium text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold">Tgl Pinjam</th>
                    <th class="px-6 py-4 font-semibold">Batas Kembali</th>
                    <th class="px-6 py-4 font-semibold">Daftar Alat</th>
                    <th class="px-6 py-4 font-semibold text-center">Status</th>
                    <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                <!-- Looping Data Peminjaman -->
                @forelse($riwayat as $item)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-700">
                        {{ \Carbon\Carbon::parse($item->tgl_pinjam)->translatedFormat('d M Y') }}
                    </td>
                    <td class="px-6 py-4 text-slate-600">
                        {{ \Carbon\Carbon::parse($item->tgl_kembali_plan)->translatedFormat('d M Y') }}
                    </td>
                    <td class="px-6 py-4 text-slate-600">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($item->detailpinjams as $detail)
                                <li>{{ $detail->alat->nama_alat ?? 'Alat' }} ({{ $detail->jumlah }}x)</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <!-- Logika Label Status -->
                        @if($item->status == 'diajukan')
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-100">Menunggu Persetujuan</span>
                        @elseif($item->status == 'dipinjam')
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-100">Sedang Dipinjam</span>
                        @elseif($item->status == 'dikembalikan')
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-600 border border-amber-100">Menunggu Pengembalian</span>
                        @elseif($item->status == 'telat')
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">telat</span>
                        @else
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-100">{{ ucfirst($item->status) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <!-- Logika Tombol Aksi Berdasarkan Status -->
                        @if($item->status == 'dipinjam')
                            <form action="{{ route('peminjam.kembalikan', $item->id) }}" method="POST" 
                                onsubmit="return confirm('Apakah kamu yakin ingin mengembalikan alat ini? \n\nPastikan alat dalam keadaan baik. Petugas berhak memberikan denda jika terdapat kerusakan/keterlambatan.');">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-bold shadow-sm transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                    Kembalikan
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-slate-400 font-medium">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                        Belum ada riwayat peminjaman saat ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection