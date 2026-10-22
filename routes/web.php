<?php

use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminCommentController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminMessageController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminSubscriberController;
use App\Http\Controllers\Admin\AdminTagController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| BlogHub Public Web Routes
|--------------------------------------------------------------------------
*/

// Home & Core Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::post('/newsletter/subscribe', [PageController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/search', [PageController::class, 'search'])->name('search');
Route::get('/search/suggest', [PageController::class, 'suggest'])->name('search.suggest');

// Blogs / Articles
Route::get('/blogs', [PostController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{slug}', [PostController::class, 'show'])->name('blogs.show');

// Categories
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// Authors
Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
Route::get('/authors/{user}', [AuthorController::class, 'show'])->name('authors.show');

// Public & AJAX Interactions
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
Route::post('/posts/{post}/bookmark', [PostController::class, 'bookmark'])->name('posts.bookmark');
Route::post('/authors/{user}/follow', [AuthorController::class, 'follow'])->name('authors.follow');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin Area (auth required)
| - Dashboard, post management & profile: admin + author
| - Categories, tags, users, comments, messages, subscribers: admin only
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth')
    ->group(function () {
        Route::middleware('author')->group(function () {
            Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

            Route::get('posts', [AdminPostController::class, 'index'])->name('posts.index');
            Route::get('posts/create', [AdminPostController::class, 'create'])->name('posts.create');
            Route::post('posts', [AdminPostController::class, 'store'])->name('posts.store');
            Route::get('posts/{post}', [AdminPostController::class, 'show'])->name('posts.show');
            Route::get('posts/{post}/edit', [AdminPostController::class, 'edit'])->name('posts.edit');
            Route::put('posts/{post}', [AdminPostController::class, 'update'])->name('posts.update');
            Route::delete('posts/{post}', [AdminPostController::class, 'destroy'])->name('posts.destroy');

            Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        });

        Route::middleware('admin')->group(function () {
            Route::resource('categories', AdminCategoryController::class)->except(['show']);
            Route::resource('tags', AdminTagController::class)->except(['show']);

            Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
            Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
            Route::put('users/{user}', [AdminUserController::class, 'update'])->name('users.update');
            Route::patch('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
            Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

            Route::get('comments', [AdminCommentController::class, 'index'])->name('comments.index');
            Route::patch('comments/{comment}/approve', [AdminCommentController::class, 'approve'])->name('comments.approve');
            Route::patch('comments/{comment}/reject', [AdminCommentController::class, 'reject'])->name('comments.reject');
            Route::delete('comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');

            Route::get('messages', [AdminMessageController::class, 'index'])->name('messages.index');
            Route::get('messages/{message}', [AdminMessageController::class, 'show'])->name('messages.show');
            Route::patch('messages/{message}/read', [AdminMessageController::class, 'toggleRead'])->name('messages.read');
            Route::delete('messages/{message}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');

            Route::get('subscribers', [AdminSubscriberController::class, 'index'])->name('subscribers.index');
            Route::delete('subscribers/{subscriber}', [AdminSubscriberController::class, 'destroy'])->name('subscribers.destroy');
        });
    });
