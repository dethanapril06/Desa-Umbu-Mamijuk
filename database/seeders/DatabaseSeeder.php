<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProfilDesaSeeder::class,
            KepalaDesaSeeder::class,
            PerangkatDesaSeeder::class,

            DusunSeeder::class,
            RtRwSeeder::class,
            KeluargaSeeder::class,
            PendudukSeeder::class,
            MutasiPendudukSeeder::class,

            KategoriBeritaSeeder::class,
            BeritaSeeder::class,
            TagSeeder::class,
            BeritaTagSeeder::class,
            KomentarBeritaSeeder::class,

            KategoriWisataSeeder::class,
            WisataSeeder::class,
            GaleriWisataSeeder::class,
            FasilitasWisataSeeder::class,
            TipsWisataSeeder::class,
            RuteWisataSeeder::class,
            UlasanWisataSeeder::class,

            AlbumGaleriSeeder::class,
            GaleriSeeder::class,

            KategoriPengaduanSeeder::class,
            PengaduanSeeder::class,
            TanggapanPengaduanSeeder::class,

            SliderSeeder::class,
            SosialMediaSeeder::class,
        ]);
    }
}