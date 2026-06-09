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
                'ketua_rt' => 'Lorem Ipsum A',
                'ketua_rw' => 'Lorem Ipsum',
            ],
            [
                'dusun' => 'Dusun I',
                'no_rt' => '002',
                'no_rw' => '001',
                'ketua_rt' => 'Lorem Ipsum B',
                'ketua_rw' => 'Lorem Ipsum',
            ],
            [
                'dusun' => 'Dusun II',
                'no_rt' => '003',
                'no_rw' => '002',
                'ketua_rt' => 'Dolor Sit A',
                'ketua_rw' => 'Dolor Sit Amet',
            ],
            [
                'dusun' => 'Dusun II',
                'no_rt' => '004',
                'no_rw' => '002',
                'ketua_rt' => 'Dolor Sit B',
                'ketua_rw' => 'Dolor Sit Amet',
            ],
            [
                'dusun' => 'Dusun III',
                'no_rt' => '005',
                'no_rw' => '003',
                'ketua_rt' => 'Consectetur A',
                'ketua_rw' => 'Consectetur Adipiscing',
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