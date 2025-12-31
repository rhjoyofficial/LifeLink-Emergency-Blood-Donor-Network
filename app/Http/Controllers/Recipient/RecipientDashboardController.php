<?php

namespace App\Http\Controllers\Recipient;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\DonorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'active_requests' => BloodRequest::where('recipient_id', $user->id)
                ->whereIn('status', ['approved', 'pending'])
                ->count(),
            'pending_requests' => BloodRequest::where('recipient_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'fulfilled_requests' => BloodRequest::where('recipient_id', $user->id)
                ->where('status', 'fulfilled')
                ->count(),
            'cancelled_requests' => BloodRequest::where('recipient_id', $user->id)
                ->where('status', 'cancelled')
                ->count(),
        ];

        $recentRequests = BloodRequest::where('recipient_id', $user->id)
            ->with(['donorResponses.donor'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get donors by blood group in user's district
        $userDistrict = $user->recipientProfile->district ?? 'Dhaka';
        $donorsByBloodGroup = DonorProfile::selectRaw('blood_group, count(*) as count')
            ->where('district', $userDistrict)
            ->where('approved_by_admin', true)
            ->where('is_available', true)
            ->groupBy('blood_group')
            ->get()
            ->pluck('count', 'blood_group');

        $urgentRequests = BloodRequest::where('urgency_level', 'critical')
            ->where('status', 'approved')
            ->where('district', $userDistrict)
            ->where('needed_at', '>', now())
            ->orderBy('needed_at')
            ->take(3)
            ->get();

        return view('recipient.dashboard', compact('stats', 'recentRequests', 'donorsByBloodGroup', 'urgentRequests'));
    }

    public function statistics()
    {
        $user = Auth::user();

        $stats = [
            'total_requests' => BloodRequest::where('recipient_id', $user->id)->count(),
            'successful_requests' => BloodRequest::where('recipient_id', $user->id)
                ->where('status', 'fulfilled')
                ->count(),
            'pending_requests' => BloodRequest::where('recipient_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'average_response_time' => '24h', // This would need calculation
            'donors_contacted' => BloodRequest::where('recipient_id', $user->id)
                ->withCount('donorResponses')
                ->get()
                ->sum('donor_responses_count'),
        ];

        $monthlyRequests = BloodRequest::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('recipient_id', $user->id)
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('recipient.statistics', compact('stats', 'monthlyRequests'));
    }
}
