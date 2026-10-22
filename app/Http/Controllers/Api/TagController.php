<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagController extends Controller
{
    /**
     * Most used tags first. ?limit= caps the list (default 30, max 100).
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $limit = max(1, min(100, (int) $request->input('limit', 30)));

        $tags = Tag::withCount('posts')
            ->orderByDesc('posts_count')
            ->orderBy('name')
            ->take($limit)
            ->get();

        return TagResource::collection($tags);
    }
}
