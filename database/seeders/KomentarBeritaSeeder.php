<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KomentarBeritaSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $beritaId = DB::table('berita')
            ->where('slug', 'pemerintah-desa-kami-tingkatkan-pelayanan-publik')
            ->value('id');

        DB::table('komentar_berita')->updateOrInsert(
            [
                'berita_id' => $beritaId,
                'email' => 'warga@example.com',
            ],
            [
                'nama' => 'Warga Desa',
                'komentar' => 'Semoga pelayanan desa semakin baik dan mudah diakses masyarakat.',
                'likes' => 3,
                'is_approved' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}