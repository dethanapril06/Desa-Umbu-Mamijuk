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
            ->where('slug', 'pemdes-umbu-mamijuk-tingkatkan-pelayanan-publik-digital-lewat-sid')
            ->value('id');

        DB::table('komentar_berita')->updateOrInsert(
            [
                'berita_id' => $beritaId,
                'email' => 'martinus.umbu@gmail.com',
            ],
            [
                'nama' => 'Martinus Umbu',
                'komentar' => 'Langkah yang sangat baik dari Pemdes! Dengan adanya sistem informasi desa ini, kami warga luar daerah juga bisa dengan mudah memantau perkembangan pembangunan dan berita terkini di kampung halaman.',
                'likes' => 3,
                'is_approved' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}