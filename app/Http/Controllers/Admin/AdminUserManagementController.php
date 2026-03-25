<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserManagementController extends Controller
{
    public function create()
    {
        $recentAdmins = User::query()
            ->where('is_admin', true)
            ->latest('created_at')
            ->take(8)
            ->get();

        return view('admin.admins.create', compact('recentAdmins'));
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
}
