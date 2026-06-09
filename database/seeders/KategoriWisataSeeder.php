<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriWisataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            [
                'nama' => 'Kategori XXXXX',
                'slug' => 'wisata-alam',
                'icon' => 'bi bi-tree',
            ],
            [
                'nama' => 'Kategori YYYYY',
                'slug' => 'wisata-budaya',
                'icon' => 'bi bi-bank',
            ],
            [
                'nama' => 'Kategori ZZZZZ',
                'slug' => 'wisata-kuliner',
                'icon' => 'bi bi-cup-hot',
            ],
        ];

        foreach ($items as $item) {
            DB::table('kategori_wisata')->updateOrInsert(
                ['slug' => $item['slug']],
                array_merge($item, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}