<?php

namespace App\Http\Controllers;

use App\Models\CommentLike;
use App\Models\Experiment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $category = request('category');

        $activeExperiments = Experiment::query()
            ->notArchived()
            ->where('status', 'active')
            ->latest('start_date')
            ->get();

        $posts = Post::query()
            ->published()
            ->when($category, fn ($q) => $q->where('category', $category))
            ->latest('published_at')
            ->get();

        return view('posts.index', compact('posts', 'category', 'activeExperiments'));
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
