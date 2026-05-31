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
            ->where('email', 'admin@sukamaju.desa.id')
            ->value('id');

        $kategoriId = DB::table('kategori_wisata')
            ->where('slug', 'wisata-alam')
            ->value('id');

        DB::table('wisata')->updateOrInsert(
            ['slug' => 'bukit-sukamaju'],
            [
                'user_id' => $userId,
                'kategori_wisata_id' => $kategoriId,
                'nama' => 'Bukit Sukamaju',
                'deskripsi_singkat' => 'Destinasi alam dengan panorama perbukitan dan pemandangan matahari terbenam.',
                'deskripsi' => '<p>Bukit Sukamaju menawarkan panorama alam yang indah dan suasana yang tenang. Destinasi ini cocok untuk wisata keluarga, fotografi, dan menikmati matahari terbenam.</p>',
                'highlight_quote' => 'Menikmati panorama desa dari ketinggian dalam suasana yang tenang dan alami.',
                'gambar_utama' => 'images/wisata/bukit-sukamaju.jpg',
                'harga_tiket' => 10000,
                'harga_parkir_motor' => 'Rp2.000',
                'harga_parkir_mobil' => 'Rp5.000',
                'jam_operasional' => '06.00–18.00 WITA',
                'hari_buka' => 'Setiap hari',
                'jarak_dari_desa' => '3 km',
                'durasi_trek' => '20 menit',
                'cocok_untuk' => 'Keluarga, fotografi, pendaki pemula',
                'telepon' => '081234567890',
                'koordinat_lat' => '-10.181000',
                'koordinat_lng' => '123.612000',
                'is_unggulan' => true,
                'is_published' => true,
                'deleted_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}