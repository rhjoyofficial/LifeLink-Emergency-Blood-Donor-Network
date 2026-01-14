@extends('layouts.app')

@section('title', 'Blood Request Report')

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
                            <h2 class="text-2xl font-bold text-gray-900">Blood Request Report</h2>
                            <p class="text-gray-600 mt-1">Complete analysis of all blood requests</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Total Requests</p>
                        <p class="text-xl font-bold text-primary">{{ $requests->total() }}</p>
                    </div>
                </div>
            </div>

            <!-- Summary Stats -->
            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <p class="text-sm text-yellow-800">Pending</p>
                    <p class="text-xl font-bold text-yellow-900">{{ $requests->where('status', 'pending')->count() }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-sm text-green-800">Approved</p>
                    <p class="text-xl font-bold text-green-900">{{ $requests->where('status', 'approved')->count() }}</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-blue-800">Fulfilled</p>
                    <p class="text-xl font-bold text-blue-900">{{ $requests->where('status', 'fulfilled')->count() }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-800">Cancelled</p>
                    <p class="text-xl font-bold text-gray-900">{{ $requests->where('status', 'cancelled')->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Requests Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Request Details
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Patient & Hospital
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Timeline
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status & Urgency
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
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                            <span class="text-sm font-bold text-red-600">{{ $request->blood_group }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $request->patient_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $request->bags_required }} bag(s)</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $request->hospital_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $request->district }}, {{ $request->upazila }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-user mr-1"></i> {{ $request->recipient->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $request->created_at->format('M d, Y') }}</div>
                                    <div class="text-sm text-gray-600">{{ $request->needed_at->format('M d, h:i A') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        @if ($request->needed_at->isFuture())
                                            <i class="fas fa-clock mr-1"></i> In {{ $request->needed_at->diffForHumans() }}
                                        @else
                                            <i class="fas fa-clock mr-1"></i> {{ $request->needed_at->diffForHumans() }}
                                            ago
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full 
                                        @if ($request->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($request->status == 'approved') bg-green-100 text-green-800
                                        @elseif($request->status == 'fulfilled') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full 
                                        @if ($request->urgency_level == 'critical') bg-red-100 text-red-800
                                        @elseif($request->urgency_level == 'high') bg-orange-100 text-orange-800
                                        @elseif($request->urgency_level == 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($request->urgency_level) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.blood-requests.show', $request) }}"
                                        class="text-primary hover:text-primary-dark">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-inbox text-3xl mb-3"></i>
                                        <p>No blood requests found</p>
                                        <p class="text-sm mt-1">No blood requests have been created yet</p>
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
                    {{ $requests->links() }}
                </div>
            @endif
        </div>

        <!-- Statistics Summary -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Request Statistics</h3>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <p class="text-3xl font-bold text-primary">{{ $requests->total() }}</p>
                    <p class="text-sm text-gray-600 mt-2">Total Requests</p>
                </div>

                <div class="text-center p-6 bg-yellow-50 rounded-lg">
                    <p class="text-3xl font-bold text-yellow-900">
                        @if ($requests->count() > 0)
                            {{ round(($requests->where('status', 'pending')->count() / $requests->count()) * 100) }}%
                        @else
                            0%
                        @endif
                    </p>
                    <p class="text-sm text-yellow-800 mt-2">Pending Rate</p>
                </div>

                <div class="text-center p-6 bg-green-50 rounded-lg">
                    <p class="text-3xl font-bold text-green-900">
                        @if ($requests->count() > 0)
                            {{ round(($requests->where('status', 'approved')->count() / $requests->count()) * 100) }}%
                        @else
                            0%
                        @endif
                    </p>
                    <p class="text-sm text-green-800 mt-2">Approval Rate</p>
                </div>

                <div class="text-center p-6 bg-blue-50 rounded-lg">
                    <p class="text-3xl font-bold text-blue-900">
                        @if ($requests->count() > 0)
                            {{ round(($requests->where('status', 'fulfilled')->count() / $requests->count()) * 100) }}%
                        @else
                            0%
                        @endif
                    </p>
                    <p class="text-sm text-blue-800 mt-2">Fulfillment Rate</p>
                </div>
            </div>
        </div>
    </div>
@endsection
