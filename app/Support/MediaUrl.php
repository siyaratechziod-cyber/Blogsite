<?php

namespace App\Support;

class MediaUrl
{
    /**
     * Uploads are stored as "/storage/..." paths; mobile clients need a full URL.
     */
    public static function absolute(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return url($path);
    }
}
