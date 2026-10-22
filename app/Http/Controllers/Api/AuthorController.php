<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\UserResource;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AuthorController extends Controller
{
    /**
     * Filters: q, specialty, per_page. Response meta includes the specialty list for filter chips.
     */
    public function index(Request $request): JsonResponse
    {
        $q = trim((string) $request->input('q', ''));

        $query = User::whereIn('role', ['author', 'admin'])
            ->withCount(['posts', 'followers']);

        $this->withFollowState($query, $request);

        if ($q !== '') {
            $query->where(function ($inner) use ($q) {
                $inner->where('name', 'like', "%{$q}%")
                      ->orWhere('username', 'like', "%{$q}%")
                      ->orWhere('specialty', 'like', "%{$q}%")
                      ->orWhere('bio', 'like', "%{$q}%");
            });
        }

        if ($specialty = $request->input('specialty')) {
            $query->where('specialty', $specialty);
        }

        $authors = $query->orderByDesc('posts_count')
            ->paginate($this->perPage($request, 12))
            ->withQueryString();

        $specialties = User::whereIn('role', ['author', 'admin'])
            ->whereNotNull('specialty')
            ->where('specialty', '!=', '')
            ->distinct()
            ->orderBy('specialty')
            ->pluck('specialty');

        return UserResource::collection($authors)
            ->additional(['specialties' => $specialties])
            ->response();
    }

    /**
     * {user} is the author's username.
     */
    public function show(Request $request, User $user): JsonResponse
    {
        $query = User::whereKey($user->id)->withCount(['posts', 'followers', 'following']);
        $this->withFollowState($query, $request);
        $author = $query->first();

        $topTags = Tag::whereHas('posts', fn ($q) => $q->published()->where('user_id', $author->id))
            ->withCount(['posts' => fn ($q) => $q->published()->where('user_id', $author->id)])
            ->orderByDesc('posts_count')
            ->take(8)
            ->get();

        return response()->json([
            'data' => new UserResource($author),
            'top_tags' => TagResource::collection($topTags),
        ]);
    }

    public function posts(Request $request, User $user): AnonymousResourceCollection
    {
        $posts = Post::published()
            ->where('user_id', $user->id)
            ->with(['author', 'category', 'tags'])
            ->withCount(['comments', 'likes'])
            ->withUserState($request->user('sanctum')?->id)
            ->recent()
            ->paginate($this->perPage($request, 6));

        return PostResource::collection($posts);
    }

    public function follow(Request $request, User $user): JsonResponse
    {
        $currentUser = $request->user();

        if ($currentUser->id === $user->id) {
            return response()->json(['message' => 'You cannot follow yourself.'], 422);
        }

        $isFollowing = $currentUser->isFollowing($user);

        if ($isFollowing) {
            $currentUser->following()->detach($user->id);
        } else {
            $currentUser->following()->attach($user->id);
        }

        return response()->json([
            'following' => ! $isFollowing,
            'followers_count' => $user->followers()->count(),
        ]);
    }

    private function withFollowState(Builder $query, Request $request): void
    {
        if ($viewerId = $request->user('sanctum')?->id) {
            $query->withExists([
                'followers as followed_by_me' => fn ($q) => $q->where('followers.follower_id', $viewerId),
            ]);
        }
    }
}
