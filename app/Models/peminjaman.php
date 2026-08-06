<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class peminjaman extends Model
{
    use HasFactory;
    protected $table = 'peminjamen';
    protected $fillable = ['user_id', 'tgl_pinjam', 'tgl_kembali_plan', 'status'];

    protected function casts(): array
    {
        return [
            'tgl_pinjam' => 'date::Y-m-d',
            'tgl_kembali_plan' => 'date::Y-m-d',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detail_peminjaman(): HasMany
    {
        return $this->hasMany(Detail_Pinjam::class);
    }

    public function peminjaman(): HasOne
    {
        return $this->hasOne(Pengembalian::class);
    }
}
