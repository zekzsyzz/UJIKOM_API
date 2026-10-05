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
        // 1. Cek alat spesifik yang diklik dari katalog
        $alatPilihan = Alat::findOrFail($id);
        
        // Cek jika stok habis, jangan izinkan buka form
        if ($alatPilihan->stok <= 0) {
            return redirect()->route('peminjam.katalog')->with('error', 'Maaf, stok alat ini sedang kosong.');
        }

        // 2. Ambil SEMUA alat (yang stoknya > 0) untuk mengisi list pilihan di dropdown <select>
        $alats = Alat::where('stok', '>', 0)->get();

        // Kirim $alats (untuk opsi dropdown) dan $alatPilihan (alat yang diklik) ke view
        return view('peminjam.form-pinjam', compact('alats', 'alatPilihan'));
    }
    
    public function ajukanpeminjaman(Request $request)
    {
        // 1. Validasi Input (Tambahkan validasi tgl_pinjam)
        $request->validate([
            'tgl_pinjam'       => 'required|date',
            'tgl_kembali_plan' => 'required|date|after_or_equal:tgl_pinjam',
            'alat_id'          => 'required|array|min:1',
            'alat_id.*'        => 'required|exists:alats,id',
            'jumlah'           => 'required|array|min:1',
            'jumlah.*'         => 'required|integer|min:1',
        ]);

        // 2. Proteksi Duplikasi: Cegah user memilih alat yang sama lebih dari 1 kali di baris berbeda
        $alatUnique = collect($request->alat_id)->unique();
        if ($alatUnique->count() !== count($request->alat_id)) {
            return back()->with('error', 'Gagal: Anda memilih alat yang sama lebih dari satu kali. Silakan gabungkan jumlahnya dalam satu baris saja.');
        }

        DB::beginTransaction();
        try {
            // 3. Buat Data Induk Peminjaman (Gunakan tgl_pinjam dari Request, bukan now())
            $peminjaman = Peminjaman::create([
                'user_id'          => auth()->id(),
                'tgl_pinjam'       => $request->tgl_pinjam,
                'tgl_kembali_plan' => $request->tgl_kembali_plan,
                'status'           => 'diajukan',
            ]);

            // 4. Looping untuk mengurangi stok dan membuat detail (Digabung agar lebih efisien)
            foreach ($request->alat_id as $index => $alatid) {
                $jumlahpinjam = $request->jumlah[$index];
                
                // Gunakan lockForUpdate() agar stok aman jika ada 2 user pinjam alat bersamaan
                $alat = Alat::lockForUpdate()->findOrFail($alatid); 

                if ($alat->stok < $jumlahpinjam) {
                    throw new \Exception("Stok alat '{$alat->nama_alat}' tidak mencukupi. Sisa stok: {$alat->stok}");
                }

                // Kurangi stok alat
                $alat->stok -= $jumlahpinjam;
                $alat->save();

                // Masukkan Detail Barang
                Detail_Pinjam::create([
                    'peminjaman_id' => $peminjaman->id, 
                    'alat_id'       => $alatid,
                    'jumlah'        => $jumlahpinjam,
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
