@extends('layouts.app')

@section('content')
    <!-- Tombol Kembali -->
    <div class="max-w-2xl mx-auto w-full mb-6">
        <a href="{{ route('peminjam.katalog') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-blue-600 transition-colors bg-white px-4 py-2 rounded-full shadow-sm border border-slate-200 w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke katalog
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-5 py-4 rounded-xl">
            <strong class="font-bold">Gagal mengajukan peminjaman:</strong>
            <ul class="list-disc pl-5 mt-2 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-5 py-4 rounded-xl text-sm font-bold">
            {{ session('error') }}
        </div>
    @endif

    <!-- Container Form Utama -->
    <div class="max-w-2xl mx-auto w-full bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200 overflow-hidden mb-10">
        
        <!-- Header Form -->
        <div class="bg-slate-900 px-8 py-6 text-center">
            <h2 class="text-2xl font-bold text-white tracking-tight">Formulir Pengajuan</h2>
            <p class="text-slate-400 text-sm mt-1">Lengkapi data di bawah ini untuk meminjam alat</p>
        </div>

        <!-- Info Alat yang Dipilih -->
        <div class="bg-blue-50/50 border-b border-slate-100 px-8 py-5 flex items-center gap-5">
            <div class="w-16 h-16 bg-white rounded-2xl border border-blue-100 shadow-sm flex items-center justify-center overflow-hidden shrink-0">
                @if($alatPilihan->foto)
                    <!-- Perbaikan: Tambahkan object-cover dan hapus tag H3 di sini -->
                    <img src="{{ asset('storage/' . $alatPilihan->foto) }}" class="w-full h-full object-cover">
                @else
                    <svg class="w-7 h-7 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                @endif
            </div>
            <div>
                <span class="text-[10px] font-bold text-blue-600 bg-blue-100 px-2 py-0.5 rounded uppercase tracking-wider mb-1 inline-block">
                    {{ $alatPilihan->kategori->nama_kategori ?? 'Alat Praktek' }}
                </span>
                <h3 class="text-lg font-bold text-slate-800 leading-none">{{ $alatPilihan->nama_alat }}</h3>
                <p class="text-xs text-slate-500 mt-1.5 font-medium">Sisa stok saat ini: <span class="text-slate-700 font-bold">{{ $alatPilihan->stok }} Unit</span></p>
            </div>
        </div>

        <!-- Form Input -->
        <!-- Perbaikan: Tambahkan p-8 (padding) di tag form -->
        <form action="{{ route('peminjam.ajukan') }}" method="POST" class="p-8">
            @csrf

            <!-- 1. Form Tanggal -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Pinjam (Booking)</label>
                    <!-- Perbaikan: Ganti form-control dengan class Tailwind -->
                    <input type="date" name="tgl_pinjam" min="{{ date('Y-m-d') }}" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Rencana Tanggal Kembali</label>
                    <input type="date" name="tgl_kembali_plan" min="{{ date('Y-m-d') }}" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" required>
                </div>
            </div>

            <!-- 2. Form Multi-Barang -->
            <div class="mb-8">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Alat & Jumlah</label>
                <div id="container-alat" class="space-y-3">
                    <div class="flex gap-3 item-alat">
                        <select name="alat_id[]" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" required>
                            <option value="">-- Pilih Alat --</option>
                            @foreach($alats as $alat)
                                <option value="{{ $alat->id }}" {{ $alat->id == $alatPilihan->id ? 'selected' : '' }}>
                                    {{ $alat->nama_alat }} (Stok: {{ $alat->stok }})
                                </option>
                            @endforeach
                        </select>
                        <input type="number" name="jumlah[]" min="1" placeholder="Qty" class="w-24 border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-center" required>
                        <button type="button" class="btn-hapus-baris bg-rose-100 text-rose-600 hover:bg-rose-500 hover:text-white transition-colors px-4 rounded-lg font-bold hidden">X</button>
                    </div>
                </div>
                <button type="button" id="btn-tambah-alat" class="mt-4 bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-bold transition-colors w-full border border-slate-200 border-dashed">
                    + Tambah Alat Lain
                </button>
            </div>

            <!-- Area Submit -->
            <div class="pt-4 border-t border-slate-100">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-3 rounded-xl transition-colors shadow-lg shadow-blue-200">
                    Ajukan Peminjaman
                </button>
            </div>
        </form>
    </div>

    <!-- JavaScript (Tetap sama) -->
    <script>
        document.getElementById('btn-tambah-alat').addEventListener('click', function() {
            const container = document.getElementById('container-alat');
            const firstRow = container.querySelector('.item-alat');
            
            const newRow = firstRow.cloneNode(true);
            newRow.querySelector('select').value = '';
            newRow.querySelector('input[type="number"]').value = '';
            
            const btnHapus = newRow.querySelector('.btn-hapus-baris');
            btnHapus.classList.remove('hidden');
            btnHapus.addEventListener('click', function() {
                newRow.remove();
            });

            container.appendChild(newRow);
        });
    </script>
@endsection