@extends('layouts.app')

@section('title', 'My Blood Requests')

@section('header')
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Blood Requests</h1>
                <p class="mt-1 text-sm text-gray-600">Track all your blood donation requests</p>
            </div>
            <div>
                <a href="{{ route('recipient.blood-requests.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-plus mr-2"></i>
                    New Request
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Stats Bar -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-sm font-medium text-gray-500">Total Requests</div>
            <div class="text-2xl font-semibold text-gray-900">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-sm font-medium text-gray-500">Pending</div>
            <div class="text-2xl font-semibold text-yellow-600">{{ $stats['pending'] }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-sm font-medium text-gray-500">Approved</div>
            <div class="text-2xl font-semibold text-green-600">{{ $stats['approved'] }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-sm font-medium text-gray-500">Fulfilled</div>
            <div class="text-2xl font-semibold text-blue-600">{{ $stats['fulfilled'] }}</div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <a href="{{ route('recipient.blood-requests.index') }}"
                    class="{{ !request()->has('status') ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    All Requests
                </a>
                <a href="{{ route('recipient.blood-requests.index', ['status' => 'pending']) }}"
                    class="{{ request()->get('status') === 'pending' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Pending
                </a>
                <a href="{{ route('recipient.blood-requests.index', ['status' => 'approved']) }}"
                    class="{{ request()->get('status') === 'approved' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Active
                </a>
                <a href="{{ route('recipient.blood-requests.index', ['status' => 'fulfilled']) }}"
                    class="{{ request()->get('status') === 'fulfilled' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Fulfilled
                </a>
            </nav>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:p-6">
            @if ($bloodRequests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Patient & Blood
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hospital & Location
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Timeline
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status & Donors
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($bloodRequests as $request)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span
                                                class="blood-group-badge {{ strtolower(str_replace('+', 'p', $request->blood_group)) }} mr-3">
                                                {{ $request->blood_group }}
                                            </span>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $request->patient_name }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $request->bags_required }} bag(s)
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $request->hospital_name }}</div>
                                        <div class="text-sm text-gray-500">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            {{ $request->district }}, {{ $request->upazila }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            <i class="far fa-calendar mr-1"></i>
                                            {{ $request->needed_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <i class="far fa-clock mr-1"></i>
                                            {{ $request->needed_at->format('h:i A') }}
                                        </div>
                                        <div class="text-xs text-gray-400 mt-1">
                                            {{ $request->created_at->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-2">
                                            <span class="status-badge {{ $request->status }}">
                                                {{ ucfirst($request->status) }}
                                            </span>

                                            @if ($request->urgency_level === 'critical')
                                                <span class="urgency-badge critical">
                                                    Critical
                                                </span>
                                            @endif

                                            @if ($request->donorResponses->count() > 0)
                                                <div class="mt-2">
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-user-check mr-1"></i>
                                                        {{ $request->donorResponses->count() }} donor(s)
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('recipient.blood-requests.show', $request) }}"
                                                class="text-red-600 hover:text-red-900" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if ($request->status === 'pending')
                                                <a href="{{ route('recipient.blood-requests.edit', $request) }}"
                                                    class="text-blue-600 hover:text-blue-900" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <button onclick="cancelRequest({{ $request->id }})"
                                                    class="text-red-600 hover:text-red-900" title="Cancel">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif

                                            @if ($request->status === 'approved')
                                                <button onclick="getDonors({{ $request->id }})"
                                                    class="text-green-600 hover:text-green-900" title="View Donors">
                                                    <i class="fas fa-users"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                    <p class="text-lg text-gray-500">No blood requests found</p>
                    <p class="text-sm text-gray-400 mt-1">
                        @if (request()->has('status'))
                            No {{ request()->get('status') }} requests
                        @else
                            <a href="{{ route('recipient.blood-requests.create') }}"
                                class="text-red-600 hover:text-red-500">Create your first request</a>
                        @endif
                    </p>
                </div>
            @endif

            <!-- Pagination -->
            @if ($bloodRequests->hasPages())
                <div class="mt-6">
                    {{ $bloodRequests->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function cancelRequest(requestId) {
            if (confirm('Are you sure you want to cancel this request?')) {
                fetch(`/recipient/blood-requests/${requestId}/cancel`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            alert(data.message);
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
            }
        }

        function getDonors(requestId) {
            window.location.href = `/recipient/blood-requests/${requestId}#donors`;
        }
    </script>
@endpush
