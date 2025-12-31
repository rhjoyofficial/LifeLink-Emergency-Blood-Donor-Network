<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\User;
use App\Services\BloodRequestService;
use Illuminate\Http\Request;

class BloodRequestAdminController extends Controller
{
    public function __construct(
        private BloodRequestService $bloodRequestService
    ) {}

    public function index(Request $request)
    {
        $status = $request->query('status');
        $urgency = $request->query('urgency');

        $query = BloodRequest::with(['recipient', 'approvedBy'])
            ->orderBy('needed_at', 'asc');

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($urgency && $urgency !== 'all') {
            $query->where('urgency_level', $urgency);
        }

        $requests = $query->paginate(20);

        $stats = [
            'total' => BloodRequest::count(),
            'pending' => BloodRequest::where('status', 'pending')->count(),
            'approved' => BloodRequest::where('status', 'approved')->count(),
            'fulfilled' => BloodRequest::where('status', 'fulfilled')->count(),
        ];

        return view('admin.blood-requests.index', compact('requests', 'stats'));
    }

    public function show(BloodRequest $bloodRequest)
    {
        $bloodRequest->load([
            'recipient',
            'approvedBy',
            'donorResponses.donor.donorProfile',
            'logs.changedBy'
        ]);

        return view('admin.blood-requests.show', compact('bloodRequest'));
    }

    public function approve(BloodRequest $bloodRequest)
    {
        $this->authorize('approve', $bloodRequest);

        $admin = auth()->user();
        $approvedRequest = $this->bloodRequestService->approve($bloodRequest, $admin);

        return redirect()->back()->with('success', 'Blood request approved successfully.');
    }

    public function fulfill(BloodRequest $bloodRequest)
    {
        $this->authorize('fulfill', $bloodRequest);

        $fulfilledRequest = $this->bloodRequestService->fulfill($bloodRequest);

        return redirect()->back()->with('success', 'Blood request marked as fulfilled.');
    }

    public function cancel(BloodRequest $bloodRequest)
    {
        $this->authorize('cancel', $bloodRequest);

        $cancelledRequest = $this->bloodRequestService->cancel($bloodRequest);

        return redirect()->back()->with('success', 'Blood request cancelled.');
    }

    public function destroy(BloodRequest $bloodRequest)
    {
        if (!$bloodRequest->isPending()) {
            return redirect()->back()->with('error', 'Only pending requests can be deleted.');
        }

        $bloodRequest->delete();

        return redirect()->route('admin.blood-requests.index')->with('success', 'Blood request deleted successfully.');
    }

    public function statistics()
    {
        $totalRequests = BloodRequest::count();
        $pendingRequests = BloodRequest::where('status', 'pending')->count();
        $approvedRequests = BloodRequest::where('status', 'approved')->count();
        $fulfilledRequests = BloodRequest::where('status', 'fulfilled')->count();

        $urgentRequests = BloodRequest::where('urgency_level', 'critical')
            ->where('status', 'approved')
            ->count();

        $todayRequests = BloodRequest::whereDate('created_at', today())->count();

        return response()->json([
            'data' => [
                'total_requests' => $totalRequests,
                'pending_requests' => $pendingRequests,
                'approved_requests' => $approvedRequests,
                'fulfilled_requests' => $fulfilledRequests,
                'urgent_requests' => $urgentRequests,
                'today_requests' => $todayRequests,
            ]
        ]);
    }
}
