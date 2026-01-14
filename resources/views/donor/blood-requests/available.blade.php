@extends('layouts.app')

@section('title', 'Available Blood Requests')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Available Blood Requests</h2>
                    <p class="text-gray-600 mt-1">Blood requests matching your blood group and location</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Matching Requests</p>
                        <p class="text-xl font-bold text-primary">{{ $requests->total() }}</p>
                    </div>
                </div>
            </div>

            <!-- Eligibility Status -->
            @if (auth()->user()->donorProfile && !auth()->user()->donorProfile->canDonate())
                <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-600 text-xl mr-3"></i>
                        <div class="flex-1">
                            <p class="font-medium text-red-800">Not Currently Eligible to Donate</p>
                            <p class="text-sm text-red-700 mt-1">
                                You are not eligible to donate yet. Minimum 90 days required between donations.
                                @if (auth()->user()->donorProfile->last_donation_date)
                                    Your last donation was on
                                    {{ auth()->user()->donorProfile->last_donation_date->format('F j, Y') }}.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Available Requests -->
        <div class="space-y-6">
            @forelse($requests as $request)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
                            <!-- Request Details -->
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="flex items-center">
                                            <span
                                                class="px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full mr-3">
                                                {{ $request->blood_group }}
                                            </span>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $request->patient_name }}
                                            </h3>
                                        </div>
                                        <p class="text-gray-600 mt-2">
                                            <i class="fas fa-hospital mr-2"></i>{{ $request->hospital_name }}
                                        </p>
                                        <p class="text-gray-600 mt-1">
                                            <i class="fas fa-map-marker-alt mr-2"></i>{{ $request->district }},
                                            {{ $request->upazila }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span
                                            class="px-3 py-1 text-sm font-medium rounded-full 
                                        @if ($request->urgency_level == 'critical') bg-red-100 text-red-800
                                        @elseif($request->urgency_level == 'high') bg-orange-100 text-orange-800
                                        @elseif($request->urgency_level == 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($request->urgency_level) }} Urgency
                                        </span>
                                        <p class="text-sm text-gray-600 mt-2">
                                            <i class="fas fa-clock mr-1"></i> Needed by:
                                            {{ $request->needed_at->format('M d, h:i A') }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Additional Details -->
                                <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Bags Required</p>
                                        <p class="font-medium text-gray-900">{{ $request->bags_required }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Contact Phone</p>
                                        <p class="font-medium text-gray-900">{{ $request->contact_phone }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Request Status</p>
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Time Remaining</p>
                                        <p
                                            class="font-medium 
                                        @if ($request->needed_at->diffInHours(now()) < 24) text-red-600
                                        @elseif($request->needed_at->diffInHours(now()) < 48) text-orange-600
                                        @else text-green-600 @endif">
                                            {{ $request->needed_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Donor Responses -->
                                @if ($request->donorResponses->count() > 0)
                                    <div class="mt-6 pt-6 border-t border-gray-100">
                                        <div class="flex items-center">
                                            <div class="flex -space-x-2 mr-3">
                                                @foreach ($request->donorResponses->take(3) as $response)
                                                    <div
                                                        class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-xs font-medium border-2 border-white">
                                                        {{ substr($response->donor->name, 0, 1) }}
                                                    </div>
                                                @endforeach
                                            </div>
                                            <p class="text-sm text-gray-600">
                                                {{ $request->donorResponses->count() }}
                                                donor{{ $request->donorResponses->count() > 1 ? 's' : '' }} have responded
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="lg:w-64 space-y-3">
                                <a href="{{ route('donor.blood-requests.show', $request) }}"
                                    class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-eye mr-2"></i> View Details
                                </a>

                                @if ($request->canDonorRespond() && auth()->user()->donorProfile && auth()->user()->donorProfile->canDonate())
                                    <form action="{{ route('donor.blood-requests.respond', $request) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full flex items-center justify-center px-4 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-medium">
                                            <i class="fas fa-hand-paper mr-2"></i> I Can Help
                                        </button>
                                    </form>
                                @else
                                    @if (!auth()->user()->donorProfile || !auth()->user()->donorProfile->canDonate())
                                        <button disabled
                                            class="w-full flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                            <i class="fas fa-clock mr-2"></i> Not Eligible
                                        </button>
                                    @elseif(!$request->canDonorRespond())
                                        <button disabled
                                            class="w-full flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                            <i class="fas fa-times mr-2"></i> Request Closed
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <div class="max-w-md mx-auto">
                        <i class="fas fa-heart text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No Available Requests</h3>
                        <p class="text-gray-600 mb-6">
                            There are currently no blood requests matching your blood group and location.
                            Check back soon or update your profile location.
                        </p>
                        <a href="{{ route('donor.profile.edit') }}"
                            class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                            <i class="fas fa-edit mr-2"></i> Update Profile
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($requests->hasPages())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                {{ $requests->links() }}
            </div>
        @endif

        <!-- Auto-refresh Notice -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-sync-alt text-primary mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Auto-refresh Enabled</p>
                        <p class="text-xs text-gray-600">This page refreshes every 30 seconds to show new requests</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-600">Last updated: <span
                            id="lastUpdated">{{ now()->format('h:i:s A') }}</span></p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Update last updated time
            function updateLastUpdated() {
                const now = new Date();
                const timeElement = document.getElementById('lastUpdated');
                const options = {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                };
                timeElement.textContent = now.toLocaleTimeString('en-US', options);
            }

            // Auto-refresh every 30 seconds
            setInterval(() => {
                location.reload();
            }, 30000);

            // Update time every second
            setInterval(updateLastUpdated, 1000);
            updateLastUpdated();
        </script>
    @endpush
@endsection
