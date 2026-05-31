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
        Schema::create('mutasi_penduduk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penduduk_id')->constrained('penduduk')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('jenis_mutasi', ['lahir', 'mati', 'pindah_masuk', 'pindah_keluar']);
            $table->date('tanggal_mutasi');
            $table->string('no_surat')->nullable();
            $table->text('alamat_tujuan')->nullable();
            $table->text('alamat_asal')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('lampiran')->nullable();
            $table->timestamps();

            $table->index(['jenis_mutasi', 'tanggal_mutasi']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_penduduk');
    }
};
