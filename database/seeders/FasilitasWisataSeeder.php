<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FasilitasWisataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $wisataId = DB::table('wisata')
            ->where('slug', 'kampung-adat-pasunga')
            ->value('id');

        $items = [
            [
                'nama' => 'Area Parkir Kendaraan',
                'icon' => 'bi bi-p-square',
            ],
            [
                'nama' => 'Papan Informasi & Peta',
                'icon' => 'bi bi-signpost',
            ],
            [
                'nama' => 'Gasebo / Tempat Istirahat',
                'icon' => 'bi bi-house',
            ],
            [
                'nama' => 'Spot Foto Budaya',
                'icon' => 'bi bi-camera',
            ],
        ];

        foreach ($items as $item) {
            DB::table('fasilitas_wisata')->updateOrInsert(
                [
                    'wisata_id' => $wisataId,
                    'nama' => $item['nama'],
                ],
                array_merge($item, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}
