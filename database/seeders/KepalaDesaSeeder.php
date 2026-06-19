<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KepalaDesaSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('kepala_desa')->updateOrInsert(
            [
                'nama' => 'Budi Santoso',
                'periode_mulai' => '2023',
            ],
            [
                'foto' => 'images/kepala-desa/budi-santoso.jpg',
                'gelar' => 'S.Sos.',
                'sambutan' => 'Selamat datang di official website Desa Umbu Mamijuk. Platform ini hadir sebagai wujud transparansi pelayanan publik dan media komunikasi untuk mendekatkan pemerintah desa dengan seluruh masyarakat.',
                'periode_selesai' => '2029',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}