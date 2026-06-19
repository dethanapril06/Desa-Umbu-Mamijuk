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
                'urutan' => 1,
            ],
            [
                'nama' => 'Dusun Watu Langit',
                'kepala_dusun' => 'Martinus Londa',
                'urutan' => 2,
            ],
            [
                'nama' => 'Dusun Sentosa',
                'kepala_dusun' => 'Joko Susilo',
                'urutan' => 3,
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