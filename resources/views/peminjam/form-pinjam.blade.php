@extends('layouts.app')


@section('content')
    <!-- Tombol Kembali (Di luar form) -->
    <div class="max-w-2xl mx-auto w-full mb-6">
        <a href="{{ route('peminjam.katalog') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-blue-600 transition-colors bg-white px-4 py-2 rounded-full shadow-sm border border-slate-200 w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke katalog
        </a>
    </div>

    <!-- Container Form Utama -->
    <div class="max-w-2xl mx-auto w-full bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200 overflow-hidden">
        
        <!-- Header Form -->
        <div class="bg-slate-900 px-8 py-6 text-center">
            <h2 class="text-2xl font-bold text-white tracking-tight">Formulir Pengajuan</h2>
            <p class="text-slate-400 text-sm mt-1">Lengkapi data di bawah ini untuk meminjam alat</p>
        </div>

        <!-- Info Alat yang Dipilih -->
        <div class="bg-blue-50/50 border-b border-slate-100 px-8 py-5 flex items-center gap-5">
            <div class="w-16 h-16 bg-white rounded-2xl border border-blue-100 shadow-sm flex items-center justify-center overflow-hidden shrink-0">
                @if($alat->gambar)
                    <img src="{{ asset('storage/' . $alat->gambar) }}" alt="{{ $alat->nama_alat }}" class="w-full h-full object-cover">
                @else
                    <svg class="w-7 h-7 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                @endif
            </div>
            <div>
                <span class="text-[10px] font-bold text-blue-600 bg-blue-100 px-2 py-0.5 rounded uppercase tracking-wider mb-1 inline-block">
                    {{ $alat->kategori->nama_kategori ?? 'Alat Praktek' }}
                </span>
                <h3 class="text-lg font-bold text-slate-800 leading-none">{{ $alat->nama_alat }}</h3>
                <p class="text-xs text-slate-500 mt-1.5 font-medium">Sisa stok saat ini: <span class="text-slate-700 font-bold">{{ $alat->stok }} Unit</span></p>
            </div>
        </div>

        <!-- Form Input -->
        <form action="{{ route('peminjam.ajukan') }}" method="POST" class="px-8 py-8">
            @csrf

            <!-- INPUT HIDDEN UNTUK ARRAY (Sesuai fungsi Controller Guru kamu) -->
            <input type="hidden" name="alat_id[]" value="{{ $alat->id }}">

            <div class="space-y-6">
                <!-- Input Jumlah -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Jumlah yang Dipinjam <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah[]" min="1" max="{{ $alat->stok }}" value="1" required
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-slate-50 focus:bg-white font-medium">
                    <p class="text-xs text-slate-400 mt-2">Maksimal jumlah yang bisa dipinjam adalah sesuai stok tersedia.</p>
                </div>

                <!-- Input Rencana Kembali -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Rencana Tanggal Kembali <span class="text-red-500">*</span></label>
                    <input type="date" name="tgl_kembali_plan" required min="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-slate-50 focus:bg-white text-slate-700 font-medium">
                </div>
            </div>

            <!-- Pesan Error Validasi (Jika Ada) -->
            @if ($errors->any())
                <div class="mt-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Tombol Submit -->
            <div class="mt-8 pt-6 border-t border-slate-100">
                <button type="submit" class="w-full flex justify-center items-center gap-2 px-6 py-3.5 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all shadow-lg shadow-blue-600/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Kirim Pengajuan Peminjaman
                </button>
            </div>
        </form>
    </div>
@endsection