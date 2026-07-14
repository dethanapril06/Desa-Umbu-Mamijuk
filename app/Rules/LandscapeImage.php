<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LandscapeImage implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value || !is_object($value) || !method_exists($value, 'getPathname')) {
            return;
        }

        $pathname = $value->getPathname();
        if (!file_exists($pathname)) {
            return;
        }

        $imageSize = @getimagesize($pathname);
        if ($imageSize && isset($imageSize[0], $imageSize[1])) {
            $width = (int) $imageSize[0];
            $height = (int) $imageSize[1];

            if ($height > $width) {
                $fail('Gambar harus berorientasi mendatar (Landscape / Lebar lebih besar atau sama dengan Tinggi). Gambar potret (berdiri) tidak cocok untuk tampilan ini.');
            }
        }
    }
}
