<?php

namespace App\Http\Resources;

use App\Support\MediaUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
{
    private bool $withPrivate = false;

    /**
     * Expose email/account fields, e.g. for the logged-in user's own profile.
     */
    public function withPrivate(): static
    {
        $this->withPrivate = true;

        return $this;
    }

    public function toArray(Request $request): array
    {
        $viewer = $request->user('sanctum');
        $showPrivate = $this->withPrivate
            || ($viewer && ($viewer->id === $this->id || $viewer->isAdmin()));

        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'role' => $this->role,
            'bio' => $this->bio,
            'specialty' => $this->specialty,
            'avatar_url' => MediaUrl::absolute($this->avatar_url),
            'cover_image' => MediaUrl::absolute($this->cover_image),
            'social_links' => (object) ($this->social_links ?? []),
            'posts_count' => $this->whenCounted('posts'),
            'followers_count' => $this->whenCounted('followers'),
            'following_count' => $this->whenCounted('following'),
            'is_followed_by_me' => (bool) ($this->followed_by_me ?? false),
            'email' => $this->when($showPrivate, $this->email),
            'is_active' => $this->when($showPrivate, (bool) $this->is_active),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
