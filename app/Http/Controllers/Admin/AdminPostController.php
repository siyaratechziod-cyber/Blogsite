<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesPostForm;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminPostController extends Controller
{
    use HandlesPostForm;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Post::class);

        $user = Auth::user();

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

        if ($status = $request->input('status')) {
            if (in_array($status, ['draft', 'published', 'archived'], true)) {
                $query->where('status', $status);
            }
        }

        if ($categoryId = $request->input('category')) {
            $query->where('category_id', $categoryId);
        }

        $posts = $query->paginate(10)->withQueryString();

        $categories = Category::orderBy('name')->get();
        $authors = $user->isAdmin()
            ? User::whereIn('role', ['author', 'admin'])->orderBy('name')->get()
            : collect();

        return view('admin.posts.index', compact('posts', 'categories', 'authors'));
    }

    public function create(): View
    {
        $this->authorize('create', Post::class);

        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $authors = Auth::user()->isAdmin()
            ? User::whereIn('role', ['author', 'admin'])->orderBy('name')->get()
            : collect();

        return view('admin.posts.create', compact('categories', 'tags', 'authors'));
    }

    public function store(PostRequest $request): RedirectResponse
    {
        $post = new Post();
        $this->fillPost($post, $request);
        $post->save();

        $post->tags()->sync($this->tagIds($request));

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post created successfully.');
    }

    public function show(Post $post): View
    {
        $this->authorize('view', $post);

        $post->load(['author', 'category', 'tags'])->loadCount(['comments', 'likes']);

        return view('admin.posts.show', compact('post'));
    }

    public function edit(Post $post): View
    {
        $this->authorize('update', $post);

        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $authors = Auth::user()->isAdmin()
            ? User::whereIn('role', ['author', 'admin'])->orderBy('name')->get()
            : collect();

        return view('admin.posts.edit', compact('post', 'categories', 'tags', 'authors'));
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $this->authorize('update', $post);

        $this->fillPost($post, $request);
        $post->save();

        $post->tags()->sync($this->tagIds($request));

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);

        $this->deleteUpload($post->featured_image);
        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post deleted successfully.');
    }
}
