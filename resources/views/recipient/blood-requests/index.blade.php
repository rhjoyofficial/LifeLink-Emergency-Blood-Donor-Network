@extends('layouts.app')

@section('title', 'My Blood Requests')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">My Blood Requests</h2>
                    <p class="text-gray-600 mt-1">Track and manage all your blood requests</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('recipient.blood-requests.create') }}"
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-medium">
                        <i class="fas fa-plus-circle mr-2"></i> New Request
                    </a>
                </div>
            </div>

            <!-- Stats Summary -->
            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                @php
                    $pendingCount = $requests->where('status', 'pending')->count();
                    $approvedCount = $requests->where('status', 'approved')->count();
                    $fulfilledCount = $requests->where('status', 'fulfilled')->count();
                    $cancelledCount = $requests->where('status', 'cancelled')->count();
                @endphp

                <div class="bg-yellow-50 p-4 rounded-lg">
                    <p class="text-sm text-yellow-800">Pending</p>
                    <p class="text-xl font-bold text-yellow-900">{{ $pendingCount }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-sm text-green-800">Approved</p>
                    <p class="text-xl font-bold text-green-900">{{ $approvedCount }}</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-blue-800">Fulfilled</p>
                    <p class="text-xl font-bold text-blue-900">{{ $fulfilledCount }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-800">Cancelled</p>
                    <p class="text-xl font-bold text-gray-900">{{ $cancelledCount }}</p>
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
                                Patient & Blood Group
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hospital & Location
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Timeline
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status & Donors
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
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $request->created_at->format('M d, Y') }}</div>
                                    <div class="text-sm text-gray-600">{{ $request->needed_at->format('M d, h:i A') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        @if ($request->needed_at->isFuture())
                                            <i class="fas fa-clock mr-1"></i> {{ $request->needed_at->diffForHumans() }}
                                        @else
                                            <i class="fas fa-check mr-1"></i> Deadline passed
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-2">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full 
                                        @if ($request->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($request->status == 'approved') bg-green-100 text-green-800
                                        @elseif($request->status == 'fulfilled') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                        <div class="flex items-center">
                                            <div class="flex -space-x-2 mr-2">
                                                @foreach ($request->donorResponses->take(3) as $response)
                                                    <div
                                                        class="w-6 h-6 rounded-full bg-primary text-white flex items-center justify-center text-xs font-medium border-2 border-white">
                                                        {{ substr($response->donor->name, 0, 1) }}
                                                    </div>
                                                @endforeach
                                            </div>
                                            <span class="text-xs text-gray-600">
                                                {{ $request->donorResponses->count() }}
                                                donor{{ $request->donorResponses->count() > 1 ? 's' : '' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('recipient.blood-requests.show', $request) }}"
                                            class="text-primary hover:text-primary-dark">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if ($request->status == 'pending')
                                            <a href="{{ route('recipient.blood-requests.edit', $request) }}"
                                                class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('recipient.blood-requests.cancel', $request) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-800"
                                                    onclick="return confirm('Are you sure you want to cancel this request?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if ($request->status == 'approved' && $request->donorResponses->count() > 0)
                                            <a href="{{ route('recipient.blood-requests.donors', $request) }}"
                                                class="text-green-600 hover:text-green-800">
                                                <i class="fas fa-users"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-inbox text-3xl mb-3"></i>
                                        <p>No blood requests yet</p>
                                        <p class="text-sm mt-1">Create your first blood request to get started</p>
                                        <a href="{{ route('recipient.blood-requests.create') }}"
                                            class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors mt-4">
                                            <i class="fas fa-plus-circle mr-2"></i> Create First Request
                                        </a>
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

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Status Distribution -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Request Status Distribution</h3>

                <div class="space-y-4">
                    @php
                        $totalRequests = $requests->count();
                        $statuses = [
                            'pending' => [
                                'count' => $pendingCount,
                                'color' => 'bg-yellow-500',
                                'text' => 'text-yellow-800',
                            ],
                            'approved' => [
                                'count' => $approvedCount,
                                'color' => 'bg-green-500',
                                'text' => 'text-green-800',
                            ],
                            'fulfilled' => [
                                'count' => $fulfilledCount,
                                'color' => 'bg-blue-500',
                                'text' => 'text-blue-800',
                            ],
                            'cancelled' => [
                                'count' => $cancelledCount,
                                'color' => 'bg-gray-500',
                                'text' => 'text-gray-800',
                            ],
                        ];
                    @endphp

                    @foreach ($statuses as $status => $data)
                        @php
                            $percentage = $totalRequests > 0 ? ($data['count'] / $totalRequests) * 100 : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-sm mb-1">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full {{ $data['color'] }} mr-2"></div>
                                    <span class="capitalize">{{ $status }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium text-gray-900">{{ $data['count'] }}</span>
                                    <span class="text-gray-600">{{ round($percentage) }}%</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full {{ $data['color'] }}" style="width: {{ $percentage }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Donor Activity -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Donor Responses</h3>
                    <span class="text-sm text-gray-600">Last 7 days</span>
                </div>

                @php
                    $recentResponses = collect();
                    foreach ($requests as $request) {
                        foreach ($request->donorResponses as $response) {
                            if ($response->created_at->gte(now()->subDays(7))) {
                                $recentResponses->push($response);
                            }
                        }
                    }
                    $recentResponses = $recentResponses->sortByDesc('created_at')->take(3);
                @endphp

                <div class="space-y-4">
                    @forelse($recentResponses as $response)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div
                                    class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-xs font-medium mr-3">
                                    {{ substr($response->donor->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $response->donor->name }}</p>
                                    <p class="text-xs text-gray-600">{{ $response->bloodRequest->patient_name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full 
                                @if ($response->response_status == 'interested') bg-yellow-100 text-yellow-800
                                @elseif($response->response_status == 'contacted') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800 @endif">
                                    {{ ucfirst($response->response_status) }}
                                </span>
                                <p class="text-xs text-gray-600 mt-1">{{ $response->created_at->format('M d') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-users-slash text-xl mb-2"></i>
                            <p class="text-sm">No recent donor responses</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
