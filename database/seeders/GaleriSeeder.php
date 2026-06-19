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
                'album' => 'kegiatan-gotong-royong-warga',
                'gambar' => 'images/galeri/kerja-bakti-1.jpg',
                'caption' => 'Warga bahu-membahu membersihkan rumput liar dan tumpukan sampah di saluran air utama desa.',
                'urutan' => 1,
            ],
            [
                'album' => 'kegiatan-gotong-royong-warga',
                'gambar' => 'images/galeri/pertemuan-warga-1.jpg',
                'caption' => 'Suasana musyawarah dan rembuk warga dalam merencanakan program kerja bakti bulanan di balai pertemuan.',
                'urutan' => 2,
            ],
            [
                'album' => 'pembangunan-infrastruktur-desa',
                'gambar' => 'images/galeri/pembangunan-jalan-1.jpg',
                'caption' => 'Proses pengecoran jalan rabat beton di Dusun Karang Indah guna mendukung kelancaran distribusi tani.',
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