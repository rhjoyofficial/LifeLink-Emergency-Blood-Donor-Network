<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\DonorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonorProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = $user->donorProfile;

        if (!$profile) {
            return redirect()->route('donor.profile.create');
        }

        $responses = $user->donorResponses()
            ->with('bloodRequest')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('donor.profile.show', compact('profile', 'responses'));
    }

    public function edit()
    {
        $user = Auth::user();
        $profile = $user->donorProfile;

        if (!$profile) {
            return redirect()->route('donor.profile.create');
        }

        return view('donor.profile.edit', compact('profile'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->donorProfile) {
            return redirect()->route('donor.profile.edit');
        }

        return view('donor.profile.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->donorProfile) {
            return redirect()->route('donor.profile.edit')
                ->with('error', 'Donor profile already exists.');
        }

        $validated = $request->validate([
            'blood_group' => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'district' => 'required|string|max:255',
            'upazila' => 'required|string|max:255',
            'last_donation_date' => 'nullable|date|before:today',
            'is_available' => 'boolean',
        ]);

        $profile = DonorProfile::create([
            'user_id' => $user->id,
            'blood_group' => $validated['blood_group'],
            'district' => $validated['district'],
            'upazila' => $validated['upazila'],
            'last_donation_date' => $validated['last_donation_date'] ?? null,
            'is_available' => $validated['is_available'] ?? true,
            'approved_by_admin' => false,
        ]);

        return redirect()->route('donor.dashboard')
            ->with('success', 'Donor profile created. Awaiting admin approval.');
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->donorProfile;

        if (!$profile) {
            return redirect()->route('donor.profile.create')
                ->with('error', 'Donor profile not found.');
        }

        $validated = $request->validate([
            'blood_group' => 'sometimes|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'district' => 'sometimes|string|max:255',
            'upazila' => 'sometimes|string|max:255',
            'last_donation_date' => 'nullable|date|before:today',
            'is_available' => 'sometimes|boolean',
        ]);

        // If blood group changes, admin needs to re-approve
        if (isset($validated['blood_group']) && $validated['blood_group'] !== $profile->blood_group) {
            $validated['approved_by_admin'] = false;
        }

        $profile->update($validated);

        return redirect()->route('donor.profile.show')
            ->with('success', 'Donor profile updated.');
    }

    public function toggleAvailability()
    {
        $user = Auth::user();
        $profile = $user->donorProfile;

        if (!$profile) {
            return redirect()->route('donor.profile.create')
                ->with('error', 'Donor profile not found.');
        }

        if (!$profile->approved_by_admin) {
            return redirect()->back()
                ->with('error', 'Profile must be approved by admin first.');
        }

        $profile->update([
            'is_available' => !$profile->is_available
        ]);

        return redirect()->back()
            ->with('success', 'Availability status updated.');
    }
}
