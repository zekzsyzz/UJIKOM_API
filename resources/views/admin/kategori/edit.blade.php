@extends('layouts.app')

@section('title', 'Edit Kategori - Panel Admin')
@section('header-title', 'Edit Kategori Alat')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Form Header -->
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="p-2.5 bg-amber-100 text-amber-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Perbarui Kategori</h3>
                <p class="text-xs text-slate-500 font-medium">Ubah nama kategori yang sudah ada di bawah ini.</p>
            </div>
        </div>

        <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST" class="p-6 md:p-8">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label class="block text-slate-700 text-sm font-semibold mb-2 ml-1">Nama Kategori</label>
                <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required
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
                    class="px-6 py-2.5 text-sm font-semibold text-white bg-amber-500 rounded-xl hover:bg-amber-600 transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>
@endsection