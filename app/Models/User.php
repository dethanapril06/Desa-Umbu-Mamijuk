<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function berita(): HasMany
    {
        return $this->hasMany(Berita::class);
    }

    public function wisata(): HasMany
    {
        return $this->hasMany(Wisata::class);
    }

    public function tanggapanPengaduan(): HasMany
    {
        return $this->hasMany(TanggapanPengaduan::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
