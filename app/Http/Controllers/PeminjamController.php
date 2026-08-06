<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\alat;
use App\Models\Peminjaman;
use App\Models\Detail_Pinjam;
use Illuminate\Support\Facades\DB;

class PeminjamController extends Controller
{
    public function katalogalat()
    {
        $alats = Alat::with('kategori')->where('stok', '>', 0)->get();
        return view('peminjam.katalog', compact('alats'));
    }

    public function ajukanpeminjaman(Request $request)
    {
        $request->validate([
            'tgl_kembali_plan' => 'required|date|after:today',
            'alat_id' => 'required|array',
            'jumlah' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::create([
                'user_id' => auth()->id(),
                'tgl_pinjam' => now(),
                'tgl_kembali_plan' => $request->tgl_kembali_plan,
                'status' => 'diajukan',
            ]);

            foreach ($request->alat_id as $index => $alatid) {
                Detail_Pinjam::create([
                    'peminjman_id' => $peminjaman->id,
                    'alat_id' => $alatid,
                    'jumlah' => $request->jumlah[$index],
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function riwayatpeminjaman()
    {
        $peminjamans = Peminjaman::with('detailpinjams.alat')->where('user_id', auth()->id())->get();
        
    }
}
