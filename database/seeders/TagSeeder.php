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
                'nama' => 'Kegiatan Desa',
                'slug' => 'kegiatan-desa',
            ],
            [
                'nama' => 'Pengumuman',
                'slug' => 'pengumuman',
            ],
            [
                'nama' => 'Infrastruktur',
                'slug' => 'infrastruktur',
            ],
            [
                'nama' => 'Budaya Sumba',
                'slug' => 'budaya-sumba',
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