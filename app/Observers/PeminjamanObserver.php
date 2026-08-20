<?php

namespace App\Observers;

use App\Models\Peminjaman;
use\App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class PeminjamanObserver
{
    public function catatLog(string $pesan): void
    {
        if(Auth::check()) {
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => $pesan,
            ]);
        }
    }

    public function created(Peminjaman $peminjaman): void
    {
        $namaPeminjam = $peminjaman->user?->name ?? 'user';
        $this->catatLog("peminjam ({$namaPeminjam}) membuat permohonan peminjaman baru (ID: #{$peminjaman->id})");
    }

    public function updated(Peminjaman $peminjaman): void
    {
        if($pemminjaman->wasChanged('status')) {
            $this->catatLog("status peminjaman (ID: #{$peminjaman->id}) berubah menjadi: '{$peminjaman->status}'");
        }else {
            if(!empty($peminjaman->getchanges())) {
                $this->catatLog("memperbarui detail data peminjaman (ID: #{$peminjaman->id}) ");
            }
        }
    }

    public function deleted(Peminjaman $peminjaman): void
    {
        $this->catatLog("membatalkan/menghapus permohonan peminjaman (ID: #{$peminjaman->id})");
    }

}
