<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Umkm extends Model
{
    use HasFactory;

    protected $table = 'umkm';

    protected $fillable = [
        'nama_usaha',
        'slug',
        'nama_pemilik',
        'deskripsi',
        'kategori',
        'alamat',
        'no_telepon',
        'email',
        'website_url',
        'foto',
        'jam_operasional',
        'status',
    ];

    /**
     * Scope for active UMKM only.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
}
