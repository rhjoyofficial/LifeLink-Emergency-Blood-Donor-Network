@extends('layouts.app')

@section('title', 'Blood Requests Management')

@section('content')
    <div class="space-y-6">
        <!-- Header with Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Blood Requests</h2>
                    <p class="text-gray-600 mt-1">Manage and approve blood requests from recipients</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Status Filter -->
                    <div class="relative">
                        <form method="GET" action="{{ route('admin.blood-requests.index') }}" id="filterForm">
                            <select name="status" onchange="document.getElementById('filterForm').submit()"
                                class="pl-10 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm appearance-none bg-white">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="fulfilled" {{ request('status') == 'fulfilled' ? 'selected' : '' }}>Fulfilled
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
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
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Pending</p>
                            <p class="text-xl font-bold text-gray-900">{{ $requests->where('status', 'pending')->count() }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Approved</p>
                            <p class="text-xl font-bold text-gray-900">{{ $requests->where('status', 'approved')->count() }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-check-double text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Fulfilled</p>
                            <p class="text-xl font-bold text-gray-900">
                                {{ $requests->where('status', 'fulfilled')->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-times text-gray-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Cancelled</p>
                            <p class="text-xl font-bold text-gray-900">
                                {{ $requests->where('status', 'cancelled')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Blood Requests Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Patient & Blood Group
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Recipient
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Location & Hospital
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Urgency & Timeline
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
                        @forelse($requests as $request)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                                    <span
                                                        class="text-sm font-bold text-red-600">{{ $request->blood_group }}</span>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $request->patient_name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $request->bags_required }} bag(s)
                                                        required</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $request->recipient->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $request->recipient->phone }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $request->hospital_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $request->district }}, {{ $request->upazila }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full 
                                        @if ($request->urgency_level == 'critical') bg-red-100 text-red-800
                                        @elseif($request->urgency_level == 'high') bg-orange-100 text-orange-800
                                        @elseif($request->urgency_level == 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($request->urgency_level) }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-500 mt-1">
                                        <i class="fas fa-clock mr-1"></i> {{ $request->needed_at->format('M d, h:i A') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full 
                                    @if ($request->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($request->status == 'approved') bg-green-100 text-green-800
                                    @elseif($request->status == 'fulfilled') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                    @if ($request->approved_by_admin)
                                        <div class="text-xs text-gray-500 mt-1">
                                            Approved by: {{ $request->approvedBy->name }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.blood-requests.show', $request) }}"
                                            class="text-primary hover:text-primary-dark">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if ($request->status == 'pending')
                                            <form action="{{ route('admin.blood-requests.approve', $request) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-600 hover:text-green-800"
                                                    onclick="return confirm('Are you sure you want to approve this request?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if ($request->status == 'approved')
                                            <form action="{{ route('admin.blood-requests.fulfill', $request) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-blue-600 hover:text-blue-800"
                                                    onclick="return confirm('Mark this request as fulfilled?')">
                                                    <i class="fas fa-check-double"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if ($request->status == 'pending' || $request->status == 'approved')
                                            <form action="{{ route('admin.blood-requests.cancel', $request) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-red-600 hover:text-red-800"
                                                    onclick="return confirm('Are you sure you want to cancel this request?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if ($request->status == 'pending')
                                            <form action="{{ route('admin.blood-requests.destroy', $request) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800"
                                                    onclick="return confirm('Are you sure you want to delete this request? This action cannot be undone.')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-inbox text-3xl mb-3"></i>
                                        <p>No blood requests found</p>
                                        <p class="text-sm mt-1">No blood requests match your current filters</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($requests->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $requests->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
