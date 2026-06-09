<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriBeritaSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            [
                'nama' => 'Kategori XXXXX',
                'slug' => 'pemerintahan',
                'icon' => 'bi bi-building',
            ],
            [
                'nama' => 'Kategori YYYYY',
                'slug' => 'pembangunan',
                'icon' => 'bi bi-tools',
            ],
            [
                'nama' => 'Kategori ZZZZZ',
                'slug' => 'kegiatan-masyarakat',
                'icon' => 'bi bi-people',
            ],
            [
                'nama' => 'Kategori WWWWW',
                'slug' => 'pengumuman',
                'icon' => 'bi bi-megaphone',
            ],
        ];

        foreach ($items as $item) {
            DB::table('kategori_berita')->updateOrInsert(
                ['slug' => $item['slug']],
                array_merge($item, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}