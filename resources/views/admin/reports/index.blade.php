@extends('layouts.app')

@section('title', 'Donation Report')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center">
                        <a href="{{ route('admin.reports') }}" class="text-primary hover:text-primary-dark mr-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Donation Report</h2>
                            <p class="text-gray-600 mt-1">Complete history of all donations in the system</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Total Donations</p>
                        <p class="text-xl font-bold text-primary">{{ $donations->total() }}</p>
                    </div>
                </div>
            </div>

            <!-- Summary Stats -->
            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Donors with History</p>
                    <p class="text-xl font-bold text-gray-900">{{ $donations->total() }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Most Recent Donation</p>
                    <p class="text-xl font-bold text-gray-900">
                        @if ($donations->count() > 0)
                            {{ $donations->first()->last_donation_date->format('M d') }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Average Donation Gap</p>
                    <p class="text-xl font-bold text-gray-900">~90 days</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Active Donors</p>
                    <p class="text-xl font-bold text-gray-900">
                        {{ \App\Models\DonorProfile::where('is_available', true)->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Donations Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Donor
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Blood Group & Location
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Last Donation
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Eligibility Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Availability
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($donations as $donation)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-medium">
                                            {{ substr($donation->user->name, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $donation->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $donation->user->phone }}</div>
                                            <div class="text-xs text-gray-500">{{ $donation->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <span
                                            class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800 mr-3">
                                            {{ $donation->blood_group }}
                                        </span>
                                        <div>
                                            <div class="text-sm text-gray-900">{{ $donation->district }}</div>
                                            <div class="text-xs text-gray-500">{{ $donation->upazila }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $donation->last_donation_date->format('F j, Y') }}</div>
                                    <div class="text-xs text-gray-500">
                                        @php
                                            $daysSinceDonation = $donation->last_donation_date->diffInDays(now());
                                        @endphp
                                        {{ $daysSinceDonation }} days ago
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($donation->canDonate())
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Eligible
                                        </span>
                                        <div class="text-xs text-green-600 mt-1">
                                            Ready to donate
                                        </div>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-clock mr-1"></i> Not Eligible
                                        </span>
                                        <div class="text-xs text-red-600 mt-1">
                                            {{ 90 - $daysSinceDonation }} days remaining
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        @if ($donation->approved_by_admin)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i> Approved
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i> Pending
                                            </span>
                                        @endif

                                        @if ($donation->is_available)
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
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.donors.show', $donation) }}"
                                            class="text-primary hover:text-primary-dark">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-tint-slash text-3xl mb-3"></i>
                                        <p>No donation records found</p>
                                        <p class="text-sm mt-1">No donors have recorded donation history yet</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($donations->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $donations->links() }}
                </div>
            @endif
        </div>

        <!-- Statistics Summary -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Donation Statistics</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <p class="text-3xl font-bold text-primary">{{ $donations->total() }}</p>
                    <p class="text-sm text-gray-600 mt-2">Total Donors with History</p>
                </div>

                <div class="text-center p-6 bg-primary-light rounded-lg">
                    <p class="text-3xl font-bold text-primary">
                        @if ($donations->count() > 0)
                            @php
                                $eligibleCount = $donations
                                    ->filter(function ($donation) {
                                        return $donation->canDonate();
                                    })
                                    ->count();
                            @endphp
                            {{ round(($eligibleCount / $donations->count()) * 100) }}%
                        @else
                            0%
                        @endif
                    </p>
                    <p class="text-sm text-gray-700 mt-2">Currently Eligible</p>
                </div>

                <div class="text-center p-6 bg-accent-light rounded-lg">
                    <p class="text-3xl font-bold text-accent">
                        @if ($donations->count() > 0)
                            @php
                                $availableCount = $donations
                                    ->filter(function ($donation) {
                                        return $donation->is_available && $donation->approved_by_admin;
                                    })
                                    ->count();
                            @endphp
                            {{ round(($availableCount / $donations->count()) * 100) }}%
                        @else
                            0%
                        @endif
                    </p>
                    <p class="text-sm text-gray-700 mt-2">Active & Available</p>
                </div>
            </div>
        </div>
    </div>
@endsection
