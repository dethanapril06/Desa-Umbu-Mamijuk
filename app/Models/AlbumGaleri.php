<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlbumGaleri extends Model
{
    use HasFactory;

    protected $table = 'album_galeri';

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'cover',
    ];

    public function galeri(): HasMany
    {
        return $this->hasMany(Galeri::class);
    }
}
