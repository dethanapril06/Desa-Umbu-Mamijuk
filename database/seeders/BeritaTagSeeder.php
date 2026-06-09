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
                'berita' => 'lorem-ipsum-dolor-sit-amet-consectetur',
                'tag' => 'tag-lorem',
            ],
            [
                'berita' => 'lorem-ipsum-dolor-sit-amet-consectetur',
                'tag' => 'tag-ipsum',
            ],
            [
                'berita' => 'sed-do-eiusmod-tempor-incididunt-ut-labore',
                'tag' => 'tag-dolor',
            ],
            [
                'berita' => 'duis-aute-irure-dolor-in-reprehenderit',
                'tag' => 'tag-sit-amet',
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