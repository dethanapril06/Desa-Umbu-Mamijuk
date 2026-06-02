<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wisata extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'wisata';

    protected $fillable = [
        'user_id',
        'kategori_wisata_id',
        'nama',
        'slug',
        'deskripsi_singkat',
        'deskripsi',
        'highlight_quote',
        'gambar_utama',
        'harga_tiket',
        'harga_parkir_motor',
        'harga_parkir_mobil',
        'jam_operasional',
        'hari_buka',
        'jarak_dari_desa',
        'durasi_trek',
        'cocok_untuk',
        'telepon',
        'koordinat_lat',
        'koordinat_lng',
        'google_maps_embed_url',
        'is_unggulan',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'harga_tiket' => 'decimal:2',
            'is_unggulan' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kategoriWisata(): BelongsTo
    {
        return $this->belongsTo(KategoriWisata::class);
    }

    public function galeriWisata(): HasMany
    {
        return $this->hasMany(GaleriWisata::class);
    }

    public function fasilitasWisata(): HasMany
    {
        return $this->hasMany(FasilitasWisata::class);
    }

    public function tipsWisata(): HasMany
    {
        return $this->hasMany(TipsWisata::class);
    }

    public function ruteWisata(): HasMany
    {
        return $this->hasMany(RuteWisata::class);
    }

    public function ulasanWisata(): HasMany
    {
        return $this->hasMany(UlasanWisata::class);
    }
}
