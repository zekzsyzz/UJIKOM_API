<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\alat;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Detail_Pinjam;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PeminjamController extends Controller
{
    public function katalog(Request $request)
    {
        $search = $request->input('search');
        $kategori_id = $request->input('kategori');

        // Mengambil data alat beserta kategorinya
        $alats = Alat::with('kategori')
            ->when($search, function ($query, $search) {
                return $query->where('nama_alat', 'like', "%{$search}%");
            })
            ->when($kategori_id, function ($query, $kategori_id) {
                return $query->where('kategori_id', $kategori_id);
            })
            ->latest()
            ->paginate(12) // Menampilkan 12 item per halaman
            ->withQueryString();

        $kategoris = Kategori::all(); // Untuk filter kategori

        return view('peminjam.katalog', compact('alats', 'kategoris', 'search'));
    }

    public function create($id)
    {
        $alat = Alat::findOrFail($id);
        
        // Cek jika stok habis, jangan izinkan buka form
        if ($alat->stok <= 0) {
            return redirect()->route('peminjam.katalog')->with('error', 'Maaf, stok alat ini sedang kosong.');
        }

        return view('peminjam.form-pinjam', compact('alat'));
    }
    
    public function ajukanpeminjaman(Request $request)
    {
        // Validasi Input
        $request->validate([
            'tgl_kembali_plan' => 'required|date|after:today',
            'alat_id'   => 'required|array', // Pastikan input alat berupa array
            'alat_id.*' => 'exists:alats,id', // Memastikan alat ada di database
            'jumlah'    => 'required|array', // Pastikan input jumlah berupa array
            'jumlah.*'  => 'integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat Data Induk Peminjaman (Master)
            $peminjaman = Peminjaman::create([
                'user_id' => auth()->id(),
                'tgl_pinjam' => now(),
                'tgl_kembali_plan' => $request->tgl_kembali_plan,
                'status' => 'diajukan',
            ]);

            // 2. Looping untuk Memasukkan Detail Barang yang Dipinjam
            foreach ($request->alat_id as $index => $alatid) {
                Detail_Pinjam::create([
                    'peminjaman_id' => $peminjaman->id, 
                    'alat_id' => $alatid,
                    'jumlah' => $request->jumlah[$index],
                ]);
            }

            DB::commit();

            return redirect()->route('peminjam.riwayat')->with('success', 'Pengajuan peminjaman berhasil dibuat dan menunggu persetujuan.');

        } catch (\Exception $e) {
            DB::rollback();
            
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }


    public function riwayat()
    {
        $riwayat = Peminjaman::with('detailPinjams.alat')
            ->where('user_id', auth()->id())
            ->orderBy('tgl_pinjam', 'desc')
            ->paginate(10);

        return view('peminjam.riwayat', compact('riwayat'));
    }

    public function ajukankembali($id)
{
    $peminjaman = Peminjaman::findOrFail($id);

    // Pastikan data milik user yang sedang login dan statusnya sedang 'dipinjam'
    if ($peminjaman->user_id == auth()->id() && $peminjaman->status == 'dipinjam') {
        $peminjaman->update([
            'status' => 'dikembalikan'
        ]);

        return back()->with('success', 'Pengajuan pengembalian terkirim. Silakan bawa barang ke meja petugas untuk dicek!');
    }

    return back()->with('error', 'Status peminjaman tidak valid atau akses ditolak.');
}
}
