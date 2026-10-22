<?php

use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\MessageController;
use App\Http\Controllers\Api\Admin\PostController as AdminPostController;
use App\Http\Controllers\Api\Admin\SubscriberController;
use App\Http\Controllers\Api\Admin\TagController as AdminTagController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| BlogHub Mobile API (v1)
|--------------------------------------------------------------------------
| Base URL: /api/v1
| Auth: Sanctum bearer token (Authorization: Bearer <token>) from /auth/login or /auth/register.
| Public endpoints also accept the token to fill liked_by_me / bookmarked_by_me / is_followed_by_me.
*/

Route::prefix('v1')->name('api.')->group(function () {

    // Auth (guest)
    Route::middleware('throttle:10,1')->prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register'])->name('auth.register');
        Route::post('login', [AuthController::class, 'login'])->name('auth.login');
        Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('auth.forgot');
        Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('auth.reset');
    });

    // Public content
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('about', [PageController::class, 'about'])->name('about');
    Route::get('search', [PageController::class, 'search'])->name('search');
    Route::get('search/suggest', [PageController::class, 'suggest'])->name('search.suggest');

    Route::get('posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('posts/{slug}', [PostController::class, 'show'])->name('posts.show');
    Route::get('posts/{post}/comments', [CommentController::class, 'index'])->whereNumber('post')->name('posts.comments.index');

    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('tags', [TagController::class, 'index'])->name('tags.index');

    Route::get('authors', [AuthorController::class, 'index'])->name('authors.index');
    Route::get('authors/{user}', [AuthorController::class, 'show'])->name('authors.show');
    Route::get('authors/{user}/posts', [AuthorController::class, 'posts'])->name('authors.posts');

    // Public forms (guests allowed; comments use the token if one is sent)
    Route::middleware('throttle:20,1')->group(function () {
        Route::post('posts/{post}/comments', [CommentController::class, 'store'])->whereNumber('post')->name('posts.comments.store');
        Route::post('contact', [PageController::class, 'contact'])->name('contact');
        Route::post('newsletter/subscribe', [PageController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
    });

    // Logged-in users (any role)
    Route::middleware(['auth:sanctum', 'active'])->group(function () {
        Route::get('auth/me', [AuthController::class, 'me'])->name('auth.me');
        Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::post('auth/logout-all', [AuthController::class, 'logoutAll'])->name('auth.logout-all');

        Route::post('posts/{post}/like', [PostController::class, 'like'])->whereNumber('post')->name('posts.like');
        Route::post('posts/{post}/bookmark', [PostController::class, 'bookmark'])->whereNumber('post')->name('posts.bookmark');
        Route::post('authors/{user}/follow', [AuthorController::class, 'follow'])->name('authors.follow');

        Route::prefix('me')->name('me.')->group(function () {
            Route::match(['put', 'post'], 'profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::put('password', [ProfileController::class, 'updatePassword'])->name('password.update');
            Route::get('bookmarks', [ProfileController::class, 'bookmarks'])->name('bookmarks');
            Route::get('likes', [ProfileController::class, 'likes'])->name('likes');
            Route::get('following', [ProfileController::class, 'following'])->name('following');
            Route::get('followers', [ProfileController::class, 'followers'])->name('followers');
            Route::get('comments', [ProfileController::class, 'comments'])->name('comments');
        });

        // Dashboard area. {post}, {category}, {tag}, {user}, ... bind by numeric id.
        Route::prefix('admin')->name('admin.')->group(function () {

            // Authors + admins
            Route::middleware('author')->group(function () {
                Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

                Route::get('posts/form-options', [AdminPostController::class, 'formOptions'])->name('posts.form-options');
                Route::get('posts', [AdminPostController::class, 'index'])->name('posts.index');
                Route::post('posts', [AdminPostController::class, 'store'])->name('posts.store');
                Route::get('posts/{post}', [AdminPostController::class, 'show'])->name('posts.show');
                Route::match(['put', 'post'], 'posts/{post}', [AdminPostController::class, 'update'])->name('posts.update');
                Route::delete('posts/{post}', [AdminPostController::class, 'destroy'])->name('posts.destroy');
            });

            // Admins only
            Route::middleware('admin')->group(function () {
                Route::apiResource('categories', AdminCategoryController::class);
                Route::apiResource('tags', AdminTagController::class);

                Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
                Route::get('users/{user:id}', [AdminUserController::class, 'show'])->name('users.show');
                Route::put('users/{user:id}', [AdminUserController::class, 'update'])->name('users.update');
                Route::patch('users/{user:id}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
                Route::delete('users/{user:id}', [AdminUserController::class, 'destroy'])->name('users.destroy');

                Route::get('comments', [AdminCommentController::class, 'index'])->name('comments.index');
                Route::patch('comments/{comment}/approve', [AdminCommentController::class, 'approve'])->name('comments.approve');
                Route::patch('comments/{comment}/reject', [AdminCommentController::class, 'reject'])->name('comments.reject');
                Route::delete('comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');

                Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
                Route::get('messages/{message}', [MessageController::class, 'show'])->name('messages.show');
                Route::patch('messages/{message}/read', [MessageController::class, 'toggleRead'])->name('messages.read');
                Route::delete('messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');

                Route::get('subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');
                Route::delete('subscribers/{subscriber}', [SubscriberController::class, 'destroy'])->name('subscribers.destroy');
            });
        });
    });
});
