<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function showCompleteProfile(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->hasRequiredProfileInfo()) {
            return redirect()->route('home');
        }

        $nextUsernameChangeAt = $user->username_changed_at?->copy()->addDays(14);
        $canEditUsername = $nextUsernameChangeAt === null || now()->greaterThanOrEqualTo($nextUsernameChangeAt);
        $suggestedUsername = $user->username ?: Str::of($user->display_name ?: $user->name)
            ->lower()
            ->replaceMatches('/[^a-z0-9_]+/', '_')
            ->trim('_')
            ->substr(0, 30)
            ->value();

        if (blank($suggestedUsername)) {
            $suggestedUsername = 'user_'.$user->id;
        }

        return view('profile.complete', [
            'user' => $user,
            'canEditUsername' => $canEditUsername,
            'nextUsernameChangeAt' => $nextUsernameChangeAt,
            'suggestedUsername' => $suggestedUsername,
        ]);
    }

    public function completeProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:30', 'regex:/^[A-Za-z0-9_]+$/', Rule::unique('users', 'username')->ignore($user->id)],
        ]);

        $nextUsernameChangeAt = $user->username_changed_at?->copy()->addDays(14);
        $canEditUsername = $nextUsernameChangeAt === null || now()->greaterThanOrEqualTo($nextUsernameChangeAt);
        $username = strtolower(trim($data['username']));

        if ($username !== $user->username && ! $canEditUsername) {
            return back()
                ->withErrors([
                    'username' => 'You can update your username every 14 days. Try again on '.$nextUsernameChangeAt?->format('M j, Y').'.',
                ])
                ->withInput();
        }

        $payload = [
            'display_name' => trim($data['display_name']),
            'username' => $username,
        ];

        if ($username !== $user->username || blank($user->username_changed_at)) {
            $payload['username_changed_at'] = now();
        }

        $user->update($payload);

        return redirect()->route('home')->with('status', 'Profile completed. Welcome!');
    }

    public function edit(): View
    {
        $user = Auth::user();
        $nextUsernameChangeAt = $user->username_changed_at?->copy()->addDays(14);
        $canEditUsername = $nextUsernameChangeAt === null || now()->greaterThanOrEqualTo($nextUsernameChangeAt);
        $currentDisplayName = $user->display_name ?: $user->name;
        $currentUsername = $user->username ?: Str::of($currentDisplayName)
            ->lower()
            ->replaceMatches('/[^a-z0-9_]+/', '_')
            ->trim('_')
            ->substr(0, 30)
            ->value();

        if (blank($currentUsername)) {
            $currentUsername = 'user_'.$user->id;
        }

        return view('profile.edit', [
            'user' => $user,
            'canEditUsername' => $canEditUsername,
            'nextUsernameChangeAt' => $nextUsernameChangeAt,
            'currentDisplayName' => $currentDisplayName,
            'currentUsername' => $currentUsername,
        ]);
    }

    public function update(Request $request): RedirectResponse|JsonResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'email_notifications_opt_in' => ['required', 'boolean'],
            'username' => ['required', 'string', 'min:3', 'max:30', 'regex:/^[A-Za-z0-9_]+$/', Rule::unique('users', 'username')->ignore($user->id)],
            'current_password' => ['nullable', 'required_with:password,password_confirmation', 'current_password'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $nextUsernameChangeAt = $user->username_changed_at?->copy()->addDays(14);
        $canEditUsername = $nextUsernameChangeAt === null || now()->greaterThanOrEqualTo($nextUsernameChangeAt);

        $updatePayload = [
            'display_name' => trim($data['display_name']),
            'email_notifications_opt_in' => (bool) $data['email_notifications_opt_in'],
        ];

        $username = strtolower(trim($data['username']));

        if ($username !== $user->username) {
            if (! $canEditUsername) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'message' => 'You can update your username every 14 days. Try again on '.$nextUsernameChangeAt?->format('M j, Y').'.',
                        'errors' => [
                            'username' => ['You can update your username every 14 days. Try again on '.$nextUsernameChangeAt?->format('M j, Y').'.'],
                        ],
                    ], 422);
                }

                return back()
                    ->withErrors([
                        'username' => 'You can update your username every 14 days. Try again on '.$nextUsernameChangeAt?->format('M j, Y').'.',
                    ])
                    ->withInput();
            }

            $updatePayload['username'] = $username;
            $updatePayload['username_changed_at'] = now();
        }

        if (! blank($data['password'] ?? null)) {
            $updatePayload['password'] = $data['password'];
        }

        $user->update($updatePayload);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Profile updated.',
            ]);
        }

        return back()->with('status', 'Profile updated.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Clean up orphaned sessions and password reset tokens
        \Illuminate\Support\Facades\DB::table('sessions')->where('user_id', $user->id)->delete();
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Your account has been deleted.');
    }
}
