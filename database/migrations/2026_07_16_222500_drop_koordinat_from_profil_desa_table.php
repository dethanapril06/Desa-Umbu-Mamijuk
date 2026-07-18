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
        Schema::table('profil_desa', function (Blueprint $table) {
            if (Schema::hasColumn('profil_desa', 'koordinat_lat')) {
                $table->dropColumn('koordinat_lat');
            }
            if (Schema::hasColumn('profil_desa', 'koordinat_lng')) {
                $table->dropColumn('koordinat_lng');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_desa', function (Blueprint $table) {
            if (!Schema::hasColumn('profil_desa', 'koordinat_lat')) {
                $table->string('koordinat_lat')->nullable();
            }
            if (!Schema::hasColumn('profil_desa', 'koordinat_lng')) {
                $table->string('koordinat_lng')->nullable();
            }
        });
    }
};
