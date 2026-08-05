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
        Schema::create('lembaga_desa', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lembaga');
            $table->string('singkatan')->nullable();
            $table->string('slug')->unique();
            $table->string('ketua')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('alamat_sekretariat')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lembaga_desa');
    }
};
