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
                'url' => 'https://facebook.com/desaudb.umbumamijuk',
                'icon' => 'bi bi-facebook',
            ],
            [
                'platform' => 'Instagram',
                'url' => 'https://instagram.com/desa.umbumamijuk',
                'icon' => 'bi bi-instagram',
            ],
            [
                'platform' => 'YouTube',
                'url' => 'https://youtube.com/@desaudb.umbumamijuk',
                'icon' => 'bi bi-youtube',
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