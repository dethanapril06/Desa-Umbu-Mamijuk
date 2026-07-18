<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RuteWisata extends Model
{
    use HasFactory;

    protected $table = 'rute_wisata';

    protected $fillable = [
        'wisata_id',
        'jenis_transportasi',
        'icon',
        'deskripsi',
        'warna_badge',
    ];

    public function wisata(): BelongsTo
    {
        return $this->belongsTo(Wisata::class);
    }
}
