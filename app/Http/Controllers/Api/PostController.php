<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostDetailResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    /**
     * Filters: q, category (slug), author (user id), tag (slug), sort (latest|oldest|popular), per_page.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Post::published()
            ->with(['author', 'category', 'tags'])
            ->withCount(['comments', 'likes'])
            ->withUserState($request->user('sanctum')?->id);

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($categorySlug = $request->input('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        if ($authorId = $request->input('author')) {
            $query->where('user_id', $authorId);
        }

        if ($tagSlug = $request->input('tag')) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $tagSlug));
        }

        match ($request->input('sort', 'latest')) {
            'popular' => $query->popular(),
            'oldest' => $query->oldest(),
            default => $query->recent(),
        };

        return PostResource::collection(
            $query->paginate($this->perPage($request, 9))->withQueryString()
        );
    }

    public function show(Request $request, string $slug): JsonResponse
    {
        $userId = $request->user('sanctum')?->id;

        $post = Post::where('slug', $slug)
            ->published()
            ->with(['author', 'category', 'tags', 'approvedComments' => fn ($q) => $q->with('user')->latest()])
            ->withCount(['comments', 'likes'])
            ->withUserState($userId)
            ->firstOrFail();

        $post->increment('views');

        $relatedPosts = Post::published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->with(['author', 'category'])
            ->withCount(['comments', 'likes'])
            ->withUserState($userId)
            ->take(3)
            ->get();

        return response()->json([
            'data' => new PostDetailResource($post),
            'related' => PostResource::collection($relatedPosts),
        ]);
    }

    public function like(Request $request, Post $post): JsonResponse
    {
        $userId = $request->user()->id;
        $existingLike = $post->likes()->where('user_id', $userId)->first();

        if ($existingLike) {
            $existingLike->delete();
        } else {
            $post->likes()->create(['user_id' => $userId]);
        }

        return response()->json([
            'liked' => ! $existingLike,
            'likes_count' => $post->likes()->count(),
        ]);
    }

    public function bookmark(Request $request, Post $post): JsonResponse
    {
        $userId = $request->user()->id;
        $existingBookmark = $post->bookmarks()->where('user_id', $userId)->first();

        if ($existingBookmark) {
            $existingBookmark->delete();
        } else {
            $post->bookmarks()->create(['user_id' => $userId]);
        }

        return response()->json([
            'bookmarked' => ! $existingBookmark,
        ]);
    }
}
