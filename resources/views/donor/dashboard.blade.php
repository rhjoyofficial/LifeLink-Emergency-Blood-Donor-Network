@extends('layouts.app')

@section('title', 'Donor Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}! 🩸</h2>
                    <p class="text-gray-600 mt-2">Ready to save lives today? Here's your donor dashboard.</p>
                </div>
                @if ($profile)
                    <div class="text-right">
                        <div class="inline-flex items-center px-4 py-2 bg-primary-light rounded-lg">
                            <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                            <span class="font-medium text-primary">Available to Donate</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">Blood Group: <span
                                class="font-bold text-primary">{{ $profile->blood_group }}</span></p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Donation Eligibility -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Donation Status</p>
                        <p
                            class="text-3xl font-bold mt-2 {{ $stats['eligible_to_donate'] ? 'text-green-600' : 'text-red-600' }}">
                            {{ $stats['eligible_to_donate'] ? 'Eligible' : 'Not Eligible' }}
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 {{ $stats['eligible_to_donate'] ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center">
                        <i
                            class="fas {{ $stats['eligible_to_donate'] ? 'fa-check-circle text-green-600' : 'fa-times-circle text-red-600' }} text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    @if ($stats['last_donation'])
                        <p class="text-xs text-gray-600">
                            <i class="fas fa-calendar mr-1"></i> Last donated:
                            {{ $stats['last_donation']->format('M d, Y') }}
                        </p>
                    @else
                        <p class="text-xs text-green-600">
                            <i class="fas fa-heart mr-1"></i> Never donated - Ready to start!
                        </p>
                    @endif
                </div>
            </div>

            <!-- Total Responses -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Responses</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_responses'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-hand-paper text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-600">
                        <i class="fas fa-clock mr-1"></i> Pending: {{ $stats['pending_responses'] }}
                        <span class="mx-2">•</span>
                        <i class="fas fa-check mr-1"></i> Donated: {{ $stats['donated_count'] }}
                    </p>
                </div>
            </div>

            <!-- Location Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Your Location</p>
                        <p class="text-lg font-bold text-gray-900 mt-2">{{ $profile->district }}</p>
                        <p class="text-sm text-gray-600">{{ $profile->upazila }}</p>
                    </div>
                    <div class="w-12 h-12 bg-primary-light rounded-lg flex items-center justify-center">
                        <i class="fas fa-map-marker-alt text-primary text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-600">
                        <i class="fas fa-search-location mr-1"></i> Matching requests in your area
                    </p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Quick Actions</p>
                        <p class="text-xl font-bold text-primary mt-2">Save Lives</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-heartbeat text-red-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('donor.blood-requests.available') }}"
                        class="text-sm font-medium text-primary hover:text-primary-dark">
                        Find Requests <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Matching Requests -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Available Requests -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Available Requests in Your Area</h3>
                    <a href="{{ route('donor.blood-requests.available') }}"
                        class="text-sm text-primary hover:text-primary-dark font-medium">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($recentRequests as $request)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-primary transition-colors">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="flex items-center">
                                        <span
                                            class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full mr-3">
                                            {{ $request->blood_group }}
                                        </span>
                                        <h4 class="font-medium text-gray-900">{{ $request->patient_name }}</h4>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-2">
                                        <i class="fas fa-hospital mr-2"></i>{{ $request->hospital_name }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-map-marker-alt mr-2"></i>{{ $request->district }},
                                        {{ $request->upazila }}
                                    </p>
                                    <div class="flex items-center mt-3 space-x-4">
                                        <span
                                            class="text-xs px-2 py-1 rounded-full 
                                        @if ($request->urgency_level == 'critical') bg-red-100 text-red-800
                                        @elseif($request->urgency_level == 'high') bg-orange-100 text-orange-800
                                        @elseif($request->urgency_level == 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($request->urgency_level) }} Urgency
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            <i class="fas fa-clock mr-1"></i> {{ $request->needed_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <a href="{{ route('donor.blood-requests.show', $request) }}"
                                        class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors text-sm">
                                        <i class="fas fa-eye mr-2"></i> View Details
                                    </a>
                                    @if ($request->canDonorRespond())
                                        <form action="{{ route('donor.blood-requests.respond', $request) }}" method="POST"
                                            class="mt-2">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                                <i class="fas fa-hand-paper mr-2"></i> I Can Help
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-heart text-3xl mb-3"></i>
                            <p>No matching requests in your area right now</p>
                            <p class="text-sm mt-2">Check back soon or update your location!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Donor Profile & Actions -->
            <div class="space-y-6">
                <!-- Profile Status -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Profile Status</h3>

                    <div class="space-y-4">
                        @if ($profile->approved_by_admin)
                            <div class="flex items-center p-4 bg-green-50 border border-green-200 rounded-lg">
                                <i class="fas fa-check-circle text-green-600 text-xl mr-4"></i>
                                <div class="flex-1">
                                    <p class="font-medium text-green-800">Profile Approved</p>
                                    <p class="text-sm text-green-600">You can respond to requests</p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <i class="fas fa-clock text-yellow-600 text-xl mr-4"></i>
                                <div class="flex-1">
                                    <p class="font-medium text-yellow-800">Awaiting Approval</p>
                                    <p class="text-sm text-yellow-600">Your profile is under review</p>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">Availability Status</p>
                                <p class="text-sm text-gray-600">
                                    @if ($profile->is_available)
                                        <span class="text-green-600">Currently available to donate</span>
                                    @else
                                        <span class="text-red-600">Currently unavailable</span>
                                    @endif
                                </p>
                            </div>
                            <form action="{{ route('donor.availability.toggle') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium rounded-lg {{ $profile->is_available ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                    {{ $profile->is_available ? 'Mark Unavailable' : 'Mark Available' }}
                                </button>
                            </form>
                        </div>

                        @if (!$stats['eligible_to_donate'])
                            <div class="flex items-center p-4 bg-red-50 border border-red-200 rounded-lg">
                                <i class="fas fa-exclamation-triangle text-red-600 text-xl mr-4"></i>
                                <div class="flex-1">
                                    <p class="font-medium text-red-800">Not Eligible to Donate</p>
                                    <p class="text-sm text-red-600">
                                        @if ($stats['last_donation'])
                                            Last donation was {{ $stats['last_donation']->format('M d, Y') }}. Need 90 days
                                            between donations.
                                        @else
                                            Please update your donation history.
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('donor.profile.edit') }}"
                            class="w-full flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fas fa-edit mr-2"></i> Edit Profile
                        </a>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Impact</h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-primary-light rounded-lg">
                            <p class="text-2xl font-bold text-primary">{{ $stats['donated_count'] }}</p>
                            <p class="text-sm text-gray-700 mt-1">Times Donated</p>
                        </div>
                        <div class="text-center p-4 bg-accent-light rounded-lg">
                            <p class="text-2xl font-bold text-accent">{{ $stats['total_responses'] }}</p>
                            <p class="text-sm text-gray-700 mt-1">Requests Helped</p>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('donor.responses.index') }}"
                            class="w-full flex items-center justify-center px-4 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                            <i class="fas fa-history mr-2"></i> View Response History
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto-refresh available requests every 30 seconds
            setInterval(() => {
                location.reload();
            }, 30000);
        </script>
    @endpush
@endsection
