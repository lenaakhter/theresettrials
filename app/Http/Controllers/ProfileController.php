<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'display_name' => ['nullable', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $updatePayload = [
            'display_name' => $data['display_name'] ?? null,
        ];

        if ($request->hasFile('profile_photo')) {
            $directory = public_path('uploads/avatars');

            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $filename = 'avatar-'.$user->id.'-'.Str::uuid().'.'.$request->file('profile_photo')->getClientOriginalExtension();
            $request->file('profile_photo')->move($directory, $filename);

            $updatePayload['profile_photo'] = 'uploads/avatars/'.$filename;
        }

        $user->update($updatePayload);

        return back()->with('status', 'Profile updated.');
    }
}
