<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Filters: status (pending|approved|rejected), q, per_page. Meta includes per-status counts.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Comment::with(['post', 'user'])->latest();

        if (in_array($status = $request->input('status'), ['pending', 'approved', 'rejected'], true)) {
            $query->where('status', $status);
        }

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $counts = [
            'all' => Comment::count(),
            'pending' => Comment::where('status', 'pending')->count(),
            'approved' => Comment::where('status', 'approved')->count(),
            'rejected' => Comment::where('status', 'rejected')->count(),
        ];

        return CommentResource::collection($query->paginate($this->perPage($request, 15))->withQueryString())
            ->additional(['counts' => $counts])
            ->response();
    }

    public function approve(Comment $comment): JsonResponse
    {
        $comment->update(['status' => 'approved']);

        return response()->json([
            'message' => 'Comment approved. It is now visible on the public site.',
            'data' => new CommentResource($comment),
        ]);
    }

    public function reject(Comment $comment): JsonResponse
    {
        $comment->update(['status' => 'rejected']);

        return response()->json([
            'message' => 'Comment rejected. It is hidden from the public site.',
            'data' => new CommentResource($comment),
        ]);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        $comment->delete();

        return response()->json(['message' => 'Comment deleted permanently.']);
    }
}
