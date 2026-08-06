<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class alat extends Model
{
    protected $table = 'alats';
    protected $fillable = ['nama_alat', 'kategori_id', 'stok', 'status_kondisi', 'deskripsi', 'gambar'];
    protected function casts(): array
    {
        return [
            'stok' => 'integer',
        ];
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(kategori::class);
    }

    public function detail_peminjama()
    {
        return $this->hasMany(Detail_Pinjam::class);
    }
}
