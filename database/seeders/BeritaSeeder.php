<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $userId = DB::table('users')
            ->where('email', 'admin@umbumamijuk.desa.id')
            ->value('id');

        $items = [
            [
                'kategori' => 'pemerintahan',
                'judul' => 'Lorem Ipsum Dolor Sit Amet Consectetur',
                'slug' => 'lorem-ipsum-dolor-sit-amet-consectetur',
                'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor.',
                'konten' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>',
                'gambar' => 'images/berita/pelayanan-publik.jpg',
                'views' => 125,
            ],
            [
                'kategori' => 'pembangunan',
                'judul' => 'Sed Do Eiusmod Tempor Incididunt Ut Labore',
                'slug' => 'sed-do-eiusmod-tempor-incididunt-ut-labore',
                'excerpt' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi.',
                'konten' => '<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.</p>',
                'gambar' => 'images/berita/pembangunan-jalan.jpg',
                'views' => 98,
            ],
            [
                'kategori' => 'kegiatan-masyarakat',
                'judul' => 'Duis Aute Irure Dolor In Reprehenderit',
                'slug' => 'duis-aute-irure-dolor-in-reprehenderit',
                'excerpt' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.',
                'konten' => '<p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error.</p>',
                'gambar' => 'images/berita/kerja-bakti.jpg',
                'views' => 76,
            ],
        ];

        foreach ($items as $item) {
            $kategoriId = DB::table('kategori_berita')
                ->where('slug', $item['kategori'])
                ->value('id');

            DB::table('berita')->updateOrInsert(
                ['slug' => $item['slug']],
                [
                    'user_id' => $userId,
                    'kategori_berita_id' => $kategoriId,
                    'judul' => $item['judul'],
                    'excerpt' => $item['excerpt'],
                    'konten' => $item['konten'],
                    'gambar' => $item['gambar'],
                    'caption_gambar' => null,
                    'views' => $item['views'],
                    'is_published' => true,
                    'published_at' => $now,
                    'deleted_at' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}