<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SosialMediaSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            [
                'platform' => 'Facebook',
                'url' => 'https://facebook.com/desakami',
                'icon' => 'bi bi-facebook',
                'urutan' => 1,
            ],
            [
                'platform' => 'Instagram',
                'url' => 'https://instagram.com/desakami',
                'icon' => 'bi bi-instagram',
                'urutan' => 2,
            ],
            [
                'platform' => 'YouTube',
                'url' => 'https://youtube.com/@desakami',
                'icon' => 'bi bi-youtube',
                'urutan' => 3,
            ],
        ];

        foreach ($items as $item) {
            DB::table('sosial_media')->updateOrInsert(
                ['platform' => $item['platform']],
                array_merge($item, [
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}