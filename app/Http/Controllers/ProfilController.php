<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('profil.index', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('profil.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string|max:255',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'foto_profil' => $request->foto_profil,
        ];

        if ($request->hasFile('foto_profil')) {
            // Hapus foto_profil lama jika ada dan bukan default
            if ($data['foto_profil'] && Storage::exists('public/' . $data['foto_profil'])) {
                Storage::delete('public/' . $data['foto_profil']);
            }

            // Simpan foto_profil baru ke folder storage/app/public/profil
            $foto_profilPath = $request->file('foto_profil')->store('profil', 'public');
            $data['foto_profil'] = $foto_profilPath;
        }

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        $user->update($data);

        return redirect()->route('profil.index')->with('success', 'Profil berhasil diperbarui.');
    }
}
