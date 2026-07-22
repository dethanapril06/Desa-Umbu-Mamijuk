<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DusunSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            [
                'nama' => 'Dusun Karang Indah',
                'kepala_dusun' => 'Yohanes Gede',
            ],
            [
                'nama' => 'Dusun Watu Langit',
                'kepala_dusun' => 'Martinus Londa',
            ],
            [
                'nama' => 'Dusun Sentosa',
                'kepala_dusun' => 'Joko Susilo',
            ],
        ];

        foreach ($items as $item) {
            DB::table('dusun')->updateOrInsert(
                ['nama' => $item['nama']],
                array_merge($item, [
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}