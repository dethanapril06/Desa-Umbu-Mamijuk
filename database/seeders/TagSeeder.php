<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            [
                'nama' => 'Pelayanan Publik',
                'slug' => 'pelayanan-publik',
            ],
            [
                'nama' => 'Pembangunan Desa',
                'slug' => 'pembangunan-desa',
            ],
            [
                'nama' => 'Gotong Royong',
                'slug' => 'gotong-royong',
            ],
            [
                'nama' => 'Informasi Desa',
                'slug' => 'informasi-desa',
            ],
        ];

        foreach ($items as $item) {
            DB::table('tags')->updateOrInsert(
                ['slug' => $item['slug']],
                array_merge($item, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}