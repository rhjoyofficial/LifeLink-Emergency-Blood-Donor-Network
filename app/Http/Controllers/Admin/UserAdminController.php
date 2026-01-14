<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['donorProfile', 'recipientProfile']);

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('verified')) {
            $query->where('is_verified', $request->verified === 'yes');
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load([
            'donorProfile',
            'recipientProfile',
            'bloodRequests',
            'donorResponses.bloodRequest',
        ]);

        return view('admin.users.show', compact('user'));
    }

    public function verify(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Admins are always verified.');
        }

        $user->update(['is_verified' => true]);

        return back()->with('success', 'User verified.');
    }

    public function unverify(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot unverify admin.');
        }

        $user->update(['is_verified' => false]);

        return back()->with('success', 'User unverified.');
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,donor,recipient',
        ]);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $user->update(['role' => $request->role]);

        return back()->with('success', 'Role updated.');
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Admin users cannot be deleted.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted.');
    }
}
