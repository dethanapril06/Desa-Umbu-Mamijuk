<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KomentarBerita extends Model
{
    use HasFactory;

    protected $table = 'komentar_berita';

    protected $fillable = [
        'berita_id',
        'nama',
        'email',
        'komentar',
        'likes',
        'is_approved',
    ];

    protected function casts(): array
    {
        return [
            'likes' => 'integer',
            'is_approved' => 'boolean',
        ];
    }

    public function berita(): BelongsTo
    {
        return $this->belongsTo(Berita::class);
    }
}
