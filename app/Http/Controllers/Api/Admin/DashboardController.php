<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Comment;
use App\Models\ContactMessage;
use App\Models\Like;
use App\Models\NewsletterSubscriber;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Admins get site-wide numbers; authors get numbers for their own posts.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $stats = [
                'total_users' => User::count(),
                'authors_count' => User::where('role', 'author')->count(),
                'total_posts' => Post::count(),
                'published_posts' => Post::published()->count(),
                'draft_posts' => Post::where('status', 'draft')->count(),
                'total_comments' => Comment::count(),
                'pending_comments' => Comment::where('status', 'pending')->count(),
                'categories_count' => Category::count(),
                'total_views' => (int) Post::sum('views'),
                'subscribers_count' => NewsletterSubscriber::count(),
                'unread_messages' => ContactMessage::where('is_read', false)->count(),
            ];

            $recentPosts = Post::with(['author', 'category'])
                ->withCount(['comments', 'likes'])
                ->latest()
                ->take(5)
                ->get();

            $recentComments = Comment::with(['post', 'user'])->latest()->take(5)->get();
            $recentMessages = ContactMessage::latest()->take(5)->get();
        } else {
            $myPostIds = Post::where('user_id', $user->id)->pluck('id');

            $stats = [
                'total_posts' => $myPostIds->count(),
                'published_posts' => Post::where('user_id', $user->id)->published()->count(),
                'draft_posts' => Post::where('user_id', $user->id)->where('status', 'draft')->count(),
                'total_comments' => Comment::whereIn('post_id', $myPostIds)->count(),
                'pending_comments' => Comment::whereIn('post_id', $myPostIds)->where('status', 'pending')->count(),
                'total_likes' => Like::whereIn('post_id', $myPostIds)->count(),
                'total_views' => (int) Post::where('user_id', $user->id)->sum('views'),
            ];

            $recentPosts = Post::where('user_id', $user->id)
                ->with(['category'])
                ->withCount(['comments', 'likes'])
                ->latest()
                ->take(5)
                ->get();

            $recentComments = Comment::whereIn('post_id', $myPostIds)
                ->with(['post', 'user'])
                ->latest()
                ->take(5)
                ->get();

            $recentMessages = collect();
        }

        return response()->json([
            'role' => $user->role,
            'stats' => $stats,
            'recent_posts' => PostResource::collection($recentPosts),
            'recent_comments' => CommentResource::collection($recentComments),
            'recent_messages' => $recentMessages,
        ]);
    }
}
