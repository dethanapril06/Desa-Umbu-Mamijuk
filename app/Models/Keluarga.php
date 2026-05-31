<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Keluarga extends Model
{
    use HasFactory;

    protected $table = 'keluarga';

    protected $fillable = [
        'rt_rw_id',
        'no_kk',
        'alamat',
        'kode_pos',
        'tanggal_terdaftar',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_terdaftar' => 'datetime',
        ];
    }

    public function rtRw(): BelongsTo
    {
        return $this->belongsTo(RtRw::class);
    }

    public function penduduk(): HasMany
    {
        return $this->hasMany(Penduduk::class);
    }
}
