<?php

namespace App\Http\Controllers;

use App\Models\CommentLike;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

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

        $comments = $post->comments()
            ->whereNull('parent_id')
            ->withCount('likes')
            ->with(['user', 'repliesRecursive'])
            ->latest()
            ->get();

        $likedCommentIds = [];

        if (Auth::check()) {
            $likedCommentIds = CommentLike::query()
                ->where('user_id', Auth::id())
                ->whereHas('comment', fn ($query) => $query->where('post_id', $post->id))
                ->pluck('comment_id')
                ->all();
        }

        $latestPosts = Post::query()
            ->published()
            ->whereKeyNot($post->id)
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('posts.show', compact('post', 'latestPosts', 'comments', 'likedCommentIds'));
    }
}
