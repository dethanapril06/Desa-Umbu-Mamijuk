<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GaleriWisataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $wisataId = DB::table('wisata')
            ->where('slug', 'bukit-sukamaju')
            ->value('id');

        $items = [
            [
                'gambar' => 'images/wisata/galeri/bukit-sukamaju-1.jpg',
                'caption' => 'Panorama Bukit Sukamaju',
                'urutan' => 1,
            ],
            [
                'gambar' => 'images/wisata/galeri/bukit-sukamaju-2.jpg',
                'caption' => 'Pemandangan matahari terbenam',
                'urutan' => 2,
            ],
            [
                'gambar' => 'images/wisata/galeri/bukit-sukamaju-3.jpg',
                'caption' => 'Area bersantai pengunjung',
                'urutan' => 3,
            ],
        ];

        foreach ($items as $item) {
            DB::table('galeri_wisata')->updateOrInsert(
                [
                    'wisata_id' => $wisataId,
                    'gambar' => $item['gambar'],
                ],
                array_merge($item, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}