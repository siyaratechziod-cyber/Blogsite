<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $categories = Category::withCount('publishedPosts')
            ->orderBy('name')
            ->get();

        return CategoryResource::collection($categories);
    }

    /**
     * Category details plus a paginated page of its published posts.
     */
    public function show(Request $request, string $slug): JsonResponse
    {
        $category = Category::where('slug', $slug)
            ->withCount('publishedPosts')
            ->firstOrFail();

        $posts = Post::published()
            ->where('category_id', $category->id)
            ->with(['author', 'category', 'tags'])
            ->withCount(['comments', 'likes'])
            ->withUserState($request->user('sanctum')?->id)
            ->recent()
            ->paginate($this->perPage($request, 9));

        return PostResource::collection($posts)
            ->additional(['category' => new CategoryResource($category)])
            ->response();
    }
}
