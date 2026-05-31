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
        Schema::create('rute_wisata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wisata_id')->constrained('wisata')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('jenis_transportasi');
            $table->string('icon')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('warna_badge')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rute_wisata');
    }
};
