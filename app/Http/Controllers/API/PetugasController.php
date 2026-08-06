<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\alat;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Support\Facades\DB;


class PetugasController extends Controller
{
    public function indexpeminjaman()
    {
        $peminjamans = Peminjaman::with('user', 'detailpinjams')->get();
    }
}
