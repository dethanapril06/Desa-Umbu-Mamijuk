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
                'caption' => 'Pintu masuk dan jajaran batu kubur megalitikum utama di Kampung Adat Pasunga.',
                'urutan' => 1,
            ],
            [
                'gambar' => 'images/wisata/galeri/bukit-kami-2.jpg',
                'caption' => 'Detail ukiran tradisional bermotif figur manusia dan simbol adat pada makam megalitik.',
                'urutan' => 2,
            ],
            [
                'gambar' => 'images/wisata/galeri/bukit-kami-3.jpg',
                'caption' => 'Uma Adung, rumah panggung tradisional Sumba dengan atap ilalang menjulang tinggi.',
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
