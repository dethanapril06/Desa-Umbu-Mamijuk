<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduan';

    protected $fillable = [
        'kategori_pengaduan_id',
        'no_tiket',
        'nama_pelapor',
        'nik_pelapor',
        'no_telepon',
        'email',
        'alamat',
        'judul',
        'isi_pengaduan',
        'lampiran',
        'status',
        'is_publik',
    ];

    protected function casts(): array
    {
        return [
            'is_publik' => 'boolean',
        ];
    }

    public function kategoriPengaduan(): BelongsTo
    {
        return $this->belongsTo(KategoriPengaduan::class);
    }

    public function tanggapanPengaduan(): HasMany
    {
        return $this->hasMany(TanggapanPengaduan::class);
    }
}
