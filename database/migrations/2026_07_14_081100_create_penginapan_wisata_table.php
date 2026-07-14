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
        Schema::create('penginapan_wisata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wisata_id')->constrained('wisata')->onDelete('cascade');
            $table->string('nama_penginapan');
            $table->string('jenis', 100)->nullable(); // e.g. Homestay, Villa, Hotel, Guesthouse, Camping Ground
            $table->string('kisaran_harga', 100)->nullable(); // e.g. Rp 150.000 - Rp 300.000 / malam
            $table->string('jarak', 100)->nullable(); // e.g. 200 meter dari wisata
            $table->string('no_telepon', 50)->nullable(); // Nomor WhatsApp reservasi
            $table->string('fasilitas_singkat')->nullable(); // e.g. AC, Wi-Fi, Sarapan Pagi
            $table->string('foto')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penginapan_wisata');
    }
};
