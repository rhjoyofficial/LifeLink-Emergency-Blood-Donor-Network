@extends('layouts.app')

@section('title', 'Donor Management')

@section('content')
    <div class="space-y-6">
        <!-- Header with Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Donor Management</h2>
                    <p class="text-gray-600 mt-1">Manage and approve donor profiles</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Status Filter -->
                    <div class="relative">
                        <form method="GET" action="{{ route('admin.donors.index') }}" id="filterForm">
                            <select name="status" onchange="document.getElementById('filterForm').submit()"
                                class="pl-10 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm appearance-none bg-white">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    Approval</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available
                                    Now</option>
                            </select>
                            <div class="absolute left-3 top-2.5 text-gray-400">
                                <i class="fas fa-filter"></i>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Stats Summary -->
            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-users text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Donors</p>
                            <p class="text-xl font-bold text-gray-900">{{ $donors->total() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user-check text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Approved</p>
                            <p class="text-xl font-bold text-gray-900">
                                {{ $donors->where('approved_by_admin', true)->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Pending</p>
                            <p class="text-xl font-bold text-gray-900">
                                {{ $donors->where('approved_by_admin', false)->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-primary-light rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-heart text-primary"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Available</p>
                            <p class="text-xl font-bold text-gray-900">{{ $donors->where('is_available', true)->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Donors Table -->
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
                                Donation History
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($donors as $donor)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-medium">
                                            {{ substr($donor->user->name, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $donor->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $donor->user->email }}</div>
                                            <div class="text-xs text-gray-500">{{ $donor->user->phone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <span
                                            class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800 mr-3">
                                            {{ $donor->blood_group }}
                                        </span>
                                        <div>
                                            <div class="text-sm text-gray-900">{{ $donor->district }}</div>
                                            <div class="text-xs text-gray-500">{{ $donor->upazila }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($donor->last_donation_date)
                                        <div class="text-sm text-gray-900">
                                            {{ $donor->last_donation_date->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            @if ($donor->canDonate())
                                                <span class="text-green-600">
                                                    <i class="fas fa-check-circle mr-1"></i> Eligible to donate
                                                </span>
                                            @else
                                                <span class="text-red-600">
                                                    <i class="fas fa-clock mr-1"></i> Wait 90 days
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-900">Never donated</div>
                                        <div class="text-xs text-green-600">
                                            <i class="fas fa-heart mr-1"></i> Ready to donate
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        @if ($donor->approved_by_admin)
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
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.donors.show', $donor) }}"
                                            class="text-primary hover:text-primary-dark">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if (!$donor->approved_by_admin)
                                            <form action="{{ route('admin.donors.approve', $donor) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-600 hover:text-green-800"
                                                    onclick="return confirm('Approve this donor profile?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if ($donor->approved_by_admin)
                                            <form action="{{ route('admin.donors.reject', $donor) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-800"
                                                    onclick="return confirm('Reject this donor profile? They will become unavailable.')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.donors.destroy', $donor) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800"
                                                onclick="return confirm('Are you sure you want to delete this donor? This will also delete their user account.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>

                                        <a href="{{ route('admin.donors.responses', $donor) }}"
                                            class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-history"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-users-slash text-3xl mb-3"></i>
                                        <p>No donors found</p>
                                        <p class="text-sm mt-1">No donors match your current filters</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($donors->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $donors->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
