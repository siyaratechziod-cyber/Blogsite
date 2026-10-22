<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Support\Slug;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Tag::withCount('posts')
            ->orderByDesc('posts_count')
            ->orderBy('name');

        if ($search = $request->input('q')) {
            $query->where('name', 'like', "%{$search}%");
        }

        return TagResource::collection($query->paginate($this->perPage($request, 20))->withQueryString());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);

        $tag = Tag::create([
            'name' => $data['name'],
            'slug' => Slug::unique(($data['slug'] ?? null) ?: $data['name'], Tag::class),
        ]);

        return response()->json([
            'message' => 'Tag created successfully.',
            'data' => new TagResource($tag),
        ], 201);
    }

    public function show(Tag $tag): TagResource
    {
        return new TagResource($tag->loadCount('posts'));
    }

    public function update(Request $request, Tag $tag): JsonResponse
    {
        $data = $this->validated($request, $tag);

        $tag->update([
            'name' => $data['name'],
            'slug' => Slug::unique(($data['slug'] ?? null) ?: $data['name'], Tag::class, $tag->id),
        ]);

        return response()->json([
            'message' => 'Tag updated successfully.',
            'data' => new TagResource($tag),
        ]);
    }

    public function destroy(Tag $tag): JsonResponse
    {
        // Many-to-many: only the pivot rows are removed, posts stay intact.
        $tag->posts()->detach();
        $tag->delete();

        return response()->json(['message' => 'Tag deleted successfully.']);
    }

    private function validated(Request $request, ?Tag $tag = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('tags', 'name')->ignore($tag?->id)],
            'slug' => ['nullable', 'string', 'max:50'],
        ]);
    }
}
