@extends('layouts.app')

@section('title', 'Riwayat Pengembalian')
@section('header-title', 'Riwayat Transaksi Pengembalian')

@section('content')
    <!-- Notification -->
    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-100 text-emerald-700 p-4 rounded-xl text-sm font-semibold flex items-center gap-2">
            ✅ {{ session('success') }}
        </div>
    @endif

    <!-- Toolbar -->
    <div class="mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ url()->current() }}" method="GET" class="w-full md:w-96 relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari peminjam / kondisi..."
                class="w-full pl-4 pr-20 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            <button type="submit" class="absolute right-1 top-1 bottom-1 bg-slate-800 text-white px-3 text-xs font-semibold rounded-lg">Cari</button>
        </form>

        <!-- Tombol Tambah HANYA untuk Admin -->
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.pengembalian.create') }}" 
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Pengembalian
            </a>
        @endif
    </div>

    <!-- Tabel Data -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold border-b border-slate-200">
                    <th class="py-4 px-6">Peminjam</th>
                    <th class="py-4 px-6">Alat</th>
                    <th class="py-4 px-6">Tanggal & Petugas</th>
                    <th class="py-4 px-6 text-center">Kondisi</th>
                    <th class="py-4 px-6 text-right">Denda</th>
                    @if(Auth::user()->role === 'admin')
                        <th class="py-4 px-6 text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pengembalians as $kembali)
                    <tr class="hover:bg-slate-50">
                        <td class="py-4 px-6 font-bold text-slate-800">
                            {{ $kembali->peminjaman->user->name ?? 'Data Dihapus' }}
                        </td>
                        <td class="py-4 px-6">
                            @foreach($kembali->peminjaman->detailPinjams ?? [] as $detail)
                                <div>• {{ $detail->alat->nama_alat ?? 'Alat Dihapus' }} ({{ $detail->jumlah }} pcs)</div>
                            @endforeach
                        </td>
                        <td class="py-4 px-6 text-xs text-slate-600">
                            <div>Dikembalikan: <strong class="text-emerald-700">{{ \Carbon\Carbon::parse($kembali->tgl_kembali)->format('d M Y') }}</strong></div>
                            <div class="text-slate-400 mt-1">Petugas: {{ $kembali->petugas->name ?? 'Admin' }}</div>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                {{ ucwords($kembali->kondisi_kembali) }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right font-bold {{ $kembali->denda > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                            Rp {{ number_format($kembali->denda, 0, ',', '.') }}
                        </td>

                        <!-- Tombol Hapus HANYA untuk Admin -->
                        @if(Auth::user()->role === 'admin')
                            <td class="py-4 px-6 text-center">
                                <form action="{{ route('admin.pengembalian.destroy', $kembali->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus log ini? Status peminjaman akan kembali menjadi Dipinjam.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Log">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">Belum ada riwayat pengembalian alat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $pengembalians->links() }}
        </div>
    </div>
@endsection