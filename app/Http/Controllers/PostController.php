<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $userId = Auth::id();

        $query = Post::published()
            ->with(['author', 'category', 'tags'])
            ->withCount(['comments', 'likes'])
            ->withUserState($userId);

        // Filter by search
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($categorySlug = $request->input('category')) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Filter by author
        if ($authorId = $request->input('author')) {
            $query->where('user_id', $authorId);
        }

        // Filter by tag
        if ($tagSlug = $request->input('tag')) {
            $query->whereHas('tags', function ($q) use ($tagSlug) {
                $q->where('slug', $tagSlug);
            });
        }

        // Sorting: latest (default) / oldest / popular
        $sort = $request->input('sort', 'latest');
        if ($sort === 'popular') {
            $query->popular();
        } elseif ($sort === 'oldest') {
            $query->oldest();
        } else {
            $sort = 'latest';
            $query->recent();
        }

        $posts = $query->paginate(9)->withQueryString();
        $categories = Category::withCount('publishedPosts')->get();
        $authors = User::whereIn('role', ['author', 'admin'])
            ->has('posts')
            ->withCount('posts')
            ->orderBy('name')
            ->get();
        $tags = Tag::withCount('posts')->orderByDesc('posts_count')->take(15)->get();

        return view('pages.blogs.index', compact('posts', 'categories', 'authors', 'tags', 'sort'));
    }

    public function show(string $slug): View
    {
        $userId = Auth::id();

        $post = Post::where('slug', $slug)
            ->published()
            ->with(['author', 'category', 'tags', 'approvedComments.user'])
            ->withCount(['comments', 'likes'])
            ->withUserState($userId)
            ->firstOrFail();

        // Increment views
        $post->increment('views');

        // Related posts in same category
        $relatedPosts = Post::published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->with(['author', 'category'])
            ->withCount(['comments', 'likes'])
            ->withUserState($userId)
            ->take(3)
            ->get();

        return view('pages.blogs.show', compact('post', 'relatedPosts'));
    }

    public function like(Post $post): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user = Auth::user();
        $existingLike = $post->likes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            $post->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $post->likes()->count(),
        ]);
    }

    public function bookmark(Post $post): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user = Auth::user();
        $existingBookmark = $post->bookmarks()->where('user_id', $user->id)->first();

        if ($existingBookmark) {
            $existingBookmark->delete();
            $bookmarked = false;
        } else {
            $post->bookmarks()->create(['user_id' => $user->id]);
            $bookmarked = true;
        }

        return response()->json([
            'success' => true,
            'bookmarked' => $bookmarked,
        ]);
    }
}
