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
        Schema::create('umkm', function (Blueprint $table) {
            $table->id();
            $table->string('nama_usaha');
            $table->string('slug')->unique();
            $table->string('nama_pemilik');
            $table->text('deskripsi');
            $table->string('kategori');
            $table->text('alamat');
            $table->string('no_telepon', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('website_url')->nullable();
            $table->string('foto')->nullable();
            $table->string('jam_operasional', 150)->nullable();
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umkm');
    }
};
