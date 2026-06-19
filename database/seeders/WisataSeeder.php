<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WisataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $userId = DB::table('users')
            ->where('email', 'admin@umbumamijuk.desa.id')
            ->value('id');

        $kategoriId = DB::table('kategori_wisata')
            ->where('slug', 'wisata-budaya')
            ->value('id');


        $googleMapsEmbedUrl = 'https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d23980.88770818352!2d119.58972801485689!3d-9.603195188105502!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e6!4m5!1s0x2c4b17c75c18f31b%3A0x3c824e79ef520f96!2sKantor%20Balai%20Desa%20Umbu%20Mamijuk%2C%209JVF%2B5PQ%2C%20Umbu%20Mamijuk%2C%20Kec.%20Umbu%20Ratu%20Nggay%20Bar.%2C%20Kabupaten%20Sumba%20Tengah%2C%20Nusa%20Tenggara%20Tim.!3m2!1d-9.6068622!2d119.62363599999999!4m5!1s0x2c4b1871ffd9bb3f%3A0x8a90635ecdf0bb11!2sKampung%20Adat%20Pasunga%2C%20CH6F%2BCR3%2C%20Anakalang%2C%20Katikutana%2C%20Central%20Sumba%20Regency%2C%20East%20Nusa%20Tenggara!3m2!1d-9.588985!2d119.5745508!5e1!3m2!1sid!2sid!4v1780400652696!5m2!1sid!2sid';


        DB::table('wisata')->updateOrInsert(
            ['slug' => 'kampung-adat-pasunga'],
            [
                'user_id' => $userId,
                'kategori_wisata_id' => $kategoriId,
                'nama' => 'Kampung Adat Pasunga',
                'deskripsi_singkat' => 'Situs perkampungan tradisional di Sumba Tengah yang kaya akan cagar budaya megalitikum, rumah panggung adat (Uma Adung), dan ritual kepercayaan leluhur Marapu.',
                'deskripsi' => '<p>Kampung Adat Pasunga merupakan salah satu destinasi wisata budaya paling bersejarah yang terletak di Kecamatan Katikutana, Kabupaten Sumba Tengah. Perkampungan ini mempertahankan tata ruang tradisional Sumba dengan jajaran rumah adat (Uma Adung) beratapkan ilalang yang menjulang tinggi.</p><p>Keunikan utama Kampung Adat Pasunga terletak pada kumpulan kubur batu megalitikum berukuran raksasa di tengah desa, lengkap dengan ukiran detail bermotif hewan, tumbuhan, dan figur manusia yang melambangkan status sosial serta penghormatan mendalam kepada arwah leluhur (Marapu). Pengunjung dapat berinteraksi langsung dengan warga setempat, mempelajari cara pembuatan kain tenun ikat tradisional, serta merasakan keasrian tradisi megalitik yang masih hidup hingga hari ini.</p>',
                'highlight_quote' => 'Menjaga warisan budaya megalitikum dan kearifan leluhur Marapu di jantung tanah Anakalang.',
                'gambar_utama' => 'images/wisata/bukit-kami.jpg',
                'harga_tiket' => 0,
                'harga_parkir_motor' => null,
                'harga_parkir_mobil' => null,
                'jam_operasional' => '08.00-17.00 WITA',
                'hari_buka' => 'Setiap hari',
                'jarak_dari_desa' => 'sekitar 8 km',
                'durasi_trek' => null,
                'cocok_untuk' => 'Wisata budaya, keluarga, fotografi, edukasi adat',
                'telepon' => '081234567890',
                'koordinat_lat' => '-9.588985',
                'koordinat_lng' => '119.5745508',
                'google_maps_embed_url' => $googleMapsEmbedUrl,
                'is_unggulan' => true,
                'is_published' => true,
                'deleted_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}
