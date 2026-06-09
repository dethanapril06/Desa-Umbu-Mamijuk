<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            [
                'nama' => 'Tag Lorem',
                'slug' => 'tag-lorem',
            ],
            [
                'nama' => 'Tag Ipsum',
                'slug' => 'tag-ipsum',
            ],
            [
                'nama' => 'Tag Dolor',
                'slug' => 'tag-dolor',
            ],
            [
                'nama' => 'Tag Sit Amet',
                'slug' => 'tag-sit-amet',
            ],
        ];

        foreach ($items as $item) {
            DB::table('tags')->updateOrInsert(
                ['slug' => $item['slug']],
                array_merge($item, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}