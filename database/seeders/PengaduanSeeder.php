<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengaduanSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $kategoriId = DB::table('kategori_pengaduan')
            ->where('slug', 'infrastruktur')
            ->value('id');

        DB::table('pengaduan')->updateOrInsert(
            ['no_tiket' => 'ADU-2026-0001'],
            [
                'kategori_pengaduan_id' => $kategoriId,
                'nama_pelapor' => 'Yohanes Manafe',
                'nik_pelapor' => '5301010101800001',
                'no_telepon' => '081200000001',
                'email' => null,
                'alamat' => 'Dusun I RT 001/RW 001',
                'judul' => 'Perbaikan lampu jalan lingkungan',
                'isi_pengaduan' => 'Mohon dilakukan pemeriksaan dan perbaikan lampu jalan pada akses menuju permukiman warga karena sudah tidak menyala.',
                'lampiran' => null,
                'status' => 'diproses',
                'is_publik' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}