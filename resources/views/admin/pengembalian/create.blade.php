@extends('layouts.app')

@section('title', 'Kelola Peminjaman - Panel Admin')
@section('header-title', 'Riwayat Pengembalian Alat')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Header Informasi Peminjaman -->
        <div class="bg-blue-50/60 p-6 border-b border-blue-100">
            <h3 class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-4">Informasi Peminjaman</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <!-- Nama Peminjam -->
                <div>
                    <span class="text-slate-400 block text-xs mb-1">Nama Peminjam</span>
                    <strong class="text-slate-800 text-base">
                        {{ $peminjaman->user->name ?? $peminjaman->nama_peminjam ?? 'Peminjam Tidak Terdaftar' }}
                    </strong>
                </div>

                <!-- Alat yang Dipinjam -->
                <div>
                    <span class="text-slate-400 block text-xs mb-1">Alat yang Dipinjam</span>
                    <div class="space-y-1">
                        @if($peminjaman->detailPinjams && $peminjaman->detailPinjams->count() > 0)
                            @foreach($peminjaman->detailPinjams as $detail)
                                <div class="font-semibold text-slate-800">
                                    {{ $detail->alat->nama_alat ?? 'Alat Dihapus' }} 
                                    <span class="text-xs text-slate-500 font-normal">({{ $detail->jumlah }} pcs)</span>
                                </div>
                            @endforeach
                        @else
                            <strong class="text-slate-800">{{ $peminjaman->alat->nama_alat ?? 'Data Alat Tidak Ditemukan' }}</strong>
                        @endif
                    </div>
                </div>

                <!-- Tanggal Pinjam -->
                <div>
                    <span class="text-slate-400 block text-xs mb-1">Tanggal Pinjam</span>
                    <strong class="text-slate-700">
                        {{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d M Y') }}
                    </strong>
                </div>

                <!-- Jatuh Tempo -->
                <div>
                    <span class="text-slate-400 block text-xs mb-1">Jatuh Tempo</span>
                    <strong class="text-slate-700">
                        {{ \Carbon\Carbon::parse($peminjaman->tgl_jatuh_tempo ?? $peminjaman->tgl_kembali)->format('d M Y') }}
                    </strong>
                </div>
            </div>
        </div>

        <!-- Form Pengembalian -->
        <form action="{{ route('admin.pengembalian.store', $peminjaman->id) }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Kondisi Alat -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Kondisi Alat Saat Dikembalikan <span class="text-rose-500">*</span>
                </label>
                <select name="kondisi_kembali" required 
                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <option value="" disabled selected>-- Pilih Kondisi Alat --</option>
                    <option value="baik">Baik / Lengkap</option>
                    <option value="rusak ringan">Rusak Ringan</option>
                    <option value="rusak berat">Rusak Berat</option>
                    <option value="hilang">Hilang</option>
                </select>
            </div>

            <!-- Total Denda -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Total Denda (Rp) <span class="text-rose-500">*</span>
                </label>

                <!-- Status Keterlambatan -->
                @if($hariTerlambat > 0)
                    <div class="mb-3 flex items-center gap-2 bg-rose-50 border border-rose-100 text-rose-700 p-3 rounded-xl text-xs font-medium">
                        <svg class="w-4 h-4 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span>Peminjam terlambat <strong>{{ $hariTerlambat }} hari</strong>. Denda keterlambatan sistem: <strong>Rp {{ number_format($dendaKeterlambatan, 0, ',', '.') }}</strong></span>
                    </div>
                @else
                    <div class="mb-3 flex items-center gap-2 bg-emerald-50 border border-emerald-100 text-emerald-700 p-3 rounded-xl text-xs font-medium">
                        <svg class="w-4 h-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Pengembalian tepat waktu. Tidak ada denda keterlambatan sistem.</span>
                    </div>
                @endif

                <!-- Input Nominal Denda (Mencegah pecahan desimal) -->
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 font-semibold text-sm">Rp</span>
                    <input type="number" min="0" step="1" name="denda" value="{{ (int) $dendaKeterlambatan }}" 
                        class="w-full pl-12 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <p class="text-xs text-slate-400 mt-1">Biarkan 0 jika tidak ada denda keterlambatan atau kerusakan.</p>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.peminjaman.index') }}" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all shadow-sm">
                    Simpan Pengembalian
                </button>
            </div>
        </form>
    </div>
</div>
@endsection