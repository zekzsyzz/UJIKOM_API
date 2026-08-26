<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class peminjaman extends Model
{
    protected $table = 'peminjamen';

    protected $fillable = ['user_id', 'tgl_pinjam', 'tgl_kembali_plan', 'status'];

    protected function casts(): array
    {
        return [
            'tgl_pinjam' => 'date:Y-m-d',
            'tgl_kembali_plan' => 'date:Y-m-d',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id');
    }

    public function detailpinjams()
    {
        return $this->hasMany(Detail_Pinjam::class, 'peminjaman_id');
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'peminjaman_id');
    }
}
