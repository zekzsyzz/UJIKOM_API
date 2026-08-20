@extends('layouts.app')

@section('title', 'Edit Alat - Panel Admin')
@section('header-title', 'Edit Data Alat')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Form Header -->
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="p-2.5 bg-amber-100 text-amber-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Perbarui Detail Alat</h3>
                <p class="text-xs text-slate-500 font-medium">Ubah informasi alat, stok, atau kondisi di bawah ini.</p>
            </div>
        </div>

        <form action="{{ route('admin.alat.update', $alat->id) }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Alat -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Nama Alat</label>
                    <input type="text" name="nama_alat" value="{{ old('nama_alat', $alat->nama_alat) }}" required 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                </div>

                <!-- Kategori -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Kategori Alat</label>
                    <select name="kategori_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer">
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('kategori_id', $alat->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Stok -->
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Jumlah Stok</label>
                    <input type="number" name="stok" value="{{ old('stok', $alat->stok) }}" min="0" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                </div>

                <!-- Status Kondisi -->
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Status Kondisi</label>
                    <input type="text" name="status_kondisi" value="{{ old('status_kondisi', $alat->status_kondisi) }}" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                </div>

                <!-- Deskripsi -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">{{ old('deskripsi', $alat->deskripsi) }}</textarea>
                </div>

                <!-- Gambar Saat Ini & Input File -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Foto Alat <span class="text-[11px] text-slate-400 font-normal ml-1">(Kosongkan jika tidak diubah)</span></label>
                    
                    <div class="flex items-start gap-4">
                        @if($alat->foto)
                            <div class="flex-shrink-0">
                                <img src="{{ asset($alat->foto) }}" alt="Preview" class="w-24 h-24 object-cover rounded-xl border border-slate-200 shadow-sm">
                            </div>
                        @endif
                        
                        <div class="flex-grow">
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-10 w-10 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                    <div class="flex text-sm text-slate-600 justify-center">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-amber-600 hover:text-amber-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-amber-500 px-1">
                                            <span>Pilih foto baru</span>
                                            <input type="file" name="foto" accept="image/*" class="sr-only">
                                        </label>
                                    </div>
                                    <p class="text-[11px] text-slate-500">Maks. 2MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 mt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.alat.index') }}" 
                    class="px-6 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-all">
                    Batal
                </a>
                <button type="submit" 
                    class="px-6 py-2.5 text-sm font-semibold text-white bg-amber-500 rounded-xl hover:bg-amber-600 transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection