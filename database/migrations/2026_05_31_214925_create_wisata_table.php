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
        Schema::create('wisata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('kategori_wisata_id')->constrained('kategori_wisata')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->text('deskripsi_singkat')->nullable();
            $table->longText('deskripsi')->nullable();
            $table->text('highlight_quote')->nullable();
            $table->string('gambar_utama')->nullable();
            $table->decimal('harga_tiket', 8, 2)->default(0);
            $table->string('harga_parkir_motor')->nullable();
            $table->string('harga_parkir_mobil')->nullable();
            $table->string('jam_operasional')->nullable();
            $table->string('hari_buka')->nullable();
            $table->string('jarak_dari_desa')->nullable();
            $table->string('durasi_trek')->nullable();
            $table->string('cocok_untuk')->nullable();
            $table->string('telepon')->nullable();
            $table->string('koordinat_lat')->nullable();
            $table->string('koordinat_lng')->nullable();
            $table->boolean('is_unggulan')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_published', 'is_unggulan']);
            $table->index('kategori_wisata_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wisata');
    }
};
