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
                'nama' => 'Yohanis Kula',
                'jabatan' => 'Sekretaris Desa',
                'foto' => 'images/perangkat-desa/perangkat-1.jpg',
                'nip' => '199005122020121002',
            ],
            [
                'nama' => 'Maria Kula',
                'jabatan' => 'Kepala Urusan Keuangan',
                'foto' => 'images/perangkat-desa/perangkat-2.jpg',
                'nip' => null,
            ],
            [
                'nama' => 'Martinus Londa',
                'jabatan' => 'Kepala Seksi Pemerintahan',
                'foto' => 'images/perangkat-desa/perangkat-3.jpg',
                'nip' => null,
            ],
            [
                'nama' => 'Elisabeth Riri',
                'jabatan' => 'Kepala Seksi Kesejahteraan & Pelayanan',
                'foto' => 'images/perangkat-desa/perangkat-4.jpg',
                'nip' => null,
            ],
            [
                'nama' => 'Joko Susilo',
                'jabatan' => 'Kepala Urusan Umum & Perencanaan',
                'foto' => 'images/perangkat-desa/perangkat-5.jpg',
                'nip' => null,
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