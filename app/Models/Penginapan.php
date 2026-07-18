<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Penginapan extends Model
{
    use HasFactory;

    protected $table = 'penginapan';

    protected $fillable = [
        'nama_penginapan',
        'jenis',
        'kisaran_harga',
        'jarak',
        'no_telepon',
        'fasilitas_singkat',
        'foto',
        'is_published',
    ];

    public function wisata(): BelongsToMany
    {
        return $this->belongsToMany(Wisata::class, 'penginapan_wisata', 'penginapan_id', 'wisata_id')->withTimestamps();
    }

    public function getKisaranHargaAttribute($value)
    {
        return self::formatHarga($value);
    }

    public function setKisaranHargaAttribute($value)
    {
        $this->attributes['kisaran_harga'] = self::formatHarga($value);
    }

    public static function formatHarga(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $value = preg_replace('/\s*-\s*/', ' - ', trim($value));
        $parts = explode(' - ', $value);
        $res = [];

        foreach ($parts as $part) {
            $part = trim($part);
            if (empty($part)) {
                continue;
            }
            if (preg_match('/^(?:[A-Za-z\.\s]*?)([\d\.,]+)(.*)$/', $part, $m)) {
                $numStr = preg_replace('/[^\d]/', '', $m[1]);
                $suf = trim($m[2]);
                if (is_numeric($numStr) && $numStr > 0) {
                    $f = 'Rp ' . number_format((float) $numStr, 0, ',', '.');
                    $res[] = $suf ? $f . ' ' . $suf : $f;
                    continue;
                }
            }
            $res[] = $part;
        }

        return implode(' - ', $res);
    }
}
