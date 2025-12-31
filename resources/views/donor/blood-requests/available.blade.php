@extends('layouts.app')

@section('title', 'Available Blood Requests')

@section('header')
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Available Blood Requests</h1>
                <p class="mt-1 text-sm text-gray-600">Patients in need of your blood type:
                    {{ auth()->user()->donorProfile->blood_group }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <select id="urgencyFilter"
                    class="block w-40 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 rounded-md">
                    <option value="all">All Urgency</option>
                    <option value="critical">Critical</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>

                <select id="locationFilter"
                    class="block w-40 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 rounded-md">
                    <option value="all">All Locations</option>
                    <option value="district">My District Only</option>
                    <option value="nearby">Nearby Areas</option>
                </select>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @if (!auth()->user()->donorProfile->approved_by_admin)
        <div class="mb-6">
            <div class="rounded-md bg-yellow-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-yellow-400 h-5 w-5"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Profile Pending Approval</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Your donor profile is awaiting admin approval. You'll be able to respond to blood requests
                                once approved.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (!auth()->user()->donorProfile->is_available)
        <div class="mb-6">
            <div class="rounded-md bg-blue-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-bed text-blue-400 h-5 w-5"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Currently Unavailable</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>You've marked yourself as unavailable to donate.
                                <button onclick="toggleAvailability()"
                                    class="text-blue-600 hover:text-blue-500 font-medium">
                                    Click here to make yourself available
                                </button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Eligibility Check -->
    @php
        $user = auth()->user();
        $canDonate = $user->donorProfile->canDonate();
        $lastDonation = $user->donorProfile->last_donation_date;
    @endphp

    @if ($lastDonation && !$canDonate)
        <div class="mb-6">
            <div class="rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-times text-red-400 h-5 w-5"></i>
                    </div>
                    <div class="ml-3">
                        @php
                            $nextEligible = $lastDonation->copy()->addDays(90);
                            $daysRemaining = now()->diffInDays($nextEligible, false);
                        @endphp
                        <h3 class="text-sm font-medium text-red-800">Donation Eligibility</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>Your last donation was on {{ $lastDonation->format('F d, Y') }}. You can donate again in
                                {{ $daysRemaining }} days (after {{ $nextEligible->format('F d, Y') }}).</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Requests Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($bloodRequests as $request)
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                <div class="p-6">
                    <!-- Request Header -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <div class="flex items-center">
                                <span
                                    class="blood-group-badge {{ strtolower(str_replace('+', 'p', $request->blood_group)) }} mr-2">
                                    {{ $request->blood_group }}
                                </span>
                                <span class="urgency-badge {{ $request->urgency_level }}">
                                    {{ ucfirst($request->urgency_level) }}
                                </span>
                            </div>
                            <h3 class="mt-2 text-lg font-semibold text-gray-900">{{ $request->patient_name }}</h3>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-500">{{ $request->needed_at->format('M d') }}</div>
                            <div class="text-xs text-gray-400">{{ $request->needed_at->format('h:i A') }}</div>
                        </div>
                    </div>

                    <!-- Request Details -->
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-hospital text-gray-400 mr-2 w-5"></i>
                            <span>{{ $request->hospital_name }}</span>
                        </div>

                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-2 w-5"></i>
                            <span>{{ $request->district }}, {{ $request->upazila }}</span>
                        </div>

                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-tint text-gray-400 mr-2 w-5"></i>
                            <span>{{ $request->bags_required }} bag(s) required</span>
                        </div>

                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-phone text-gray-400 mr-2 w-5"></i>
                            <span>{{ $request->contact_phone }}</span>
                        </div>

                        @if ($request->additional_notes)
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-sticky-note text-gray-400 mr-2 w-5"></i>
                                <span class="italic">"{{ Str::limit($request->additional_notes, 80) }}"</span>
                            </div>
                        @endif
                    </div>

                    <!-- Distance & Time -->
                    <div class="flex items-center justify-between mb-6 text-sm text-gray-500">
                        <div class="flex items-center">
                            <i class="fas fa-road mr-1"></i>
                            <span>In your district</span>
                        </div>
                        <div class="flex items-center">
                            <i class="far fa-clock mr-1"></i>
                            <span>Needed {{ $request->needed_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="border-t border-gray-100 pt-4">
                        @if ($request->hasResponded)
                            <div class="text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-check mr-1"></i>
                                    You've shown interest
                                </span>
                                <p class="mt-2 text-xs text-gray-500">The recipient will contact you</p>
                            </div>
                        @elseif($canDonate && $user->donorProfile->is_available && $user->donorProfile->approved_by_admin)
                            <button onclick="respondToRequest({{ $request->id }})"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="fas fa-hand-paper mr-2"></i>
                                I Can Help
                            </button>
                        @else
                            <div class="text-center">
                                <button disabled
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-400 cursor-not-allowed">
                                    <i class="fas fa-lock mr-2"></i>
                                    Not Available to Respond
                                </button>
                                <p class="mt-2 text-xs text-gray-500">
                                    @if (!$user->donorProfile->approved_by_admin)
                                        Profile pending approval
                                    @elseif(!$user->donorProfile->is_available)
                                        Mark yourself as available
                                    @elseif(!$canDonate)
                                        Not eligible to donate yet
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <i class="fas fa-heartbeat text-4xl mb-3 text-gray-300"></i>
                    <p class="text-lg text-gray-500">No blood requests available</p>
                    <p class="text-sm text-gray-400 mt-1">Check back later for new requests</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($bloodRequests->hasPages())
        <div class="mt-6">
            {{ $bloodRequests->links() }}
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        function toggleAvailability() {
            if (confirm('Are you sure you want to make yourself available to donate?')) {
                fetch('{{ route('donor.availability.toggle') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            alert('You are now available to donate!');
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
            }
        }

        function respondToRequest(requestId) {
            if (confirm('Are you interested in donating for this request? The recipient will contact you.')) {
                fetch(`/donor/blood-requests/${requestId}/respond`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            alert('Thank you for your interest! The recipient will contact you soon.');
                            location.reload();
                        } else {
                            alert(data.message || 'Something went wrong.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
            }
        }

        // Initialize filters
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const urgency = urlParams.get('urgency') || 'all';
            const location = urlParams.get('location') || 'all';

            document.getElementById('urgencyFilter').value = urgency;
            document.getElementById('locationFilter').value = location;

            // Add filter change listeners
            document.getElementById('urgencyFilter').addEventListener('change', applyFilters);
            document.getElementById('locationFilter').addEventListener('change', applyFilters);
        });

        function applyFilters() {
            const urgency = document.getElementById('urgencyFilter').value;
            const location = document.getElementById('locationFilter').value;

            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search);

            if (urgency !== 'all') params.set('urgency', urgency);
            else params.delete('urgency');

            if (location !== 'all') params.set('location', location);
            else params.delete('location');

            params.set('page', '1');
            window.location.href = url.pathname + '?' + params.toString();
        }
    </script>
@endpush
