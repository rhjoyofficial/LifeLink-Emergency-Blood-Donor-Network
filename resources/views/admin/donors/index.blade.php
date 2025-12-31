@extends('layouts.app')

@section('title', 'Donor Management')

@section('header')
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Donor Management</h1>
                <p class="mt-1 text-sm text-gray-600">Manage and approve blood donors</p>
            </div>
            <div class="flex items-center space-x-3">
                <select id="statusFilter"
                    class="block w-48 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 rounded-md">
                    <option value="all">All Donors</option>
                    <option value="pending">Pending Approval</option>
                    <option value="approved">Approved Donors</option>
                    <option value="available">Available Now</option>
                </select>

                <select id="bloodGroupFilter"
                    class="block w-32 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 rounded-md">
                    <option value="all">All Blood Groups</option>
                    @foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $group)
                        <option value="{{ $group }}">{{ $group }}</option>
                    @endforeach
                </select>

                <button onclick="applyFilters()"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:p-6">
            <!-- Stats Bar -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-gray-500">Total Donors</div>
                    <div class="text-2xl font-semibold text-gray-900">{{ $stats['total'] }}</div>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-yellow-700">Pending</div>
                    <div class="text-2xl font-semibold text-yellow-700">{{ $stats['pending'] }}</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-green-700">Approved</div>
                    <div class="text-2xl font-semibold text-green-700">{{ $stats['approved'] }}</div>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-blue-700">Available</div>
                    <div class="text-2xl font-semibold text-blue-700">{{ $stats['available'] }}</div>
                </div>
            </div>

            <!-- Donors Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($donors as $donor)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <!-- Donor Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 rounded-full bg-red-100 flex items-center justify-center">
                                        <i class="fas fa-user text-red-600 text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $donor->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $donor->email }}</p>
                                    </div>
                                </div>
                                <span
                                    class="blood-group-badge {{ strtolower(str_replace('+', 'p', $donor->donorProfile->blood_group)) }}">
                                    {{ $donor->donorProfile->blood_group }}
                                </span>
                            </div>

                            <!-- Donor Details -->
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt text-gray-400 mr-2 w-5"></i>
                                    <span>{{ $donor->donorProfile->district }}, {{ $donor->donorProfile->upazila }}</span>
                                </div>

                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-phone text-gray-400 mr-2 w-5"></i>
                                    <span>{{ $donor->phone ?? 'Not provided' }}</span>
                                </div>

                                @if ($donor->donorProfile->last_donation_date)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-history text-gray-400 mr-2 w-5"></i>
                                        <span>Last donation:
                                            {{ $donor->donorProfile->last_donation_date->format('M d, Y') }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Status & Actions -->
                            <div class="border-t border-gray-100 pt-4">
                                <div class="flex items-center justify-between">
                                    <div class="space-x-2">
                                        @if ($donor->donorProfile->approved_by_admin)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i>
                                                Approved
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>
                                                Pending
                                            </span>
                                        @endif

                                        @if ($donor->donorProfile->is_available)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-heart mr-1"></i>
                                                Available
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-bed mr-1"></i>
                                                Unavailable
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.donors.show', $donor->donorProfile) }}"
                                            class="text-gray-400 hover:text-red-600">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if (!$donor->donorProfile->approved_by_admin)
                                            <button onclick="approveDonor({{ $donor->donorProfile->id }})"
                                                class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="text-center py-12">
                            <i class="fas fa-users text-4xl mb-3 text-gray-300"></i>
                            <p class="text-lg text-gray-500">No donors found</p>
                            <p class="text-sm text-gray-400 mt-1">Try changing your filters</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($donors->hasPages())
                <div class="mt-6">
                    {{ $donors->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function applyFilters() {
            const status = document.getElementById('statusFilter').value;
            const bloodGroup = document.getElementById('bloodGroupFilter').value;

            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search);

            if (status !== 'all') params.set('status', status);
            else params.delete('status');

            if (bloodGroup !== 'all') params.set('blood_group', bloodGroup);
            else params.delete('blood_group');

            params.set('page', '1');
            window.location.href = url.pathname + '?' + params.toString();
        }

        function approveDonor(profileId) {
            if (confirm('Approve this donor?')) {
                fetch(`/admin/donors/${profileId}/approve`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            alert(data.message);
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
            }
        }

        // Initialize filters from URL
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status') || 'all';
            const bloodGroup = urlParams.get('blood_group') || 'all';

            document.getElementById('statusFilter').value = status;
            document.getElementById('bloodGroupFilter').value = bloodGroup;
        });
    </script>
@endpush
