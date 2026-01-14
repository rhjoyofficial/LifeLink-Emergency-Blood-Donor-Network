@extends('layouts.app')

@section('title', 'My Donor Profile')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">My Donor Profile</h2>
                    <p class="text-gray-600 mt-1">Manage your donor information and availability</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('donor.profile.edit') }}"
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-medium">
                        <i class="fas fa-edit mr-2"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Profile Status -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Basic Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Blood Group</label>
                                <div class="mt-1">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        {{ $profile->blood_group }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">District</label>
                                <p class="mt-1 text-gray-900">{{ $profile->district }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Upazila</label>
                                <p class="mt-1 text-gray-900">{{ $profile->upazila }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Last Donation Date</label>
                                <p class="mt-1 text-gray-900">
                                    @if ($profile->last_donation_date)
                                        {{ $profile->last_donation_date->format('F j, Y') }}
                                    @else
                                        <span class="text-gray-500">Never donated</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Profile Created</label>
                                <p class="mt-1 text-gray-900">{{ $profile->created_at->format('F j, Y') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Last Updated</label>
                                <p class="mt-1 text-gray-900">{{ $profile->updated_at->format('F j, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Availability Status -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Availability Status</h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">Profile Approval Status</p>
                                <p class="text-sm text-gray-600">
                                    @if ($profile->approved_by_admin)
                                        Your profile is approved by admin and you can respond to requests
                                    @else
                                        Your profile is pending admin approval
                                    @endif
                                </p>
                            </div>
                            <div>
                                @if ($profile->approved_by_admin)
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-2"></i> Approved
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-2"></i> Pending
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">Donation Availability</p>
                                <p class="text-sm text-gray-600">
                                    @if ($profile->is_available)
                                        You are currently available to donate blood
                                    @else
                                        You are currently unavailable to donate
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span
                                    class="px-3 py-1 rounded-full text-sm font-medium 
                                @if ($profile->is_available) bg-primary-light text-primary
                                @else bg-gray-100 text-gray-800 @endif">
                                    @if ($profile->is_available)
                                        <i class="fas fa-heart mr-2"></i> Available
                                    @else
                                        <i class="fas fa-times mr-2"></i> Unavailable
                                    @endif
                                </span>

                                <form action="{{ route('donor.availability.toggle') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="px-4 py-2 text-sm font-medium rounded-lg 
                                            @if ($profile->is_available) bg-red-100 text-red-700 hover:bg-red-200
                                            @else bg-green-100 text-green-700 hover:bg-green-200 @endif">
                                        {{ $profile->is_available ? 'Mark Unavailable' : 'Mark Available' }}
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">Donation Eligibility</p>
                                <p class="text-sm text-gray-600">
                                    @if ($profile->canDonate())
                                        You are eligible to donate blood
                                    @else
                                        You are not eligible to donate yet. Minimum 90 days required between donations.
                                    @endif
                                </p>
                            </div>
                            <div>
                                @if ($profile->canDonate())
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-2"></i> Eligible
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-clock mr-2"></i> Not Eligible
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats & Actions -->
            <div class="space-y-6">
                <!-- Donation Statistics -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Donation Stats</h3>

                    @php
                        $user = auth()->user();
                        $responses = $user->donorResponses;
                        $totalResponses = $responses->count();
                        $donatedCount = $responses->where('response_status', 'donated')->count();
                    @endphp

                    <div class="space-y-4">
                        <div class="text-center p-4 bg-primary-light rounded-lg">
                            <p class="text-3xl font-bold text-primary">{{ $donatedCount }}</p>
                            <p class="text-sm text-gray-700 mt-2">Successful Donations</p>
                        </div>

                        <div class="text-center p-4 bg-accent-light rounded-lg">
                            <p class="text-3xl font-bold text-accent">{{ $totalResponses }}</p>
                            <p class="text-sm text-gray-700 mt-2">Total Responses</p>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Donation Success Rate</span>
                                <span class="font-medium text-gray-900">
                                    {{ $totalResponses > 0 ? round(($donatedCount / $totalResponses) * 100) : 0 }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-primary h-2 rounded-full"
                                    style="width: {{ $totalResponses > 0 ? ($donatedCount / $totalResponses) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('donor.responses.index') }}"
                            class="w-full flex items-center justify-center px-4 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                            <i class="fas fa-history mr-2"></i> View Response History
                        </a>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>

                    <div class="space-y-3">
                        <a href="{{ route('donor.blood-requests.available') }}"
                            class="flex items-center p-3 bg-primary-light rounded-lg hover:bg-primary hover:text-white transition-colors group">
                            <div
                                class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3 group-hover:bg-primary-light">
                                <i class="fas fa-search text-primary group-hover:text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 group-hover:text-white">Find Requests</p>
                                <p class="text-xs text-gray-600 group-hover:text-white/80">Browse available requests</p>
                            </div>
                            <i class="fas fa-arrow-right text-gray-400 group-hover:text-white"></i>
                        </a>

                        <a href="{{ route('donor.profile.edit') }}"
                            class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-600 hover:text-white transition-colors group">
                            <div
                                class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-100">
                                <i class="fas fa-edit text-blue-600 group-hover:text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 group-hover:text-white">Update Profile</p>
                                <p class="text-xs text-gray-600 group-hover:text-white/80">Edit your information</p>
                            </div>
                            <i class="fas fa-arrow-right text-gray-400 group-hover:text-white"></i>
                        </a>

                        @if ($profile->last_donation_date)
                            <div class="p-3 bg-green-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-calendar-check text-green-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">Last Donation</p>
                                        <p class="text-xs text-gray-600">
                                            {{ $profile->last_donation_date->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Eligibility Information -->
        @if (!$profile->canDonate() && $profile->last_donation_date)
            <div class="bg-white rounded-xl shadow-sm border border-yellow-200 p-6">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mt-1 mr-4"></i>
                    <div class="flex-1">
                        <h4 class="font-semibold text-yellow-800">Eligibility Notice</h4>
                        <p class="text-yellow-700 mt-2">
                            You are not currently eligible to donate blood. According to our records, your last donation was
                            on
                            <span class="font-medium">{{ $profile->last_donation_date->format('F j, Y') }}</span>.
                        </p>
                        <p class="text-yellow-700 mt-2">
                            For health and safety reasons, donors must wait at least 90 days between donations. You will be
                            eligible to donate again after
                            <span
                                class="font-medium">{{ $profile->last_donation_date->addDays(90)->format('F j, Y') }}</span>.
                        </p>
                        <div class="mt-4">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-yellow-800">Days since last donation</span>
                                <span
                                    class="font-medium text-yellow-900">{{ now()->diffInDays($profile->last_donation_date) }}
                                    days</span>
                            </div>
                            <div class="w-full bg-yellow-200 rounded-full h-2 mt-2">
                                @php
                                    $daysSince = now()->diffInDays($profile->last_donation_date);
                                    $percentage = min(($daysSince / 90) * 100, 100);
                                @endphp
                                <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            <div class="text-right text-xs text-yellow-700 mt-1">
                                {{ 90 - $daysSince }} days remaining
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
