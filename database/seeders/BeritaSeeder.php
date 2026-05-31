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
            ->where('email', 'admin@sukamaju.desa.id')
            ->value('id');

        $items = [
            [
                'kategori' => 'pemerintahan',
                'judul' => 'Pemerintah Desa Sukamaju Tingkatkan Pelayanan Publik',
                'slug' => 'pemerintah-desa-sukamaju-tingkatkan-pelayanan-publik',
                'excerpt' => 'Pemerintah desa meningkatkan kualitas pelayanan administrasi bagi masyarakat.',
                'konten' => '<p>Pemerintah Desa Sukamaju terus meningkatkan kualitas pelayanan administrasi bagi masyarakat melalui pembenahan alur pelayanan dan penyampaian informasi secara digital.</p>',
                'gambar' => 'images/berita/pelayanan-publik.jpg',
                'views' => 125,
            ],
            [
                'kategori' => 'pembangunan',
                'judul' => 'Pembangunan Jalan Lingkungan Dusun II Mulai Dilaksanakan',
                'slug' => 'pembangunan-jalan-lingkungan-dusun-ii',
                'excerpt' => 'Pembangunan jalan lingkungan di Dusun II diharapkan mendukung mobilitas warga.',
                'konten' => '<p>Pembangunan jalan lingkungan di Dusun II mulai dilaksanakan dengan melibatkan masyarakat setempat. Kegiatan ini bertujuan memperlancar akses warga dan distribusi hasil pertanian.</p>',
                'gambar' => 'images/berita/pembangunan-jalan.jpg',
                'views' => 98,
            ],
            [
                'kategori' => 'kegiatan-masyarakat',
                'judul' => 'Kerja Bakti Bersama Menjaga Kebersihan Lingkungan Desa',
                'slug' => 'kerja-bakti-bersama-menjaga-kebersihan-lingkungan-desa',
                'excerpt' => 'Warga Desa Sukamaju melaksanakan kegiatan kerja bakti bersama.',
                'konten' => '<p>Masyarakat Desa Sukamaju melaksanakan kerja bakti membersihkan lingkungan dan fasilitas umum sebagai wujud gotong royong.</p>',
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