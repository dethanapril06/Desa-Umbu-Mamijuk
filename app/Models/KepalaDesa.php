<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KepalaDesa extends Model
{
    use HasFactory;

    protected $table = 'kepala_desa';

    protected $fillable = [
        'nama',
        'foto',
        'gelar',
        'sambutan',
        'periode_mulai',
        'periode_selesai',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
