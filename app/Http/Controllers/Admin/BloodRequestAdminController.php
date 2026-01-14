<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Services\BloodRequestService;
use Illuminate\Http\Request;

class BloodRequestAdminController extends Controller
{
    public function __construct(
        private readonly BloodRequestService $bloodRequestService
    ) {}

    public function index(Request $request)
    {
        $query = BloodRequest::with('recipient')
            ->orderBy('needed_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('urgency')) {
            $query->where('urgency_level', $request->urgency);
        }

        $requests = $query->paginate(20);

        return view('admin.blood-requests.index', compact('requests'));
    }

    public function show(BloodRequest $bloodRequest)
    {
        $bloodRequest->load([
            'recipient',
            'approvedBy',
            'donorResponses.donor',
            'logs.changedBy',
        ]);

        return view('admin.blood-requests.show', compact('bloodRequest'));
    }

    public function approve(BloodRequest $bloodRequest)
    {
        $this->authorize('approve', $bloodRequest);

        $this->bloodRequestService->approve($bloodRequest, auth()->user());

        return back()->with('success', 'Blood request approved.');
    }

    public function fulfill(BloodRequest $bloodRequest)
    {
        $this->authorize('fulfill', $bloodRequest);

        $this->bloodRequestService->fulfill($bloodRequest);

        return back()->with('success', 'Blood request marked as fulfilled.');
    }

    public function cancel(BloodRequest $bloodRequest)
    {
        $this->authorize('cancel', $bloodRequest);

        $this->bloodRequestService->cancel($bloodRequest);

        return back()->with('success', 'Blood request cancelled.');
    }

    public function destroy(BloodRequest $bloodRequest)
    {
        if (! $bloodRequest->isPending()) {
            return back()->with('error', 'Only pending requests can be deleted.');
        }

        $bloodRequest->delete();

        return redirect()
            ->route('admin.blood-requests.index')
            ->with('success', 'Blood request deleted.');
    }

    public function statistics()
    {
        return response()->json([
            'pending'   => BloodRequest::where('status', BloodRequest::STATUS_PENDING)->count(),
            'approved'  => BloodRequest::where('status', BloodRequest::STATUS_APPROVED)->count(),
            'fulfilled' => BloodRequest::where('status', BloodRequest::STATUS_FULFILLED)->count(),
            'cancelled' => BloodRequest::where('status', BloodRequest::STATUS_CANCELLED)->count(),
        ]);
    }
}
