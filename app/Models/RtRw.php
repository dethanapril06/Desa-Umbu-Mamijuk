<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RtRw extends Model
{
    use HasFactory;

    protected $table = 'rt_rw';

    protected $fillable = [
        'dusun_id',
        'no_rt',
        'no_rw',
        'ketua_rt',
        'ketua_rw',
    ];

    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class);
    }

    public function keluarga(): HasMany
    {
        return $this->hasMany(Keluarga::class);
    }
}
