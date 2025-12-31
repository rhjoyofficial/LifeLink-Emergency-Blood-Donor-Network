<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\DonorResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonorDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->donorProfile;

        if (!$profile) {
            return redirect()->route('donor.profile.create');
        }

        $stats = [
            'eligible_to_donate' => $this->canDonate($profile),
            'last_donation' => $profile->last_donation_date,
            'available_requests' => $this->getAvailableRequestsCount($profile),
            'donated_count' => DonorResponse::where('donor_id', $user->id)
                ->where('response_status', 'donated')
                ->count(),
            'total_responses' => DonorResponse::where('donor_id', $user->id)->count(),
            'pending_responses' => DonorResponse::where('donor_id', $user->id)
                ->where('response_status', 'interested')
                ->count(),
        ];

        $recentRequests = $this->getAvailableRequests($profile)
            ->take(5)
            ->get();

        $myResponses = DonorResponse::where('donor_id', $user->id)
            ->with('bloodRequest.recipient')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('donor.dashboard', compact('stats', 'recentRequests', 'myResponses', 'profile'));
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $profile = $user->donorProfile;

        if (!$profile) {
            return response()->json(['data' => []]);
        }

        $stats = [
            'total_responses' => DonorResponse::where('donor_id', $user->id)->count(),
            'donated_count' => DonorResponse::where('donor_id', $user->id)
                ->where('response_status', 'donated')
                ->count(),
            'available_requests' => $this->getAvailableRequestsCount($profile),
            'eligible_to_donate' => $this->canDonate($profile),
            'last_donation' => $profile->last_donation_date,
            'profile_status' => $profile->isActive() ? 'active' : 'inactive',
        ];

        return view('donor.statistics', compact('stats'));
    }

    private function canDonate($profile)
    {
        if (!$profile->last_donation_date) {
            return true;
        }

        return $profile->last_donation_date->diffInDays(now()) >= 90;
    }

    private function getAvailableRequestsCount($profile)
    {
        return BloodRequest::where('blood_group', $profile->blood_group)
            ->where('district', $profile->district)
            ->where('status', 'approved')
            ->where('needed_at', '>', now())
            ->count();
    }

    private function getAvailableRequests($profile)
    {
        return BloodRequest::where('blood_group', $profile->blood_group)
            ->where('district', $profile->district)
            ->where('status', 'approved')
            ->where('needed_at', '>', now())
            ->whereDoesntHave('donorResponses', function ($query) use ($profile) {
                $query->where('donor_id', $profile->user_id);
            })
            ->with('recipient')
            ->orderBy('urgency_level', 'desc')
            ->orderBy('needed_at', 'asc');
    }
}
