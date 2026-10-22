<?php

namespace App\Support;

use Illuminate\Support\Str;

class Slug
{
    /**
     * Build a URL-safe slug from the given source, appending a numeric
     * suffix when the slug is already taken by another record.
     */
    public static function unique(string $source, string $modelClass, int|string|null $ignoreId = null): string
    {
        $base = Str::slug($source) ?: 'item';
        $slug = $base;
        $suffix = 1;

        while ($modelClass::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists()
        ) {
            $slug = $base . '-' . $suffix++;
        }

        return $slug;
    }
}
