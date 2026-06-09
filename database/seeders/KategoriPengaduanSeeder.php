<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriPengaduanSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            [
                'nama' => 'Kategori XXXXX',
                'slug' => 'infrastruktur',
                'icon' => 'bi bi-tools',
            ],
            [
                'nama' => 'Kategori YYYYY',
                'slug' => 'pelayanan-publik',
                'icon' => 'bi bi-person-check',
            ],
            [
                'nama' => 'Kategori ZZZZZ',
                'slug' => 'kebersihan',
                'icon' => 'bi bi-trash',
            ],
            [
                'nama' => 'Kategori WWWWW',
                'slug' => 'keamanan',
                'icon' => 'bi bi-shield-check',
            ],
            [
                'nama' => 'Kategori VVVVV',
                'slug' => 'lainnya',
                'icon' => 'bi bi-chat-dots',
            ],
        ];

        foreach ($items as $item) {
            DB::table('kategori_pengaduan')->updateOrInsert(
                ['slug' => $item['slug']],
                array_merge($item, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}