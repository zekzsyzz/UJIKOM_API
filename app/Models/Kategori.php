<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasMany;


class Kategori extends Model
{
    protected $table = 'kategoris';

    protected $fillable = ['nama_kategori'];

    public function alat()
    {
        return $this->hasMany(Alat::class, 'kategori_id');
    }
}
