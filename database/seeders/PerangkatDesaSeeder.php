<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerangkatDesaSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            [
                'nama' => 'Lorem Ipsum A',
                'jabatan' => 'Jabatan XXXXX',
                'foto' => 'images/perangkat-desa/perangkat-1.jpg',
                'nip' => null,
                'urutan' => 1,
            ],
            [
                'nama' => 'Lorem Ipsum B',
                'jabatan' => 'Jabatan YYYYY',
                'foto' => 'images/perangkat-desa/perangkat-2.jpg',
                'nip' => null,
                'urutan' => 2,
            ],
            [
                'nama' => 'Lorem Ipsum C',
                'jabatan' => 'Jabatan ZZZZZ',
                'foto' => 'images/perangkat-desa/perangkat-3.jpg',
                'nip' => null,
                'urutan' => 3,
            ],
            [
                'nama' => 'Lorem Ipsum D',
                'jabatan' => 'Jabatan WWWWW',
                'foto' => 'images/perangkat-desa/perangkat-4.jpg',
                'nip' => null,
                'urutan' => 4,
            ],
            [
                'nama' => 'Lorem Ipsum E',
                'jabatan' => 'Jabatan VVVVV',
                'foto' => 'images/perangkat-desa/perangkat-5.jpg',
                'nip' => null,
                'urutan' => 5,
            ],
        ];

        foreach ($items as $item) {
            DB::table('perangkat_desa')->updateOrInsert(
                [
                    'nama' => $item['nama'],
                    'jabatan' => $item['jabatan'],
                ],
                array_merge($item, [
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}