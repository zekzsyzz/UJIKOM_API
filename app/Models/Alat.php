<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class alat extends Model
{
    protected $table = 'alats';

    protected $fillable = ['nama_alat', 'kategori_id', 'stok', 'status_kondisi', 'deskripsi', 'foto'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function detailpinjam()
    {
        return $this->hasMany(DetailPinjam::class, 'alat_id');
    }
}
