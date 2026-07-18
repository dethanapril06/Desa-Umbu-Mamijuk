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
            ['nama_desa' => 'Umbu Mamijuk'],
            [
                'kecamatan' => 'Umbu Ratu Nggay Barat',
                'kabupaten' => 'Sumba Tengah',
                'provinsi' => 'Nusa Tenggara Timur',
                'kode_pos' => '87281',
                'telepon' => '08113948576',
                'email' => 'pemdes@umbumamijuk.desa.id',
                'alamat_lengkap' => 'Jalan Utama Desa Umbu Mamijuk, Kecamatan Umbu Ratu Nggay Barat, Kabupaten Sumba Tengah, Nusa Tenggara Timur 87281',
                'luas_wilayah' => '100 km²',
                'ketinggian' => '1000 mdpl',
                'jam_pelayanan' => 'Senin–Jumat, 08.00–15.00 WITA',
                'visi' => 'Mewujudkan Desa Umbu Mamijuk yang Mandiri, Sejahtera, Sehat, dan Berbudaya Berlandaskan Semangat Gotong Royong.',
                'misi' => "1. Meningkatkan pembangunan infrastruktur desa yang merata, berkelanjutan, dan ramah lingkungan.\n"
                    . "2. Mendorong kemandirian ekonomi masyarakat melalui pemberdayaan UMKM lokal dan sektor pertanian/peternakan.\n"
                    . "3. Meningkatkan kualitas pelayanan publik berbasis teknologi informasi guna terwujudnya tata kelola pemerintahan yang bersih dan transparan.\n"
                    . "4. Melestarikan nilai-nilai adat Sumba dan kearifan lokal komunitas adat Umbu Pabal sebagai fondasi kerukunan warga.",
                'sejarah_desa' => 'Desa Umbu Mamijuk secara historis berakar dari peradaban adat komunitas adat Umbu Pabal yang mendiami wilayah Anakalang, Sumba Tengah. Wilayah ini secara tradisional dikenal sebagai kawasan agraris yang subur dan kaya akan tradisi megalitikum. Seiring dengan perkembangan administratif di Kabupaten Sumba Tengah, Desa Umbu Mamijuk dibentuk untuk mengoptimalkan pelayanan masyarakat dengan tetap memegang teguh hukum adat, sistem kekerabatan Marapu, dan gotong royong antar-suku (kabihu).',
                'logo' => 'images/profil/logo-desa.png',
                'batas_utara' => 'Desa Umbu Jodu',
                'batas_timur' => 'Desa Umbu Pabal',
                'batas_selatan' => 'Desa Umbu Pabal Selatan',
                'batas_barat' => 'Desa Soba',
                'gambar_struktur_organisasi' => 'images/profil/struktur-organisasi.svg',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}