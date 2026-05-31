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
            ->where('slug', 'bukit-sukamaju')
            ->value('id');

        $items = [
            [
                'jenis_transportasi' => 'Sepeda Motor',
                'icon' => 'bi bi-bicycle',
                'deskripsi' => 'Dari kantor desa, ikuti jalan utama ke arah utara sekitar 3 km hingga area parkir.',
                'warna_badge' => 'primary',
                'urutan' => 1,
            ],
            [
                'jenis_transportasi' => 'Mobil',
                'icon' => 'bi bi-car-front',
                'deskripsi' => 'Mobil dapat mencapai area parkir utama. Perjalanan dilanjutkan dengan berjalan kaki menuju puncak.',
                'warna_badge' => 'success',
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