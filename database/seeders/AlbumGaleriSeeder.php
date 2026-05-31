<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlbumGaleriSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            [
                'nama' => 'Kegiatan Masyarakat',
                'slug' => 'kegiatan-masyarakat',
                'deskripsi' => 'Dokumentasi kegiatan masyarakat Desa Sukamaju.',
                'cover' => 'images/galeri/kegiatan-masyarakat-cover.jpg',
            ],
            [
                'nama' => 'Pembangunan Desa',
                'slug' => 'pembangunan-desa',
                'deskripsi' => 'Dokumentasi pembangunan sarana dan prasarana desa.',
                'cover' => 'images/galeri/pembangunan-desa-cover.jpg',
            ],
        ];

        foreach ($items as $item) {
            DB::table('album_galeri')->updateOrInsert(
                ['slug' => $item['slug']],
                array_merge($item, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}