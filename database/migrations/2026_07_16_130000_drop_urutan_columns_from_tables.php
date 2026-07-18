<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'dusun',
            'perangkat_desa',
            'slider',
            'sosial_media',
            'galeri',
            'galeri_wisata',
            'tips_wisata',
            'rute_wisata',
            'penginapan_wisata'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'urutan')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('urutan');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'dusun',
            'perangkat_desa',
            'slider',
            'sosial_media',
            'galeri',
            'galeri_wisata',
            'tips_wisata',
            'rute_wisata',
            'penginapan_wisata'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'urutan')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->integer('urutan')->default(0);
                });
            }
        }
    }
};
