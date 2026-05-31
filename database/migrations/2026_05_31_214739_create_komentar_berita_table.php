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
        Schema::create('komentar_berita', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berita_id')->constrained('berita')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->text('komentar');
            $table->unsignedInteger('likes')->default(0);
            $table->boolean('is_approved')->default(false);
            $table->timestamps();

            $table->index(['berita_id', 'is_approved']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komentar_berita');
    }
};
