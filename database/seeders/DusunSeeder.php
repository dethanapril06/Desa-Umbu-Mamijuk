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
                'nama' => 'Dusun I',
                'kepala_dusun' => 'Yohanes Kefi',
                'urutan' => 1,
            ],
            [
                'nama' => 'Dusun II',
                'kepala_dusun' => 'Samuel Benu',
                'urutan' => 2,
            ],
            [
                'nama' => 'Dusun III',
                'kepala_dusun' => 'Martinus Nalle',
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