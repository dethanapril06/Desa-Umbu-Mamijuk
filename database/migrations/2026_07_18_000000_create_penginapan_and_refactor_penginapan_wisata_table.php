<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Buat tabel penginapan mandiri
        Schema::create('penginapan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_penginapan');
            $table->string('jenis', 100)->nullable(); // e.g. Homestay, Villa, Hotel, Guesthouse, Camping Ground
            $table->string('kisaran_harga', 100)->nullable(); // e.g. Rp 150.000 - Rp 300.000 / malam
            $table->string('jarak', 100)->nullable(); // e.g. 200 meter dari wisata / pusat desa
            $table->string('no_telepon', 50)->nullable(); // Nomor WhatsApp reservasi
            $table->string('fasilitas_singkat')->nullable(); // e.g. AC, Wi-Fi, Sarapan Pagi
            $table->string('foto')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        // 2. Ambil data lama dari tabel penginapan_wisata jika ada
        $oldRecords = [];
        if (Schema::hasTable('penginapan_wisata')) {
            $oldRecords = DB::table('penginapan_wisata')->get();
        }

        // 3. Simpan relasi lama dan masukkan ke tabel penginapan baru
        $pivotData = [];
        foreach ($oldRecords as $row) {
            $newId = DB::table('penginapan')->insertGetId([
                'nama_penginapan' => $row->nama_penginapan,
                'jenis' => $row->jenis ?? null,
                'kisaran_harga' => $row->kisaran_harga ?? null,
                'jarak' => $row->jarak ?? null,
                'no_telepon' => $row->no_telepon ?? null,
                'fasilitas_singkat' => $row->fasilitas_singkat ?? null,
                'foto' => $row->foto ?? null,
                'is_published' => true,
                'created_at' => $row->created_at ?? now(),
                'updated_at' => $row->updated_at ?? now(),
            ]);

            if (!empty($row->wisata_id)) {
                $pivotData[] = [
                    'wisata_id' => $row->wisata_id,
                    'penginapan_id' => $newId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // 4. Hapus tabel penginapan_wisata lama
        Schema::dropIfExists('penginapan_wisata');

        // 5. Buat kembali tabel penginapan_wisata sebagai tabel pivot Many-to-Many
        Schema::create('penginapan_wisata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wisata_id')->constrained('wisata')->onDelete('cascade');
            $table->foreignId('penginapan_id')->constrained('penginapan')->onDelete('cascade');
            $table->timestamps();
        });

        // 6. Masukkan kembali relasi pivot
        if (!empty($pivotData)) {
            DB::table('penginapan_wisata')->insert($pivotData);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penginapan_wisata');
        Schema::dropIfExists('penginapan');
    }
};
