<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\DonorProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'       => User::count(),
            'total_donors'      => User::donors()->count(),
            'verified_donors'   => User::donors()->verified()->count(),

            'total_requests'    => BloodRequest::count(),
            'pending_requests'  => BloodRequest::where('status', BloodRequest::STATUS_PENDING)->count(),
            'approved_requests' => BloodRequest::where('status', BloodRequest::STATUS_APPROVED)->count(),
            'fulfilled_requests' => BloodRequest::where('status', BloodRequest::STATUS_FULFILLED)->count(),

            'active_donors'     => DonorProfile::where('approved_by_admin', true)
                ->where('is_available', true)
                ->count(),
        ];

        $recentRequests = BloodRequest::with(['recipient', 'approvedBy'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentRequests'));
    }

    public function reports()
    {
        return view('admin.reports.index');
    }

    public function donationReport()
    {
        $donations = DonorProfile::with('user')
            ->whereNotNull('last_donation_date')
            ->latest('last_donation_date')
            ->paginate(20);

        return view('admin.reports.donations', compact('donations'));
    }

    public function requestReport()
    {
        $requests = BloodRequest::with(['recipient', 'approvedBy'])
            ->latest()
            ->paginate(20);

        return view('admin.reports.requests', compact('requests'));
    }
}
