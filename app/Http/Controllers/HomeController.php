<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();

        // Featured spotlight set: hero pick + 3 for the asymmetric grid.
        $featuredPosts = Post::published()
            ->with(['author', 'category'])
            ->withCount(['comments', 'likes'])
            ->withUserState($userId)
            ->popular()
            ->take(4)
            ->get();

        $featuredPost = $featuredPosts->first();
        $featuredGrid = $featuredPosts->slice(1)->values();

        // Trending: popular articles excluding everything already featured.
        $trendingPosts = Post::published()
            ->with(['author', 'category'])
            ->withCount(['comments', 'likes'])
            ->withUserState($userId)
            ->whereNotIn('id', $featuredPosts->pluck('id'))
            ->popular()
            ->take(4)
            ->get();

        // Latest published articles (never repeating featured/trending picks).
        $latestPosts = Post::published()
            ->with(['author', 'category'])
            ->withCount(['comments', 'likes'])
            ->withUserState($userId)
            ->whereNotIn('id', $featuredPosts->pluck('id')->merge($trendingPosts->pluck('id')))
            ->recent()
            ->take(6)
            ->get();

        // Popular categories (10+ shown on the home page).
        $categories = Category::withCount('publishedPosts')
            ->orderByDesc('published_posts_count')
            ->take(12)
            ->get();

        // Featured authors (6).
        $featuredAuthors = User::whereIn('role', ['author', 'admin'])
            ->withCount(['posts', 'followers'])
            ->orderByDesc('posts_count')
            ->take(6)
            ->get();

        // Testimonials for the slider (chunked into slides of 3).
        $testimonials = Testimonial::where('is_featured', true)
            ->orderByDesc('rating')
            ->take(9)
            ->get()
            ->chunk(3);

        // Live platform statistics (all derived from real data).
        $stats = [
            'articles_count' => Post::published()->count(),
            'authors_count' => User::whereIn('role', ['author', 'admin'])->count(),
            'total_views' => (int) Post::published()->sum('views'),
            'categories_count' => Category::count(),
        ];

        return view('pages.home', compact(
            'featuredPosts',
            'featuredPost',
            'featuredGrid',
            'trendingPosts',
            'latestPosts',
            'categories',
            'featuredAuthors',
            'testimonials',
            'stats'
        ));
    }
}
