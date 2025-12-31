<?php

namespace App\Http\Controllers\Recipient;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBloodRequestRequest;
use App\Models\BloodRequest;
use App\Services\BloodRequestService;
use App\Services\DonorMatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BloodRequestController extends Controller
{
    public function __construct(
        private BloodRequestService $bloodRequestService,
        private DonorMatchingService $donorMatchingService
    ) {}

    public function index(Request $request)
    {
        $user = Auth::user();

        $status = $request->query('status', 'all');

        $query = BloodRequest::where('recipient_id', $user->id)
            ->with(['donorResponses.donor'])
            ->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $bloodRequests = $query->paginate(15);

        $stats = [
            'total' => BloodRequest::where('recipient_id', $user->id)->count(),
            'pending' => BloodRequest::where('recipient_id', $user->id)
                ->where('status', 'pending')->count(),
            'approved' => BloodRequest::where('recipient_id', $user->id)
                ->where('status', 'approved')->count(),
            'fulfilled' => BloodRequest::where('recipient_id', $user->id)
                ->where('status', 'fulfilled')->count(),
        ];

        return view('recipient.blood-requests.index', compact('bloodRequests', 'stats'));
    }

    public function create()
    {
        $user = Auth::user();
        $profile = $user->recipientProfile;

        return view('recipient.blood-requests.create', compact('profile'));
    }

    public function store(StoreBloodRequestRequest $request)
    {
        $user = Auth::user();

        $this->authorize('create', BloodRequest::class);

        $bloodRequest = $this->bloodRequestService->create(
            $request->validated(),
            $user
        );

        return redirect()->route('recipient.blood-requests.index')
            ->with('success', 'Blood request created successfully. Awaiting admin approval.');
    }

    public function show(BloodRequest $bloodRequest)
    {
        $this->authorize('view', $bloodRequest);

        $bloodRequest->load([
            'donorResponses.donor.donorProfile',
            'logs.changedBy',
            'approvedBy'
        ]);

        $eligibleDonors = $this->donorMatchingService->match($bloodRequest);

        return view('recipient.blood-requests.show', compact('bloodRequest', 'eligibleDonors'));
    }

    public function edit(BloodRequest $bloodRequest)
    {
        $this->authorize('update', $bloodRequest);

        if (!$bloodRequest->isPending()) {
            return redirect()->back()->with('error', 'Only pending requests can be updated.');
        }

        return view('recipient.blood-requests.edit', compact('bloodRequest'));
    }

    public function update(StoreBloodRequestRequest $request, BloodRequest $bloodRequest)
    {
        $this->authorize('update', $bloodRequest);

        if (!$bloodRequest->isPending()) {
            return redirect()->back()->with('error', 'Only pending requests can be updated.');
        }

        $bloodRequest->update($request->validated());

        return redirect()->route('recipient.blood-requests.index')
            ->with('success', 'Blood request updated successfully.');
    }

    public function getMatchingDonors(BloodRequest $bloodRequest)
    {
        $this->authorize('view', $bloodRequest);

        if (!$bloodRequest->isApproved()) {
            return redirect()->back()->with('error', 'Request is not approved yet.');
        }

        $eligibleDonors = $this->donorMatchingService->match($bloodRequest);

        return view('recipient.blood-requests.donors', compact('bloodRequest', 'eligibleDonors'));
    }

    public function cancel(BloodRequest $bloodRequest)
    {
        $this->authorize('cancel', $bloodRequest);

        if (!$bloodRequest->isPending() && !$bloodRequest->isApproved()) {
            return redirect()->back()->with('error', 'Only pending or approved requests can be cancelled.');
        }

        $bloodRequest->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Blood request cancelled.');
    }
}
