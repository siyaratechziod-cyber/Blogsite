<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public static function platformStats(): array
    {
        return [
            'articles_count' => Post::published()->count(),
            'authors_count' => User::whereIn('role', ['author', 'admin'])->count(),
            'total_views' => (int) Post::published()->sum('views'),
            'categories_count' => Category::count(),
        ];
    }

    /**
     * Full search: paginated posts plus matching categories and authors.
     */
    public function search(Request $request): JsonResponse
    {
        $q = trim((string) $request->input('q', ''));

        $suggested = Category::withCount('publishedPosts')
            ->orderByDesc('published_posts_count')
            ->take(8)
            ->get();

        if ($q === '') {
            return response()->json([
                'query' => $q,
                'posts' => null,
                'categories' => [],
                'authors' => [],
                'suggested_categories' => CategoryResource::collection($suggested),
            ]);
        }

        $posts = Post::published()
            ->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                      ->orWhere('excerpt', 'like', "%{$q}%")
                      ->orWhere('content', 'like', "%{$q}%")
                      ->orWhereHas('category', fn ($inner) => $inner->where('name', 'like', "%{$q}%"))
                      ->orWhereHas('author', fn ($inner) => $inner->where('name', 'like', "%{$q}%"));
            })
            ->with(['author', 'category'])
            ->withCount(['comments', 'likes'])
            ->withUserState($request->user('sanctum')?->id)
            ->recent()
            ->paginate($this->perPage($request, 9))
            ->withQueryString();

        $categories = Category::where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%");
            })
            ->withCount('publishedPosts')
            ->take(6)
            ->get();

        $authors = User::whereIn('role', ['author', 'admin'])
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('specialty', 'like', "%{$q}%")
                      ->orWhere('bio', 'like', "%{$q}%");
            })
            ->withCount('posts')
            ->take(6)
            ->get();

        return response()->json([
            'query' => $q,
            'posts' => PostResource::collection($posts)->response()->getData(true),
            'categories' => CategoryResource::collection($categories),
            'authors' => UserResource::collection($authors),
            'suggested_categories' => CategoryResource::collection($suggested),
        ]);
    }

    /**
     * Lightweight type-ahead suggestions (min 2 characters).
     */
    public function suggest(Request $request): JsonResponse
    {
        $q = trim((string) $request->input('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json(['query' => $q, 'posts' => [], 'categories' => []]);
        }

        $posts = Post::published()
            ->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                      ->orWhere('excerpt', 'like', "%{$q}%");
            })
            ->with('category')
            ->popular()
            ->take(5)
            ->get()
            ->map(fn (Post $post) => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'category' => $post->category?->name,
            ]);

        $categories = Category::where('name', 'like', "%{$q}%")
            ->withCount('publishedPosts')
            ->orderByDesc('published_posts_count')
            ->take(3)
            ->get();

        return response()->json([
            'query' => $q,
            'posts' => $posts,
            'categories' => CategoryResource::collection($categories),
        ]);
    }

    public function about(): JsonResponse
    {
        $team = User::whereIn('role', ['admin', 'author'])
            ->withCount('posts')
            ->orderByDesc('posts_count')
            ->take(8)
            ->get();

        return response()->json([
            'team' => UserResource::collection($team),
            'stats' => self::platformStats(),
        ]);
    }

    public function contact(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        ContactMessage::create($validated);

        return response()->json([
            'message' => 'Thank you! Your message has been received. Our editorial team will get back to you shortly.',
        ], 201);
    }

    public function subscribeNewsletter(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:150'],
        ]);

        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $validated['email']],
            ['status' => 'active']
        );

        return response()->json([
            'subscribed' => true,
            'message' => $subscriber->wasRecentlyCreated
                ? 'You are now subscribed to the BlogHub weekly digest!'
                : 'You are already on the list. Thanks for staying with us!',
        ]);
    }
}
