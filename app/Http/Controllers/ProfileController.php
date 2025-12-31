<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $user->load(['donorProfile', 'recipientProfile']);

        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $user->update($validated);

        // Update role-specific profile
        if ($user->isDonor() && $user->donorProfile) {
            $user->donorProfile->update($request->validate([
                'district' => 'sometimes|string|max:255',
                'upazila' => 'sometimes|string|max:255',
            ]));
        }

        if ($user->isRecipient() && $user->recipientProfile) {
            $user->recipientProfile->update($request->validate([
                'blood_group' => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
                'district' => 'sometimes|string|max:255',
                'upazila' => 'sometimes|string|max:255',
            ]));
        }

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('profile.show')->with('success', 'Password changed successfully.');
    }
}
