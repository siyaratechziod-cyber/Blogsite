<?php

namespace App\Http\Controllers\Concerns;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Tag;
use App\Support\Slug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Post create/update logic shared by the web admin panel and the mobile API.
 */
trait HandlesPostForm
{
    use DeletesPublicUploads;

    private function fillPost(Post $post, PostRequest $request): void
    {
        $data = $request->validated();

        $post->user_id = $this->resolveAuthorId($request);
        $post->category_id = $data['category_id'];
        $post->title = $data['title'];
        $post->slug = Slug::unique(($data['slug'] ?? null) ?: $data['title'], Post::class, $post->id);
        $post->excerpt = $data['excerpt'] ?? null;
        $post->content = $data['content'];
        $post->status = $data['status'];
        $post->reading_time = $data['reading_time'] ?? $this->estimateReadingTime($data['content']);
        $post->published_at = $this->resolvePublishedAt($data);

        if ($request->hasFile('featured_image')) {
            $this->deleteUpload($post->featured_image);
            $post->featured_image = $this->storeImage($request);
        } elseif ($request->boolean('remove_featured_image')) {
            $this->deleteUpload($post->featured_image);
            $post->featured_image = null;
        }
    }

    /**
     * Admins may assign any author; authors always own their posts.
     */
    private function resolveAuthorId(PostRequest $request): int
    {
        if (Auth::user()->isAdmin() && $request->filled('user_id')) {
            return (int) $request->input('user_id');
        }

        return (int) Auth::id();
    }

    private function resolvePublishedAt(array $data): ?string
    {
        if ($data['status'] !== 'published') {
            return $data['published_at'] ?? null;
        }

        return $data['published_at'] ?? now()->toDateTimeString();
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('featured_image')) {
            return null;
        }

        $path = $request->file('featured_image')->store('posts', 'public');

        return Storage::disk('public')->url($path);
    }

    /**
     * Combine selected tag IDs with any comma-separated new tag names.
     */
    private function tagIds(Request $request): array
    {
        $ids = collect($request->input('tags', []))->map(fn ($id) => (int) $id);

        $newTags = collect(explode(',', (string) $request->input('new_tags')))
            ->map(fn ($name) => Str::limit(trim($name), 50, ''))
            ->filter()
            ->unique()
            ->take(10);

        foreach ($newTags as $name) {
            $tag = Tag::where('name', $name)->first()
                ?? Tag::create(['name' => $name, 'slug' => Slug::unique($name, Tag::class)]);

            $ids->push($tag->id);
        }

        return $ids->unique()->values()->all();
    }

    private function estimateReadingTime(string $content): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($content)) / 200));
    }
}
