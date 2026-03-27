<?php

namespace App\Http\Controllers;

use App\Models\Experiment;
use App\Models\Comment;
use App\Models\CommentLike;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ExperimentController extends Controller
{
    public function show(Experiment $experiment)
    {
        $comments = $experiment->comments()
            ->whereNull('parent_id')
            ->with(['user'])
            ->withCount('likes')
            ->with(['repliesRecursive'])
            ->latest()
            ->get();

        $likedCommentIds = [];

        if (Auth::check()) {
            $likedCommentIds = CommentLike::query()
                ->where('user_id', Auth::id())
                ->whereHas('comment', function ($query) use ($experiment): void {
                    $query->where('commentable_type', Experiment::class)
                        ->where('commentable_id', $experiment->id);
                })
                ->pluck('comment_id')
                ->all();
        }

        return view('experiments.show', compact('experiment', 'comments', 'likedCommentIds'));
    }

    public function comment(Request $request, Experiment $experiment)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'commentable_type' => Experiment::class,
            'commentable_id' => $experiment->id,
            'content' => $request->content,
        ]);

        return redirect()->route('experiments.show', $experiment)->with('success', 'Comment posted successfully!');
    }

    public function reply(Request $request, Experiment $experiment, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        if ($comment->commentable_type !== Experiment::class || (int) $comment->commentable_id !== (int) $experiment->id) {
            abort(404);
        }

        Comment::create([
            'user_id' => auth()->id(),
            'parent_id' => $comment->id,
            'commentable_type' => Experiment::class,
            'commentable_id' => $experiment->id,
            'content' => $request->content,
        ]);

        return redirect()->route('experiments.show', $experiment)->with('success', 'Reply posted successfully!');
    }
}
