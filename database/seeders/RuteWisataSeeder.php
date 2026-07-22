<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuteWisataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $wisataId = DB::table('wisata')
            ->where('slug', 'kampung-adat-pasunga')
            ->value('id');

        $items = [
            [
                'jenis_transportasi' => 'Sepeda Motor',
                'icon' => 'bi bi-bicycle',
                'deskripsi' => 'Dari Kantor Desa Umbu Mamijuk, arahkan kendaraan ke Jalan Raya Trans Sumba ke arah barat laut. Perjalanan memakan waktu sekitar 15-20 menit dengan kondisi jalan aspal yang sebagian besar sudah beraspal halus.',
                'warna_badge' => '#2d5a3d',
            ],
            [
                'jenis_transportasi' => 'Mobil',
                'icon' => 'bi bi-car-front',
                'deskripsi' => 'Melalui Jalan Raya Trans Sumba, berkendara ke arah barat laut menuju Anakalang selama kurang lebih 20-25 menit. Jalur ini sangat mudah diakses karena Kampung Adat Pasunga berada tepat di tepi jalan lintas kabupaten.',
                'warna_badge' => '#52a96e',
            ],
        ];

        foreach ($items as $item) {
            DB::table('rute_wisata')->updateOrInsert(
                [
                    'wisata_id' => $wisataId,
                    'jenis_transportasi' => $item['jenis_transportasi'],
                ],
                array_merge($item, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}
