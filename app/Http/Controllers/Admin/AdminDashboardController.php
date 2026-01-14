<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\User;
use App\Models\DonorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_requests' => BloodRequest::count(),
            'pending_requests' => BloodRequest::where('status', 'pending')->count(),
            'approved_requests' => BloodRequest::where('status', 'approved')->count(),
            'fulfilled_requests' => BloodRequest::where('status', 'fulfilled')->count(),
            'approved_donors' => DonorProfile::where('approved_by_admin', true)->count(),
            'pending_donors' => User::where('role', 'donor')
                ->whereHas('donorProfile', function ($q) {
                    $q->where('approved_by_admin', false);
                })->count(),
            'total_users' => User::count(),
            'today_requests' => BloodRequest::whereDate('created_at', today())->count(),
            'urgent_requests' => BloodRequest::where('urgency_level', 'critical')
                ->whereIn('status', ['pending', 'approved'])
                ->count(),
        ];

        $recentRequests = BloodRequest::with(['recipient', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Chart 1: Monthly Request Trends (Last 6 months)
        $monthlyRequests = $this->getMonthlyRequestData();

        // Chart 2: Blood Group Distribution
        $bloodGroupDistribution = $this->getBloodGroupDistribution();

        // Chart 3: Request Status Distribution
        $statusDistribution = $this->getRequestStatusDistribution();

        return view('admin.dashboard', compact(
            'stats',
            'recentRequests',
            'monthlyRequests',
            'bloodGroupDistribution',
            'statusDistribution'
        ));
    }

    public function reports()
    {
        $requestsByMonth = BloodRequest::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $donationsByMonth = DonorProfile::select(
            DB::raw('DATE_FORMAT(last_donation_date, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereYear('last_donation_date', date('Y'))
            ->whereNotNull('last_donation_date')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $bloodGroupDistribution = DonorProfile::select('blood_group', DB::raw('COUNT(*) as count'))
            ->where('approved_by_admin', true)
            ->groupBy('blood_group')
            ->orderBy('count', 'desc')
            ->get();

        $urgencyDistribution = BloodRequest::select('urgency_level', DB::raw('COUNT(*) as count'))
            ->groupBy('urgency_level')
            ->get();

        return view('admin.reports', compact(
            'requestsByMonth',
            'donationsByMonth',
            'bloodGroupDistribution',
            'urgencyDistribution'
        ));
    }

    public function donationReport()
    {
        $donations = DonorProfile::whereNotNull('last_donation_date')
            ->with('user')
            ->orderBy('last_donation_date', 'desc')
            ->paginate(20);

        $totalDonations = DonorProfile::whereNotNull('last_donation_date')->count();
        $thisMonthDonations = DonorProfile::whereNotNull('last_donation_date')
            ->whereMonth('last_donation_date', date('m'))
            ->whereYear('last_donation_date', date('Y'))
            ->count();

        return view('admin.reports.donations', compact('donations', 'totalDonations', 'thisMonthDonations'));
    }

    public function requestReport()
    {
        $requests = BloodRequest::with(['recipient', 'approvedBy', 'donorResponses'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $statusStats = [
            'pending' => BloodRequest::where('status', 'pending')->count(),
            'approved' => BloodRequest::where('status', 'approved')->count(),
            'fulfilled' => BloodRequest::where('status', 'fulfilled')->count(),
            'cancelled' => BloodRequest::where('status', 'cancelled')->count(),
        ];

        return view('admin.reports.requests', compact('requests', 'statusStats'));
    }

    private function getMonthlyRequestData()
    {
        $months = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M Y');

            $count = BloodRequest::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $data[] = $count;
        }

        return [
            'labels' => $months,
            'data' => $data
        ];
    }

    private function getBloodGroupDistribution()
    {
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $distribution = [];

        foreach ($bloodGroups as $group) {
            $count = BloodRequest::where('blood_group', $group)->count();
            if ($count > 0) {
                $distribution[] = [
                    'group' => $group,
                    'count' => $count
                ];
            }
        }

        return $distribution;
    }

    private function getRequestStatusDistribution()
    {
        return [
            'pending' => BloodRequest::where('status', 'pending')->count(),
            'approved' => BloodRequest::where('status', 'approved')->count(),
            'fulfilled' => BloodRequest::where('status', 'fulfilled')->count(),
            // 'rejected' => BloodRequest::where('status', 'rejected')->count(),
            'cancelled' => BloodRequest::where('status', 'cancelled')->count(),
        ];
    }
}
