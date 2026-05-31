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
                'dusun' => 'Dusun I',
                'no_rt' => '001',
                'no_rw' => '001',
                'ketua_rt' => 'Anton Lake',
                'ketua_rw' => 'Yohanes Kefi',
            ],
            [
                'dusun' => 'Dusun I',
                'no_rt' => '002',
                'no_rw' => '001',
                'ketua_rt' => 'Paulus Tallo',
                'ketua_rw' => 'Yohanes Kefi',
            ],
            [
                'dusun' => 'Dusun II',
                'no_rt' => '003',
                'no_rw' => '002',
                'ketua_rt' => 'Markus Nope',
                'ketua_rw' => 'Samuel Benu',
            ],
            [
                'dusun' => 'Dusun II',
                'no_rt' => '004',
                'no_rw' => '002',
                'ketua_rt' => 'Petrus Kase',
                'ketua_rw' => 'Samuel Benu',
            ],
            [
                'dusun' => 'Dusun III',
                'no_rt' => '005',
                'no_rw' => '003',
                'ketua_rt' => 'Agustinus Foes',
                'ketua_rw' => 'Martinus Nalle',
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