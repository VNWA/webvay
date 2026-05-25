<?php

namespace App\Services;

use App\Models\LoanApplication;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UploadService
{
    public const MAX_KB = 5120;

    /**
     * Store image on local (private) disk with randomized filename.
     */
    public function storeEkycImage(LoanApplication $application, UploadedFile $file, string $kind): string
    {
        Validator::make(
            ['upload' => $file],
            [
                'upload' => [
                    'required',
                    'file',
                    'max:'.self::MAX_KB,
                    'mimes:jpeg,jpg,png,webp',
                ],
            ],
            [],
            ['upload' => __('validation.attributes.upload')],
        )->validate();

        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $filename = Str::uuid().'.'.$extension;
        $directory = 'ekyc/'.$application->id;
        $path = $directory.'/'.$filename;

        $binary = file_get_contents($file->getRealPath());
        if ($binary === false) {
            throw new \RuntimeException('Unable to read upload.');
        }

        $optimized = $this->optimizeWithGd($binary, $extension);
        Storage::disk('local')->put($path, $optimized);

        return $path;
    }

    private function optimizeWithGd(string $binary, string $extension): string
    {
        if (! function_exists('imagecreatefromstring')) {
            return $binary;
        }

        $src = @imagecreatefromstring($binary);
        if ($src === false) {
            return $binary;
        }

        $width = imagesx($src);
        $height = imagesy($src);
        $maxW = 1600;

        if ($width <= $maxW) {
            imagedestroy($src);

            return $binary;
        }

        $newW = $maxW;
        $newH = (int) round($height * ($newW / $width));
        $dst = imagecreatetruecolor($newW, $newH);
        if ($dst === false) {
            imagedestroy($src);

            return $binary;
        }

        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);
        imagedestroy($src);

        ob_start();
        match ($extension) {
            'png' => imagepng($dst, null, 6),
            'webp' => imagewebp($dst, null, 82),
            default => imagejpeg($dst, null, 82),
        };
        $out = (string) ob_get_clean();
        imagedestroy($dst);

        return $out !== '' ? $out : $binary;
    }
}
