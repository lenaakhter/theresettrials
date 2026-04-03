<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserManagementController extends Controller
{
    public function create()
    {
        $recentAdmins = User::query()
            ->where('is_admin', true)
            ->latest('created_at')
            ->take(8)
            ->get();

        $promotableUsers = User::query()
            ->where('is_admin', false)
            ->latest('created_at')
            ->take(12)
            ->get(['id', 'name', 'email']);

        return view('admin.admins.create', compact('recentAdmins', 'promotableUsers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'is_admin' => true,
        ]);

        return redirect()->route('admin.admins.create')->with('status', 'Admin account created successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.admins.create')->with('error', 'You cannot revoke your own admin access.');
        }

        $user->update(['is_admin' => false]);

        return redirect()->route('admin.admins.create')->with('status', "{$user->name}'s admin access has been revoked.");
    }

    public function promote(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255'],
        ]);

        $user = User::query()->where('email', $data['email'])->first();

        if (! $user) {
            return redirect()->route('admin.admins.create')->with('error', 'No account found with that email.');
        }

        if ($user->is_admin) {
            return redirect()->route('admin.admins.create')->with('status', "{$user->name} already has admin access.");
        }

        $user->update(['is_admin' => true]);

        return redirect()->route('admin.admins.create')->with('status', "{$user->name} is now an admin.");
    }
}
