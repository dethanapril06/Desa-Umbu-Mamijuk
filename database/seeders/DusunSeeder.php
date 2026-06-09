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
                'kepala_dusun' => 'Lorem Ipsum',
                'urutan' => 1,
            ],
            [
                'nama' => 'Dusun II',
                'kepala_dusun' => 'Dolor Sit Amet',
                'urutan' => 2,
            ],
            [
                'nama' => 'Dusun III',
                'kepala_dusun' => 'Consectetur Adipiscing',
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