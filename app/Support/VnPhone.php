<?php

namespace App\Support;

final class VnPhone
{
    public static function normalize(string $raw): string
    {
        $digits = preg_replace('/\D+/', '', $raw) ?? '';

        if (str_starts_with($digits, '84') && strlen($digits) >= 10) {
            return '0'.substr($digits, -9);
        }

        if (strlen($digits) === 9 && preg_match('/^[35789]/', $digits)) {
            return '0'.$digits;
        }

        return $digits;
    }

    public static function isValidNormalized(string $normalized): bool
    {
        return (bool) preg_match('/^0[35789]\d{8}$/', $normalized);
    }
}
