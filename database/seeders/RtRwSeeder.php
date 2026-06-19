<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RtRwSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            [
                'dusun' => 'Dusun Karang Indah',
                'no_rt' => '001',
                'no_rw' => '001',
                'ketua_rt' => 'Andreas Ndara',
                'ketua_rw' => 'Fransiscus Umbu',
            ],
            [
                'dusun' => 'Dusun Karang Indah',
                'no_rt' => '002',
                'no_rw' => '001',
                'ketua_rt' => 'Petrus Gara',
                'ketua_rw' => 'Fransiscus Umbu',
            ],
            [
                'dusun' => 'Dusun Watu Langit',
                'no_rt' => '003',
                'no_rw' => '002',
                'ketua_rt' => 'Elisabeth Riri',
                'ketua_rw' => 'Albertus Londa',
            ],
            [
                'dusun' => 'Dusun Watu Langit',
                'no_rt' => '004',
                'no_rw' => '002',
                'ketua_rt' => 'Maria Ndala',
                'ketua_rw' => 'Albertus Londa',
            ],
            [
                'dusun' => 'Dusun Sentosa',
                'no_rt' => '005',
                'no_rw' => '003',
                'ketua_rt' => 'Yohanis Kula',
                'ketua_rw' => 'Yoseph Susilo',
            ],
        ];

        foreach ($items as $item) {
            $dusunId = DB::table('dusun')
                ->where('nama', $item['dusun'])
                ->value('id');

            DB::table('rt_rw')->updateOrInsert(
                [
                    'dusun_id' => $dusunId,
                    'no_rt' => $item['no_rt'],
                    'no_rw' => $item['no_rw'],
                ],
                [
                    'ketua_rt' => $item['ketua_rt'],
                    'ketua_rw' => $item['ketua_rw'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}