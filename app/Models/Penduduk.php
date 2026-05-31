<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penduduk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penduduk';

    protected $fillable = [
        'keluarga_id',
        'nik',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'pendidikan_terakhir',
        'pekerjaan',
        'status_perkawinan',
        'status_hubungan_keluarga',
        'kewarganegaraan',
        'golongan_darah',
        'no_paspor',
        'no_kitas_kitap',
        'nama_ayah',
        'nama_ibu',
        'no_telepon',
        'is_asuransi_kesehatan',
        'is_disabilitas',
        'jenis_disabilitas',
        'status',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'is_asuransi_kesehatan' => 'boolean',
            'is_disabilitas' => 'boolean',
        ];
    }

    public function keluarga(): BelongsTo
    {
        return $this->belongsTo(Keluarga::class);
    }

    public function mutasiPenduduk(): HasMany
    {
        return $this->hasMany(MutasiPenduduk::class);
    }
}
