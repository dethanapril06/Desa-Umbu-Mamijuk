<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipsWisataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $wisataId = DB::table('wisata')
            ->where('slug', 'kampung-adat-pasunga')
            ->value('id');

        $items = [
            [
                'judul' => 'Hormati Adat dan Sopan Santun',
                'deskripsi' => 'Sebelum mengambil foto warga lokal, rumah adat, atau kubur batu megalitikum, sebaiknya mintalah izin terlebih dahulu kepada pemandu lokal atau tetua adat sebagai bentuk penghormatan.',
            ],
            [
                'judul' => 'Gunakan Pakaian yang Sopan',
                'deskripsi' => 'Mengingat Kampung Adat Pasunga merupakan situs sakral tempat bersemayamnya arwah leluhur Marapu, pengunjung diimbau mengenakan pakaian yang sopan dan tertutup.',
            ],
        ];

        foreach ($items as $item) {
            DB::table('tips_wisata')->updateOrInsert(
                [
                    'wisata_id' => $wisataId,
                    'judul' => $item['judul'],
                ],
                array_merge($item, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}
