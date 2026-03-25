<?php

use App\Http\Controllers\PostController;
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

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::redirect('/shop', '/posts');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/plain', function () {
    return view('plain');
})->name('plain');
