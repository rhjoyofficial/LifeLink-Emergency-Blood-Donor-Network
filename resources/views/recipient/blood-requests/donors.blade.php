@extends('layouts.app')

@section('title', 'Matching Donors')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center">
                        <a href="{{ route('recipient.blood-requests.show', $bloodRequest) }}"
                            class="text-primary hover:text-primary-dark mr-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Matching Donors</h2>
                            <p class="text-gray-600 mt-1">Donors available for {{ $bloodRequest->patient_name }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Matching Donors</p>
                        <p class="text-xl font-bold text-primary">{{ $donors->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Request Summary -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                            <span class="text-lg font-bold text-red-600">{{ $bloodRequest->blood_group }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $bloodRequest->patient_name }}</p>
                            <div class="flex items-center space-x-4 mt-1">
                                <span class="text-sm text-gray-600">
                                    <i class="fas fa-hospital mr-1"></i>{{ $bloodRequest->hospital_name }}
                                </span>
                                <span class="text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-1"></i>{{ $bloodRequest->district }},
                                    {{ $bloodRequest->upazila }}
                                </span>
                                <span class="text-sm text-gray-600">
                                    <i class="fas fa-clock mr-1"></i>{{ $bloodRequest->needed_at->format('M d, h:i A') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Request Status</p>
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                            {{ ucfirst($bloodRequest->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Donors List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Showing {{ $donors->count() }} matching donors</p>
                        <p class="text-xs text-gray-500 mt-1">
                            Donors with blood group {{ $bloodRequest->blood_group }} in {{ $bloodRequest->district }}
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600">Sort by:</span>
                        <select class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
                            <option>Availability</option>
                            <option>Location</option>
                            <option>Last Donation</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse($donors as $donor)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
                            <!-- Donor Information -->
                            <div class="flex-1">
                                <div class="flex items-start">
                                    <div
                                        class="w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center font-medium text-lg">
                                        {{ substr($donor->user->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $donor->user->name }}
                                                </h3>
                                                <div class="flex items-center space-x-4 mt-1">
                                                    <span
                                                        class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                                        {{ $donor->blood_group }}
                                                    </span>
                                                    <span class="text-sm text-gray-600">
                                                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $donor->district }},
                                                        {{ $donor->upazila }}
                                                    </span>
                                                    <span class="text-sm text-gray-600">
                                                        <i class="fas fa-phone mr-1"></i>{{ $donor->user->phone }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="space-y-1">
                                                    @if ($donor->is_available)
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-primary-light text-primary">
                                                            <i class="fas fa-heart mr-1"></i> Available
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            <i class="fas fa-times mr-1"></i> Unavailable
                                                        </span>
                                                    @endif

                                                    @if ($donor->canDonate())
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <i class="fas fa-check-circle mr-1"></i> Eligible
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            <i class="fas fa-clock mr-1"></i> Not Eligible
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Donation History -->
                                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <p class="text-xs text-gray-500">Last Donation</p>
                                                <p class="text-sm font-medium text-gray-900">
                                                    @if ($donor->last_donation_date)
                                                        {{ $donor->last_donation_date->format('M d, Y') }}
                                                    @else
                                                        <span class="text-gray-500">Never donated</span>
                                                    @endif
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Distance</p>
                                                <p class="text-sm font-medium text-gray-900">
                                                    @if ($donor->district == $bloodRequest->district)
                                                        <span class="text-green-600">Same District</span>
                                                    @else
                                                        <span class="text-blue-600">Different District</span>
                                                    @endif
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Profile Status</p>
                                                <p
                                                    class="text-sm font-medium 
                                                @if ($donor->approved_by_admin) text-green-600
                                                @else text-yellow-600 @endif">
                                                    {{ $donor->approved_by_admin ? 'Approved' : 'Pending' }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Availability Timeline -->
                                        @if (!$donor->canDonate() && $donor->last_donation_date)
                                            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                                <div class="flex items-center">
                                                    <i class="fas fa-clock text-yellow-600 mr-3"></i>
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-yellow-800">Not Currently
                                                            Eligible</p>
                                                        <p class="text-xs text-yellow-700">
                                                            Last donated
                                                            {{ $donor->last_donation_date->format('M d, Y') }}.
                                                            Eligible after
                                                            {{ $donor->last_donation_date->addDays(90)->format('M d, Y') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="lg:w-64 space-y-3">
                                <a href="tel:{{ $donor->user->phone }}"
                                    class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                                    <i class="fas fa-phone mr-2"></i> Call Donor
                                </a>

                                <button type="button"
                                    onclick="openContactModal('{{ $donor->user->name }}', '{{ $donor->user->phone }}', '{{ $donor->user->email }}')"
                                    class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-envelope mr-2"></i> Contact Info
                                </button>

                                @if ($donor->is_available && $donor->canDonate())
                                    <button type="button" onclick="markAsContacted('{{ $donor->user->name }}')"
                                        class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                        <i class="fas fa-check-circle mr-2"></i> Mark Contacted
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <div class="max-w-md mx-auto">
                            <i class="fas fa-users-slash text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Matching Donors Found</h3>
                            <p class="text-gray-600 mb-6">
                                There are currently no donors available with blood group {{ $bloodRequest->blood_group }}
                                in {{ $bloodRequest->district }}.
                            </p>
                            <div class="space-y-3">
                                <p class="text-sm text-gray-500">Suggestions:</p>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Check back later as new donors may register</li>
                                    <li>• Consider expanding the search area</li>
                                    <li>• Ensure the blood group is correctly specified</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Donor Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Availability Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Availability</h3>

                @php
                    $availableCount = $donors->where('is_available', true)->count();
                    $unavailableCount = $donors->where('is_available', false)->count();
                    $total = $donors->count();
                @endphp

                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-green-600">Available</span>
                            <span class="font-medium text-gray-900">{{ $availableCount }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full"
                                style="width: {{ $total > 0 ? ($availableCount / $total) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-red-600">Unavailable</span>
                            <span class="font-medium text-gray-900">{{ $unavailableCount }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full"
                                style="width: {{ $total > 0 ? ($unavailableCount / $total) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Eligibility Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Eligibility</h3>

                @php
                    $eligibleCount = $donors
                        ->filter(function ($donor) {
                            return $donor->canDonate();
                        })
                        ->count();
                    $ineligibleCount = $total - $eligibleCount;
                @endphp

                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-green-600">Eligible</span>
                            <span class="font-medium text-gray-900">{{ $eligibleCount }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full"
                                style="width: {{ $total > 0 ? ($eligibleCount / $total) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-red-600">Not Eligible</span>
                            <span class="font-medium text-gray-900">{{ $ineligibleCount }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full"
                                style="width: {{ $total > 0 ? ($ineligibleCount / $total) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Location Match</h3>

                @php
                    $exactMatchCount = $donors
                        ->where('district', $bloodRequest->district)
                        ->where('upazila', $bloodRequest->upazila)
                        ->count();
                    $districtMatchCount = $donors
                        ->where('district', $bloodRequest->district)
                        ->where('upazila', '!=', $bloodRequest->upazila)
                        ->count();
                    $otherCount = $total - $exactMatchCount - $districtMatchCount;
                @endphp

                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-primary">Exact Location</span>
                            <span class="font-medium text-gray-900">{{ $exactMatchCount }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full"
                                style="width: {{ $total > 0 ? ($exactMatchCount / $total) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-blue-600">Same District</span>
                            <span class="font-medium text-gray-900">{{ $districtMatchCount }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full"
                                style="width: {{ $total > 0 ? ($districtMatchCount / $total) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Modal -->
    <div id="contactModal"
        class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2" id="modalDonorName"></h3>
                <p class="text-sm text-gray-600 mb-4">Contact information for this donor</p>

                <div class="space-y-4">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500">Phone Number</p>
                        <p class="font-medium text-gray-900" id="modalDonorPhone"></p>
                    </div>

                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500">Email Address</p>
                        <p class="font-medium text-gray-900" id="modalDonorEmail"></p>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeContactModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Close
                        </button>
                        <a href="#" id="modalCallLink"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-phone mr-2"></i> Call Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openContactModal(name, phone, email) {
                const modal = document.getElementById('contactModal');
                const nameElement = document.getElementById('modalDonorName');
                const phoneElement = document.getElementById('modalDonorPhone');
                const emailElement = document.getElementById('modalDonorEmail');
                const callLink = document.getElementById('modalCallLink');

                nameElement.textContent = name;
                phoneElement.textContent = phone;
                emailElement.textContent = email;
                callLink.href = `tel:${phone}`;

                modal.classList.remove('hidden');
            }

            function closeContactModal() {
                const modal = document.getElementById('contactModal');
                modal.classList.add('hidden');
            }

            function markAsContacted(donorName) {
                if (confirm(`Mark ${donorName} as contacted? This will help track donor engagement.`)) {
                    // Implement API call to mark donor as contacted
                    alert(`${donorName} marked as contacted.`);
                }
            }

            // Close modal on outside click
            document.getElementById('contactModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeContactModal();
                }
            });
        </script>
    @endpush
@endsection
