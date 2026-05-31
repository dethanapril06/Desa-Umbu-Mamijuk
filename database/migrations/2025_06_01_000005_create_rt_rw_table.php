<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rt_rw', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dusun_id')->constrained('dusun')->cascadeOnDelete();
            $table->string('no_rt');
            $table->string('no_rw');
            $table->string('ketua_rt')->nullable();
            $table->string('ketua_rw')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rt_rw');
    }
};
