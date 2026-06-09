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
            ->where('slug', 'lorem-ipsum-dolor-sit-amet-consectetur')
            ->value('id');

        DB::table('komentar_berita')->updateOrInsert(
            [
                'berita_id' => $beritaId,
                'email' => 'user@example.com',
            ],
            [
                'nama' => 'Lorem Ipsum',
                'komentar' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt.',
                'likes' => 3,
                'is_approved' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}