<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserAdminController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->query('role', 'all');
        $verified = $request->query('verified');

        $query = User::with(['donorProfile', 'recipientProfile']);

        if ($role && $role !== 'all') {
            $query->where('role', $role);
        }

        if ($verified !== null) {
            $query->where('is_verified', $verified === 'verified' ? true : false);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'donors' => User::where('role', 'donor')->count(),
            'recipients' => User::where('role', 'recipient')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function show(User $user)
    {
        $user->load(['donorProfile', 'recipientProfile', 'bloodRequests', 'donorResponses']);

        $bloodRequests = $user->bloodRequests()->with('donorResponses')->get();
        $donorResponses = $user->donorResponses()->with('bloodRequest')->get();

        return view('admin.users.show', compact('user', 'bloodRequests', 'donorResponses'));
    }

    public function verify(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Admin users are automatically verified.');
        }

        $user->update(['is_verified' => true]);

        return redirect()->back()->with('success', 'User verified successfully.');
    }

    public function unverify(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Cannot unverify admin users.');
        }

        $user->update(['is_verified' => false]);

        return redirect()->back()->with('success', 'User unverified.');
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,donor,recipient'
        ]);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot change your own role.');
        }

        $user->update(['role' => $request->role]);

        return redirect()->back()->with('success', 'User role updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Cannot delete admin users.');
        }

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
