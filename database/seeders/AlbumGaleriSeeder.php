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
                'nama' => 'Album Lorem Ipsum',
                'slug' => 'album-lorem-ipsum',
                'deskripsi' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'cover' => 'images/galeri/kegiatan-masyarakat-cover.jpg',
            ],
            [
                'nama' => 'Album Dolor Sit',
                'slug' => 'album-dolor-sit',
                'deskripsi' => 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
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