<?php

namespace App\Http\Controllers\Recipient;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBloodRequestRequest;
use App\Models\BloodRequest;
use App\Services\BloodRequestService;
use App\Services\DonorMatchingService;
use Illuminate\Support\Facades\Auth;

class BloodRequestController extends Controller
{
    public function __construct(
        private readonly BloodRequestService $bloodRequestService,
        private readonly DonorMatchingService $donorMatchingService
    ) {}

    public function index()
    {
        $requests = BloodRequest::where('recipient_id', Auth::id())
            ->with('donorResponses.donor')
            ->latest()
            ->paginate(15);

        return view('recipient.blood-requests.index', compact('requests'));
    }

    public function create()
    {
        return view('recipient.blood-requests.create');
    }

    public function store(StoreBloodRequestRequest $request)
    {
        $this->authorize('create', BloodRequest::class);

        $this->bloodRequestService->create(
            $request->validated(),
            Auth::user()
        );

        return redirect()
            ->route('recipient.blood-requests.index')
            ->with('success', 'Blood request submitted. Awaiting admin approval.');
    }

    public function show(BloodRequest $bloodRequest)
    {
        $this->authorize('view', $bloodRequest);

        $bloodRequest->load([
            'donorResponses.donor.donorProfile',
            'logs.changedBy',
            'approvedBy',
        ]);

        $eligibleDonors = $bloodRequest->isApproved()
            ? $this->donorMatchingService->match($bloodRequest)
            : collect();

        return view(
            'recipient.blood-requests.show',
            compact('bloodRequest', 'eligibleDonors')
        );
    }

    public function edit(BloodRequest $bloodRequest)
    {
        $this->authorize('update', $bloodRequest);

        return view('recipient.blood-requests.edit', compact('bloodRequest'));
    }

    public function update(
        StoreBloodRequestRequest $request,
        BloodRequest $bloodRequest
    ) {
        $this->authorize('update', $bloodRequest);

        $bloodRequest->update($request->validated());

        return redirect()
            ->route('recipient.blood-requests.index')
            ->with('success', 'Blood request updated.');
    }

    public function cancel(BloodRequest $bloodRequest)
    {
        $this->authorize('cancel', $bloodRequest);

        $this->bloodRequestService->cancel($bloodRequest);

        return back()->with('success', 'Blood request cancelled.');
    }

    public function getMatchingDonors(BloodRequest $bloodRequest)
    {
        $this->authorize('view', $bloodRequest);

        if (! $bloodRequest->isApproved()) {
            return back()->with('error', 'Request is not approved yet.');
        }

        $donors = $this->donorMatchingService->match($bloodRequest);

        return view(
            'recipient.blood-requests.donors',
            compact('bloodRequest', 'donors')
        );
    }
}
