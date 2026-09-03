<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\alat;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Support\Facades\DB;

class PetugasController extends Controller
{
    public function indexpeminjaman(Request $request)
    {
        $search = $request->input('search');
        $peminjamans = Peminjaman::with('user', 'detailpinjams.alat')
        ->where('status', 'diajukan')
        ->when($search, function($query, $search) {
            return $query->whereHas('user', function($q) use($search){
                $q->where('name', 'like', "%{$search}}");
            });
        })
        ->latest()
        ->get();

        return view('petugas.peminjaman.index', compact('peminjamans', 'search'));
    }

    public function setujuipeminjaman($id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::with('detailpinjams')->findOrFail($id);
            $peminjaman->update(['status' => 'dipinjam']);

            foreach ($peminjaman->detailpinjams as $detail){
                $alat = Alat::findOrFail($detail->alat_id);
                $alat->stok -= $detail->jumlah;
                $alat->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Peminjaman disetujui dan stok alat diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function tolakpeminjaman($id)
    {
        try{
            $peminjaman = Peminjaman::findOrFail($id);

            if($peminjaman->status == 'diajukan') {
                $peminjaman->delete();
                return redirect()->back()->with('success', 'pengajuan peminjaman berhasil ditolak');
            }

            return redirect()->back()->with('error', 'status peminjaman sudah berubah');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'terjadi kesalahan: '. $e->getMessage());
        }
    }

    public function indexpengembalian(Request $request)
    {
        $search = $request->input('search');

        $peminjamans = Peminjaman::with('user', 'detailpinjams.alat')
        ->where('status', ['dipinjam', 'telat'])
        ->when($search, function($query, $search) {
            return $query->whereHas('user', function($q) use($search){
                $q->where('name', 'like', "%{$search}}");
            });
        })
        ->latest()
        ->get();

        return view('petugas.pengembalian.index', compact('peminjamans', 'search'));
    }

    public function prosespengembalian(Request $request, $id)
    {
        $request->validate([
            'kondisi_kembali' => 'required|string',
            'denda' => 'nullable|integer',
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::with('detailpinjams')->findOrFail($id);

            Pengembalian::create([
                'peminjaman_id' => $peminjaman->id,
                'tgl_kembali' => now(),
                'kondisi_kembali' => $request->kondisi_kembali,
                'denda' => $request->denda ?? 0,
                'petugas_id' => auth()->id(),
            ]);

            $peminjaman->update(['status' => 'dikembalikan']);

            foreach ($peminjaman->detailpinjams as $detail) {
                $alat = Alat::findOrFail($detail->alat_id);
                $alat->stok += $detail->jumlah;
                $alat->save();
            }
            DB::commit();
            return redirect()->back()->with('success', 'Pengembalian berhasil dicatat dan stok alat dipulihkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function laporan(Request $request)
    {
        $status = $request->input('status');
        $dari_tanggal = $request->input('dari_tanggal');
        $sampai_tanggal = $request->input('sampai_tanggal');

        $laporans = Peminjaman::with(['user', 'detailpinjams.alat', 'pengembalian'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($dari_tanggal && $sampai_tanggal, function ($query) use ($dari_tanggal, $sampai_tanggal) {
                return$query->whereBetween('tgl_pinjam', [$dari_tanggal, $sampai_tanggal]);
            })
            ->latest()
            ->get();

        return view('petugas.laporan.index', compact('laporans', 'status', 'dari_tanggal', 'sampai_tanggal'));
    }

    public function cetaklaporan(Request $request)
    {
        $status = $request->input('status');
        $dari_tanggal = $request->input('dari_tanggal');
        $sampai_tanggal = $request->input('sampai_tanggal');

        $laporans = Peminjaman::with(['user', 'detailpinjams.alat', 'pengembalian'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($dari_tanggal && $sampai_tanggal, function ($query) use ($dari_tanggal, $sampai_tanggal) {
                return$query->whereBetween('tgl_pinjam', [$dari_tanggal, $sampai_tanggal]);
            })
            ->latest()
            ->get();

        return view('petugas.laporan.cetak', compact('laporans', 'status', 'dari_tanggal', 'sampai_tanggal'));
    }
}
