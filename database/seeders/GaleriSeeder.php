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
                'album' => 'kegiatan-masyarakat',
                'gambar' => 'images/galeri/kerja-bakti-1.jpg',
                'caption' => 'Kegiatan kerja bakti masyarakat',
                'urutan' => 1,
            ],
            [
                'album' => 'kegiatan-masyarakat',
                'gambar' => 'images/galeri/pertemuan-warga-1.jpg',
                'caption' => 'Pertemuan warga desa',
                'urutan' => 2,
            ],
            [
                'album' => 'pembangunan-desa',
                'gambar' => 'images/galeri/pembangunan-jalan-1.jpg',
                'caption' => 'Pembangunan jalan lingkungan',
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