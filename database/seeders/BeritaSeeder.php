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
                'judul' => 'Pemerintah Desa Umbu Mamijuk Tingkatkan Pelayanan Publik Digital Lewat Sistem Informasi Desa',
                'slug' => 'pemdes-umbu-mamijuk-tingkatkan-pelayanan-publik-digital-lewat-sid',
                'excerpt' => 'Guna mempercepat proses administrasi kependudukan dan meningkatkan keterbukaan informasi, Pemdes meluncurkan website desa baru.',
                'konten' => '<p>Pemerintah Desa Umbu Mamijuk resmi memperkenalkan sistem informasi desa digital guna mengoptimalkan kualitas pelayanan kepada warga setempat. Melalui platform online ini, masyarakat kini dapat mengakses data kependudukan, pengaduan publik, serta informasi kegiatan desa secara cepat dan transparan.</p><p>Kepala Desa menyatakan bahwa inovasi digital ini merupakan komitmen desa dalam mewujudkan tata kelola administrasi yang bersih, modern, dan tanggap terhadap kebutuhan era digital. Warga diharapkan dapat memanfaatkan fitur pengaduan dan direktori UMKM untuk memajukan perekonomian mandiri desa.</p>',
                'gambar' => 'images/berita/pelayanan-publik.jpg',
                'views' => 125,
            ],
            [
                'kategori' => 'pembangunan',
                'judul' => 'Pembangunan Jalan Rabat Beton Dusun Karang Indah Selesai 100%, Hubungkan Akses Pertanian Warga',
                'slug' => 'pembangunan-jalan-rabat-beton-dusun-karang-indah-selesai-100-persen',
                'excerpt' => 'Proyek infrastruktur jalan rabat beton sepanjang 500 meter di Dusun Karang Indah telah rampung dikerjakan oleh warga secara swakelola.',
                'konten' => '<p>Pembangunan infrastruktur jalan desa berupa rabat beton di Dusun Karang Indah akhirnya selesai sepenuhnya. Jalan ini dirancang khusus untuk mempermudah akses transportasi hasil tani, seperti kopi robusta, kemiri, dan jagung yang selama ini sulit diangkut akibat kondisi jalan tanah yang berlumpur saat musim hujan.</p><p>Didanai dari Anggaran Dana Desa (ADD), pengerjaan fisik ini diselesaikan secara gotong royong oleh puluhan pemuda dan warga dusun. Dengan terselesaikannya jalan ini, biaya operasional pengiriman komoditas pertanian ke pasar kecamatan diharapkan dapat ditekan drastis.</p>',
                'gambar' => 'images/berita/pembangunan-jalan.jpg',
                'views' => 98,
            ],
            [
                'kategori' => 'kegiatan-masyarakat',
                'judul' => 'Masyarakat Desa Gotong Royong Bersihkan Saluran Irigasi Jelang Musim Tanam',
                'slug' => 'masyarakat-desa-gotong-royong-bersihkan-saluran-irigasi-jelang-musim-tanam',
                'excerpt' => 'Puluhan warga Desa Umbu Mamijuk berpartisipasi dalam aksi gotong royong memberikan saluran irigasi pertanian utama.',
                'konten' => '<p>Menjelang musim tanam tahun ini, puluhan petani dan warga Desa Umbu Mamijuk berkumpul untuk melaksanakan kerja bakti pembersihan saluran irigasi primer sepanjang 1,2 kilometer. Aksi ini bertujuan untuk membersihkan sedimen lumpur dan rumput liar agar pasokan air ke sawah warga berjalan lancar.</p><p>Tradisi gotong royong ini terus dipelihara sebagai wujud kebersamaan warga adat Umbu Pabal dalam menjaga sarana pertanian kolektif. Pembersihan irigasi berkala ini diharapkan dapat meminimalkan risiko gagal tanam akibat kekurangan suplai air saat fase pertumbuhan padi.</p>',
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