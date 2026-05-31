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
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_pengaduan_id')->constrained('kategori_pengaduan')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('no_tiket')->unique();
            $table->string('nama_pelapor');
            $table->string('nik_pelapor', 16)->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->string('judul');
            $table->text('isi_pengaduan');
            $table->string('lampiran')->nullable();
            $table->enum('status', ['masuk', 'diproses', 'selesai', 'ditolak'])->default('masuk');
            $table->boolean('is_publik')->default(false);
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('kategori_pengaduan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};
