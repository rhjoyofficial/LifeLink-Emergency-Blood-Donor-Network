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
        $query = DonorProfile::with('user');

        if ($request->filled('status')) {
            match ($request->status) {
                'approved' => $query->where('approved_by_admin', true),
                'pending'  => $query->where('approved_by_admin', false),
                'available' => $query->where('is_available', true),
                default    => null,
            };
        }

        $donors = $query->paginate(20);

        return view('admin.donors.index', compact('donors'));
    }

    public function show(DonorProfile $donorProfile)
    {
        $donorProfile->load('user.donorResponses.bloodRequest');

        return view('admin.donors.show', compact('donorProfile'));
    }

    public function approve(DonorProfile $donorProfile)
    {
        $this->authorize('approve', DonorProfile::class);

        $donorProfile->update([
            'approved_by_admin' => true,
            'is_available'      => true,
        ]);

        return back()->with('success', 'Donor approved.');
    }

    public function reject(DonorProfile $donorProfile)
    {
        $this->authorize('approve', DonorProfile::class);

        $donorProfile->update([
            'approved_by_admin' => false,
            'is_available'      => false,
        ]);

        return back()->with('success', 'Donor rejected.');
    }

    public function responses(DonorProfile $donorProfile)
    {
        $responses = $donorProfile->user
            ->donorResponses()
            ->with('bloodRequest.recipient')
            ->latest()
            ->paginate(20);

        return view('admin.donors.responses', compact('donorProfile', 'responses'));
    }

    public function destroy(DonorProfile $donorProfile)
    {
        $donorProfile->user->delete();

        return redirect()
            ->route('admin.donors.index')
            ->with('success', 'Donor deleted.');
    }

    public function statistics()
    {
        $stats = [
            'total'     => User::donors()->count(),
            'approved'  => DonorProfile::where('approved_by_admin', true)->count(),
            'available' => DonorProfile::where('is_available', true)->count(),
        ];

        return view('admin.donors.statistics', compact('stats'));
    }
}
