<?php

namespace App\Http\Controllers;

use App\Mail\BanAppealSubmittedMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class BanAppealController extends Controller
{
    public function create(Request $request): View
    {
        $banId = trim((string) $request->query('ban_id', ''));
        $username = trim((string) $request->query('username', ''));

        return view('auth.ban-appeal', compact('banId', 'username'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ban_id' => ['required', 'string', 'max:120'],
            'username' => ['required', 'string', 'max:255'],
            'appeal' => ['required', 'string', 'max:4000'],
        ]);

        Mail::to('theresettrials@gmail.com')->send(new BanAppealSubmittedMail(
            banId: trim($data['ban_id']),
            username: trim($data['username']),
            appeal: trim($data['appeal'])
        ));

        return redirect()->route('login')->with('status', 'Appeal submitted. We will review it and get back to you.');
    }
}
