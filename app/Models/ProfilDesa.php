<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilDesa extends Model
{
    use HasFactory;

    protected $table = 'profil_desa';

    protected $fillable = [
        'nama_desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'telepon',
        'email',
        'alamat_lengkap',
        'luas_wilayah',
        'ketinggian',
        'jam_pelayanan',
        'visi',
        'misi',
        'sejarah_desa',
        'logo',
        'batas_utara',
        'batas_timur',
        'batas_selatan',
        'batas_barat',
        'peta_wilayah',
        'gambar_struktur_organisasi',
    ];
}
