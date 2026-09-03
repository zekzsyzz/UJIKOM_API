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
                            <td class="py-4 px-6 text-center">
                                <form action="{{ route('petugas.pengembalian.proses', $item->id) }}" method="POST"
                                    class="inline-block w-full max-w-[200px] bg-slate-50/70 p-3.5 rounded-xl border border-slate-200 text-left space-y-3 shadow-sm">
                                    @csrf
                                    <div>
                                        <label class="block text-[11px] uppercase font-bold text-slate-500 mb-1.5 tracking-wider">Kondisi Kembali</label>
                                        <select name="kondisi_kembali" required 
                                            class="w-full text-xs border border-slate-300 rounded-lg px-2.5 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white outline-none transition-all shadow-sm font-medium text-slate-700">
                                            <option value="Baik">Baik</option>
                                            <option value="Rusak Ringan">Rusak Ringan</option>
                                            <option value="Rusak Berat">Rusak Berat</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] uppercase font-bold text-slate-500 mb-1.5 tracking-wider">Denda (Rp)</label>
                                        <input type="number" name="denda" value="0" placeholder="0" min="0"
                                            class="w-full text-xs border border-slate-300 rounded-lg px-2.5 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white outline-none transition-all shadow-sm font-medium text-slate-700">
                                    </div>
                                    <button type="submit" onclick="return confirm('Proses pengembalian alat ini?')"
                                        class="w-full mt-1 bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded-lg text-xs font-semibold transition-all shadow-sm flex items-center justify-center gap-1.5 hover:shadow">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Terima
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3 border border-slate-100">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <p class="font-semibold text-slate-500">Tidak ada peminjaman yang sedang aktif</p>
                                    <p class="text-xs mt-1">Data peminjam yang belum mengembalikan alat akan tampil di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection