<?php

namespace App\Http\Controllers\Recipient;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\DonorProfile;
use Illuminate\Support\Facades\Auth;

class RecipientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'active_requests' => BloodRequest::where('recipient_id', $user->id)
                ->whereIn('status', [
                    BloodRequest::STATUS_PENDING,
                    BloodRequest::STATUS_APPROVED,
                ])->count(),

            'pending_requests' => BloodRequest::where('recipient_id', $user->id)
                ->where('status', BloodRequest::STATUS_PENDING)
                ->count(),

            'fulfilled_requests' => BloodRequest::where('recipient_id', $user->id)
                ->where('status', BloodRequest::STATUS_FULFILLED)
                ->count(),

            'cancelled_requests' => BloodRequest::where('recipient_id', $user->id)
                ->where('status', BloodRequest::STATUS_CANCELLED)
                ->count(),
        ];

        $recentRequests = BloodRequest::where('recipient_id', $user->id)
            ->with('donorResponses.donor')
            ->latest()
            ->limit(5)
            ->get();

        $district = $user->recipientProfile?->district;

        $donorsByBloodGroup = $district
            ? DonorProfile::where('district', $district)
            ->where('approved_by_admin', true)
            ->where('is_available', true)
            ->selectRaw('blood_group, COUNT(*) as total')
            ->groupBy('blood_group')
            ->pluck('total', 'blood_group')
            : collect();

        return view('recipient.dashboard', compact(
            'stats',
            'recentRequests',
            'donorsByBloodGroup'
        ));
    }

    public function statistics()
    {
        $user = Auth::user();

        $monthlyRequests = BloodRequest::selectRaw(
            'DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total'
        )
            ->where('recipient_id', $user->id)
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('recipient.statistics', compact('monthlyRequests'));
    }
}
