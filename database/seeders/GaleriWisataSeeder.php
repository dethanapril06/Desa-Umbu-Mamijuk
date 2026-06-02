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
            ->where('slug', 'kampung-adat-pasunga')
            ->value('id');

        $items = [
            [
                'gambar' => 'images/wisata/galeri/bukit-kami-1.jpg',
                'caption' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'urutan' => 1,
            ],
            [
                'gambar' => 'images/wisata/galeri/bukit-kami-2.jpg',
                'caption' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'urutan' => 2,
            ],
            [
                'gambar' => 'images/wisata/galeri/bukit-kami-3.jpg',
                'caption' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
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
