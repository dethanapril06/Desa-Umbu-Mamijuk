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
                'deskripsi' => 'lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'warna_badge' => '#2d5a3d',
                'urutan' => 1,
            ],
            [
                'jenis_transportasi' => 'Mobil',
                'icon' => 'bi bi-car-front',
                'deskripsi' => 'lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'warna_badge' => '#52a96e',
                'urutan' => 2,
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
