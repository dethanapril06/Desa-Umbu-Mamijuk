<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfilDesaSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('profil_desa')->updateOrInsert(
            ['nama_desa' => 'Desa Sukamaju'],
            [
                'kecamatan' => 'Kecamatan Harapan',
                'kabupaten' => 'Kabupaten Sejahtera',
                'provinsi' => 'Nusa Tenggara Timur',
                'kode_pos' => '85111',
                'telepon' => '081234567890',
                'email' => 'pemdes@sukamaju.desa.id',
                'alamat_lengkap' => 'Jalan Utama Desa Sukamaju, Kecamatan Harapan, Kabupaten Sejahtera',
                'luas_wilayah' => '12,50 km²',
                'ketinggian' => '250 mdpl',
                'jam_pelayanan' => 'Senin–Jumat, 08.00–15.00 WITA',
                'visi' => 'Terwujudnya Desa Sukamaju yang mandiri, transparan, sejahtera, dan berdaya saing.',
                'misi' => "1. Meningkatkan kualitas pelayanan publik.\n"
                    . "2. Mengembangkan potensi ekonomi dan wisata desa.\n"
                    . "3. Mendorong partisipasi masyarakat dalam pembangunan.\n"
                    . "4. Mewujudkan tata kelola pemerintahan desa yang transparan.",
                'sejarah_desa' => 'Desa Sukamaju merupakan desa yang tumbuh dari semangat gotong royong masyarakat. Seiring perkembangan wilayah, pemerintah desa terus meningkatkan pelayanan publik dan mengembangkan potensi lokal secara berkelanjutan.',
                'logo' => 'images/profil/logo-desa.png',
                'batas_utara' => 'Desa Mekarjaya',
                'batas_timur' => 'Desa Harapan Baru',
                'batas_selatan' => 'Desa Sumber Makmur',
                'batas_barat' => 'Desa Karya Indah',
                'koordinat_lat' => '-10.177200',
                'koordinat_lng' => '123.607000',
                'gambar_struktur_organisasi' => 'images/profil/struktur-organisasi.png',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}