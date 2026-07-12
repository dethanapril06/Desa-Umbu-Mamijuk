<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MutasiPendudukSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $pendudukId = DB::table('penduduk')
            ->where('nik', '5301010606240010')
            ->value('id');

        DB::table('mutasi_penduduk')->updateOrInsert(
            [
                'penduduk_id' => $pendudukId,
                'jenis_mutasi' => 'pindah_masuk',
                'tanggal_mutasi' => '2024-06-06',
            ],
            [
                'no_surat' => 'SKP-2024-0001',
                'alamat_tujuan' => null,
                'alamat_asal' => 'Jl. Merdeka No. 10, Kota Kupang',
                'keterangan' => 'Pencatatan pindah masuk untuk warga atas nama Gabriel Umbu.',
                'lampiran' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}