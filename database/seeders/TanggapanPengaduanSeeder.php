<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TanggapanPengaduanSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $pengaduanId = DB::table('pengaduan')
            ->where('no_tiket', 'ADU-2026-0001')
            ->value('id');

        $userId = DB::table('users')
            ->where('email', 'admin@umbumamijuk.desa.id')
            ->value('id');

        DB::table('tanggapan_pengaduan')->updateOrInsert(
            [
                'pengaduan_id' => $pengaduanId,
                'user_id' => $userId,
            ],
            [
                'isi_tanggapan' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'lampiran' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}