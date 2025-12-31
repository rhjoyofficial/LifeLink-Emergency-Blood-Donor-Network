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
        private DonorResponseService $donorResponseService
    ) {}

    public function getAvailableRequests(Request $request)
    {
        $user = Auth::user();

        if (!$user->donorProfile || !$user->donorProfile->isActive()) {
            return redirect()->route('donor.dashboard')
                ->with('error', 'Your donor profile is not active.');
        }

        $urgency = $request->query('urgency', 'all');
        $location = $request->query('location', 'all');

        $bloodGroup = $user->donorProfile->blood_group;
        $district = $user->donorProfile->district;

        $query = BloodRequest::where('blood_group', $bloodGroup)
            ->where('status', 'approved')
            ->where('needed_at', '>', now())
            ->whereDoesntHave('donorResponses', function ($query) use ($user) {
                $query->where('donor_id', $user->id);
            });

        if ($urgency !== 'all') {
            $query->where('urgency_level', $urgency);
        }

        if ($location === 'district') {
            $query->where('district', $district);
        }

        $bloodRequests = $query->with('recipient')
            ->orderBy('urgency_level', 'desc')
            ->orderBy('needed_at', 'asc')
            ->paginate(15);

        $stats = [
            'total' => $bloodRequests->total(),
            'critical' => BloodRequest::where('blood_group', $bloodGroup)
                ->where('urgency_level', 'critical')
                ->where('status', 'approved')
                ->where('needed_at', '>', now())
                ->count(),
        ];

        return view('donor.blood-requests.available', compact('bloodRequests', 'stats'));
    }

    public function show(BloodRequest $bloodRequest)
    {
        $user = Auth::user();

        if (!$user->donorProfile) {
            return redirect()->route('donor.profile.create')
                ->with('error', 'You need a donor profile to view requests.');
        }

        $hasResponded = DonorResponse::where('blood_request_id', $bloodRequest->id)
            ->where('donor_id', $user->id)
            ->exists();

        $bloodRequest->load('recipient');

        return view('donor.blood-requests.show', compact('bloodRequest', 'hasResponded'));
    }

    public function respond(BloodRequest $bloodRequest)
    {
        $user = Auth::user();

        $this->authorize('respond', [$bloodRequest]);

        $response = $this->donorResponseService->respond($bloodRequest, $user);

        return redirect()->back()
            ->with('success', 'You have shown interest in this blood request. The recipient will contact you.');
    }

    public function myResponses(Request $request)
    {
        $user = Auth::user();

        $responses = DonorResponse::where('donor_id', $user->id)
            ->with(['bloodRequest.recipient'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => $responses->total(),
            'interested' => DonorResponse::where('donor_id', $user->id)
                ->where('response_status', 'interested')->count(),
            'contacted' => DonorResponse::where('donor_id', $user->id)
                ->where('response_status', 'contacted')->count(),
            'donated' => DonorResponse::where('donor_id', $user->id)
                ->where('response_status', 'donated')->count(),
        ];

        return view('donor.responses.index', compact('responses', 'stats'));
    }

    public function updateStatus(Request $request, DonorResponse $donorResponse)
    {
        $request->validate([
            'status' => 'required|in:contacted,donated'
        ]);

        $this->authorize('update', $donorResponse);

        $updatedResponse = $this->donorResponseService->updateStatus(
            $donorResponse,
            $request->status
        );

        return redirect()->back()
            ->with('success', 'Response status updated.');
    }
}
