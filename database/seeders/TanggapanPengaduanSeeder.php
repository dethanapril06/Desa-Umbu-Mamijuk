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
                'isi_tanggapan' => 'Terima kasih atas laporan Bapak Yohanis Kula. Pengaduan Anda telah kami terima dan diteruskan ke Kepala Urusan Pembangunan dan Kesejahteraan Desa. Pihak Pemdes telah berkoordinasi dengan petugas PLN/teknisi kelistrikan desa untuk melakukan pengecekan dan penggantian bohlam lampu jalan yang mati dalam waktu 2x24 jam. Harap tetap berhati-hati saat berkendara di malam hari.',
                'lampiran' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}