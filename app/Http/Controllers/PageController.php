<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PageController extends Controller
{
    public function search(Request $request): View
    {
        $q = trim((string) $request->input('q', ''));

        $posts = null;
        $categories = collect();
        $authors = collect();

        if ($q !== '') {
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
                ->withUserState(Auth::id())
                ->recent()
                ->paginate(9)
                ->withQueryString();

            $categories = Category::where('name', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%")
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
        }

        // Suggested topics to explore when there is no query or nothing matched.
        $suggestedCategories = Category::withCount('publishedPosts')
            ->orderByDesc('published_posts_count')
            ->take(8)
            ->get();

        return view('pages.search', compact('q', 'posts', 'categories', 'authors', 'suggestedCategories'));
    }

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
                'title' => $post->title,
                'url' => route('blogs.show', $post->slug),
                'category' => $post->category?->name,
            ]);

        $categories = Category::where('name', 'like', "%{$q}%")
            ->withCount('publishedPosts')
            ->orderByDesc('published_posts_count')
            ->take(3)
            ->get()
            ->map(fn (Category $category) => [
                'name' => $category->name,
                'icon' => $category->icon,
                'url' => route('categories.show', $category->slug),
                'posts_count' => $category->published_posts_count,
            ]);

        return response()->json([
            'query' => $q,
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }

    public function about(): View
    {
        $team = User::whereIn('role', ['admin', 'author'])
            ->withCount('posts')
            ->orderByDesc('posts_count')
            ->take(8)
            ->get();

        $stats = [
            'articles_count' => Post::published()->count(),
            'authors_count' => User::whereIn('role', ['author', 'admin'])->count(),
            'total_views' => (int) Post::published()->sum('views'),
            'categories_count' => Category::count(),
        ];

        return view('pages.about', compact('team', 'stats'));
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function submitContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'Thank you! Your message has been received. Our editorial team will get back to you shortly.');
    }

    public function subscribeNewsletter(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:150'],
        ]);

        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $validated['email']],
            ['status' => 'active']
        );

        $message = $subscriber->wasRecentlyCreated
            ? 'You are now subscribed to the BlogHub weekly digest!'
            : 'You are already on the list — thanks for staying with us!';

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return back()->with('success', $message);
    }

    public function privacy(): View
    {
        return view('pages.privacy');
    }

    public function terms(): View
    {
        return view('pages.terms');
    }
}
