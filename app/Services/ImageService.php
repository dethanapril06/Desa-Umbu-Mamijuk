<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImageService
{
    /**
     * Store and optimize uploaded image.
     * - If portrait/square (aspect ratio < 1.33), converts into a landscape canvas (16:9) with blurred side background.
     * - If already landscape, downscales to maximum 1600px width (if larger) and compresses to ~300KB - 500KB.
     *
     * @param UploadedFile $file
     * @param string $folder Relative folder inside public storage
     * @param int $targetWidth Target landscape width (default 1200)
     * @param int $targetHeight Target landscape height (default 675 -> 16:9 ratio)
     * @return string Relative storage path
     */
    public static function processAndStore(UploadedFile $file, string $folder = 'uploads', int $targetWidth = 1200, int $targetHeight = 675): string
    {
        $realPath = $file->getRealPath();
        $imageInfo = @getimagesize($realPath);

        if (!$imageInfo) {
            return $file->store($folder, 'public');
        }

        $srcWidth = $imageInfo[0];
        $srcHeight = $imageInfo[1];
        $mime = $imageInfo['mime'];

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $srcImg = @imagecreatefromjpeg($realPath);
                break;
            case 'image/png':
                $srcImg = @imagecreatefrompng($realPath);
                break;
            case 'image/webp':
                $srcImg = @imagecreatefromwebp($realPath);
                break;
            default:
                return $file->store($folder, 'public');
        }

        if (!$srcImg) {
            return $file->store($folder, 'public');
        }

        // Check EXIF orientation for JPEGs
        if (function_exists('exif_read_data') && ($mime === 'image/jpeg' || $mime === 'image/jpg')) {
            $exif = @exif_read_data($realPath);
            if (!empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3:
                        $rotated = imagerotate($srcImg, 180, 0);
                        imagedestroy($srcImg);
                        $srcImg = $rotated;
                        break;
                    case 6:
                        $rotated = imagerotate($srcImg, -90, 0);
                        imagedestroy($srcImg);
                        $srcImg = $rotated;
                        $temp = $srcWidth; $srcWidth = $srcHeight; $srcHeight = $temp;
                        break;
                    case 8:
                        $rotated = imagerotate($srcImg, 90, 0);
                        imagedestroy($srcImg);
                        $srcImg = $rotated;
                        $temp = $srcWidth; $srcWidth = $srcHeight; $srcHeight = $temp;
                        break;
                }
            }
        }

        $aspectRatio = $srcWidth / $srcHeight;

        $filename = md5(uniqid(microtime(), true)) . '.jpg';
        $relativePath = $folder . '/' . $filename;
        $fullPath = storage_path('app/public/' . $relativePath);

        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Case A: Portrait / Square (< 1.33 aspect ratio) -> Blurred side borders 16:9 landscape
        if ($aspectRatio < 1.33) {
            $canvas = imagecreatetruecolor($targetWidth, $targetHeight);

            // 1. Create blurred background (cover)
            $bgScale = max($targetWidth / $srcWidth, $targetHeight / $srcHeight);
            $bgW = (int) ceil($srcWidth * $bgScale);
            $bgH = (int) ceil($srcHeight * $bgScale);
            $bgX = (int) (($targetWidth - $bgW) / 2);
            $bgY = (int) (($targetHeight - $bgH) / 2);

            $bgTemp = imagecreatetruecolor($bgW, $bgH);
            imagecopyresampled($bgTemp, $srcImg, 0, 0, 0, 0, $bgW, $bgH, $srcWidth, $srcHeight);

            imagecopy($canvas, $bgTemp, $bgX, $bgY, 0, 0, $bgW, $bgH);
            imagedestroy($bgTemp);

            // Fast gaussian blur (downscale + upscale)
            $smallW = (int) max(10, $targetWidth / 25);
            $smallH = (int) max(10, $targetHeight / 25);
            $smallImg = imagecreatetruecolor($smallW, $smallH);
            imagecopyresampled($smallImg, $canvas, 0, 0, 0, 0, $smallW, $smallH, $targetWidth, $targetHeight);
            imagecopyresampled($canvas, $smallImg, 0, 0, 0, 0, $targetWidth, $targetHeight, $smallW, $smallH);
            imagedestroy($smallImg);

            // Dark overlay for background
            $overlay = imagecreatetruecolor($targetWidth, $targetHeight);
            $darkColor = imagecolorallocatealpha($overlay, 0, 0, 0, 85);
            imagefill($overlay, 0, 0, $darkColor);
            imagecopy($canvas, $overlay, 0, 0, 0, 0, $targetWidth, $targetHeight);
            imagedestroy($overlay);

            // 2. Centered foreground image (contain)
            $fgScale = min($targetWidth / $srcWidth, $targetHeight / $srcHeight);
            $fgW = (int) ($srcWidth * $fgScale);
            $fgH = (int) ($srcHeight * $fgScale);
            $fgX = (int) (($targetWidth - $fgW) / 2);
            $fgY = (int) (($targetHeight - $fgH) / 2);

            imagecopyresampled($canvas, $srcImg, $fgX, $fgY, 0, 0, $fgW, $fgH, $srcWidth, $srcHeight);
            imagedestroy($srcImg);

            imagejpeg($canvas, $fullPath, 85);
            imagedestroy($canvas);

            return $relativePath;
        }

        // Case B: Already Landscape (>= 1.33) -> Downscale if width > 1600 & compress
        $maxWidth = 1600;
        if ($srcWidth > $maxWidth) {
            $outW = $maxWidth;
            $outH = (int) round($maxWidth / $aspectRatio);

            $canvas = imagecreatetruecolor($outW, $outH);
            imagecopyresampled($canvas, $srcImg, 0, 0, 0, 0, $outW, $outH, $srcWidth, $srcHeight);
            imagedestroy($srcImg);

            imagejpeg($canvas, $fullPath, 85);
            imagedestroy($canvas);

            return $relativePath;
        }

        // If landscape and already <= 1600px width, save compressed JPG
        imagejpeg($srcImg, $fullPath, 85);
        imagedestroy($srcImg);

        return $relativePath;
    }
}
