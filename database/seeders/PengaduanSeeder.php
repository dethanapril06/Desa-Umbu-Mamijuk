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
                'nama_pelapor' => 'Lorem Ipsum',
                'nik_pelapor' => '5301010101800001',
                'no_telepon' => '080000000001',
                'email' => null,
                'alamat' => 'Dusun I RT 001/RW 001',
                'judul' => 'Lorem ipsum dolor sit amet',
                'isi_pengaduan' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'lampiran' => null,
                'status' => 'diproses',
                'is_publik' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}