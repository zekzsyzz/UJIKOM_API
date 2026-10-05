@extends('layouts.app')

@section('title', 'Pemantauan Pengembalian - Dashboard Petugas')
@section('header-title', 'Pemantauan & Proses Pengembalian Alat')

@section('content')
    <!-- Notifikasi Alert -->
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-100 text-emerald-700 p-4 rounded-xl shadow-sm text-sm font-semibold">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 bg-rose-50 border border-rose-100 text-rose-700 p-4 rounded-xl shadow-sm text-sm font-semibold">
            <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Toolbar Modern (Pencarian & Judul Tabel) -->
    <div class="mb-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <h3 class="text-lg font-bold text-slate-800">Daftar Peminjaman Aktif (Belum Kembali)</h3>
        
        <form action="{{ route('petugas.pengembalian.index') }}" method="GET" class="w-full lg:w-96 relative group">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-slate-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peminjam..."
                class="w-full pl-10 pr-20 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm placeholder-slate-400">
            
            <div class="absolute inset-y-1 right-1 flex items-center gap-1">
                @if(request('search'))
                    <a href="{{ route('petugas.pengembalian.index') }}" class="p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Reset">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors shadow-sm">
                    Cari
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Data Pengembalian -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden w-full">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider font-semibold">
                        <th class="py-4 px-6 w-48">Peminjam</th>
                        <th class="py-4 px-6 w-32">Tgl Pinjam</th>
                        <th class="py-4 px-6 w-32">Rencana Kembali</th>
                        <th class="py-4 px-6 w-28">Status</th>
                        <th class="py-4 px-6 min-w-[200px]">Detail Alat</th>
                        <th class="py-4 px-6 w-56 text-center">Aksi Pengembalian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($peminjamans as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors align-top">
                            <!-- Peminjam -->
                            <td class="py-5 px-6 font-semibold text-slate-800">
                                {{ $item->user->name ?? 'User Dihapus' }}
                            </td>
                            
                            <!-- Tgl Pinjam -->
                            <td class="py-5 px-6 text-slate-600">
                                {{ $item->tgl_pinjam }}
                            </td>
                            
                            <!-- Rencana Kembali -->
                            <td class="py-5 px-6 text-slate-600">
                                {{ $item->tgl_kembali_plan }}
                            </td>
                            
                            <!-- Status -->
                            <td class="py-5 px-6">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $item->status == 'telat' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-blue-50 text-blue-700 border-blue-200' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>

                            <!-- Detail Alat -->
                            <td class="py-5 px-6">
                                <ul class="list-disc list-inside space-y-1.5 text-xs text-slate-600">
                                    @foreach($item->detailPinjams as $detail)
                                        <li class="flex items-start gap-1">
                                            <span class="font-semibold text-slate-700">{{ $detail->alat->nama_alat ?? 'Alat Dihapus' }}</span>
                                            <span class="text-slate-400 whitespace-nowrap">(Jumlah: {{ $detail->jumlah }})</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>

                            <!-- Form Proses Pengembalian -->
                            <td>
                                <!-- PERBAIKAN: Gunakan $item->id -->
                                <button type="button" onclick="openModal('modal-kembali-{{ $item->id }}')" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-4 py-2 rounded-lg w-full transition-colors shadow-sm">
                                    Proses Pengembalian
                                </button>
                            </td>

                            <!-- PERBAIKAN: Gunakan $item->id -->
                            <div id="modal-kembali-{{ $item->id }}" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
                                <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden">
                                    <form action="{{ route('petugas.pengembalian.proses') }}" method="POST">
                                        @csrf
                                        <!-- PERBAIKAN: Gunakan $item->id -->
                                        <input type="hidden" name="peminjaman_id" value="{{ $item->id }}">

                                        <div class="bg-slate-900 border-b border-slate-200 px-6 py-4 flex justify-between items-center">
                                            <!-- PERBAIKAN: Gunakan $item->user->name -->
                                            <h3 class="text-lg font-bold text-white">Proses Pengembalian: {{ $item->user->name ?? 'Peminjam' }}</h3>
                                            <button type="button" onclick="closeModal('modal-kembali-{{ $item->id }}')" class="text-slate-400 hover:text-white">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                        @php
                                            $dendaOtomatis = 0;
                                            $hariTelat = 0;
                                            $tarifPerHari = 2000;

                                            $tglRencana = \Carbon\Carbon::parse($item->tgl_kembali_plan)->startOfDay();
                                            $hariIni = \Carbon\Carbon::now()->startOfDay();

                                            // Jika hari ini melewati batas tanggal rencana kembali, hitung selisih harinya
                                            if ($hariIni > $tglRencana) {
                                                $hariTelat = $tglRencana->diffInDays($hariIni);
                                                $dendaOtomatis = $hariTelat * $tarifPerHari;
                                            }
                                        @endphp
                                        <div class="p-6 max-h-[60vh] overflow-y-auto space-y-4 bg-slate-50">
                                            
                                            <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl mb-4 flex items-center justify-between">
                                                <div>
                                                    <h4 class="text-sm font-bold text-amber-800">Denda Keterlambatan</h4>
                                                    <!-- Tambahkan informasi visual agar petugas tahu berapa hari telatnya -->
                                                    @if($hariTelat > 0)
                                                        <p class="text-xs font-semibold text-rose-600">Telat {{ $hariTelat }} Hari (Tarif: Rp {{ number_format($tarifPerHari, 0, ',', '.') }}/hari)</p>
                                                    @else
                                                        <p class="text-xs text-amber-600">Dihitung otomatis berdasarkan hari telat</p>
                                                    @endif
                                                </div>
                                                
                                                <!-- Ubah atribut value="0" menjadi value="{{ $dendaOtomatis }}" -->
                                                <input type="number" name="denda_keterlambatan" value="{{ $dendaOtomatis }}" class="w-32 bg-slate-100 border border-amber-300 rounded-lg px-3 py-2 text-sm outline-none font-bold text-slate-700 cursor-not-allowed" readonly>
                                            </div>

                                            <h4 class="text-sm font-bold text-slate-700 border-b pb-2">Pengecekan Kondisi Per Alat:</h4>

                                            <!-- PERBAIKAN: Gunakan $item->detailPinjams -->
                                            @foreach($item->detailPinjams as $detail)
                                                <div class="border border-slate-200 rounded-xl p-4 bg-white flex flex-col md:flex-row gap-4 items-start md:items-center shadow-sm">
                                                    <div class="flex-1">
                                                        <h5 class="font-bold text-slate-800">{{ $detail->alat->nama_alat ?? 'Alat' }}</h5>
                                                        <p class="text-xs text-slate-500 mt-1">Jumlah: <span class="font-bold text-slate-700">{{ $detail->jumlah }} Unit</span></p>
                                                        
                                                        <input type="hidden" name="detail_id[]" value="{{ $detail->id }}">
                                                        <input type="hidden" name="alat_id[]" value="{{ $detail->alat_id }}">
                                                        <input type="hidden" name="jumlah[]" value="{{ $detail->jumlah }}">
                                                    </div>

                                                    <div class="w-full md:w-1/3">
                                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Kondisi</label>
                                                        <select name="kondisi_kembali[]" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500" required onchange="toggleDenda(this, {{ $detail->id }})">
                                                            <option value="Baik">Baik</option>
                                                            <option value="Rusak">Rusak</option>
                                                            <option value="Hilang">Hilang</option>
                                                        </select>
                                                    </div>

                                                    <div class="w-full md:w-1/3">
                                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Denda Kerusakan (Rp)</label>
                                                        <input type="number" name="denda_kerusakan[]" id="denda-{{ $detail->id }}" value="0" min="0" class="w-full bg-slate-100 border border-slate-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500 transition-colors" readonly>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="bg-white border-t border-slate-200 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                                            <!-- PERBAIKAN: Gunakan $item->id -->
                                            <button type="button" onclick="closeModal('modal-kembali-{{ $item->id }}')" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">Batal</button>
                                            <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-colors">
                                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Selesaikan Pengembalian
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </tr>
                    @empty
                        <!-- (Isi area kosong biarkan sama) -->
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    // Fungsi untuk membuka/mengunci input denda berdasarkan kondisi alat
    function toggleDenda(selectElement, detailId) {
        const inputDenda = document.getElementById('denda-' + detailId);
        
        if (selectElement.value === 'Baik') {
            inputDenda.value = 0;
            inputDenda.readOnly = true;
            inputDenda.classList.add('bg-slate-100');
            inputDenda.classList.remove('bg-white');
        } else {
            // Jika Rusak / Hilang, buka input denda
            inputDenda.readOnly = false;
            inputDenda.classList.remove('bg-slate-100');
            inputDenda.classList.add('bg-white');
            inputDenda.focus();
        }
    }
</script>
@endsection