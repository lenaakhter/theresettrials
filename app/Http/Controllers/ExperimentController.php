<?php

namespace App\Http\Controllers;

use App\Models\Experiment;
use App\Models\ExperimentEntry;
use App\Models\Comment;
use Illuminate\Http\Request;

class ExperimentController extends Controller
{
    public function show(Experiment $experiment)
    {
        return view('experiments.show', compact('experiment'));
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
}
