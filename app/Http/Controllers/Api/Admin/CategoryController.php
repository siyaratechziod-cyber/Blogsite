<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Support\Slug;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $categories = Category::withCount('posts')
            ->orderBy('name')
            ->paginate($this->perPage($request, 12));

        return CategoryResource::collection($categories);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);

        $category = Category::create([
            'name' => $data['name'],
            'slug' => Slug::unique(($data['slug'] ?? null) ?: $data['name'], Category::class),
            'icon' => $data['icon'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        return response()->json([
            'message' => 'Category created successfully.',
            'data' => new CategoryResource($category),
        ], 201);
    }

    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category->loadCount('posts'));
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $data = $this->validated($request, $category);

        $category->update([
            'name' => $data['name'],
            'slug' => Slug::unique(($data['slug'] ?? null) ?: $data['name'], Category::class, $category->id),
            'icon' => $data['icon'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        return response()->json([
            'message' => 'Category updated successfully.',
            'data' => new CategoryResource($category),
        ]);
    }

    public function destroy(Category $category): JsonResponse
    {
        if ($category->posts()->exists()) {
            return response()->json([
                'message' => 'This category still contains posts, so it cannot be deleted. Move or delete those posts first.',
            ], 409);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully.']);
    }

    private function validated(Request $request, ?Category $category = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($category?->id)],
            'slug' => ['nullable', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);
    }
}
