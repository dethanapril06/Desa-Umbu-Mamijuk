<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UlasanWisataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $wisataId = DB::table('wisata')
            ->where('slug', 'kampung-adat-pasunga')
            ->value('id');

        DB::table('ulasan_wisata')->updateOrInsert(
            [
                'wisata_id' => $wisataId,
                'nama' => 'Budi S.',
            ],
            [
                'avatar' => null,
                'rating' => 5,
                'ulasan' => 'Pengalaman luar biasa berkunjung ke Kampung Adat Pasunga. Kubur batu megalitikumnya sangat megah dan sejarahnya sangat mendalam. Warga setempat juga sangat ramah dalam menjelaskan makna dari setiap motif ukiran tenun dan makam leluhur.',
                'is_approved' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}
