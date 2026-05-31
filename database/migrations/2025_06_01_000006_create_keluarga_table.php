<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keluarga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rt_rw_id')->constrained('rt_rw')->cascadeOnDelete();
            $table->string('no_kk', 16)->unique();
            $table->text('alamat');
            $table->string('kode_pos')->nullable();
            $table->date('tanggal_terdaftar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keluarga');
    }
};
