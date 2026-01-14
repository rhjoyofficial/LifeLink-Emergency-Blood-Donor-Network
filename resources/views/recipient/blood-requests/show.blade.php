@extends('layouts.app')

@section('title', 'Blood Request Details')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center">
                        <a href="{{ route('recipient.blood-requests.index') }}"
                            class="text-primary hover:text-primary-dark mr-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Blood Request Details</h2>
                            <p class="text-gray-600 mt-1">Complete information about this blood request</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    @if ($bloodRequest->status == 'pending')
                        <a href="{{ route('recipient.blood-requests.edit', $bloodRequest) }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            <i class="fas fa-edit mr-2"></i> Edit Request
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Request Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Patient Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Patient Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Contact Phone</label>
                                <p class="mt-1 text-gray-900">{{ $bloodRequest->contact_phone }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Urgency Level</label>
                                <div class="mt-1">
                                    <span
                                        class="px-3 py-1 text-sm font-medium rounded-full 
                                    @if ($bloodRequest->urgency_level == 'critical') bg-red-100 text-red-800
                                    @elseif($bloodRequest->urgency_level == 'high') bg-orange-100 text-orange-800
                                    @elseif($bloodRequest->urgency_level == 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($bloodRequest->urgency_level) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Request Status</label>
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
                        </div>
                    </div>
                </div>

                <!-- Hospital & Location -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Hospital & Location</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Hospital Name</label>
                                <p class="mt-1 text-gray-900">{{ $bloodRequest->hospital_name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">District</label>
                                <p class="mt-1 text-gray-900">{{ $bloodRequest->district }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Upazila</label>
                                <p class="mt-1 text-gray-900">{{ $bloodRequest->upazila }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Blood Needed By</label>
                                <p class="mt-1 text-gray-900">{{ $bloodRequest->needed_at->format('F j, Y h:i A') }}</p>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-clock mr-1"></i> {{ $bloodRequest->needed_at->diffForHumans() }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Request Created</label>
                                <p class="mt-1 text-gray-900">{{ $bloodRequest->created_at->format('F j, Y h:i A') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Last Updated</label>
                                <p class="mt-1 text-gray-900">{{ $bloodRequest->updated_at->format('F j, Y h:i A') }}</p>
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
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-medium">
                                                {{ substr($response->donor->name, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <p class="font-medium text-gray-900">{{ $response->donor->name }}</p>
                                                <p class="text-sm text-gray-600">{{ $response->donor->phone }}</p>
                                                @if ($response->donor->donorProfile)
                                                    <div class="flex items-center mt-2 space-x-2">
                                                        <span
                                                            class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                                            {{ $response->donor->donorProfile->blood_group }}
                                                        </span>
                                                        <span class="text-xs text-gray-600">
                                                            {{ $response->donor->donorProfile->district }},
                                                            {{ $response->donor->donorProfile->upazila }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                class="px-3 py-1 text-sm font-medium rounded-full 
                                            @if ($response->response_status == 'interested') bg-yellow-100 text-yellow-800
                                            @elseif($response->response_status == 'contacted') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800 @endif">
                                                {{ ucfirst($response->response_status) }}
                                            </span>
                                            <p class="text-xs text-gray-600 mt-1">
                                                {{ $response->created_at->format('M d, h:i A') }}
                                            </p>
                                        </div>
                                    </div>

                                    @if ($response->donor->donorProfile && $response->donor->donorProfile->last_donation_date)
                                        <div class="mt-4 pt-4 border-t border-gray-100">
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <p class="text-xs text-gray-500">Last Donation</p>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $response->donor->donorProfile->last_donation_date->format('M d, Y') }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Eligibility</p>
                                                    <p
                                                        class="text-sm font-medium 
                                                    @if ($response->donor->donorProfile->canDonate()) text-green-600
                                                    @else text-red-600 @endif">
                                                        {{ $response->donor->donorProfile->canDonate() ? 'Eligible' : 'Not Eligible' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if ($bloodRequest->status == 'approved' && $eligibleDonors->count() > 0)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-900">Matching Donors Available</p>
                                        <p class="text-sm text-gray-600">{{ $eligibleDonors->count() }} donor(s) with
                                            matching blood group and location</p>
                                    </div>
                                    <a href="{{ route('recipient.blood-requests.donors', $bloodRequest) }}"
                                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-medium">
                                        <i class="fas fa-users mr-2"></i> View All Donors
                                    </a>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-users-slash text-3xl mb-3"></i>
                            <p>No donors have responded yet</p>
                            <p class="text-sm mt-1">
                                @if ($bloodRequest->status == 'pending')
                                    Donors will be able to respond once your request is approved by admin
                                @elseif($bloodRequest->status == 'approved')
                                    Donors with matching blood group will be notified
                                @else
                                    This request is no longer active
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Actions & Timeline -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>

                    <div class="space-y-3">
                        @if ($bloodRequest->status == 'pending')
                            <a href="{{ route('recipient.blood-requests.edit', $bloodRequest) }}"
                                class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                <i class="fas fa-edit mr-2"></i> Edit Request
                            </a>

                            <form action="{{ route('recipient.blood-requests.cancel', $bloodRequest) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium"
                                    onclick="return confirm('Are you sure you want to cancel this request?')">
                                    <i class="fas fa-times mr-2"></i> Cancel Request
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('recipient.blood-requests.index') }}"
                            class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i> Back to List
                        </a>

                        @if ($bloodRequest->status == 'approved' && $eligibleDonors->count() > 0)
                            <a href="{{ route('recipient.blood-requests.donors', $bloodRequest) }}"
                                class="w-full flex items-center justify-center px-4 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-medium">
                                <i class="fas fa-users mr-2"></i> View Matching Donors
                            </a>
                        @endif
                    </div>

                    <!-- Request Information -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="font-medium text-gray-900 mb-3">Request Information</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Request ID</span>
                                <span class="font-medium text-gray-900">#{{ $bloodRequest->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Created By</span>
                                <span class="font-medium text-gray-900">{{ auth()->user()->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Responses</span>
                                <span
                                    class="font-medium text-gray-900">{{ $bloodRequest->donorResponses->count() }}</span>
                            </div>
                            @if ($bloodRequest->approved_by_admin)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Approved By</span>
                                    <span
                                        class="font-medium text-gray-900">{{ $bloodRequest->approvedBy->name ?? 'Admin' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Activity Timeline -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Activity Timeline</h3>

                    <div class="space-y-4">
                        <div class="border-l-2 border-primary pl-4 py-2">
                            <p class="font-medium text-gray-900">Request Created</p>
                            <p class="text-sm text-gray-600">{{ $bloodRequest->created_at->format('M d, Y h:i A') }}</p>
                        </div>

                        @if ($bloodRequest->approved_by_admin)
                            <div class="border-l-2 border-green-500 pl-4 py-2">
                                <p class="font-medium text-gray-900">Request Approved</p>
                                <p class="text-sm text-gray-600">Approved by admin</p>
                            </div>
                        @endif

                        @foreach ($bloodRequest->logs as $log)
                            <div class="border-l-2 border-blue-500 pl-4 py-2">
                                <p class="font-medium text-gray-900">Status Changed</p>
                                <p class="text-sm text-gray-600">
                                    {{ ucfirst($log->old_status) }} → {{ ucfirst($log->new_status) }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $log->created_at->format('M d, h:i A') }} by {{ $log->changedBy->name }}
                                </p>
                            </div>
                        @endforeach

                        @if ($bloodRequest->donorResponses->count() > 0)
                            <div class="border-l-2 border-purple-500 pl-4 py-2">
                                <p class="font-medium text-gray-900">First Donor Response</p>
                                <p class="text-sm text-gray-600">
                                    {{ $bloodRequest->donorResponses->first()->created_at->format('M d, h:i A') }}
                                </p>
                            </div>
                        @endif

                        <div class="border-l-2 border-gray-300 pl-4 py-2">
                            <p class="font-medium text-gray-900">Blood Needed By</p>
                            <p class="text-sm text-gray-600">{{ $bloodRequest->needed_at->format('M d, Y h:i A') }}</p>
                            <p
                                class="text-xs 
                            @if ($bloodRequest->needed_at->isFuture()) text-blue-600
                            @else text-red-600 @endif">
                                @if ($bloodRequest->needed_at->isFuture())
                                    <i class="fas fa-clock mr-1"></i> {{ $bloodRequest->needed_at->diffForHumans() }}
                                @else
                                    <i class="fas fa-check mr-1"></i> Deadline passed
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Matching Donors -->
                @if ($eligibleDonors->count() > 0 && $bloodRequest->status == 'approved')
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Matching Donors</h3>

                        <div class="space-y-3">
                            @foreach ($eligibleDonors->take(3) as $donor)
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div
                                        class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-xs font-medium">
                                        {{ substr($donor->user->name, 0, 1) }}
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $donor->user->name }}</p>
                                        <p class="text-xs text-gray-600">{{ $donor->district }}, {{ $donor->upazila }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach

                            @if ($eligibleDonors->count() > 3)
                                <div class="text-center">
                                    <a href="{{ route('recipient.blood-requests.donors', $bloodRequest) }}"
                                        class="text-sm text-primary hover:text-primary-dark font-medium">
                                        + {{ $eligibleDonors->count() - 3 }} more donors
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="text-center">
                                <p class="text-sm text-gray-600">Total Matching Donors</p>
                                <p class="text-2xl font-bold text-primary">{{ $eligibleDonors->count() }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
