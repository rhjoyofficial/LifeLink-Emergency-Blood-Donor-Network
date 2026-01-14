@extends('layouts.app')

@section('title', 'Blood Request Details')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center">
                        <a href="{{ route('donor.blood-requests.available') }}"
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
                    @if ($bloodRequest->canDonorRespond() && auth()->user()->donorProfile && auth()->user()->donorProfile->canDonate())
                        <form action="{{ route('donor.blood-requests.respond', $bloodRequest) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-medium">
                                <i class="fas fa-hand-paper mr-2"></i> I Can Help
                            </button>
                        </form>
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
                                <label class="text-sm font-medium text-gray-500">Contact Phone</label>
                                <p class="mt-1 text-gray-900">{{ $bloodRequest->contact_phone }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Request Status</label>
                                <div class="mt-1">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full 
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
                        </div>
                    </div>
                </div>

                <!-- Your Eligibility -->
                @if (auth()->user()->donorProfile)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Eligibility</h3>

                        <div class="space-y-4">
                            @if (!auth()->user()->donorProfile->canDonate())
                                <div class="flex items-center p-4 bg-red-50 border border-red-200 rounded-lg">
                                    <i class="fas fa-exclamation-triangle text-red-600 text-xl mr-4"></i>
                                    <div class="flex-1">
                                        <p class="font-medium text-red-800">Not Eligible to Donate</p>
                                        <p class="text-sm text-red-700 mt-1">
                                            @if (auth()->user()->donorProfile->last_donation_date)
                                                Your last donation was
                                                {{ auth()->user()->donorProfile->last_donation_date->format('F j, Y') }}.
                                                You need to wait 90 days between donations.
                                            @else
                                                Please update your donation history in your profile.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if (!$bloodRequest->canDonorRespond())
                                <div class="flex items-center p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <i class="fas fa-info-circle text-yellow-600 text-xl mr-4"></i>
                                    <div class="flex-1">
                                        <p class="font-medium text-yellow-800">Request Not Active</p>
                                        <p class="text-sm text-yellow-700 mt-1">
                                            @if ($bloodRequest->status == 'fulfilled')
                                                This request has already been fulfilled.
                                            @elseif($bloodRequest->status == 'cancelled')
                                                This request has been cancelled.
                                            @elseif($bloodRequest->needed_at->isPast())
                                                The deadline for this request has passed.
                                            @else
                                                This request is not currently accepting responses.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if (auth()->user()->donorProfile->canDonate() && $bloodRequest->canDonorRespond())
                                <div class="flex items-center p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <i class="fas fa-check-circle text-green-600 text-xl mr-4"></i>
                                    <div class="flex-1">
                                        <p class="font-medium text-green-800">You Can Help!</p>
                                        <p class="text-sm text-green-700 mt-1">
                                            You are eligible to donate and this request is active.
                                            Click "I Can Help" to express your interest.
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Action & Information -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>

                    <div class="space-y-3">
                        @if ($bloodRequest->canDonorRespond() && auth()->user()->donorProfile && auth()->user()->donorProfile->canDonate())
                            <form action="{{ route('donor.blood-requests.respond', $bloodRequest) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center justify-center px-4 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-medium">
                                    <i class="fas fa-hand-paper mr-2"></i> I Can Help
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('donor.blood-requests.available') }}"
                            class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i> Back to List
                        </a>
                    </div>

                    <!-- Request Timeline -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="font-medium text-gray-900 mb-3">Request Timeline</h4>
                        <div class="space-y-3">
                            <div class="flex items-center text-sm">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                <div>
                                    <p class="text-gray-900">Request Created</p>
                                    <p class="text-gray-600">{{ $bloodRequest->created_at->format('M d, h:i A') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center text-sm">
                                <div
                                    class="w-2 h-2 
                                @if ($bloodRequest->status == 'approved') bg-green-500
                                @elseif($bloodRequest->status == 'pending') bg-yellow-500
                                @else bg-gray-400 @endif 
                                rounded-full mr-3">
                                </div>
                                <div>
                                    <p class="text-gray-900">Current Status</p>
                                    <p class="text-gray-600">{{ ucfirst($bloodRequest->status) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center text-sm">
                                <div
                                    class="w-2 h-2 
                                @if ($bloodRequest->needed_at->isFuture()) bg-blue-500
                                @else bg-red-500 @endif 
                                rounded-full mr-3">
                                </div>
                                <div>
                                    <p class="text-gray-900">Blood Needed By</p>
                                    <p class="text-gray-600">{{ $bloodRequest->needed_at->format('M d, h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Your Profile Match -->
                @if (auth()->user()->donorProfile)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Profile Match</h3>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Blood Group</span>
                                <div class="flex items-center">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full 
                                    @if (auth()->user()->donorProfile->blood_group == $bloodRequest->blood_group) bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                        {{ auth()->user()->donorProfile->blood_group }}
                                    </span>
                                    <span class="mx-2">→</span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                        {{ $bloodRequest->blood_group }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Location</span>
                                <div class="flex items-center">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full 
                                    @if (auth()->user()->donorProfile->district == $bloodRequest->district) bg-green-100 text-green-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                        {{ auth()->user()->donorProfile->district }}
                                    </span>
                                    <span class="mx-2">→</span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                        {{ $bloodRequest->district }}
                                    </span>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-100">
                                <div class="text-center">
                                    <p class="text-sm text-gray-600">Match Score</p>
                                    <p class="text-2xl font-bold text-primary">
                                        @php
                                            $score = 0;
                                            if (
                                                auth()->user()->donorProfile->blood_group == $bloodRequest->blood_group
                                            ) {
                                                $score += 50;
                                            }
                                            if (auth()->user()->donorProfile->district == $bloodRequest->district) {
                                                $score += 30;
                                            }
                                            if (auth()->user()->donorProfile->upazila == $bloodRequest->upazila) {
                                                $score += 20;
                                            }
                                        @endphp
                                        {{ $score }}%
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Safety Guidelines -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Safety Guidelines</h3>

                    <div class="space-y-3">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-600 mt-1 mr-3"></i>
                            <p class="text-sm text-gray-700">Ensure you are healthy and well-rested</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-600 mt-1 mr-3"></i>
                            <p class="text-sm text-gray-700">Eat a healthy meal before donation</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-600 mt-1 mr-3"></i>
                            <p class="text-sm text-gray-700">Drink plenty of water</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-600 mt-1 mr-3"></i>
                            <p class="text-sm text-gray-700">Bring valid ID to the hospital</p>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            By responding, you agree to follow all safety protocols and hospital guidelines.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
