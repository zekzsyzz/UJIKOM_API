@extends('layouts.app')

@section('title', 'Tambah Alat - Panel Admin')
@section('header-title', 'Tambah Alat Baru')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Form Header -->
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="p-2.5 bg-blue-100 text-blue-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Formulir Penambahan Alat</h3>
                <p class="text-xs text-slate-500 font-medium">Lengkapi rincian di bawah ini untuk mendata alat ke dalam inventaris.</p>
            </div>
        </div>

        <form action="{{ route('admin.alat.store') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Alat -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Nama Alat</label>
                    <input type="text" name="nama_alat" value="{{ old('nama_alat') }}" required placeholder="Contoh: Multimeter Digital"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    @error('nama_alat') <span class="text-red-500 text-xs mt-1 ml-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Kategori -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Kategori Alat</label>
                    <select name="kategori_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer text-slate-700">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id') <span class="text-red-500 text-xs mt-1 ml-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Stok -->
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Jumlah Stok</label>
                    <input type="number" name="stok" value="{{ old('stok', 1) }}" min="0" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    @error('stok') <span class="text-red-500 text-xs mt-1 ml-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Status Kondisi -->
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Status Kondisi</label>
                    <input type="text" name="status_kondisi" value="{{ old('status_kondisi', 'Baik') }}" required placeholder="Contoh: Baik / Rusak Ringan"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    @error('status_kondisi') <span class="text-red-500 text-xs mt-1 ml-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Deskripsi -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Deskripsi <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <textarea name="deskripsi" rows="3" placeholder="Tambahkan keterangan spesifikasi atau catatan khusus..."
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <span class="text-red-500 text-xs mt-1 ml-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Gambar -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Foto Alat <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                            <div class="flex text-sm text-slate-600 justify-center">
                                <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500 px-1">
                                    <span>Upload sebuah file</span>
                                    <input type="file" name="gambar" accept="image/*" class="sr-only">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-slate-500">PNG, JPG, GIF up to 2MB</p>
                        </div>
                    </div>
                    @error('gambar') <span class="text-red-500 text-xs mt-1 ml-1 block font-medium">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 mt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.alat.index') }}" 
                    class="px-6 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-all">
                    Batal
                </a>
                <button type="submit" 
                    class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Alat
                </button>
            </div>
        </form>
    </div>
</div>
@endsection