<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAuthor();
    }

    public function view(User $user, Post $post): bool
    {
        return $this->manage($user, $post);
    }

    public function create(User $user): bool
    {
        return $user->isAuthor();
    }

    public function update(User $user, Post $post): bool
    {
        return $this->manage($user, $post);
    }

    public function delete(User $user, Post $post): bool
    {
        return $this->manage($user, $post);
    }

    /**
     * Admins manage every post; authors only their own.
     */
    private function manage(User $user, Post $post): bool
    {
        return $user->isAdmin() || $post->user_id === $user->id;
    }
}
