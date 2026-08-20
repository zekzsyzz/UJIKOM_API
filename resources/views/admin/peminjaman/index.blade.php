@extends('layouts.app')

@section('title', 'Kelola Peminjaman - Panel Admin')
@section('header-title', 'Manajemen Transaksi Peminjaman')

@section('content')
    <!-- Notifikasi -->
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-100 text-emerald-700 p-4 rounded-xl shadow-sm">
            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-semibold text-sm">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 bg-rose-50 border border-rose-100 text-rose-700 p-4 rounded-xl shadow-sm">
            <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-semibold text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Toolbar (Pencarian & Tombol Tambah) -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Search Bar Modern -->
        <form action="{{ route('admin.peminjaman.index') }}" method="GET" class="w-full md:w-96 relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peminjam / status..."
                class="w-full pl-10 pr-20 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm placeholder-slate-400">
            
            <div class="absolute inset-y-1 right-1 flex items-center gap-1">
                @if(request('search'))
                    <a href="{{ route('admin.peminjaman.index') }}" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Reset">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors shadow-sm">
                    Cari
                </button>
            </div>
        </form>

        <!-- Tombol Tambah -->
        <a href="{{ route('admin.peminjaman.create') }}" 
           class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 whitespace-nowrap">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Peminjaman
        </a>
    </div>

    <!-- Tabel Data Peminjaman -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider font-semibold">
                        <th class="py-4 px-6">Peminjam</th>
                        <th class="py-4 px-6">Alat yang Dipinjam</th>
                        <th class="py-4 px-6">Tanggal</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($peminjamans as $peminjaman)
                        <tr class="hover:bg-slate-50/80 transition-colors align-top group">
                            <!-- Peminjam -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold shrink-0">
                                        {{ strtoupper(substr($peminjaman->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $peminjaman->user->name ?? 'User Dihapus' }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Daftar Alat -->
                            <td class="py-4 px-6">
                                <ul class="space-y-2">
                                    @foreach($peminjaman->detailPinjams as $detail)
                                    <li class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <div>
                                            <span class="font-semibold text-slate-700">{{ $detail->alat->nama_alat ?? 'Alat Dihapus' }}</span>
                                            <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                                {{ $detail->jumlah }} pcs
                                            </span>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </td>

                            <!-- Tanggal -->
                            <td class="py-4 px-6">
                                <div class="space-y-1.5 text-xs">
                                    <div class="flex items-center gap-1.5 text-slate-600">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span>Pinjam: <span class="font-medium">{{ $peminjaman->tgl_pinjam }}</span></span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-slate-600">
                                        <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span>Kembali: <span class="font-medium">{{ $peminjaman->tgl_kembali_plan }}</span></span>
                                    </div>
                                </div>
                            </td>

                            <!-- Status Label -->
                            <td class="py-4 px-6">
                                @php
                                    $statusColors = [
                                        'diajukan' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'dipinjam' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'dikembalikan' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'telat' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    ];
                                    $colorClass = $statusColors[strtolower($peminjaman->status)] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $colorClass }}">
                                    {{ ucfirst($peminjaman->status) }}
                                </span>
                            </td>

                            <!-- Aksi (Update Status & Hapus) -->
                            <td class="py-4 px-6">
                                <div class="flex flex-col items-end gap-2">
                                    <!-- Form Ubah Status Cepat -->
                                    <form action="{{ route('admin.peminjaman.update', $peminjaman->id) }}" method="POST" class="w-full md:w-auto">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" onchange="this.form.submit()" 
                                            class="w-full text-xs font-medium border border-slate-200 rounded-lg px-2 py-1.5 bg-slate-50 hover:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer transition-colors shadow-sm">
                                            <option value="diajukan" {{ $peminjaman->status == 'diajukan' ? 'selected' : '' }}>Set: Diajukan</option>
                                            <option value="dipinjam" {{ $peminjaman->status == 'dipinjam' ? 'selected' : '' }}>Set: Dipinjam</option>
                                            <option value="dikembalikan" {{ $peminjaman->status == 'dikembalikan' ? 'selected' : '' }}>Set: Dikembalikan</option>
                                            <option value="telat" {{ $peminjaman->status == 'telat' ? 'selected' : '' }}>Set: Telat</option>
                                        </select>
                                    </form>

                                    <!-- Tombol Hapus Icon -->
                                    <form action="{{ route('admin.peminjaman.destroy', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data peminjaman ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 hover:text-red-600 transition-colors px-2 py-1 rounded-md hover:bg-red-50">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <!-- Empty State -->
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3 border border-slate-100">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    </div>
                                    <p class="font-semibold text-slate-500">Belum ada data peminjaman</p>
                                    <p class="text-xs mt-1">Silakan tambahkan data transaksi baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $peminjamans->links() }}
        </div>
    </div>
@endsection