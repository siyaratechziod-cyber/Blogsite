<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\TestimonialResource;
use App\Http\Resources\UserResource;
use App\Models\Category;
use App\Models\Post;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user('sanctum')?->id;

        $featuredPosts = Post::published()
            ->with(['author', 'category'])
            ->withCount(['comments', 'likes'])
            ->withUserState($userId)
            ->popular()
            ->take(4)
            ->get();

        $trendingPosts = Post::published()
            ->with(['author', 'category'])
            ->withCount(['comments', 'likes'])
            ->withUserState($userId)
            ->whereNotIn('id', $featuredPosts->pluck('id'))
            ->popular()
            ->take(4)
            ->get();

        $latestPosts = Post::published()
            ->with(['author', 'category'])
            ->withCount(['comments', 'likes'])
            ->withUserState($userId)
            ->whereNotIn('id', $featuredPosts->pluck('id')->merge($trendingPosts->pluck('id')))
            ->recent()
            ->take(6)
            ->get();

        $categories = Category::withCount('publishedPosts')
            ->orderByDesc('published_posts_count')
            ->take(12)
            ->get();

        $featuredAuthors = User::whereIn('role', ['author', 'admin'])
            ->withCount(['posts', 'followers'])
            ->orderByDesc('posts_count')
            ->take(6)
            ->get();

        $testimonials = Testimonial::where('is_featured', true)
            ->orderByDesc('rating')
            ->take(9)
            ->get();

        return response()->json([
            'featured' => PostResource::collection($featuredPosts),
            'trending' => PostResource::collection($trendingPosts),
            'latest' => PostResource::collection($latestPosts),
            'categories' => CategoryResource::collection($categories),
            'featured_authors' => UserResource::collection($featuredAuthors),
            'testimonials' => TestimonialResource::collection($testimonials),
            'stats' => PageController::platformStats(),
        ]);
    }
}
