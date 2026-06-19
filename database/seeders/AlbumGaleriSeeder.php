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
                'nama' => 'Kegiatan Gotong Royong Warga',
                'slug' => 'kegiatan-gotong-royong-warga',
                'deskripsi' => 'Dokumentasi kebersamaan dan aksi gotong royong warga Desa Umbu Mamijuk dalam memelihara lingkungan dan sarana umum.',
                'cover' => 'images/galeri/kegiatan-masyarakat-cover.jpg',
            ],
            [
                'nama' => 'Pembangunan & Infrastruktur Desa',
                'slug' => 'pembangunan-infrastruktur-desa',
                'deskripsi' => 'Kumpulan foto dokumentasi proses pembangunan fisik, jalan, jembatan, dan fasilitas umum di seluruh wilayah desa.',
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