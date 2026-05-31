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
                'nama' => 'Maria Lestari',
                'jabatan' => 'Sekretaris Desa',
                'foto' => 'images/perangkat-desa/maria-lestari.jpg',
                'nip' => null,
                'urutan' => 1,
            ],
            [
                'nama' => 'Andi Pratama',
                'jabatan' => 'Kaur Keuangan',
                'foto' => 'images/perangkat-desa/andi-pratama.jpg',
                'nip' => null,
                'urutan' => 2,
            ],
            [
                'nama' => 'Yuliana Ningsih',
                'jabatan' => 'Kaur Umum dan Perencanaan',
                'foto' => 'images/perangkat-desa/yuliana-ningsih.jpg',
                'nip' => null,
                'urutan' => 3,
            ],
            [
                'nama' => 'Rudi Hartono',
                'jabatan' => 'Kasi Pemerintahan',
                'foto' => 'images/perangkat-desa/rudi-hartono.jpg',
                'nip' => null,
                'urutan' => 4,
            ],
            [
                'nama' => 'Dewi Anggraini',
                'jabatan' => 'Kasi Pelayanan',
                'foto' => 'images/perangkat-desa/dewi-anggraini.jpg',
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