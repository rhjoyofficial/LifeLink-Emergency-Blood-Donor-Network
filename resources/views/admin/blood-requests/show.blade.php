@extends('layouts.app')

@section('title', 'Blood Request Details')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Blood Request Details</h2>
                    <p class="text-gray-600 mt-1">Complete information about this blood request</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.blood-requests.index') }}"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Back to List
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Request Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Request Information Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Request Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Patient Details -->
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Patient Name</label>
                                <p class="mt-1 text-gray-900 font-medium">{{ $bloodRequest->patient_name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Blood Group</label>
                                <div class="mt-1">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        {{ $bloodRequest->blood_group }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Bags Required</label>
                                <p class="mt-1 text-gray-900">{{ $bloodRequest->bags_required }} bag(s)</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Urgency Level</label>
                                <div class="mt-1">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full 
                                    @if ($bloodRequest->urgency_level == 'critical') bg-red-100 text-red-800
                                    @elseif($bloodRequest->urgency_level == 'high') bg-orange-100 text-orange-800
                                    @elseif($bloodRequest->urgency_level == 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($bloodRequest->urgency_level) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Hospital & Location -->
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Hospital Name</label>
                                <p class="mt-1 text-gray-900">{{ $bloodRequest->hospital_name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Location</label>
                                <p class="mt-1 text-gray-900">{{ $bloodRequest->district }}, {{ $bloodRequest->upazila }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Contact Phone</label>
                                <p class="mt-1 text-gray-900">{{ $bloodRequest->contact_phone }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Needed By</label>
                                <p class="mt-1 text-gray-900">{{ $bloodRequest->needed_at->format('F j, Y h:i A') }}</p>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-clock mr-1"></i> {{ $bloodRequest->needed_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Status Section -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Current Status</label>
                                <div class="mt-1">
                                    <span
                                        class="px-3 py-1 text-sm font-medium rounded-full 
                                    @if ($bloodRequest->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($bloodRequest->status == 'approved') bg-green-100 text-green-800
                                    @elseif($bloodRequest->status == 'fulfilled') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($bloodRequest->status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center space-x-3">
                                @if ($bloodRequest->status == 'pending')
                                    <form action="{{ route('admin.blood-requests.approve', $bloodRequest) }}"
                                        method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium"
                                            onclick="return confirm('Approve this blood request?')">
                                            <i class="fas fa-check mr-2"></i> Approve Request
                                        </button>
                                    </form>
                                @endif

                                @if ($bloodRequest->status == 'approved')
                                    <form action="{{ route('admin.blood-requests.fulfill', $bloodRequest) }}"
                                        method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                                            onclick="return confirm('Mark this request as fulfilled?')">
                                            <i class="fas fa-check-double mr-2"></i> Mark as Fulfilled
                                        </button>
                                    </form>
                                @endif

                                @if ($bloodRequest->status == 'pending' || $bloodRequest->status == 'approved')
                                    <form action="{{ route('admin.blood-requests.cancel', $bloodRequest) }}"
                                        method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium"
                                            onclick="return confirm('Cancel this blood request?')">
                                            <i class="fas fa-times mr-2"></i> Cancel Request
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Donor Responses -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Donor Responses</h3>
                        <span class="text-sm text-gray-600">{{ $bloodRequest->donorResponses->count() }} donor(s)
                            responded</span>
                    </div>

                    @if ($bloodRequest->donorResponses->count() > 0)
                        <div class="space-y-4">
                            @foreach ($bloodRequest->donorResponses as $response)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-primary transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-medium">
                                                {{ substr($response->donor->name, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <p class="font-medium text-gray-900">{{ $response->donor->name }}</p>
                                                <p class="text-sm text-gray-600">{{ $response->donor->phone }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <span
                                                class="px-3 py-1 text-sm font-medium rounded-full 
                                            @if ($response->response_status == 'interested') bg-yellow-100 text-yellow-800
                                            @elseif($response->response_status == 'contacted') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800 @endif">
                                                {{ ucfirst($response->response_status) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-sm text-gray-600">Blood Group</p>
                                                <p class="font-medium text-gray-900">
                                                    {{ $response->donor->donorProfile->blood_group }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600">Location</p>
                                                <p class="font-medium text-gray-900">
                                                    {{ $response->donor->donorProfile->district }},
                                                    {{ $response->donor->donorProfile->upazila }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <p class="text-sm text-gray-600">Last Donation</p>
                                            <p class="font-medium text-gray-900">
                                                @if ($response->donor->donorProfile->last_donation_date)
                                                    {{ $response->donor->donorProfile->last_donation_date->format('M d, Y') }}
                                                @else
                                                    Never donated
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-users-slash text-3xl mb-3"></i>
                            <p>No donors have responded yet</p>
                            <p class="text-sm mt-1">Donors will be notified and can respond to this request</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Recipient & Activity -->
            <div class="space-y-6">
                <!-- Recipient Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recipient Information</h3>

                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div
                                class="w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center font-medium">
                                {{ substr($bloodRequest->recipient->name, 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">{{ $bloodRequest->recipient->name }}</p>
                                <p class="text-sm text-gray-600">{{ $bloodRequest->recipient->email }}</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Phone Number</label>
                                <p class="mt-1 text-gray-900">{{ $bloodRequest->recipient->phone }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Account Status</label>
                                <div class="mt-1">
                                    @if ($bloodRequest->recipient->is_verified)
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Verified
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i> Not Verified
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Total Requests</label>
                                <p class="mt-1 text-gray-900">{{ $bloodRequest->recipient->bloodRequests->count() }}
                                    request(s)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Log -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Activity Log</h3>

                    <div class="space-y-4">
                        @forelse($bloodRequest->logs as $log)
                            <div class="border-l-2 border-primary pl-4 py-2">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-900">Status Changed</p>
                                        <p class="text-sm text-gray-600">
                                            {{ $log->old_status }} → {{ $log->new_status }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600">{{ $log->created_at->format('M d, h:i A') }}</p>
                                        <p class="text-xs text-gray-500">by {{ $log->changedBy->name }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-gray-500">
                                <i class="fas fa-history text-xl mb-2"></i>
                                <p>No activity recorded</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Request Created -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="text-sm text-gray-600">
                            <p>Request Created: {{ $bloodRequest->created_at->format('F j, Y h:i A') }}</p>
                            <p>Last Updated: {{ $bloodRequest->updated_at->format('F j, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
