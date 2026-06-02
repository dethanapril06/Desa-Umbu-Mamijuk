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
                'kode_pos' => '85111',
                'telepon' => '08xxxxxxxxxx',
                'email' => 'pemdes@umbumamijuk.desa.id',
                'alamat_lengkap' => 'Jalan Utama Desa Umbu Mamijuk, Kecamatan Umbu Ratu Nggay Barat, Kabupaten Sumba Tengah, Nusa Tenggara Timur 85111',
                'luas_wilayah' => 'xxxx km²',
                'ketinggian' => 'xxxx mdpl',
                'jam_pelayanan' => 'Senin–Jumat, 08.00–15.00 WITA',
                'visi' => 'lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'misi' => "1. lorem ipsum dolor sit amet, consectetur adipiscing elit.\n"
                    . "2. lorem ipsum dolor sit amet, consectetur adipiscing elit.\n"
                    . "3. lorem ipsum dolor sit amet, consectetur adipiscing elit.\n"
                    . "4. lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                'sejarah_desa' => 'lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'logo' => 'images/profil/logo-desa.png',
                'batas_utara' => 'Desa XXXX',
                'batas_timur' => 'Desa XXXX',
                'batas_selatan' => 'Desa XXXX',
                'batas_barat' => 'Desa XXXX',
                'koordinat_lat' => '-9.570188',
                'koordinat_lng' => '119.629533',
                'gambar_struktur_organisasi' => 'images/profil/struktur-organisasi.svg',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}