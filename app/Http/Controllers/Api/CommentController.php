<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentController extends Controller
{
    public function index(Request $request, Post $post): AnonymousResourceCollection
    {
        $comments = $post->approvedComments()
            ->with('user')
            ->latest()
            ->paginate($this->perPage($request, 20));

        return CommentResource::collection($comments);
    }

    /**
     * Logged-in users comment as themselves; guests must send name and email.
     */
    public function store(Request $request, Post $post): JsonResponse
    {
        $user = $request->user('sanctum');

        $rules = [
            'content' => ['required', 'string', 'min:3', 'max:2000'],
        ];

        if (! $user) {
            $rules['name'] = ['required', 'string', 'max:100'];
            $rules['email'] = ['required', 'email', 'max:150'];
        }

        $validated = $request->validate($rules);

        $comment = $post->comments()->create([
            'user_id' => $user?->id,
            'name' => $user ? $user->name : $validated['name'],
            'email' => $user ? $user->email : $validated['email'],
            'content' => $validated['content'],
            'status' => 'approved',
        ]);

        return response()->json([
            'message' => 'Your comment has been posted successfully!',
            'data' => new CommentResource($comment->load('user')),
        ], 201);
    }
}
