<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UlasanWisataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $wisataId = DB::table('wisata')
            ->where('slug', 'bukit-sukamaju')
            ->value('id');

        DB::table('ulasan_wisata')->updateOrInsert(
            [
                'wisata_id' => $wisataId,
                'nama' => 'Maria A.',
            ],
            [
                'avatar' => null,
                'rating' => 5,
                'ulasan' => 'Pemandangannya indah dan cocok untuk bersantai bersama keluarga.',
                'is_approved' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}