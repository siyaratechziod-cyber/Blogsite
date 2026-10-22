<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait DeletesPublicUploads
{
    /**
     * Remove a previously uploaded file; external URLs are left untouched.
     */
    private function deleteUpload(?string $url): void
    {
        if (! $url || ! str_starts_with($url, '/storage/')) {
            return;
        }

        Storage::disk('public')->delete(Str::after($url, '/storage/'));
    }
}
