<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JoinController extends Controller
{
    public function create(): View
    {
        return view('join');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:newsletter_subscribers,email'],
        ]);

        NewsletterSubscriber::create([
            'email' => $validated['email'],
        ]);

        return back()->with('success', 'You are officially on the list 🎉');
    }
}
