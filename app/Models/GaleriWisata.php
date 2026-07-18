<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GaleriWisata extends Model
{
    use HasFactory;

    protected $table = 'galeri_wisata';

    protected $fillable = [
        'wisata_id',
        'gambar',
        'caption',
    ];

    public function wisata(): BelongsTo
    {
        return $this->belongsTo(Wisata::class);
    }
}
