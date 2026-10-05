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

                if ($alat->stok < $detail->jumlah) {
                DB::rollBack();
                return redirect()->back()->with('error', "Persetujuan gagal! Stok alat '{$alat->nama_alat}' tidak mencukupi (Sisa stok: {$alat->stok}).");
            }
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
            // PERBAIKAN: Gunakan whereIn. Status aktif adalah dipinjam/telat, bukan dikembalikan
            ->whereIn('status', ['dikembalikan']) 
            ->when($search, function($query, $search) {
                return $query->whereHas('user', function($q) use($search){
                    // PERBAIKAN: Hapus kelebihan tanda } pada %{$search}%
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return view('petugas.pengembalian.index', compact('peminjamans', 'search'));
    }

    public function prosespengembalian(Request $request)
    {
        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);
        $tgl_rencana = \Carbon\Carbon::parse($peminjaman->tgl_kembali_plan)->startOfDay();
        $hari_ini = \Carbon\Carbon::now()->startOfDay();
        
        $request->validate([
            'peminjaman_id'       => 'required|exists:peminjamen,id',
            'detail_id'           => 'required|array',
            'alat_id'             => 'required|array',
            'kondisi_kembali'     => 'required|array',
            'jumlah'              => 'required|array',
            'denda_kerusakan'     => 'required|array',
            'denda_keterlambatan' => 'nullable|numeric'
        ]);

        DB::beginTransaction();
        try {
            $totalDendaKerusakan = 0;

            foreach ($request->detail_id as $index => $detailId) {
                $kondisiItem = $request->kondisi_kembali[$index];
                $dendaItem = $request->denda_kerusakan[$index] ?? 0;
                $totalDendaKerusakan += $dendaItem;

                if ($kondisiItem == 'Baik') {
                    $alat = Alat::find($request->alat_id[$index]);
                    if ($alat) {
                        $alat->increment('stok', $request->jumlah[$index]);
                    }
                }
            }

            $dendaTelat = $request->denda_keterlambatan ?? 0;
            $totalDendaKeseluruhan = $totalDendaKerusakan + $dendaTelat;

            Pengembalian::create([
                'peminjaman_id'   => $request->peminjaman_id,
                'petugas_id'      => auth()->id(),
                'tgl_kembali'     => now(),
                'kondisi_kembali' => 'Diproses',
                'denda'           => $totalDendaKeseluruhan,
            ]);

            if ($hari_ini > $tgl_rencana) {
                $statusAkhir = 'telat';
            } else {
                $statusAkhir = 'selesai';
            }

            Peminjaman::where('id', $request->peminjaman_id)->update(['status' => $statusAkhir]);

            DB::commit();
            return redirect()->back()->with('success', 'Pengembalian berhasil diproses. Total Denda: Rp ' . number_format($totalDendaKeseluruhan, 0, ',', '.'));

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
