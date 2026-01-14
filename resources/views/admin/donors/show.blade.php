@extends('layouts.app')

@section('title', 'Donor Details')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Donor Details</h2>
                    <p class="text-gray-600 mt-1">Complete information about this donor</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.donors.index') }}"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Back to List
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Donor Profile -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Donor Information Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Donor Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Personal Details -->
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Full Name</label>
                                <p class="mt-1 text-gray-900 font-medium">{{ $donorProfile->user->name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Email Address</label>
                                <p class="mt-1 text-gray-900">{{ $donorProfile->user->email }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Phone Number</label>
                                <p class="mt-1 text-gray-900">{{ $donorProfile->user->phone }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Account Status</label>
                                <div class="mt-1">
                                    @if ($donorProfile->user->is_verified)
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
                        </div>

                        <!-- Medical & Location -->
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Blood Group</label>
                                <div class="mt-1">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        {{ $donorProfile->blood_group }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Location</label>
                                <p class="mt-1 text-gray-900">{{ $donorProfile->district }}, {{ $donorProfile->upazila }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Last Donation Date</label>
                                <p class="mt-1 text-gray-900">
                                    @if ($donorProfile->last_donation_date)
                                        {{ $donorProfile->last_donation_date->format('F j, Y') }}
                                    @else
                                        Never donated
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Eligibility Status</label>
                                <div class="mt-1">
                                    @if ($donorProfile->canDonate())
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Eligible to donate
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-clock mr-1"></i> Not eligible (90-day rule)
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status & Actions -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="space-y-2">
                                <div class="flex items-center space-x-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Approval Status</label>
                                        <div class="mt-1">
                                            @if ($donorProfile->approved_by_admin)
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-2"></i> Approved
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-2"></i> Pending Approval
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Availability</label>
                                        <div class="mt-1">
                                            @if ($donorProfile->is_available)
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-light text-primary">
                                                    <i class="fas fa-heart mr-2"></i> Available
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                    <i class="fas fa-times mr-2"></i> Unavailable
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if ($donorProfile->approved_by_admin)
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-calendar-alt mr-2"></i> Profile created on
                                        {{ $donorProfile->created_at->format('F j, Y') }}
                                    </p>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center space-x-3">
                                @if (!$donorProfile->approved_by_admin)
                                    <form action="{{ route('admin.donors.approve', $donorProfile) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium"
                                            onclick="return confirm('Approve this donor profile?')">
                                            <i class="fas fa-check mr-2"></i> Approve Donor
                                        </button>
                                    </form>
                                @endif

                                @if ($donorProfile->approved_by_admin)
                                    <form action="{{ route('admin.donors.reject', $donorProfile) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors font-medium"
                                            onclick="return confirm('Reject this donor profile? They will become unavailable.')">
                                            <i class="fas fa-times mr-2"></i> Reject
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.donors.destroy', $donorProfile) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium"
                                        onclick="return confirm('Are you sure you want to delete this donor? This will also delete their user account.')">
                                        <i class="fas fa-trash mr-2"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Donor Responses -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Responses</h3>
                        <a href="{{ route('admin.donors.responses', $donorProfile) }}"
                            class="text-sm text-primary hover:text-primary-dark font-medium">
                            View All Responses <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>

                    @if ($donorProfile->user->donorResponses->count() > 0)
                        <div class="space-y-4">
                            @foreach ($donorProfile->user->donorResponses->take(3) as $response)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-primary transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-medium text-gray-900">
                                                {{ $response->bloodRequest->patient_name }}</p>
                                            <div class="flex items-center mt-2 space-x-4">
                                                <span
                                                    class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                                    {{ $response->bloodRequest->blood_group }}
                                                </span>
                                                <span class="text-sm text-gray-600">
                                                    <i
                                                        class="fas fa-hospital mr-1"></i>{{ $response->bloodRequest->hospital_name }}
                                                </span>
                                                <span class="text-sm text-gray-600">
                                                    <i
                                                        class="fas fa-map-marker-alt mr-1"></i>{{ $response->bloodRequest->district }}
                                                </span>
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
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $response->created_at->format('M d, Y') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-xs text-gray-500">Request Status</p>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ ucfirst($response->bloodRequest->status) }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Urgency Level</p>
                                                <p
                                                    class="text-sm font-medium 
                                                @if ($response->bloodRequest->urgency_level == 'critical') text-red-600
                                                @elseif($response->bloodRequest->urgency_level == 'high') text-orange-600
                                                @elseif($response->bloodRequest->urgency_level == 'medium') text-yellow-600
                                                @else text-green-600 @endif">
                                                    {{ ucfirst($response->bloodRequest->urgency_level) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-hand-paper text-3xl mb-3"></i>
                            <p>No responses yet</p>
                            <p class="text-sm mt-1">This donor hasn't responded to any blood requests</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Stats & Quick Info -->
            <div class="space-y-6">
                <!-- Donation Statistics -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Donation Statistics</h3>

                    <div class="space-y-4">
                        @php
                            $responses = $donorProfile->user->donorResponses;
                            $totalResponses = $responses->count();
                            $donatedCount = $responses->where('response_status', 'donated')->count();
                            $interestedCount = $responses->where('response_status', 'interested')->count();
                            $contactedCount = $responses->where('response_status', 'contacted')->count();
                        @endphp

                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Responses</span>
                                <span class="font-medium text-gray-900">{{ $totalResponses }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: 100%"></div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-green-600">Successful Donations</span>
                                <span class="font-medium text-gray-900">{{ $donatedCount }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full"
                                    style="width: {{ $totalResponses > 0 ? ($donatedCount / $totalResponses) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-yellow-600">Interested Responses</span>
                                <span class="font-medium text-gray-900">{{ $interestedCount }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-600 h-2 rounded-full"
                                    style="width: {{ $totalResponses > 0 ? ($interestedCount / $totalResponses) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-blue-600">Contacted Status</span>
                                <span class="font-medium text-gray-900">{{ $contactedCount }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full"
                                    style="width: {{ $totalResponses > 0 ? ($contactedCount / $totalResponses) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Donation Success Rate</p>
                            <p class="text-2xl font-bold text-primary">
                                {{ $totalResponses > 0 ? round(($donatedCount / $totalResponses) * 100) : 0 }}%
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>

                    <div class="space-y-3">
                        <a href="{{ route('admin.donors.responses', $donorProfile) }}"
                            class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-history text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">View All Responses</p>
                                <p class="text-xs text-gray-600">{{ $totalResponses }} total responses</p>
                            </div>
                            <i class="fas fa-arrow-right text-gray-400"></i>
                        </a>

                        <a href="mailto:{{ $donorProfile->user->email }}"
                            class="flex items-center p-3 bg-primary-light rounded-lg hover:bg-primary hover:text-white transition-colors group">
                            <div
                                class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3 group-hover:bg-primary-light">
                                <i class="fas fa-envelope text-primary group-hover:text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 group-hover:text-white">Send Email</p>
                                <p class="text-xs text-gray-600 group-hover:text-white/80">Contact the donor</p>
                            </div>
                            <i class="fas fa-arrow-right text-gray-400 group-hover:text-white"></i>
                        </a>

                        <a href="tel:{{ $donorProfile->user->phone }}"
                            class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-600 hover:text-white transition-colors group">
                            <div
                                class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-100">
                                <i class="fas fa-phone text-green-600 group-hover:text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 group-hover:text-white">Call Donor</p>
                                <p class="text-xs text-gray-600 group-hover:text-white/80">
                                    {{ $donorProfile->user->phone }}</p>
                            </div>
                            <i class="fas fa-arrow-right text-gray-400 group-hover:text-white"></i>
                        </a>
                    </div>
                </div>

                <!-- Profile Timeline -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Timeline</h3>

                    <div class="space-y-4">
                        <div class="border-l-2 border-primary pl-4 py-2">
                            <p class="font-medium text-gray-900">Profile Created</p>
                            <p class="text-sm text-gray-600">{{ $donorProfile->created_at->format('F j, Y h:i A') }}</p>
                        </div>

                        @if ($donorProfile->last_donation_date)
                            <div class="border-l-2 border-green-500 pl-4 py-2">
                                <p class="font-medium text-gray-900">Last Donation</p>
                                <p class="text-sm text-gray-600">{{ $donorProfile->last_donation_date->format('F j, Y') }}
                                </p>
                            </div>
                        @endif

                        @if ($donorProfile->approved_by_admin)
                            <div class="border-l-2 border-green-500 pl-4 py-2">
                                <p class="font-medium text-gray-900">Profile Approved</p>
                                <p class="text-sm text-gray-600">Approved by admin</p>
                            </div>
                        @endif

                        <div class="border-l-2 border-gray-300 pl-4 py-2">
                            <p class="font-medium text-gray-900">Last Updated</p>
                            <p class="text-sm text-gray-600">{{ $donorProfile->updated_at->format('F j, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
