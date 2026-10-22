<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('publishedPosts')
            ->orderBy('name')
            ->get();

        return view('pages.categories.index', compact('categories'));
    }

    public function show(string $slug): View
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $posts = Post::published()
            ->where('category_id', $category->id)
            ->with(['author', 'category', 'tags'])
            ->withCount(['comments', 'likes'])
            ->recent()
            ->paginate(9);

        return view('pages.categories.show', compact('category', 'posts'));
    }
}
