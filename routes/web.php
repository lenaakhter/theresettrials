<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\AdminUserManagementController;
use App\Http\Controllers\Admin\PostManagementController;
use App\Http\Controllers\Admin\SubscriberManagementController;
use App\Http\Controllers\Auth\ReaderAuthController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\JoinController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $latestPosts = Post::query()
        ->published()
        ->latest('published_at')
        ->take(6)
        ->get();

    return view('welcome', compact('latestPosts'));
})->name('home');

Route::get('/blogs', [PostController::class, 'index'])->name('blogs.index');
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::redirect('/shop', '/blogs');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/disclaimer', function () {
    return view('disclaimer');
})->name('disclaimer');

Route::get('/resources', function () {
    return view('resources');
})->name('resources');

Route::redirect('/contact', '/disclaimer');

Route::middleware('guest')->group(function () {
    Route::get('/login', [ReaderAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [ReaderAuthController::class, 'login'])->name('login.attempt');
    Route::get('/register', [ReaderAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [ReaderAuthController::class, 'register'])->name('register.store');
    Route::redirect('/signup', '/register');

    // Social auth routes
    Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);
    Route::get('/auth/facebook', [SocialAuthController::class, 'redirectToFacebook'])->name('login.facebook');
    Route::get('/auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback']);

    // Password reset routes
    Route::get('/forgot-password', [ReaderAuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [ReaderAuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ReaderAuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [ReaderAuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [ReaderAuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/posts/{post:slug}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/comments/{comment}/replies', [CommentController::class, 'reply'])->name('comments.reply');
    Route::post('/comments/{comment}/like', [CommentController::class, 'toggleLike'])->name('comments.like');
    Route::post('/comments/{comment}/delete', [CommentController::class, 'delete'])->name('comments.delete');
});

Route::get('/join', [JoinController::class, 'create'])->name('join.create');
Route::post('/join', [JoinController::class, 'store'])->name('join.store');

Route::get('/plain', function () {
    return view('plain');
})->name('plain');

Route::prefix('adminslair')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.attempt');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/posts/create', [PostManagementController::class, 'create'])->name('posts.create');
        Route::post('/posts', [PostManagementController::class, 'store'])->name('posts.store');
        Route::get('/posts/{post}/edit', [PostManagementController::class, 'edit'])->name('posts.edit');
        Route::put('/posts/{post}', [PostManagementController::class, 'update'])->name('posts.update');
        Route::get('/subscribers', [SubscriberManagementController::class, 'index'])->name('subscribers.index');
        Route::get('/subscribers/export', [SubscriberManagementController::class, 'export'])->name('subscribers.export');
        Route::get('/admins/create', [AdminUserManagementController::class, 'create'])->name('admins.create');
        Route::post('/admins', [AdminUserManagementController::class, 'store'])->name('admins.store');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});
