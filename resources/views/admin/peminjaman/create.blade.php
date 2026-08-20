@extends('layouts.app')

@section('title', 'Tambah Peminjaman - Panel Admin')
@section('header-title', 'Form Tambah Transaksi')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Form Header -->
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="p-2.5 bg-blue-100 text-blue-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Catat Transaksi Peminjaman</h3>
                <p class="text-xs text-slate-500 font-medium">Pilih user, tentukan tanggal, dan tambahkan daftar alat yang dipinjam.</p>
            </div>
        </div>

        @if(session('error'))
            <div class="m-6 mb-0 flex items-center gap-3 bg-rose-50 border border-rose-100 text-rose-700 p-4 rounded-xl shadow-sm">
                <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-semibold text-sm">{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.peminjaman.store') }}" method="POST" class="p-6 md:p-8 space-y-6">
            @csrf

            <!-- Pilihan User -->
            <div>
                <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Pilih Peminjam (User)</label>
                <select name="user_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer">
                    <option value="">-- Pilih User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tanggal Pinjam & Kembali -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Tanggal Pinjam</label>
                    <input type="date" name="tgl_pinjam" value="{{ old('tgl_pinjam', date('Y-m-d')) }}" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Rencana Tanggal Kembali</label>
                    <input type="date" name="tgl_kembali_plan" value="{{ old('tgl_kembali_plan', date('Y-m-d', strtotime('+3 days'))) }}" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Bagian Daftar Alat yang Dipinjam (Dinamis) -->
            <div>
                <div class="flex items-center justify-between mb-3 ml-1">
                    <label class="text-slate-700 text-sm font-semibold">Daftar Alat yang Dipinjam</label>
                </div>
                
                <div id="alat-container" class="space-y-3">
                    <div class="flex items-center gap-3 alat-row bg-slate-50 p-3 rounded-xl border border-slate-200">
                        <!-- Select Alat -->
                        <div class="flex-1">
                            <select name="alat_id[]" required class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer">
                                <option value="">-- Pilih Alat --</option>
                                @foreach($peminjaman as $alat)
                                    <option value="{{ $alat->id }}">{{ $alat->nama_alat }} (Stok: {{ $alat->stok }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Input Jumlah -->
                        <div class="w-24 shrink-0">
                            <input type="number" name="jumlah[]" value="1" min="1" placeholder="Jml" required
                                class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-center">
                        </div>
                        
                        <!-- Tombol Hapus Baris -->
                        <button type="button" onclick="removeRow(this)" title="Hapus Alat"
                            class="shrink-0 p-2.5 text-slate-400 bg-white border border-slate-200 rounded-lg hover:text-rose-500 hover:bg-rose-50 hover:border-rose-200 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- Tombol Tambah Baris -->
                <button type="button" onclick="addRow()" 
                    class="mt-4 w-full py-3 flex items-center justify-center gap-2 border-2 border-dashed border-slate-300 rounded-xl text-sm font-semibold text-slate-500 hover:bg-slate-50 hover:border-blue-400 hover:text-blue-600 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Alat Lain
                </button>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 mt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.peminjaman.index') }}" 
                    class="px-6 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-all">
                    Batal
                </a>
                <button type="submit" 
                    class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script Dinamis Baris Alat -->
<script>
function addRow() {
    const container = document.getElementById('alat-container');
    const firstRow = container.querySelector('.alat-row');
    const newRow = firstRow.cloneNode(true);
    
    // Reset nilai inputan pada baris baru
    newRow.querySelector('select').value = '';
    newRow.querySelector('input').value = '1';
    
    container.appendChild(newRow);
}

function removeRow(button) {
    const rows = document.querySelectorAll('.alat-row');
    if (rows.length > 1) {
        // Hapus elemen dengan animasi halus jika menggunakan library, atau remove() langsung
        button.closest('.alat-row').remove();
    } else {
        alert('Minimal harus ada 1 alat yang dipilih untuk peminjaman.');
    }
}
</script>
@endsection