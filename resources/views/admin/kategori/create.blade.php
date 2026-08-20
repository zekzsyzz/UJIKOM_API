@extends('layouts.app')

@section('title', 'Tambah Kategori - Panel Admin')
@section('header-title', 'Tambah Kategori Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Form Header -->
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="p-2.5 bg-blue-100 text-blue-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Formulir Kategori</h3>
                <p class="text-xs text-slate-500 font-medium">Buat kategori baru untuk mempermudah pengelompokan data alat.</p>
            </div>
        </div>

        <form action="{{ route('admin.kategori.store') }}" method="POST" class="p-6 md:p-8">
            @csrf

            <div class="mb-6">
                <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Nama Kategori</label>
                <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}" required placeholder="Contoh: Jaringan, Mikrokontroler, Power Tools"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                @error('nama_kategori') <span class="text-red-500 text-xs mt-1 ml-1 block font-medium">{{ $message }}</span> @enderror
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.kategori.index') }}" 
                    class="px-6 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-all">
                    Batal
                </a>
                <button type="submit" 
                    class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection