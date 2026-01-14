<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\DonorResponse;
use Illuminate\Support\Facades\Auth;

class DonorDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->donorProfile;

        if (! $profile) {
            return redirect()->route('donor.profile.show');
        }

        $stats = [
            'eligible_to_donate' => $profile->canDonate(),
            'last_donation'      => $profile->last_donation_date,
            'total_responses'    => $user->donorResponses()->count(),
            'donated_count'      => $user->donorResponses()
                ->where('response_status', 'donated')
                ->count(),
            'pending_responses'  => $user->donorResponses()
                ->where('response_status', 'interested')
                ->count(),
        ];

        $recentRequests = BloodRequest::where('blood_group', $profile->blood_group)
            ->where('district', $profile->district)
            ->where('status', BloodRequest::STATUS_APPROVED)
            ->where('needed_at', '>', now())
            ->latest()
            ->limit(5)
            ->get();

        return view('donor.dashboard', compact(
            'profile',
            'stats',
            'recentRequests'
        ));
    }

    public function dashboard()
    {
        return $this->index();
    }
}
