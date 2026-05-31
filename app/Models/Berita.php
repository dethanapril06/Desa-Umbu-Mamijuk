<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Berita extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'berita';

    protected $fillable = [
        'user_id',
        'kategori_berita_id',
        'judul',
        'slug',
        'excerpt',
        'konten',
        'gambar',
        'caption_gambar',
        'views',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'views' => 'integer',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kategoriBerita(): BelongsTo
    {
        return $this->belongsTo(KategoriBerita::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'berita_tag')
            ->using(BeritaTag::class);
    }

    public function komentarBerita(): HasMany
    {
        return $this->hasMany(KomentarBerita::class);
    }
}
