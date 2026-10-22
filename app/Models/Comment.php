<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'name',
        'email',
        'content',
        'status',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', 'rejected');
    }

    public function getAuthorNameAttribute(): string
    {
        return $this->user ? $this->user->name : ($this->name ?? 'Guest Reader');
    }

    public function getAuthorAvatarAttribute(): string
    {
        if ($this->user) {
            return $this->user->avatar_url;
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->author_name) . '&background=e2e8f0&color=334155&size=80';
    }
}
