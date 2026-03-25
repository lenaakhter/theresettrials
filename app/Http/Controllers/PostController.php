<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::query()
            ->published()
            ->latest('published_at')
            ->paginate(9);

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        abort_if($post->published_at === null || $post->published_at->isFuture(), 404);

        $latestPosts = Post::query()
            ->published()
            ->whereKeyNot($post->id)
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('posts.show', compact('post', 'latestPosts'));
    }
}
