<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonorProfile;
use App\Models\User;
use Illuminate\Http\Request;

class DonorAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $bloodGroup = $request->query('blood_group', 'all');

        $query = User::with('donorProfile')
            ->where('role', 'donor')
            ->where('is_verified', true);

        if ($status === 'pending') {
            $query->whereHas('donorProfile', function ($q) {
                $q->where('approved_by_admin', false);
            });
        } elseif ($status === 'approved') {
            $query->whereHas('donorProfile', function ($q) {
                $q->where('approved_by_admin', true);
            });
        } elseif ($status === 'available') {
            $query->whereHas('donorProfile', function ($q) {
                $q->where('approved_by_admin', true)
                    ->where('is_available', true);
            });
        }

        if ($bloodGroup !== 'all') {
            $query->whereHas('donorProfile', function ($q) use ($bloodGroup) {
                $q->where('blood_group', $bloodGroup);
            });
        }

        $donors = $query->paginate(20);

        $stats = [
            'total' => User::where('role', 'donor')->count(),
            'pending' => User::where('role', 'donor')
                ->whereHas('donorProfile', function ($q) {
                    $q->where('approved_by_admin', false);
                })->count(),
            'approved' => User::where('role', 'donor')
                ->whereHas('donorProfile', function ($q) {
                    $q->where('approved_by_admin', true);
                })->count(),
            'available' => User::where('role', 'donor')
                ->whereHas('donorProfile', function ($q) {
                    $q->where('approved_by_admin', true)
                        ->where('is_available', true);
                })->count(),
        ];

        return view('admin.donors.index', compact('donors', 'stats'));
    }

    public function show(DonorProfile $donorProfile)
    {
        $donorProfile->load(['user', 'user.donorResponses.bloodRequest']);

        $responses = $donorProfile->user->donorResponses()
            ->with('bloodRequest')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.donors.show', compact('donorProfile', 'responses'));
    }

    public function approve(DonorProfile $donorProfile)
    {
        $this->authorize('approve', DonorProfile::class);

        $donorProfile->update([
            'approved_by_admin' => true,
            'is_available' => true,
        ]);

        return redirect()->back()->with('success', 'Donor approved successfully.');
    }

    public function reject(DonorProfile $donorProfile)
    {
        $this->authorize('approve', DonorProfile::class);

        $donorProfile->update([
            'approved_by_admin' => false,
            'is_available' => false,
        ]);

        return redirect()->back()->with('success', 'Donor rejected.');
    }

    public function destroy(DonorProfile $donorProfile)
    {
        $donorProfile->user->delete();

        return redirect()->route('admin.donors.index')->with('success', 'Donor deleted successfully.');
    }

    public function responses(DonorProfile $donorProfile)
    {
        $responses = $donorProfile->user->donorResponses()
            ->with('bloodRequest.recipient')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.donors.responses', compact('donorProfile', 'responses'));
    }

    public function statistics()
    {
        $totalDonors = User::where('role', 'donor')->count();
        $verifiedDonors = User::where('role', 'donor')->where('is_verified', true)->count();
        $approvedDonors = DonorProfile::where('approved_by_admin', true)->count();
        $availableDonors = DonorProfile::where('is_available', true)->count();

        $donorsByBloodGroup = DonorProfile::selectRaw('blood_group, count(*) as count')
            ->where('approved_by_admin', true)
            ->groupBy('blood_group')
            ->get();

        return view('admin.donors.statistics', compact(
            'totalDonors',
            'verifiedDonors',
            'approvedDonors',
            'availableDonors',
            'donorsByBloodGroup'
        ));
    }
}
