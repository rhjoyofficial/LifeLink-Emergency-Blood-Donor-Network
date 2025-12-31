@extends('layouts.app')

@section('title', 'Blood Request Details')

@section('header')
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Blood Request Details</h1>
                <p class="mt-1 text-sm text-gray-600">Request #{{ $request->id }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.blood-requests.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to List
                </a>

                @if ($request->status === 'pending')
                    <button onclick="approveRequest()"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-check mr-2"></i>
                        Approve Request
                    </button>
                @endif

                @if ($request->status === 'approved')
                    <button onclick="fulfillRequest()"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-heart mr-2"></i>
                        Mark as Fulfilled
                    </button>
                @endif

                @if ($request->status !== 'fulfilled' && $request->status !== 'cancelled')
                    <button onclick="cancelRequest()"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-times mr-2"></i>
                        Cancel Request
                    </button>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Request Details -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Patient Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Patient Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Patient Name</label>
                                    <p class="mt-1 text-lg font-medium text-gray-900">{{ $request->patient_name }}</p>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Blood Group</label>
                                        <span
                                            class="blood-group-badge {{ strtolower(str_replace('+', 'p', $request->blood_group)) }} mt-1">
                                            {{ $request->blood_group }}
                                        </span>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Bags Required</label>
                                        <p class="mt-1 text-lg font-medium text-gray-900">{{ $request->bags_required }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Urgency Level</label>
                                        <span class="urgency-badge {{ $request->urgency_level }} mt-1">
                                            {{ ucfirst($request->urgency_level) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hospital Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Hospital Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Hospital Name</label>
                                    <p class="mt-1 text-lg font-medium text-gray-900">{{ $request->hospital_name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Location</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                                        {{ $request->district }}, {{ $request->upazila }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Contact Phone</label>
                                    <p class="mt-1 text-lg font-medium text-gray-900">
                                        <i class="fas fa-phone text-green-500 mr-2"></i>
                                        {{ $request->contact_phone }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Request Timeline -->
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Request Timeline</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Created On</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ $request->created_at->format('F d, Y h:i A') }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Blood Needed By</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ $request->needed_at->format('F d, Y h:i A') }}</p>
                                    </div>
                                </div>

                                <!-- Status History -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-2">Status History</label>
                                    <div class="space-y-2">
                                        @foreach ($request->logs as $log)
                                            <div class="flex items-center text-sm">
                                                <span
                                                    class="w-32 text-gray-500">{{ $log->created_at->format('M d, h:i A') }}</span>
                                                <span class="mx-4">
                                                    <i class="fas fa-arrow-right text-gray-400"></i>
                                                </span>
                                                <span
                                                    class="status-badge {{ $log->old_status }}">{{ ucfirst($log->old_status) }}</span>
                                                <span class="mx-2">
                                                    <i class="fas fa-long-arrow-alt-right text-gray-400"></i>
                                                </span>
                                                <span
                                                    class="status-badge {{ $log->new_status }}">{{ ucfirst($log->new_status) }}</span>
                                                <span class="ml-4 text-gray-500">by {{ $log->changedBy->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Requester Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Requester Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Recipient Name</label>
                                    <p class="mt-1 text-lg font-medium text-gray-900">{{ $request->recipient->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Email</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $request->recipient->email }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Phone</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $request->recipient->phone }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Approval Information -->
                        @if ($request->status !== 'pending')
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Approval Information</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Approved By</label>
                                        <p class="mt-1 text-lg font-medium text-gray-900">
                                            {{ $request->approvedBy->name ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Approved On</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            @if ($request->approved_at)
                                                {{ $request->approved_at->format('F d, Y h:i A') }}
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Donor Responses & Actions -->
        <div>
            <!-- Current Status -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Current Status</h3>
                    <div class="text-center">
                        <span class="status-badge {{ $request->status }} text-lg">
                            {{ ucfirst($request->status) }}
                        </span>
                        <div class="mt-4 text-sm text-gray-500">
                            @if ($request->status === 'pending')
                                <p>Awaiting admin approval</p>
                            @elseif($request->status === 'approved')
                                <p>Active - Donors can respond</p>
                            @elseif($request->status === 'fulfilled')
                                <p>Request completed successfully</p>
                            @elseif($request->status === 'cancelled')
                                <p>Request cancelled</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Donor Responses -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Donor Responses</h3>
                    <div class="space-y-4">
                        @forelse($request->donorResponses as $response)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                                            <i class="fas fa-user text-red-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $response->donor->name }}</p>
                                            <p class="text-xs text-gray-500">
                                                {{ $response->donor->donorProfile->blood_group ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <span class="status-badge {{ $response->response_status }}">
                                        {{ ucfirst($response->response_status) }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $response->created_at->format('M d, h:i A') }}
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-gray-500">
                                <i class="fas fa-users text-3xl mb-2 text-gray-300"></i>
                                <p>No donor responses yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function approveRequest() {
            if (confirm('Are you sure you want to approve this blood request?')) {
                fetch('{{ route('admin.blood-requests.approve', $request) }}', {
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

        function fulfillRequest() {
            if (confirm('Mark this request as fulfilled?')) {
                fetch('{{ route('admin.blood-requests.fulfill', $request) }}', {
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

        function cancelRequest() {
            if (confirm('Are you sure you want to cancel this blood request?')) {
                fetch('{{ route('admin.blood-requests.cancel', $request) }}', {
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
    </script>
@endpush
