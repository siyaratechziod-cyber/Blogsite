<?php

namespace App\Http\Resources;

use App\Support\MediaUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Card/list shape of a post. PostDetailResource adds the full content.
 *
 * @mixin \App\Models\Post
 */
class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'featured_image' => MediaUrl::absolute($this->featured_image),
            'status' => $this->status,
            'views' => $this->views,
            'reading_time' => $this->estimated_reading_time,
            'published_at' => $this->published_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'author' => new UserResource($this->whenLoaded('author')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'comments_count' => $this->whenCounted('comments'),
            'likes_count' => $this->whenCounted('likes'),
            'liked_by_me' => (bool) ($this->liked_by_me ?? false),
            'bookmarked_by_me' => (bool) ($this->bookmarked_by_me ?? false),
            'web_url' => route('blogs.show', $this->slug),
        ];
    }
}
