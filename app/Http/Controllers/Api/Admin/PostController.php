<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Concerns\HandlesPostForm;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PostDetailResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\UserResource;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Post management for authors (own posts) and admins (all posts).
 */
class PostController extends Controller
{
    use HandlesPostForm;

    /**
     * Filters: q, status (draft|published|archived), category (id), author (id, admin only), per_page.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Post::class);

        $user = $request->user();

        $query = Post::with(['author', 'category'])
            ->withCount(['comments', 'likes'])
            ->latest();

        if (! $user->isAdmin()) {
            $query->where('user_id', $user->id);
        } elseif ($authorId = $request->input('author')) {
            $query->where('user_id', $authorId);
        }

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if (in_array($status = $request->input('status'), ['draft', 'published', 'archived'], true)) {
            $query->where('status', $status);
        }

        if ($categoryId = $request->input('category')) {
            $query->where('category_id', $categoryId);
        }

        return PostResource::collection(
            $query->paginate($this->perPage($request, 10))->withQueryString()
        );
    }

    /**
     * Dropdown data for the create/edit post form.
     */
    public function formOptions(Request $request): JsonResponse
    {
        $this->authorize('create', Post::class);

        $authors = $request->user()->isAdmin()
            ? User::whereIn('role', ['author', 'admin'])->orderBy('name')->get()
            : collect();

        return response()->json([
            'categories' => CategoryResource::collection(Category::orderBy('name')->get()),
            'tags' => TagResource::collection(Tag::orderBy('name')->get()),
            'authors' => UserResource::collection($authors),
            'statuses' => ['draft', 'published'],
        ]);
    }

    public function store(PostRequest $request): JsonResponse
    {
        $this->authorize('create', Post::class);

        $post = new Post();
        $this->fillPost($post, $request);
        $post->save();

        $post->tags()->sync($this->tagIds($request));

        return response()->json([
            'message' => 'Post created successfully.',
            'data' => new PostDetailResource($this->loadForDetail($post)),
        ], 201);
    }

    public function show(Post $post): JsonResponse
    {
        $this->authorize('view', $post);

        return response()->json([
            'data' => new PostDetailResource($this->loadForDetail($post)),
        ]);
    }

    /**
     * Use POST (multipart) when replacing the featured image; PUT works for JSON bodies.
     */
    public function update(PostRequest $request, Post $post): JsonResponse
    {
        $this->authorize('update', $post);

        $this->fillPost($post, $request);
        $post->save();

        $post->tags()->sync($this->tagIds($request));

        return response()->json([
            'message' => 'Post updated successfully.',
            'data' => new PostDetailResource($this->loadForDetail($post)),
        ]);
    }

    public function destroy(Post $post): JsonResponse
    {
        $this->authorize('delete', $post);

        $this->deleteUpload($post->featured_image);
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully.']);
    }

    private function loadForDetail(Post $post): Post
    {
        return $post->load(['author', 'category', 'tags'])->loadCount(['comments', 'likes']);
    }
}
