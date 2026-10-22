<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthorController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->input('q', ''));
        $specialty = $request->input('specialty');

        $query = User::whereIn('role', ['author', 'admin'])
            ->withCount(['posts', 'followers']);

        if ($q !== '') {
            $query->where(function ($inner) use ($q) {
                $inner->where('name', 'like', "%{$q}%")
                      ->orWhere('username', 'like', "%{$q}%")
                      ->orWhere('specialty', 'like', "%{$q}%")
                      ->orWhere('bio', 'like', "%{$q}%");
            });
        }

        if ($specialty) {
            $query->where('specialty', $specialty);
        }

        $authors = $query->orderByDesc('posts_count')
            ->paginate(12)
            ->withQueryString();

        $specialties = User::whereIn('role', ['author', 'admin'])
            ->whereNotNull('specialty')
            ->where('specialty', '!=', '')
            ->distinct()
            ->orderBy('specialty')
            ->pluck('specialty');

        return view('pages.authors.index', compact('authors', 'specialties', 'q', 'specialty'));
    }

    public function show(User $user): View
    {
        $author = $user;

        $posts = Post::published()
            ->where('user_id', $author->id)
            ->with(['category', 'tags'])
            ->withCount(['comments', 'likes'])
            ->withUserState(Auth::id())
            ->recent()
            ->paginate(6);

        $author->loadCount(['posts', 'followers', 'following']);

        $topTags = Tag::whereHas('posts', function ($query) use ($author) {
                $query->published()->where('user_id', $author->id);
            })
            ->withCount(['posts' => function ($query) use ($author) {
                $query->published()->where('user_id', $author->id);
            }])
            ->orderByDesc('posts_count')
            ->take(8)
            ->get();

        return view('pages.authors.show', compact('author', 'posts', 'topTags'));
    }

    public function follow(User $user): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $currentUser = Auth::user();

        if ($currentUser->id === $user->id) {
            return response()->json(['error' => 'You cannot follow yourself.'], 422);
        }

        $isFollowing = $currentUser->following()->where('following_id', $user->id)->exists();

        if ($isFollowing) {
            $currentUser->following()->detach($user->id);
            $following = false;
        } else {
            $currentUser->following()->attach($user->id);
            $following = true;
        }

        return response()->json([
            'success' => true,
            'following' => $following,
            'followers_count' => $user->followers()->count(),
        ]);
    }
}
