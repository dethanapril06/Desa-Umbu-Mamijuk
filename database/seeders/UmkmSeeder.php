<?php

namespace Database\Seeders;

use App\Models\Umkm;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UmkmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $umkms = [
            [
                'nama_usaha' => 'Kopi Robusta Mamijuk',
                'slug' => 'kopi-robusta-mamijuk',
                'nama_pemilik' => 'Bapak Yohanes Gede',
                'deskripsi' => "Kopi Robusta Mamijuk ditanam langsung di perkebunan dataran tinggi Desa Umbu Mamijuk secara organik. Diproses dengan metode semi-washed tradisional untuk menghasilkan cita rasa yang kuat, aroma cokelat yang kaya, dan tingkat keasaman yang rendah.\n\nKami menyediakan biji kopi sangrai (roasted beans) dan kopi bubuk siap seduh dengan berbagai ukuran kemasan (100g, 250g, 500g, dan 1kg).\n\nDapatkan kehangatan kopi asli desa kami untuk menemani hari-hari Anda!",
                'kategori' => 'Kuliner',
                'alamat' => 'RT 02 / RW 01, Dusun Karang Indah, Desa Umbu Mamijuk',
                'no_telepon' => '081234567890',
                'email' => 'yohanes.coffee@gmail.com',
                'website_url' => 'https://tokopedia.com',
                'foto' => null,
                'jam_operasional' => '08:00 - 18:00 WITA',
                'status' => 'aktif',
            ],
            [
                'nama_usaha' => 'Tenun Ikat Ikat Sumba Ibu Maria',
                'slug' => 'tenun-ikat-sumba-ibu-maria',
                'nama_pemilik' => 'Ibu Maria Londa',
                'deskripsi' => "Kerajinan kain tenun ikat khas Sumba yang dibuat secara manual menggunakan benang katun berkualitas dan pewarna alam (daun tarum, akar mengkudu, dll.). Setiap motif tenun memiliki makna filosofis mendalam tentang kehidupan masyarakat Sumba.\n\nKami melayani pembuatan kain tenun custom, selendang, sarung, hingga pakaian siap pakai. Pengerjaan berkisar antara 2 minggu hingga 3 bulan tergantung pada kerumitan motif.\n\nDengan membeli produk kami, Anda turut melestarikan warisan budaya leluhur Sumba.",
                'kategori' => 'Kerajinan',
                'alamat' => 'RT 05 / RW 02, Dusun Watu Langit, Desa Umbu Mamijuk',
                'no_telepon' => '082345678901',
                'email' => 'maria.tenunsumba@gmail.com',
                'website_url' => 'https://instagram.com',
                'foto' => null,
                'jam_operasional' => '07:00 - 17:00 WITA',
                'status' => 'aktif',
            ],
            [
                'nama_usaha' => 'Warung Nasi Campur Bu Sandra',
                'slug' => 'warung-nasi-campur-bu-sandra',
                'nama_pemilik' => 'Ibu Sandra Ndala',
                'deskripsi' => "Warung makan sederhana yang menyajikan menu sarapan dan makan siang khas pedesaan. Menu andalan kami adalah Nasi Campur Spesial dengan lauk ayam betutu, lawar sayur, telur balado, sambal korek pedas mantap, dan kuah kaldu hangat.\n\nSemua bahan baku disiapkan segar setiap hari dari hasil tani masyarakat desa sekitar.\n\nMenerima pesanan nasi kotak (box) untuk acara keluarga, rapat desa, maupun rombongan wisatawan. Silakan pesan minimal H-1.",
                'kategori' => 'Kuliner',
                'alamat' => 'Jalan Trans Sumba Km 12, RT 01 / RW 01, Desa Umbu Mamijuk',
                'no_telepon' => '087890123456',
                'email' => null,
                'website_url' => null,
                'foto' => null,
                'jam_operasional' => '06:00 - 15:00 WITA',
                'status' => 'aktif',
            ],
            [
                'nama_usaha' => 'Bengkel Motor Berkah Jaya',
                'slug' => 'bengkel-motor-berkah-jaya',
                'nama_pemilik' => 'Mas Joko Susilo',
                'deskripsi' => "Bengkel sepeda motor roda dua yang melayani servis rutin, ganti oli, tambal ban, kelistrikan, hingga turun mesin untuk berbagai merk motor (Honda, Yamaha, Suzuki, Kawasaki).\n\nKami juga menyediakan suku cadang (spare parts) orisinal maupun aftermarket berkualitas dengan harga terjangkau.\n\nDitangani oleh mekanik berpengalaman yang siap memberikan pelayanan cepat dan hasil maksimal.",
                'kategori' => 'Jasa',
                'alamat' => 'Pertigaan Dusun Sentosa, RT 03 / RW 02, Desa Umbu Mamijuk',
                'no_telepon' => '081345678912',
                'email' => null,
                'website_url' => null,
                'foto' => null,
                'jam_operasional' => '08:00 - 20:00 WITA',
                'status' => 'aktif',
            ],
            [
                'nama_usaha' => 'Butik Busana Indah',
                'slug' => 'butik-busana-indah',
                'nama_pemilik' => 'Ibu Elisabeth Riri',
                'deskripsi' => "Menjual berbagai pilihan pakaian wanita, pria, dan anak-anak modern. Mulai dari dress santai, kemeja kerja, kaos kasual, jilbab, hingga pakaian formal.\n\nKami juga menawarkan jasa jahit baju/kebaya custom sesuai ukuran dan desain yang Anda inginkan dengan hasil jahitan yang rapi dan pas di badan.",
                'kategori' => 'Fashion',
                'alamat' => 'RT 04 / RW 01, Dusun Karang Indah, Desa Umbu Mamijuk',
                'no_telepon' => '085234567888',
                'email' => 'elisabeth.boutique@gmail.com',
                'website_url' => 'https://shopee.co.id',
                'foto' => null,
                'jam_operasional' => '09:00 - 21:00 WITA',
                'status' => 'aktif',
            ],
        ];

        foreach ($umkms as $data) {
            Umkm::create($data);
        }
    }
}
