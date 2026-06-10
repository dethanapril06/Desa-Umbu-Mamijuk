<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function kepalaKeluarga(): HasOne
    {
        return $this->hasOne(Penduduk::class)->where('status_hubungan_keluarga', 'kepala_keluarga');
    }

    public function istri(): HasOne
    {
        return $this->hasOne(Penduduk::class)->where('status_hubungan_keluarga', 'istri');
    }

    public function getNamaAyahAttribute(): string
    {
        $kepala = $this->kepalaKeluarga;
        if ($kepala && $kepala->jenis_kelamin === 'laki-laki') {
            return $kepala->nama_lengkap;
        }
        return '';
    }

    public function getNamaIbuAttribute(): string
    {
        $kepala = $this->kepalaKeluarga;
        if ($kepala && $kepala->jenis_kelamin === 'perempuan') {
            return $kepala->nama_lengkap;
        }
        $istri = $this->istri;
        return $istri ? $istri->nama_lengkap : '';
    }
}
