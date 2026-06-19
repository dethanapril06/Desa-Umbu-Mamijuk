<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BeritaTagSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'berita' => 'pemdes-umbu-mamijuk-tingkatkan-pelayanan-publik-digital-lewat-sid',
                'tag' => 'kegiatan-desa',
            ],
            [
                'berita' => 'pemdes-umbu-mamijuk-tingkatkan-pelayanan-publik-digital-lewat-sid',
                'tag' => 'pengumuman',
            ],
            [
                'berita' => 'pembangunan-jalan-rabat-beton-dusun-karang-indah-selesai-100-persen',
                'tag' => 'infrastruktur',
            ],
            [
                'berita' => 'masyarakat-desa-gotong-royong-bersihkan-saluran-irigasi-jelang-musim-tanam',
                'tag' => 'budaya-sumba',
            ],
        ];

        foreach ($items as $item) {
            DB::table('berita_tag')->insertOrIgnore([
                'berita_id' => DB::table('berita')
                    ->where('slug', $item['berita'])
                    ->value('id'),

                'tag_id' => DB::table('tags')
                    ->where('slug', $item['tag'])
                    ->value('id'),
            ]);
        }
    }
}