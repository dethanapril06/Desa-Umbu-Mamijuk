<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tambahkan FULLTEXT index pada kolom-kolom teks penduduk
     * agar bisa menggunakan whereFullText() di Laravel.
     * Kolom angka (nik, no_telepon) tetap pakai LIKE.
     */
    public function up(): void
    {
        $existing = collect(DB::select("SHOW INDEX FROM penduduk WHERE Index_type = 'FULLTEXT'"))
            ->pluck('Key_name')->toArray();

        Schema::table('penduduk', function (Blueprint $table) use ($existing) {
            if (!in_array('penduduk_nama_lengkap_fulltext', $existing)) $table->fullText('nama_lengkap');
            if (!in_array('penduduk_tempat_lahir_fulltext', $existing)) $table->fullText('tempat_lahir');
            if (!in_array('penduduk_pekerjaan_fulltext', $existing)) $table->fullText('pekerjaan');
            if (!in_array('penduduk_nama_ayah_fulltext', $existing)) $table->fullText('nama_ayah');
            if (!in_array('penduduk_nama_ibu_fulltext', $existing)) $table->fullText('nama_ibu');
        });
    }

    public function down(): void
    {
        Schema::table('penduduk', function (Blueprint $table) {
            $table->dropFullText('penduduk_nama_lengkap_fulltext');
            $table->dropFullText('penduduk_tempat_lahir_fulltext');
            $table->dropFullText('penduduk_pekerjaan_fulltext');
            $table->dropFullText('penduduk_nama_ayah_fulltext');
            $table->dropFullText('penduduk_nama_ibu_fulltext');
        });
    }
};
