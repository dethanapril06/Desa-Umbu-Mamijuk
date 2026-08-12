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
        if (Schema::hasTable('lembaga_desa')) {
            Schema::table('lembaga_desa', function (Blueprint $table) {
                // Drop old columns if they exist
                $oldColumns = ['nama_lembaga', 'singkatan', 'slug', 'ketua', 'no_telepon', 'alamat_sekretariat', 'deskripsi', 'logo'];
                foreach ($oldColumns as $col) {
                    if (Schema::hasColumn('lembaga_desa', $col)) {
                        $table->dropColumn($col);
                    }
                }

                // Add new columns matching perangkat_desa if they don't exist
                if (!Schema::hasColumn('lembaga_desa', 'nama')) {
                    $table->string('nama')->after('id');
                }
                if (!Schema::hasColumn('lembaga_desa', 'jabatan')) {
                    $table->string('jabatan')->after('nama');
                }
                if (!Schema::hasColumn('lembaga_desa', 'foto')) {
                    $table->string('foto')->nullable()->after('jabatan');
                }
                if (!Schema::hasColumn('lembaga_desa', 'nip')) {
                    $table->string('nip')->nullable()->after('foto');
                }
                if (!Schema::hasColumn('lembaga_desa', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('nip');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('lembaga_desa')) {
            Schema::table('lembaga_desa', function (Blueprint $table) {
                if (Schema::hasColumn('lembaga_desa', 'nama')) {
                    $table->dropColumn(['nama', 'jabatan', 'foto', 'nip']);
                }

                $table->string('nama_lembaga')->after('id');
                $table->string('singkatan')->nullable()->after('nama_lembaga');
                $table->string('slug')->unique()->after('singkatan');
                $table->string('ketua')->nullable()->after('slug');
                $table->string('no_telepon')->nullable()->after('ketua');
                $table->string('alamat_sekretariat')->nullable()->after('no_telepon');
                $table->text('deskripsi')->nullable()->after('alamat_sekretariat');
                $table->string('logo')->nullable()->after('deskripsi');
            });
        }
    }
};
