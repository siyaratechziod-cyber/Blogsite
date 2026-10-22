<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Concerns\DeletesPublicUploads;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

/**
 * Endpoints for the logged-in user's own account ("/me").
 */
class ProfileController extends Controller
{
    use DeletesPublicUploads;

    /**
     * Send as multipart/form-data (POST) when uploading images.
     */
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'specialty' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:3072'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'remove_profile_image' => ['nullable', 'boolean'],
            'remove_cover_image' => ['nullable', 'boolean'],
            'twitter' => ['nullable', 'url', 'max:255'],
            'github' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
        ]);

        foreach (['profile_image' => 'avatars', 'cover_image' => 'covers'] as $field => $folder) {
            if ($request->hasFile($field)) {
                $this->deleteUpload($user->{$field});
                $data[$field] = Storage::disk('public')->url($request->file($field)->store($folder, 'public'));
            } elseif ($request->boolean("remove_{$field}")) {
                $this->deleteUpload($user->{$field});
                $data[$field] = null;
            } else {
                unset($data[$field]);
            }
        }

        $data['social_links'] = array_filter([
            'twitter' => $data['twitter'] ?? null,
            'github' => $data['github'] ?? null,
            'linkedin' => $data['linkedin'] ?? null,
        ]);

        unset($data['twitter'], $data['github'], $data['linkedin'], $data['remove_profile_image'], $data['remove_cover_image']);

        $user->update($data);

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => (new UserResource($user->loadCount(['posts', 'followers', 'following'])))->withPrivate(),
        ]);
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password:sanctum'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();
        $user->update(['password' => $data['password']]);

        // Keep this device signed in, sign out the rest.
        $user->tokens()->whereKeyNot($user->currentAccessToken()->id)->delete();

        return response()->json(['message' => 'Password changed successfully.']);
    }

    public function bookmarks(Request $request): AnonymousResourceCollection
    {
        $posts = $request->user()->bookmarkedPosts()
            ->published()
            ->with(['author', 'category'])
            ->withCount(['comments', 'likes'])
            ->withUserState($request->user()->id)
            ->orderByDesc('bookmarks.created_at')
            ->paginate($this->perPage($request, 10));

        return PostResource::collection($posts);
    }

    public function likes(Request $request): AnonymousResourceCollection
    {
        $posts = $request->user()->likedPosts()
            ->published()
            ->with(['author', 'category'])
            ->withCount(['comments', 'likes'])
            ->withUserState($request->user()->id)
            ->orderByDesc('likes.created_at')
            ->paginate($this->perPage($request, 10));

        return PostResource::collection($posts);
    }

    public function following(Request $request): AnonymousResourceCollection
    {
        $users = $request->user()->following()
            ->withCount(['posts', 'followers'])
            ->orderByDesc('followers.created_at')
            ->paginate($this->perPage($request, 20));

        // Everyone in this list is followed by the viewer.
        $users->getCollection()->each(fn ($user) => $user->setAttribute('followed_by_me', true));

        return UserResource::collection($users);
    }

    public function followers(Request $request): AnonymousResourceCollection
    {
        $viewerId = $request->user()->id;

        $users = $request->user()->followers()
            ->withCount(['posts', 'followers'])
            ->withExists([
                'followers as followed_by_me' => fn ($q) => $q->where('followers.follower_id', $viewerId),
            ])
            ->orderByDesc('followers.created_at')
            ->paginate($this->perPage($request, 20));

        return UserResource::collection($users);
    }

    public function comments(Request $request): AnonymousResourceCollection
    {
        $comments = $request->user()->comments()
            ->with(['post', 'user'])
            ->latest()
            ->paginate($this->perPage($request, 20));

        return CommentResource::collection($comments);
    }
}
