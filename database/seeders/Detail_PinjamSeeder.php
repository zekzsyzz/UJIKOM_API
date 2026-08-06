<?php

namespace Database\Seeders;

use App\Models\Detail_Pinjam;
use Illuminate\Database\Seeder;

class Detail_PinjamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $details =
        [
            ['peminjaman_id' => 1, 'alat_id' => 1, 'jumlah' => 2],
            ['peminjaman_id' => 2, 'alat_id' => 2, 'jumlah' => 1],
            ['peminjaman_id' => 3, 'alat_id' => 3, 'jumlah' => 1],
            ['peminjaman_id' => 4, 'alat_id' => 4, 'jumlah' => 2],
            ['peminjaman_id' => 5, 'alat_id' => 5, 'jumlah' => 3],
        ];

        foreach ($details as $detail) {
            Detail_Pinjam::create($detail);
        }
    }
}
