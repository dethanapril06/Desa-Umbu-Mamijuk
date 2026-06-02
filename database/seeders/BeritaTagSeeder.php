<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BeritaTagSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'berita' => 'pemerintah-desa-kami-tingkatkan-pelayanan-publik',
                'tag' => 'pelayanan-publik',
            ],
            [
                'berita' => 'pemerintah-desa-kami-tingkatkan-pelayanan-publik',
                'tag' => 'informasi-desa',
            ],
            [
                'berita' => 'pembangunan-jalan-lingkungan-dusun-ii',
                'tag' => 'pembangunan-desa',
            ],
            [
                'berita' => 'kerja-bakti-bersama-menjaga-kebersihan-lingkungan-desa',
                'tag' => 'gotong-royong',
            ],
        ];

        foreach ($items as $item) {
            DB::table('berita_tag')->insertOrIgnore([
                'berita_id' => DB::table('berita')
                    ->where('slug', $item['berita'])
                    ->value('id'),

                'tag_id' => DB::table('tags')
                    ->where('slug', $item['tag'])
                    ->value('id'),
            ]);
        }
    }
}