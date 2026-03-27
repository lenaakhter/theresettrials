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

        $currentAvatarFocusX = old('avatar_focus_x', $user->avatar_focus_x ?? 50);
        $currentAvatarFocusY = old('avatar_focus_y', $user->avatar_focus_y ?? 50);

        return view('profile.edit', [
            'user' => $user,
            'canEditUsername' => $canEditUsername,
            'nextUsernameChangeAt' => $nextUsernameChangeAt,
            'currentDisplayName' => $currentDisplayName,
            'currentUsername' => $currentUsername,
            'currentAvatarFocusX' => $currentAvatarFocusX,
            'currentAvatarFocusY' => $currentAvatarFocusY,
        ]);
    }

    public function update(Request $request): RedirectResponse|JsonResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'email_notifications_opt_in' => ['required', 'boolean'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'remove_profile_photo' => ['nullable', 'boolean'],
            'avatar_focus_x' => ['nullable', 'numeric', 'between:0,100'],
            'avatar_focus_y' => ['nullable', 'numeric', 'between:0,100'],
            'username' => ['required', 'string', 'min:3', 'max:30', 'regex:/^[A-Za-z0-9_]+$/', Rule::unique('users', 'username')->ignore($user->id)],
            'current_password' => ['nullable', 'required_with:password,password_confirmation', 'current_password'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $nextUsernameChangeAt = $user->username_changed_at?->copy()->addDays(14);
        $canEditUsername = $nextUsernameChangeAt === null || now()->greaterThanOrEqualTo($nextUsernameChangeAt);

        $updatePayload = [
            'display_name' => trim($data['display_name']),
            'email_notifications_opt_in' => (bool) $data['email_notifications_opt_in'],
            'avatar_focus_x' => (float) ($data['avatar_focus_x'] ?? $user->avatar_focus_x ?? 50),
            'avatar_focus_y' => (float) ($data['avatar_focus_y'] ?? $user->avatar_focus_y ?? 50),
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

        if ($request->hasFile('profile_photo')) {
            $directory = public_path('uploads/avatars');

            if ($user->profile_photo) {
                $existingPhotoPath = public_path($user->profile_photo);

                if (is_file($existingPhotoPath)) {
                    @unlink($existingPhotoPath);
                }
            }

            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $filename = 'avatar-'.$user->id.'-'.Str::uuid().'.'.$request->file('profile_photo')->getClientOriginalExtension();
            $request->file('profile_photo')->move($directory, $filename);

            $updatePayload['profile_photo'] = 'uploads/avatars/'.$filename;
        } elseif (($data['remove_profile_photo'] ?? false) && $user->profile_photo) {
            $existingPhotoPath = public_path($user->profile_photo);

            if (is_file($existingPhotoPath)) {
                @unlink($existingPhotoPath);
            }

            $updatePayload['profile_photo'] = null;
            $updatePayload['avatar_focus_x'] = 50;
            $updatePayload['avatar_focus_y'] = 50;
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
}
