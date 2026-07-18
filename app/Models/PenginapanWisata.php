<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenginapanWisata extends Model
{
    use HasFactory;

    protected $table = 'penginapan_wisata';

    protected $fillable = [
        'wisata_id',
        'nama_penginapan',
        'jenis',
        'kisaran_harga',
        'jarak',
        'no_telepon',
        'fasilitas_singkat',
        'foto',
    ];

    public function wisata(): BelongsTo
    {
        return $this->belongsTo(Wisata::class);
    }

    public function getKisaranHargaAttribute($value)
    {
        return Penginapan::formatHarga($value);
    }

    public function setKisaranHargaAttribute($value)
    {
        $this->attributes['kisaran_harga'] = Penginapan::formatHarga($value);
    }
}
