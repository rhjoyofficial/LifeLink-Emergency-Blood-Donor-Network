@extends('layouts.app')

@section('title', 'Donor Responses')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center">
                        <a href="{{ route('admin.donors.show', $donorProfile) }}"
                            class="text-primary hover:text-primary-dark mr-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Donor Responses</h2>
                            <p class="text-gray-600 mt-1">Response history for {{ $donorProfile->user->name }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Total Responses</p>
                        <p class="text-xl font-bold text-primary">{{ $responses->total() }}</p>
                    </div>
                </div>
            </div>

            <!-- Donor Summary -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center font-medium">
                        {{ substr($donorProfile->user->name, 0, 1) }}
                    </div>
                    <div class="ml-4">
                        <p class="font-medium text-gray-900">{{ $donorProfile->user->name }}</p>
                        <div class="flex items-center space-x-4 mt-1">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                {{ $donorProfile->blood_group }}
                            </span>
                            <span class="text-sm text-gray-600">{{ $donorProfile->district }},
                                {{ $donorProfile->upazila }}</span>
                            <span class="text-sm text-gray-600">{{ $donorProfile->user->phone }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Responses Table -->
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
                                Patient Information
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Response Info
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
                        @forelse($responses as $response)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                            <span
                                                class="text-sm font-bold text-red-600">{{ $response->bloodRequest->blood_group }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $response->bloodRequest->hospital_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $response->bloodRequest->district }},
                                                {{ $response->bloodRequest->upazila }}</div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ $response->bloodRequest->needed_at->format('M d, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $response->bloodRequest->patient_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $response->bloodRequest->bags_required }} bag(s)
                                        required</div>
                                    <div class="mt-1">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full 
                                        @if ($response->bloodRequest->urgency_level == 'critical') bg-red-100 text-red-800
                                        @elseif($response->bloodRequest->urgency_level == 'high') bg-orange-100 text-orange-800
                                        @elseif($response->bloodRequest->urgency_level == 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($response->bloodRequest->urgency_level) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $response->created_at->format('M d, Y h:i A') }}
                                    </div>
                                    <div class="text-xs text-gray-500">Response sent</div>
                                    <div class="mt-1 text-xs text-gray-600">
                                        <i class="fas fa-history mr-1"></i> {{ $response->updated_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full 
                                        @if ($response->response_status == 'interested') bg-yellow-100 text-yellow-800
                                        @elseif($response->response_status == 'contacted') bg-blue-100 text-blue-800
                                        @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($response->response_status) }}
                                        </span>
                                        <div class="text-xs text-gray-500">
                                            Request:
                                            <span
                                                class="font-medium 
                                            @if ($response->bloodRequest->status == 'pending') text-yellow-600
                                            @elseif($response->bloodRequest->status == 'approved') text-green-600
                                            @elseif($response->bloodRequest->status == 'fulfilled') text-blue-600
                                            @else text-gray-600 @endif">
                                                {{ ucfirst($response->bloodRequest->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.blood-requests.show', $response->bloodRequest) }}"
                                            class="text-primary hover:text-primary-dark">
                                            <i class="fas fa-eye"></i> View Request
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-hand-paper text-3xl mb-3"></i>
                                        <p>No response history found</p>
                                        <p class="text-sm mt-1">This donor hasn't responded to any blood requests yet</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($responses->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $responses->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
