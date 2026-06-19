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
                'nama' => 'Pemerintahan',
                'slug' => 'pemerintahan',
                'icon' => 'bi bi-building',
            ],
            [
                'nama' => 'Pembangunan',
                'slug' => 'pembangunan',
                'icon' => 'bi bi-tools',
            ],
            [
                'nama' => 'Kegiatan Masyarakat',
                'slug' => 'kegiatan-masyarakat',
                'icon' => 'bi bi-people',
            ],
            [
                'nama' => 'Pengumuman',
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