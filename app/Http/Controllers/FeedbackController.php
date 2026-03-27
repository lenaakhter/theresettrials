<?php

namespace App\Http\Controllers;

use App\Mail\FeedbackSubmittedMail;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Throwable;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $feedback = Feedback::create($validated);

        try {
            Mail::to('theresettrials@gmail.com')->send(new FeedbackSubmittedMail($feedback));
        } catch (Throwable $exception) {
            report($exception);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('feedback_sent', true);
    }
}
