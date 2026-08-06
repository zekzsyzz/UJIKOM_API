<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\BelongsTo;

class detail_pinjam extends Model
{
    protected $table = 'detail_pinjams';

    protected $fillable = ['peminjaman_id', 'alat_id', 'jumlah_pinjam'];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
        ];
    }
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id');
    }
}
