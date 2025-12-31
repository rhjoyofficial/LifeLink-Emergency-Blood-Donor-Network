@extends('layouts.app')

@section('title', 'Donor Dashboard')

@section('header')
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Welcome, {{ auth()->user()->name }}</h1>
                <p class="mt-1 text-sm text-gray-600">
                    @if ($profile && $profile->approved_by_admin)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Verified Donor
                        </span>
                        @if ($profile->is_available)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                <i class="fas fa-heart mr-1"></i>
                                Available to Donate
                            </span>
                        @endif
                    @elseif($profile)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>
                            Awaiting Verification
                        </span>
                    @endif
                </p>
            </div>
            <div>
                @if ($profile && $profile->approved_by_admin)
                    <a href="{{ route('donor.blood-requests.available') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-search mr-2"></i>
                        Find Requests
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('content')
    @if (!$profile)
        <!-- No Profile Warning -->
        <div class="mb-6">
            <div class="rounded-md bg-yellow-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400 h-5 w-5"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Profile Setup Required</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>You haven't created your donor profile yet. Complete your profile to start helping save
                                lives.</p>
                            <div class="mt-3">
                                <a href="{{ route('donor.profile.create') }}"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    <i class="fas fa-user-plus mr-2"></i>
                                    Create Donor Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif(!$profile->approved_by_admin)
        <!-- Pending Approval -->
        <div class="mb-6">
            <div class="rounded-md bg-blue-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400 h-5 w-5"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Profile Under Review</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>Your donor profile is awaiting admin approval. You'll be able to respond to blood requests
                                once approved.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($profile)
        <!-- Eligibility Check -->
        @if ($stats['last_donation'] && !$stats['eligible_to_donate'])
            <div class="mb-6">
                <div class="rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-times text-red-400 h-5 w-5"></i>
                        </div>
                        <div class="ml-3">
                            @php
                                $nextEligible = $stats['last_donation']->copy()->addDays(90);
                                $daysRemaining = now()->diffInDays($nextEligible, false);
                            @endphp
                            <h3 class="text-sm font-medium text-red-800">Donation Eligibility</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>Your last donation was on {{ $stats['last_donation']->format('F d, Y') }}.
                                    You can donate again in {{ $daysRemaining }} days (after
                                    {{ $nextEligible->format('F d, Y') }}).</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Donation Eligibility -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 {{ $stats['eligible_to_donate'] ? 'bg-green-100' : 'bg-red-100' }} rounded-md p-3">
                            <i
                                class="fas fa-heart {{ $stats['eligible_to_donate'] ? 'text-green-600' : 'text-red-600' }} h-6 w-6"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Donation Status
                                </dt>
                                <dd class="text-lg font-semibold text-gray-900">
                                    @if ($stats['eligible_to_donate'])
                                        Eligible to Donate
                                    @else
                                        Not Eligible
                                    @endif
                                </dd>
                                @if ($stats['last_donation'])
                                    <dd class="text-sm text-gray-500">
                                        Last: {{ $stats['last_donation']->format('M d, Y') }}
                                    </dd>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Requests -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                            <i class="fas fa-tint text-red-600 h-6 w-6"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Available Requests
                                </dt>
                                <dd class="text-lg font-semibold text-gray-900">
                                    {{ $stats['available_requests'] ?? '0' }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                    @if ($stats['available_requests'] > 0)
                        <div class="mt-4">
                            <a href="{{ route('donor.blood-requests.available') }}"
                                class="text-sm font-medium text-red-600 hover:text-red-500">
                                View requests
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Donations Made -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                            <i class="fas fa-award text-purple-600 h-6 w-6"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Donations Made
                                </dt>
                                <dd class="text-lg font-semibold text-gray-900">
                                    {{ $stats['donated_count'] ?? '0' }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Responses -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                            <i class="fas fa-hand-paper text-blue-600 h-6 w-6"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Total Responses
                                </dt>
                                <dd class="text-lg font-semibold text-gray-900">
                                    {{ $stats['total_responses'] ?? '0' }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($profile->approved_by_admin)
            <!-- Profile Summary -->
            <div class="bg-white shadow rounded-lg mb-8">
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="flex items-center mb-4">
                                <span
                                    class="blood-group-badge {{ strtolower(str_replace('+', 'p', $profile->blood_group)) }} text-lg">
                                    {{ $profile->blood_group }}
                                </span>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">Blood Group</h4>
                                    <p class="text-sm text-gray-600">Your blood type</p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Location</h4>
                                <p class="text-lg text-gray-900">
                                    <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                                    {{ $profile->district }}, {{ $profile->upazila }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Availability Status</h4>
                                <div class="flex items-center">
                                    @if ($profile->is_available)
                                        <div class="flex items-center text-green-600">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            <span class="font-medium">Available to donate</span>
                                        </div>
                                    @else
                                        <div class="flex items-center text-yellow-600">
                                            <i class="fas fa-clock mr-2"></i>
                                            <span class="font-medium">Currently unavailable</span>
                                        </div>
                                    @endif
                                    <form action="{{ route('donor.availability.toggle') }}" method="POST"
                                        class="ml-4">
                                        @csrf
                                        <button type="submit"
                                            class="text-sm font-medium text-red-600 hover:text-red-500">
                                            Change
                                        </button>
                                    </form>
                                </div>
                            </div>

                            @if ($profile->last_donation_date)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-1">Last Donation</h4>
                                    <p class="text-lg text-gray-900">
                                        <i class="fas fa-calendar-alt text-red-500 mr-2"></i>
                                        {{ $profile->last_donation_date->format('F d, Y') }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        @php
                                            $daysSince = $profile->last_donation_date->diffInDays(now());
                                            $daysToWait = 90 - $daysSince;
                                        @endphp
                                        @if ($daysSince >= 90)
                                            <span class="text-green-600">You can donate now</span>
                                        @else
                                            Eligible in {{ $daysToWait }} days
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('donor.profile.edit') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Available Requests -->
            <div class="bg-white shadow rounded-lg mb-8">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Recent Blood Requests</h3>
                        <a href="{{ route('donor.blood-requests.available') }}"
                            class="text-sm font-medium text-red-600 hover:text-red-500">
                            View all
                        </a>
                    </div>
                </div>
                <div class="flow-root">
                    <ul role="list" class="divide-y divide-gray-200">
                        @forelse($recentRequests as $request)
                            <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center mb-2">
                                            <span
                                                class="blood-group-badge {{ strtolower(str_replace('+', 'p', $request->blood_group)) }}">
                                                {{ $request->blood_group }}
                                            </span>
                                            <span class="ml-2 text-sm font-medium text-gray-900">
                                                {{ $request->patient_name }}
                                            </span>
                                            <span class="ml-3 text-sm text-gray-500">
                                                <i class="fas fa-hospital mr-1"></i>
                                                {{ $request->hospital_name }}
                                            </span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-500">
                                            <span class="mr-4">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                {{ $request->district }}, {{ $request->upazila }}
                                            </span>
                                            <span class="mr-4">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ $request->needed_at->format('M d, h:i A') }}
                                            </span>
                                            <span class="mr-4">
                                                <i class="fas fa-tint mr-1"></i>
                                                {{ $request->bags_required }} bag(s)
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="urgency-badge {{ $request->urgency_level }}">
                                            {{ ucfirst($request->urgency_level) }}
                                        </span>
                                        @if (!$myResponses->contains('blood_request_id', $request->id))
                                            <form action="{{ route('donor.blood-requests.respond', $request) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    <i class="fas fa-hand-paper mr-1"></i>
                                                    Respond
                                                </button>
                                            </form>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-check mr-1"></i>
                                                Responded
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-heartbeat text-3xl mb-2 text-gray-300"></i>
                                <p class="text-lg">No matching blood requests at the moment.</p>
                                <p class="text-sm text-gray-400 mt-1">Check back later for new requests</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        @endif

        <!-- My Recent Responses -->
        @if ($myResponses->count() > 0)
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">My Recent Responses</h3>
                        <a href="{{ route('donor.responses.index') }}"
                            class="text-sm font-medium text-red-600 hover:text-red-500">
                            View all
                        </a>
                    </div>
                </div>
                <div class="flow-root">
                    <ul role="list" class="divide-y divide-gray-200">
                        @foreach ($myResponses as $response)
                            <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center mb-2">
                                            <span
                                                class="blood-group-badge {{ strtolower(str_replace('+', 'p', $response->bloodRequest->blood_group)) }}">
                                                {{ $response->bloodRequest->blood_group }}
                                            </span>
                                            <span class="ml-2 text-sm font-medium text-gray-900">
                                                {{ $response->bloodRequest->patient_name }}
                                            </span>
                                            <span class="ml-3 text-sm text-gray-500">
                                                <i class="fas fa-user mr-1"></i>
                                                {{ $response->bloodRequest->recipient->name }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            Responded on {{ $response->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="status-badge {{ $response->response_status }}">
                                            {{ ucfirst($response->response_status) }}
                                        </span>
                                        <a href="{{ route('recipient.blood-requests.show', $response->bloodRequest) }}"
                                            class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    @endif
@endsection
