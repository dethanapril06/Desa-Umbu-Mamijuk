<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GaleriSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            [
                'album' => 'album-lorem-ipsum',
                'gambar' => 'images/galeri/kerja-bakti-1.jpg',
                'caption' => 'Lorem ipsum dolor sit amet.',
                'urutan' => 1,
            ],
            [
                'album' => 'album-lorem-ipsum',
                'gambar' => 'images/galeri/pertemuan-warga-1.jpg',
                'caption' => 'Consectetur adipiscing elit sed do.',
                'urutan' => 2,
            ],
            [
                'album' => 'album-dolor-sit',
                'gambar' => 'images/galeri/pembangunan-jalan-1.jpg',
                'caption' => 'Eiusmod tempor incididunt ut labore.',
                'urutan' => 1,
            ],
        ];

        foreach ($items as $item) {
            $albumId = DB::table('album_galeri')
                ->where('slug', $item['album'])
                ->value('id');

            DB::table('galeri')->updateOrInsert(
                [
                    'album_galeri_id' => $albumId,
                    'gambar' => $item['gambar'],
                ],
                [
                    'caption' => $item['caption'],
                    'urutan' => $item['urutan'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}