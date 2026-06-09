<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeluargaSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            [
                'no_kk' => '5301010101010001',
                'no_rt' => '001',
                'no_rw' => '001',
                'alamat' => 'Dusun I RT 001/RW 001',
                'kode_pos' => '00000',
            ],
            [
                'no_kk' => '5301010101010002',
                'no_rt' => '002',
                'no_rw' => '001',
                'alamat' => 'Dusun I RT 002/RW 001',
                'kode_pos' => '00000',
            ],
            [
                'no_kk' => '5301010101010003',
                'no_rt' => '003',
                'no_rw' => '002',
                'alamat' => 'Dusun II RT 003/RW 002',
                'kode_pos' => '00000',
            ],
            [
                'no_kk' => '5301010101010004',
                'no_rt' => '004',
                'no_rw' => '002',
                'alamat' => 'Dusun II RT 004/RW 002',
                'kode_pos' => '00000',
            ],
            [
                'no_kk' => '5301010101010005',
                'no_rt' => '005',
                'no_rw' => '003',
                'alamat' => 'Dusun III RT 005/RW 003',
                'kode_pos' => '00000',
            ],
        ];

        foreach ($items as $item) {
            $rtRwId = DB::table('rt_rw')
                ->where('no_rt', $item['no_rt'])
                ->where('no_rw', $item['no_rw'])
                ->value('id');

            DB::table('keluarga')->updateOrInsert(
                ['no_kk' => $item['no_kk']],
                [
                    'rt_rw_id' => $rtRwId,
                    'alamat' => $item['alamat'],
                    'kode_pos' => $item['kode_pos'],
                    'tanggal_terdaftar' => '2024-01-10 08:00:00',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}