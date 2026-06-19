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
                'nama_pelapor' => 'Yohanis Kula',
                'nik_pelapor' => '5301010101800001',
                'no_telepon' => '081234778899',
                'email' => null,
                'alamat' => 'Dusun Watu Langit RT 05/RW 02',
                'judul' => 'Lampu Penerangan Jalan Umum (PJU) Padam di Dusun Watu Langit',
                'isi_pengaduan' => 'Mohon perhatian dari pihak Pemerintah Desa Umbu Mamijuk, sudah sekitar satu minggu terakhir ini ada tiga titik lampu jalan utama di wilayah RT 05 / RW 02 Dusun Watu Langit yang padam. Kondisi jalan di malam hari menjadi sangat gelap dan rawan kecelakaan bagi pengendara motor yang lewat. Mohon segera dilakukan perbaikan atau penggantian lampu. Terima kasih.',
                'lampiran' => null,
                'status' => 'diproses',
                'is_publik' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}