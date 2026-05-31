<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipsWisataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $wisataId = DB::table('wisata')
            ->where('slug', 'bukit-sukamaju')
            ->value('id');

        $items = [
            [
                'judul' => 'Datang pada sore hari',
                'deskripsi' => 'Waktu terbaik untuk menikmati panorama dan matahari terbenam adalah sekitar pukul 16.00 WITA.',
                'urutan' => 1,
            ],
            [
                'judul' => 'Gunakan alas kaki yang nyaman',
                'deskripsi' => 'Gunakan alas kaki yang sesuai karena pengunjung perlu berjalan menuju area puncak bukit.',
                'urutan' => 2,
            ],
        ];

        foreach ($items as $item) {
            DB::table('tips_wisata')->updateOrInsert(
                [
                    'wisata_id' => $wisataId,
                    'judul' => $item['judul'],
                ],
                array_merge($item, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}