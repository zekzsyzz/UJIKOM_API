@extends('layouts.app') 

@section('content')
<!-- Container utama tanpa background tambahan, menyatu dengan layout bawaan -->
<div class="w-full text-gray-800">

    <!-- Form Pencarian & Filter (Dibuat mirip dengan kotak tabel di halaman Petugas) -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 mb-6">
        <form action="{{ route('peminjam.katalog') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center">
            
            <!-- Input Cari dengan Ikon -->
            <div class="relative w-full md:w-1/2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <!-- Ikon Kaca Pembesar -->
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama alat..." class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-slate-500 focus:border-slate-500 transition-colors bg-gray-50/50">
            </div>

            <!-- Filter Kategori -->
            <div class="w-full md:w-1/4">
                <select name="kategori" class="w-full appearance-none px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-slate-500 focus:border-slate-500 bg-gray-50/50 text-gray-600">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tombol Cari (Disesuaikan warnanya agar senada dengan tombol gelap di Petugas) -->
            <button type="submit" class="w-full md:w-auto bg-slate-800 hover:bg-slate-900 text-white font-medium px-6 py-2.5 rounded-lg text-sm transition-colors flex justify-center items-center">
                Cari
            </button>
        </form>
    </div>

    <!-- Grid Katalog Alat -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($alats as $alat)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden flex flex-col">
                
                <!-- Area Gambar -->
                <div class="relative w-full h-48 bg-gray-50 flex items-center justify-center border-b border-gray-100">
                    @if($alat->gambar)
                        <img src="{{ asset('storage/' . $alat->gambar) }}" alt="{{ $alat->nama_alat }}" class="w-full h-full object-cover">
                    @else
                        <!-- Placeholder Gambar -->
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    @endif
                    
                    <!-- Label Habis -->
                    @if($alat->stok <= 0)
                        <div class="absolute inset-0 bg-white/60 backdrop-blur-[1px] flex items-center justify-center">
                            <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-md uppercase tracking-wide">Habis</span>
                        </div>
                    @endif
                </div>

                <!-- Area Detail Alat -->
                <div class="p-4 flex flex-col flex-grow">
                    <!-- Kategori -->
                    <span class="text-[11px] font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded w-fit mb-2">
                        {{ $alat->kategori->nama_kategori ?? 'Umum' }}
                    </span>
                    
                    <!-- Nama Alat -->
                    <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-4 leading-snug">
                        {{ $alat->nama_alat }}
                    </h3>
                    
                    <!-- Footer Card: Stok & Aksi -->
                    <div class="mt-auto pt-3 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-gray-400 block uppercase font-medium">Stok</span>
                            <span class="text-xs font-bold {{ $alat->stok > 0 ? 'text-gray-800' : 'text-red-500' }}">
                                {{ $alat->stok }} Unit
                            </span>
                        </div>
                        
                        @if($alat->stok > 0)
                            <!-- Perhatikan perubahan nama route dan penambahan $alat->id di sini -->
                            <a href="{{ route('peminjam.ajukan.create', $alat->id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-1">
                                Pinjam
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        @else
                            <span class="text-sm font-medium text-gray-400 cursor-not-allowed">Kosong</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <!-- State Kosong -->
            <div class="col-span-full bg-white rounded-xl border border-gray-200 p-10 text-center flex flex-col items-center">
                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <h3 class="text-sm font-semibold text-gray-700">Tidak ada alat ditemukan</h3>
                <p class="text-xs text-gray-500 mt-1">Coba sesuaikan kata kunci pencarian Anda.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $alats->links() }}
    </div>

</div>
@endsection