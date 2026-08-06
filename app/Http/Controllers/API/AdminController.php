<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\alat;
use App\Models\Kategori;
use App\Models\LogAktivitas;

class AdminController extends Controller
{
    public function index()
    {
        $logs = LogAktivitas::with('user')->latest()->take(10)->get();
        return view('admin.dashboard', compact('logs'));
    }

    public function indexalat()
    {
        $alats = Alat::with('kategori')->get();
        return view('admin.alat.index', compact('alats'));
    }

    public function storealat(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required',
            'nama_alat' => 'required|string|max:255',
            'stok' => 'required|integer',
            'status_kondisi' => 'required|string'
        ]);

        Alat::create($request->all());

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'menambahkan alat baru' . $request->nama_alat
        ]);

        return redirect()->back()->with('success', 'alat berhasil dittambahkan');
    }

    public function indexuser()
    {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }
}
