@extends('layouts.app')

@section('title', 'Tambah Pengembalian')
@section('header-title', 'Form Pengembalian Alat')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        
        <form action="{{ route('admin.pengembalian.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- 1. Pilih Peminjam -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Peminjam / Transaksi <span class="text-rose-500">*</span></label>
                <select id="select-peminjaman" name="peminjaman_id" required 
                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <option value="" disabled selected>-- Pilih Transaksi Peminjaman --</option>
                    @foreach($peminjamans as $p)
                        <option value="{{ $p->id }}">
                            {{ $p->user->name ?? 'User Tidak Dikenal' }} (Tgl Pinjam: {{ \Carbon\Carbon::parse($p->tgl_pinjam)->format('d M Y') }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- 2. Informasi Otomatis (Muncul saat Peminjam Dipilih) -->
            <div id="box-detail" class="hidden bg-slate-50 border border-slate-200 rounded-xl p-5 space-y-4">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Detail Peminjaman</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-slate-400 block text-xs">Barang yang Dipinjam:</span>
                        <div id="list-barang" class="font-bold text-slate-800 mt-1"></div>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs">Batas Tanggal Kembali (Jatuh Tempo):</span>
                        <div id="tgl-jatuh-tempo" class="font-bold text-slate-800 mt-1"></div>
                    </div>
                </div>

                <!-- Status Keterlambatan -->
                <div id="alert-keterlambatan" class="p-3 rounded-lg text-xs font-semibold flex items-center gap-2"></div>
            </div>

            <!-- 3. Kondisi Alat -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Kondisi Alat Saat Dikembalikan <span class="text-rose-500">*</span></label>
                <select name="kondisi_kembali" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <option value="" disabled selected>-- Pilih Kondisi --</option>
                    <option value="baik">Lengkap dan Berfungsi (Baik)</option>
                    <option value="rusak ringan">Rusak Ringan</option>
                    <option value="rusak berat">Rusak Berat</option>
                    <option value="hilang">Hilang</option>
                </select>
            </div>

            <!-- 4. Nominal Denda (Otomatis terisi dari JS, tetap bisa diedit manual) -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Total Denda (Rp) <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 font-semibold text-sm">Rp</span>
                    <input type="number" id="input-denda" name="denda" min="0" value="0" required
                        class="w-full pl-12 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <p class="text-xs text-slate-400 mt-1">Nominal terisi otomatis jika terlambat (Rp 5.000/hari). Bisa disesuaikan jika ada denda kerusakan.</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.pengembalian.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm transition-all">
                    Simpan Pengembalian
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript Auto-Fill & Kalkulasi Denda -->
<script>
    const dataPeminjaman = @json($peminjamans);
    const TARIF_DENDA_PER_HARI = 5000;

    document.getElementById('select-peminjaman').addEventListener('change', function() {
        const selectedId = this.value;
        const data = dataPeminjaman.find(item => item.id == selectedId);

        if (!data) return;

        // Render List Barang
        const listBarangEl = document.getElementById('list-barang');
        listBarangEl.innerHTML = '';
        if (data.detail_pinjams && data.detail_pinjams.length > 0) {
            data.detail_pinjams.forEach(detail => {
                const namaAlat = detail.alat ? detail.alat.nama_alat : 'Alat Dihapus';
                listBarangEl.innerHTML += `<div>• ${namaAlat} <span class="text-slate-500 text-xs">(${detail.jumlah} pcs)</span></div>`;
            });
        } else {
            listBarangEl.innerText = 'Data alat tidak ditemukan';
        }

        // Render Tanggal Jatuh Tempo
        const rawTglKembali = data.tgl_jatuh_tempo || data.tgl_kembali;
        const tglJatuhTempo = new Date(rawTglKembali);
        document.getElementById('tgl-jatuh-tempo').innerText = tglJatuhTempo.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });

        // Hitung Keterlambatan
        const today = new Date();
        tglJatuhTempo.setHours(0, 0, 0, 0);
        today.setHours(0, 0, 0, 0);

        const diffTime = today - tglJatuhTempo;
        const selisihHari = Math.floor(diffTime / (1000 * 60 * 60 * 24));

        const alertEl = document.getElementById('alert-keterlambatan');
        const dendaInput = document.getElementById('input-denda');

        if (selisihHari > 0) {
            const totalDenda = selisihHari * TARIF_DENDA_PER_HARI;
            alertEl.className = "p-3 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200 flex items-center gap-2";
            alertEl.innerHTML = `⚠️ Terlambat <strong>${selisihHari} Hari</strong>. Denda keterlambatan sistem: <strong>Rp ${totalDenda.toLocaleString('id-ID')}</strong>`;
            dendaInput.value = totalDenda;
        } else {
            alertEl.className = "p-3 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-2";
            alertEl.innerHTML = `✅ Dikembalikan tepat waktu. Tidak ada denda keterlambatan.`;
            dendaInput.value = 0;
        }

        document.getElementById('box-detail').classList.remove('hidden');
    });
</script>
@endsection