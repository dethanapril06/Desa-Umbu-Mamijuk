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
                'nama' => 'Lorem Ipsum',
                'periode_mulai' => '2023',
            ],
            [
                'foto' => 'images/kepala-desa/budi-santoso.jpg',
                'gelar' => 'S.Sos.',
                'sambutan' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Non, laborum.',
                'periode_selesai' => '2029',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}