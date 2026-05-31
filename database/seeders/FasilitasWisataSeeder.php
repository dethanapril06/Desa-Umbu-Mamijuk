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
            ->where('slug', 'bukit-sukamaju')
            ->value('id');

        $items = [
            [
                'nama' => 'Area Parkir',
                'icon' => 'bi bi-p-square',
            ],
            [
                'nama' => 'Toilet',
                'icon' => 'bi bi-signpost',
            ],
            [
                'nama' => 'Gazebo',
                'icon' => 'bi bi-house',
            ],
            [
                'nama' => 'Spot Foto',
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