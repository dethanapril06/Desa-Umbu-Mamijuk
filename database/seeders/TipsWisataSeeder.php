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
                'judul' => 'Lorem ipsum dolor',
                'deskripsi' => 'Lorem inpsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'urutan' => 1,
            ],
            [
                'judul' => 'Dolor sit amet',
                'deskripsi' => 'Lorem inpsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'urutan' => 2,
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
