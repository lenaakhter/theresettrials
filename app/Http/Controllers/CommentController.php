<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    public function store(Request $request, Post $post): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        Comment::query()->create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'body' => $data['body'],
        ]);

        return back()->with('comment_status', 'Comment posted.');
    }

    public function reply(Request $request, Comment $comment): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
            'post_slug' => ['required', 'string', Rule::exists('posts', 'slug')->where(fn ($query) => $query->where('id', $comment->post_id))],
        ]);

        Comment::query()->create([
            'post_id' => $comment->post_id,
            'user_id' => Auth::id(),
            'parent_id' => $comment->id,
            'body' => $data['body'],
        ]);

        return back()->with('comment_status', 'Reply posted.');
    }

    public function toggleLike(Comment $comment)
    {
        $existing = CommentLike::query()
            ->where('comment_id', $comment->id)
            ->where('user_id', Auth::id())
            ->first();

        $liked = false;
        if ($existing) {
            $existing->delete();
        } else {
            CommentLike::query()->create([
                'comment_id' => $comment->id,
                'user_id' => Auth::id(),
            ]);
            $liked = true;
        }

        // Reload like count from database
        $comment->loadCount('likes');

        if (request()->expectsJson()) {
            return response()->json([
                'liked' => $liked,
                'likes_count' => $comment->likes_count,
            ]);
        }

        return back()->with('comment_status', $liked ? 'Comment liked.' : 'Like removed.');
    }

    public function delete(Comment $comment): RedirectResponse
    {
        // Check if user is admin or comment author
        if (Auth::user()->is_admin || Auth::id() === $comment->user_id) {
            // Delete all nested replies first
            $this->deleteReplies($comment);
            $comment->delete();

            return back()->with('comment_status', 'Comment deleted.');
        }

        return back()->with('error', 'Unauthorized to delete this comment.');
    }

    private function deleteReplies(Comment $comment): void
    {
        foreach ($comment->replies as $reply) {
            $this->deleteReplies($reply);
            $reply->delete();
        }
    }
}
