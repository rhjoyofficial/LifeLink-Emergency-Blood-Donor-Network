<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\DonorResponse;
use App\Services\DonorResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonorResponseController extends Controller
{
    public function __construct(
        private readonly DonorResponseService $donorResponseService
    ) {}

    public function getAvailableRequests(Request $request)
    {
        $profile = Auth::user()->donorProfile;

        if (! $profile || ! $profile->isActive()) {
            return redirect()->route('donor.dashboard')
                ->with('error', 'Donor profile not active.');
        }

        $requests = BloodRequest::where('blood_group', $profile->blood_group)
            ->where('district', $profile->district)
            ->where('status', BloodRequest::STATUS_APPROVED)
            ->where('needed_at', '>', now())
            ->whereDoesntHave('donorResponses', function ($q) {
                $q->where('donor_id', Auth::id());
            })
            ->orderBy('urgency_level', 'desc')
            ->orderBy('needed_at')
            ->paginate(15);

        return view('donor.blood-requests.available', compact('requests'));
    }

    public function show(BloodRequest $bloodRequest)
    {
        return view('donor.blood-requests.show', compact('bloodRequest'));
    }

    public function respond(BloodRequest $bloodRequest)
    {
        $this->authorize('respond', [$bloodRequest]);

        $this->donorResponseService->respond($bloodRequest, Auth::user());

        return back()->with(
            'success',
            'You have expressed interest in this blood request.'
        );
    }

    public function myResponses()
    {
        $responses = DonorResponse::where('donor_id', Auth::id())
            ->with('bloodRequest.recipient')
            ->latest()
            ->paginate(15);

        return view('donor.responses.index', compact('responses'));
    }

    public function updateStatus(Request $request, DonorResponse $donorResponse)
    {
        $this->authorize('update', $donorResponse);

        $request->validate([
            'status' => 'required|in:contacted,donated',
        ]);

        $this->donorResponseService->updateStatus(
            $donorResponse,
            $request->status
        );

        return back()->with('success', 'Response updated.');
    }
}
