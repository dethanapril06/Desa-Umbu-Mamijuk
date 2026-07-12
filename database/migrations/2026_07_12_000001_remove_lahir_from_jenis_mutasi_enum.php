<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Delete any existing 'lahir' mutations if present
        DB::table('mutasi_penduduk')->where('jenis_mutasi', 'lahir')->delete();

        // Alter enum column to remove 'lahir'
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE mutasi_penduduk MODIFY COLUMN jenis_mutasi ENUM('mati', 'pindah_masuk', 'pindah_keluar') NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE mutasi_penduduk MODIFY COLUMN jenis_mutasi ENUM('lahir', 'mati', 'pindah_masuk', 'pindah_keluar') NOT NULL");
        }
    }
};
