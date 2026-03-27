<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\AdminUserManagementController;
use App\Http\Controllers\Admin\PostManagementController;
use App\Http\Controllers\Admin\SubscriberManagementController;
use App\Http\Controllers\Admin\ExperimentController as AdminExperimentController;
use App\Http\Controllers\Admin\ResourceController as AdminResourceController;
use App\Http\Controllers\Auth\ReaderAuthController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ExperimentController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\JoinController;
use App\Http\Controllers\BanAppealController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

Route::get('/', function () {
    $latestPosts = Post::query()
        ->published()
        ->latest('published_at')
        ->take(7)
        ->get();
    
    $experiments = \App\Models\Experiment::where('status', 'active')
            ->notArchived()
        ->latest('start_date')
        ->take(2)
        ->get();

    return view('welcome', compact('latestPosts', 'experiments'));
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
    $currentlyTesting = \App\Models\Resource::with('linkable')
        ->where('linkable_type', \App\Models\Experiment::class)
        ->whereHas('linkable', fn ($q) => $q->where('archived', false)->where('status', 'active'))
        ->latest()
        ->get();

    $currentlyTestingIds = $currentlyTesting->pluck('id');

    $resources = \App\Models\Resource::with('linkable')
        ->whereNotIn('id', $currentlyTestingIds)
        ->latest()
        ->get();

    return view('resources', compact('resources', 'currentlyTesting'));
})->name('resources');

Route::redirect('/contact', '/disclaimer');

Route::get('/ban-appeal', [BanAppealController::class, 'create'])->name('ban.appeal.create');
Route::post('/ban-appeal', [BanAppealController::class, 'store'])->name('ban.appeal.store');

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
    Route::get('/complete-profile', [ProfileController::class, 'showCompleteProfile'])->name('profile.complete.show');
    Route::put('/complete-profile', [ProfileController::class, 'completeProfile'])->name('profile.complete.update');
});

Route::middleware(['auth', 'not.banned', 'profile.complete'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/posts/{post:slug}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/comments/{comment}/replies', [CommentController::class, 'reply'])->name('comments.reply');
    Route::post('/comments/{comment}/like', [CommentController::class, 'toggleLike'])->name('comments.like');
    Route::post('/comments/{comment}/delete', [CommentController::class, 'delete'])->name('comments.delete');
    Route::post('/experiments/{experiment}/comments', [ExperimentController::class, 'comment'])->name('experiments.comment');
    Route::post('/experiments/{experiment}/comments/{comment}/reply', [ExperimentController::class, 'reply'])->name('experiments.reply');
});

Route::get('/experiments/{experiment}', [ExperimentController::class, 'show'])->name('experiments.show');

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

    Route::middleware(['auth', 'not.banned', 'admin'])->group(function () {
        Route::get('/posts/create', [PostManagementController::class, 'create'])->name('posts.create');
        Route::post('/posts', [PostManagementController::class, 'store'])->name('posts.store');
        Route::get('/posts/{post}/edit', [PostManagementController::class, 'edit'])->name('posts.edit');
        Route::put('/posts/{post}', [PostManagementController::class, 'update'])->name('posts.update');
        Route::delete('/posts/{post}', [PostManagementController::class, 'destroy'])->name('posts.destroy');
        Route::get('/subscribers', [SubscriberManagementController::class, 'index'])->name('subscribers.index');
        Route::get('/subscribers/export', [SubscriberManagementController::class, 'export'])->name('subscribers.export');
        Route::get('/subscribers/export-excel', [SubscriberManagementController::class, 'exportExcel'])->name('subscribers.export-excel');
        Route::post('/subscribers/users/{user}/ban', [SubscriberManagementController::class, 'ban'])->name('subscribers.ban');
        Route::post('/subscribers/users/{user}/unban', [SubscriberManagementController::class, 'unban'])->name('subscribers.unban');
        Route::delete('/subscribers/users/{user}', [SubscriberManagementController::class, 'destroy'])->name('subscribers.destroy');
        Route::get('/admins/create', [AdminUserManagementController::class, 'create'])->name('admins.create');
        Route::post('/admins', [AdminUserManagementController::class, 'store'])->name('admins.store');
        Route::delete('/admins/{user}', [AdminUserManagementController::class, 'destroy'])->name('admins.destroy');
        Route::get('/experiments/{experiment}/add-entry', [AdminExperimentController::class, 'addEntry'])->name('experiments.add-entry');
        Route::post('/experiments/{experiment}/entries', [AdminExperimentController::class, 'storeEntry'])->name('experiments.store-entry');
        Route::get('/experiments', [AdminExperimentController::class, 'index'])->name('experiments.index');
        Route::get('/experiments/create', [AdminExperimentController::class, 'create'])->name('experiments.create');
        Route::post('/experiments', [AdminExperimentController::class, 'store'])->name('experiments.store');
        Route::get('/experiments/{experiment}/edit', [AdminExperimentController::class, 'edit'])->name('experiments.edit');
        Route::put('/experiments/{experiment}', [AdminExperimentController::class, 'update'])->name('experiments.update');
        Route::delete('/experiments/{experiment}', [AdminExperimentController::class, 'destroy'])->name('experiments.destroy');
        Route::patch('/experiments/{experiment}/archive', [AdminExperimentController::class, 'archive'])->name('experiments.archive');
        Route::get('/resources', [AdminResourceController::class, 'index'])->name('resources.index');
        Route::get('/resources/create', [AdminResourceController::class, 'create'])->name('resources.create');
        Route::post('/resources', [AdminResourceController::class, 'store'])->name('resources.store');
        Route::get('/resources/{resource}/edit', [AdminResourceController::class, 'edit'])->name('resources.edit');
        Route::put('/resources/{resource}', [AdminResourceController::class, 'update'])->name('resources.update');
        Route::delete('/resources/{resource}', [AdminResourceController::class, 'destroy'])->name('resources.destroy');
        Route::post('/posts/{post}/resources', [AdminResourceController::class, 'storeForPost'])->name('posts.resources.store');
        Route::post('/experiments/{experiment}/resources', [AdminResourceController::class, 'storeForExperiment'])->name('experiments.resources.store');
        Route::delete('/resources/{resource}/inline', [AdminResourceController::class, 'destroyInline'])->name('resources.destroy-inline');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});
