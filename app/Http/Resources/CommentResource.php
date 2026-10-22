<?php

namespace App\Http\Resources;

use App\Support\MediaUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Comment */
class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'post_id' => $this->post_id,
            'content' => $this->content,
            'status' => $this->status,
            'author_name' => $this->author_name,
            'author_avatar' => MediaUrl::absolute($this->author_avatar),
            'user_id' => $this->user_id,
            'email' => $this->when((bool) $request->user('sanctum')?->isAdmin(), $this->email),
            'post' => $this->whenLoaded('post', fn () => $this->post ? [
                'id' => $this->post->id,
                'title' => $this->post->title,
                'slug' => $this->post->slug,
            ] : null),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
