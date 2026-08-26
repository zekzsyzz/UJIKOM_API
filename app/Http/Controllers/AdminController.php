<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Alat;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Carbon\Carbon;
use App\Models\Detail_Pinjam;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $logs = LogAktivitas::with('user')->latest()->take(10)->get();
        return view('admin.dashboard', compact('logs'));
    }

    public function indexalat(Request $request)
    {
        $search = $request->input('search');

        $alats = Alat::with('kategori')
        ->when($search, function($query, $search){
            return $query->where('nama_alat', 'like', "%{$search}%")
                ->orWhere('status_kondisi', 'like', "%{$search}%")
                ->orWhereHas('kategori', function ($q) use ($search){
                    $q->where('nama_kategori', 'like', "%{$search}%");
                });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view('admin.alat.index', compact('alats', 'search'));
    }

    public function createalat()
    {
        $kategoris = Kategori::all();
        return view('admin.alat.create', compact('kategoris'));
    }


    public function storealat(Request $request)
    {
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'stok' => 'required|integer|min:0',
            'status_kondisi' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if($request->Hasfile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/alat'), $filename);
            $data['foto'] = 'storage/alat/' . $filename;
        }

        Alat::create($data);

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'menambahkan alat baru' . $request->nama_alat
        ]);

        return redirect()->route('admin.alat.index')->with('success', 'data alat berhasil ditambahkan');
    }

    public function editalat($id)
    {
        $alat = Alat::findOrFail($id);
        $kategoris = Kategori::all();
        return view('admin.alat.edit', compact('alat', 'kategoris'));
    }

    public function updatealat(Request $request, $id)
    {
        $alat = Alat::findOrFail($id);

        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'stok' => 'required|integer|min:0',
            'status_kondisi' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            if ($alat->foto && file_exists(public_path($alat->foto))) {
                unlink(public_path($alat->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/alat'), $filename);
            $data['foto'] = 'storage/alat/' . $filename;
        }

        $alat->update($data);

        return redirect()->route('admin.alat.index')->with('success', 'data berhasil diperbarui');
    }

    public function destroyalat($id) 
    {
        $alat = Alat::findOrFail($id);

        if ($alat->foto && $file_exists(public_path($alat_gambar))) {
            unlink(public_path($alat->foto));
        }

        $alat->delete();

        return redirect()->route('admin.alat.index')->with('success', 'dataalat berhasil dihapus');
    }
    
    public function indexuser(Request $request)
    {
        $search = $request->input('search');
        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.user.index', compact('users'));
    }


    public function createuser()
    {
        return view('admin.user.create');
    }

    public function storeuser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,petugas,peminjam'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('admin.user.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edituser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }

    public function updateuser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:admin,petugas,peminjam'
        ]);

        $data =[
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'no_hp' => $request->no_hp,
        ];

        if ($request->filled('password')) {
            $data[password] = Hash::make($request->password);
        }
        $user->update($data);

        return redirect()->route('admin.user.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroyuser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus');
    }

    public function indexkategori(Request $request)
    {
        $search = $request->input('search');
        $kategoris = Kategori::when($search, function ($query, $search) {
            return $query->where('nama_kategory', 'like', "%{search}%");

        })
            ->latest()
            ->paginate(10)
            ->withQueryString();

            return view('admin.kategori.index', compact('kategoris', 'search'));
    }
    
    public function createkategori()
    {
        return view('admin.kategori.create');
    }

    public function storekategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori',
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function editkategori($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function updatekategori(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori,' . $id,
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroykategori($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus');
    }

    public function indexpeminjaman(Request $request)
    {
        $search = $request->input('search');

        $peminjamans = Peminjaman::with(['user', 'detailpinjams.alat'])
            ->when($search, function ($query, $search) {
                return $query->where('status', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
        
        return view('admin.peminjaman.index', compact('peminjamans', 'search'));
    }

    public function createpeminjaman()
    {
        $users = User::where('role', 'peminjam')->get();
        $peminjaman = Alat::where('stok', '>', 0)->get();
        return view('admin.peminjaman.create', compact('users', 'peminjaman'));
    }

    public function storepeminjaman(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tgl_pinjam' => 'required|date',
            'tgl_kembali_plan' => 'required|date|after_or_equal:tgl_pinjam',
            'alat_id' => 'required|array',
            'alat_id.*' => 'exists:alats,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::create([
                'user_id' => $request->user_id,
                'tgl_pinjam' =>  $request->tgl_pinjam,
                'tgl_kembali_plan' => $request->tgl_kembali_plan,
                'status' => 'diajukan',
            ]);

            foreach($request->alat_id as $index => $alatid) {
                $jumlahpinjam = $request->jumlah[$index];

                $alat = Alat::findOrFail($alatid);

            if ($alat->stok < $jumlahpinjam) {
                throw new \Exception("stok alat '{$alat->nama_alat}' tidak mencukupi");
            }

            Detail_Pinjam::create([
                'peminjaman_id' => $peminjaman->id,
                'alat_id' => $alatid,
                'jumlah' => $jumlahpinjam
            ]);
            }

            DB::commit();
            return redirect()->route('admin.peminjaman.index')->with('success', 'data peminjaman berhasil diajukan');
        }catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function updatepeminjaman(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('detailpinjams.alat')->findOrFail($id);

        $request->validate([
            'status' => 'required|in:diajukan,dipinjam,dikembalikan,telat',
        ]);

        DB::beginTransaction();
        try{
            $statuslama = $peminjaman->status;
            $statusbaru = $request->status;

            if($statuslama != 'dipinjam' && $statusbaru == 'dipinjam') {
                foreach ($peminjaman->detailpinjams as $detail) {
                    $alat = $detail->alat;
                    if ($alat->stok < $detail->jumlah){
                        throw new \Exception("stok alat {$alat->nama_alat} tidak mencukupi untuk dipinjam");
                    }
                    $alat->decrement('stok', $detail->jumlah);
                }
            }elseif ($statuslama == 'dipinjam' && ($statusbaru == 'dikembalikan')) {
                foreach ($peminjaman->detailpinjams as $detail) {
                    $detail->alat->increment('stok', $detail->jumlah);
                }
            }

            $peminjaman->update(['status' => $statusbaru]);

            DB::commit();
            return redirect()->route('admin.peminjaman.index')->with('success', 'status peminjaman berhasil di perbarui');
        }catch (\Exception $e){
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroypeminjaman($id)
    {
        $peminjaman = Peminjaman::with('detailpinjams')->findOrFail($id);

        if ($peminjaman->status == 'dipinjam') {
            foreach ($peminjaman->detailpinjams as $detail) {
                $detail->alat->increament('stok', $detail->jumlah);
            }
        }

        $peminjaman->delete();

        return redirect()->route('admin.peminjaman.index')->with('success', 'data peminjaman berhasil dihapus');
    }

    public function indexpengembalian(Request $request)
    {
        $search = $request->input('search');
        
        // Mengambil data pengembalian beserta relasi peminjaman dan usernya
        $pengembalians = Pengembalian::with(['peminjaman.user', 'peminjaman.alat'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('peminjaman.user', function($q) use ($search) {
                    $q->where('Name', 'like', "%{$search}%"); // Sesuaikan nama kolom nama user di database
                })
                ->orWhere('Kondisi_Kembali', 'like', "%{$search}%")
                ->orWhere('Denda', 'like', "%{$search}%");
            })
            ->latest('Tgl_Kembali') // Urutkan dari yang terbaru dikembalikan
            ->paginate(10)
            ->withQueryString();

        return view('admin.pengembalian.index', compact('pengembalians', 'search'));
    }

    public function createpengembalian($id_peminjaman)
    {
        $peminjaman = Peminjaman::with(['user', 'detailpinjams.alat'])->findOrFail($id_peminjaman);
        
        if ($peminjaman->Status == 'Dikembalikan') {
            return redirect()->route('admin.peminjaman.index')->with('error', 'Alat sudah dikembalikan!');
        }

        $tglJatuhTempo = Carbon::parse($peminjaman->tgl_jatuh_tempo ?? $peminjaman->tgl_kembali)->startOfDay();
        $tglHariIni    = Carbon::now()->startOfDay();
        
        $hariTerlambat = 0;
    if ($tglHariIni->greaterThan($tglJatuhTempo)) {
        $hariTerlambat = (int) $tglJatuhTempo->diffInDays($tglHariIni);
    }

    // 4. Hitung denda keterlambatan (misal Rp 5.000 / hari)
    $tarifDendaPerHari = 5000; 
    $dendaKeterlambatan = $hariTerlambat * $tarifDendaPerHari;

    return view('admin.pengembalian.create', compact(
        'peminjaman', 
        'hariTerlambat', 
        'dendaKeterlambatan'
    ));
    }

    public function storePengembalian(Request $request, $id_peminjaman)
    {
        // 1. Buat data pengembalian baru
        Pengembalian::create([
            'peminjaman_id'   => $id_peminjaman,
            'petugas_id'      => Auth::id(), 
            'tgl_kembali'     => Carbon::now(),
            'kondisi_kembali' => $request->kondisi_kembali,
            'denda'           => $request->denda,
        ]);

        // 2. Cari data peminjaman yang terkait, lalu UPDATE statusnya otomatis
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);
        $peminjaman->update([
            'status' => 'dikembalikan' // Sesuaikan jika di DB kamu namanya 'Status' (huruf besar)
        ]);

        // 3. Arahkan ke halaman Riwayat Pengembalian dengan pesan sukses
        return redirect()->route('admin.pengembalian.index')
                        ->with('success', 'Proses pengembalian berhasil dicatat dan status peminjaman telah diperbarui!');
    }
}
