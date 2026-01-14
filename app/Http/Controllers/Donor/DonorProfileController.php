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
        $profile = Auth::user()->donorProfile;

        if (! $profile) {
            return redirect()->route('donor.profile.edit');
        }

        return view('donor.profile.show', compact('profile'));
    }

    public function edit()
    {
        $profile = Auth::user()->donorProfile;

        return view('donor.profile.edit', compact('profile'));
    }

    public function store(Request $request)
    {
        $this->authorize('update', DonorProfile::class);

        $data = $request->validate([
            'blood_group'         => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'district'            => 'required|string|max:255',
            'upazila'             => 'required|string|max:255',
            'last_donation_date'  => 'nullable|date|before:today',
        ]);

        DonorProfile::create([
            ...$data,
            'user_id'             => Auth::id(),
            'approved_by_admin'   => false,
            'is_available'        => false,
        ]);

        return redirect()
            ->route('donor.dashboard')
            ->with('success', 'Donor profile created. Awaiting admin approval.');
    }

    public function update(Request $request)
    {
        $profile = Auth::user()->donorProfile;

        $this->authorize('update', $profile);

        $data = $request->validate([
            'blood_group'        => 'sometimes|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'district'           => 'sometimes|string|max:255',
            'upazila'            => 'sometimes|string|max:255',
            'last_donation_date' => 'nullable|date|before:today',
        ]);

        if (isset($data['blood_group']) &&
            $data['blood_group'] !== $profile->blood_group) {
            $data['approved_by_admin'] = false;
        }

        $profile->update($data);

        return back()->with('success', 'Profile updated.');
    }

    public function toggleAvailability()
    {
        $profile = Auth::user()->donorProfile;

        if (! $profile->approved_by_admin) {
            return back()->with('error', 'Profile not approved by admin.');
        }

        $profile->update([
            'is_available' => ! $profile->is_available,
        ]);

        return back()->with('success', 'Availability updated.');
    }
}
