@extends('layouts.app')

@section('title', 'Blood Requests Management')

@section('header')
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Blood Requests</h1>
                <p class="mt-1 text-sm text-gray-600">Review and manage all blood requests</p>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Filters -->
                <div class="flex space-x-2">
                    <select id="statusFilter"
                        class="block w-40 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 rounded-md">
                        <option value="all">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="fulfilled">Fulfilled</option>
                        <option value="cancelled">Cancelled</option>
                    </select>

                    <select id="urgencyFilter"
                        class="block w-40 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 rounded-md">
                        <option value="all">All Urgency</option>
                        <option value="critical">Critical</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>

                <button onclick="applyFilters()"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:p-6">
            <!-- Stats Bar -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-gray-500">Total</div>
                    <div class="text-2xl font-semibold text-gray-900">{{ $stats['total'] }}</div>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-yellow-700">Pending</div>
                    <div class="text-2xl font-semibold text-yellow-700">{{ $stats['pending'] }}</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-green-700">Approved</div>
                    <div class="text-2xl font-semibold text-green-700">{{ $stats['approved'] }}</div>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-blue-700">Fulfilled</div>
                    <div class="text-2xl font-semibold text-blue-700">{{ $stats['fulfilled'] }}</div>
                </div>
            </div>

            <!-- Requests Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Request ID
                            </th>
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
                                Urgency & Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date & Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($requests as $request)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">#{{ $request->id }}</div>
                                    <div class="text-sm text-gray-500">{{ $request->created_at->format('M d, Y') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <span
                                            class="blood-group-badge {{ strtolower(str_replace('+', 'p', $request->blood_group)) }} mr-3">
                                            {{ $request->blood_group }}
                                        </span>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $request->patient_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $request->bags_required }} bag(s)</div>
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
                                    <div class="space-y-1">
                                        <span class="urgency-badge {{ $request->urgency_level }}">
                                            {{ ucfirst($request->urgency_level) }}
                                        </span>
                                        <span class="status-badge {{ $request->status }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="text-gray-500 mb-2">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $request->needed_at->format('M d, h:i A') }}
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.blood-requests.show', $request) }}"
                                            class="text-red-600 hover:text-red-900" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if ($request->status === 'pending')
                                            <button onclick="approveRequest({{ $request->id }})"
                                                class="text-green-600 hover:text-green-900" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>

                                            <button onclick="cancelRequest({{ $request->id }})"
                                                class="text-red-600 hover:text-red-900" title="Cancel">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif

                                        @if ($request->status === 'approved')
                                            <button onclick="fulfillRequest({{ $request->id }})"
                                                class="text-blue-600 hover:text-blue-900" title="Mark as Fulfilled">
                                                <i class="fas fa-heart"></i>
                                            </button>

                                            <button onclick="cancelRequest({{ $request->id }})"
                                                class="text-red-600 hover:text-red-900" title="Cancel">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                    <p class="text-lg">No blood requests found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($requests->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function applyFilters() {
            const status = document.getElementById('statusFilter').value;
            const urgency = document.getElementById('urgencyFilter').value;

            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search);

            if (status !== 'all') params.set('status', status);
            else params.delete('status');

            if (urgency !== 'all') params.set('urgency', urgency);
            else params.delete('urgency');

            params.set('page', '1'); // Reset to first page
            window.location.href = url.pathname + '?' + params.toString();
        }

        function approveRequest(requestId) {
            if (confirm('Are you sure you want to approve this blood request?')) {
                fetch(`/admin/blood-requests/${requestId}/approve`, {
                        method: 'PATCH',
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

        function fulfillRequest(requestId) {
            if (confirm('Mark this request as fulfilled?')) {
                fetch(`/admin/blood-requests/${requestId}/fulfill`, {
                        method: 'PATCH',
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

        function cancelRequest(requestId) {
            if (confirm('Are you sure you want to cancel this blood request?')) {
                fetch(`/admin/blood-requests/${requestId}/cancel`, {
                        method: 'PATCH',
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

        // Initialize filters from URL
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status') || 'all';
            const urgency = urlParams.get('urgency') || 'all';

            document.getElementById('statusFilter').value = status;
            document.getElementById('urgencyFilter').value = urgency;
        });
    </script>
@endpush
