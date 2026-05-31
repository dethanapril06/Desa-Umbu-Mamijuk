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
        Schema::create('penduduk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keluarga_id')->constrained('keluarga')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('nik', 16)->unique();
            $table->string('nama_lengkap');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->enum('agama', ['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'konghucu', 'lainnya'])->nullable();
            $table->enum('pendidikan_terakhir', ['tidak_sekolah', 'sd', 'smp', 'sma', 'd1', 'd2', 'd3', 's1', 's2', 's3'])->nullable();
            $table->string('pekerjaan')->nullable();
            $table->enum('status_perkawinan', ['belum_kawin', 'kawin', 'cerai_hidup', 'cerai_mati'])->nullable();
            $table->enum('status_hubungan_keluarga', ['kepala_keluarga', 'istri', 'anak', 'menantu', 'cucu', 'orang_tua', 'mertua', 'famili_lain', 'lainnya'])->nullable();
            $table->enum('kewarganegaraan', ['WNI', 'WNA'])->default('WNI');
            $table->string('golongan_darah')->nullable();
            $table->string('no_paspor')->nullable();
            $table->string('no_kitas_kitap')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('no_telepon')->nullable();
            $table->boolean('is_asuransi_kesehatan')->default(false);
            $table->boolean('is_disabilitas')->default(false);
            $table->string('jenis_disabilitas')->nullable();
            $table->enum('status', ['aktif', 'pindah', 'meninggal'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'jenis_kelamin']);
            $table->index(['status', 'tanggal_lahir']);
            $table->index('pekerjaan');
            $table->index('pendidikan_terakhir');
            $table->index('agama');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penduduk');
    }
};
