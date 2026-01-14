@extends('layouts.app')

@section('title', 'Recipient Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Welcome, {{ auth()->user()->name }}! 🏥</h2>
                    <p class="text-gray-600 mt-2">Manage your blood requests and find donors quickly.</p>
                </div>
                <div>
                    <a href="{{ route('recipient.blood-requests.create') }}"
                        class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-medium">
                        <i class="fas fa-plus-circle mr-2"></i> New Blood Request
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Active Requests -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Active Requests</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['active_requests'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tasks text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-600">
                        <i class="fas fa-clock mr-1"></i> Pending: {{ $stats['pending_requests'] }}
                        <span class="mx-2">•</span>
                        <i class="fas fa-check-circle mr-1"></i> Approved:
                        {{ $stats['active_requests'] - $stats['pending_requests'] }}
                    </p>
                </div>
            </div>

            <!-- Fulfilled Requests -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Fulfilled</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['fulfilled_requests'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-double text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-green-600">
                        <i class="fas fa-heart mr-1"></i> Lives saved through your requests
                    </p>
                </div>
            </div>

            <!-- Cancelled Requests -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Cancelled</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['cancelled_requests'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times-circle text-gray-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-600">
                        <i class="fas fa-calendar-times mr-1"></i> Requests not completed
                    </p>
                </div>
            </div>

            <!-- Available Donors -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Available Donors</p>
                        <p class="text-3xl font-bold text-primary mt-2">{{ $donorsByBloodGroup->sum() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-primary-light rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-primary text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-600">
                        <i class="fas fa-map-marker-alt mr-1"></i> In
                        {{ auth()->user()->recipientProfile?->district ?? 'your area' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Donor Availability -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Requests -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Blood Requests</h3>
                    <a href="{{ route('recipient.blood-requests.index') }}"
                        class="text-sm text-primary hover:text-primary-dark font-medium">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($recentRequests as $request)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-primary transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <span
                                                class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full mr-3">
                                                {{ $request->blood_group }}
                                            </span>
                                            <h4 class="font-medium text-gray-900">{{ $request->patient_name }}</h4>
                                            <span
                                                class="ml-3 px-2 py-1 text-xs font-medium rounded-full 
                                            @if ($request->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($request->status == 'approved') bg-green-100 text-green-800
                                            @elseif($request->status == 'fulfilled') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </div>
                                        <span class="text-sm text-gray-600">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $request->created_at->format('M d, h:i A') }}
                                        </span>
                                    </div>

                                    <div class="mt-3 grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-600">
                                                <i class="fas fa-hospital mr-2"></i>{{ $request->hospital_name }}
                                            </p>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <i class="fas fa-map-marker-alt mr-2"></i>{{ $request->district }},
                                                {{ $request->upazila }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">
                                                <i class="fas fa-bell mr-2"></i> {{ ucfirst($request->urgency_level) }}
                                                Urgency
                                            </p>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <i class="fas fa-calendar mr-2"></i> Needed:
                                                {{ $request->needed_at->format('M d, h:i A') }}
                                            </p>
                                        </div>
                                    </div>

                                    @if ($request->donorResponses->count() > 0)
                                        <div class="mt-4 pt-4 border-t border-gray-100">
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
                                                    donor{{ $request->donorResponses->count() > 1 ? 's' : '' }} responded
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                                <a href="{{ route('recipient.blood-requests.show', $request) }}"
                                    class="text-sm font-medium text-primary hover:text-primary-dark">
                                    <i class="fas fa-eye mr-2"></i> View Details
                                </a>

                                @if ($request->status == 'pending')
                                    <form action="{{ route('recipient.blood-requests.cancel', $request) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">
                                            <i class="fas fa-times mr-2"></i> Cancel Request
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-3"></i>
                            <p>No blood requests yet</p>
                            <p class="text-sm mt-2">Create your first blood request to get started!</p>
                            <a href="{{ route('recipient.blood-requests.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors mt-4">
                                <i class="fas fa-plus-circle mr-2"></i> Create First Request
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Donor Availability & Quick Stats -->
            <div class="space-y-6">
                <!-- Donor Availability by Blood Group -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Available Donors by Blood Group</h3>
                        <span class="text-sm text-gray-600">{{ $donorsByBloodGroup->sum() }} total donors</span>
                    </div>

                    <div class="space-y-3">
                        @php
                            $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
                        @endphp

                        @foreach ($bloodGroups as $bloodGroup)
                            @php
                                $count = $donorsByBloodGroup[$bloodGroup] ?? 0;
                                $percentage =
                                    $donorsByBloodGroup->sum() > 0 ? ($count / $donorsByBloodGroup->sum()) * 100 : 0;
                            @endphp
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center">
                                        <span
                                            class="w-12 text-center px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded">
                                            {{ $bloodGroup }}
                                        </span>
                                        <span class="ml-3 text-gray-700">{{ $count }} donors</span>
                                    </div>
                                    <span class="text-gray-600">{{ round($percentage) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-primary h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach

                        @if ($donorsByBloodGroup->sum() == 0)
                            <div class="text-center py-4 text-gray-500">
                                <i class="fas fa-users-slash text-xl mb-2"></i>
                                <p>No donors available in your district</p>
                                <p class="text-sm mt-1">Try expanding your search area</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('recipient.statistics') }}"
                            class="w-full flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fas fa-chart-line mr-2"></i> View Detailed Statistics
                        </a>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>

                    <div class="space-y-3">
                        <a href="{{ route('recipient.blood-requests.create') }}"
                            class="flex items-center p-4 bg-primary-light rounded-lg hover:bg-primary hover:text-white transition-colors group">
                            <div
                                class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-4 group-hover:bg-primary-light">
                                <i class="fas fa-plus-circle text-primary text-xl group-hover:text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 group-hover:text-white">New Blood Request</p>
                                <p class="text-sm text-gray-600 group-hover:text-white/80">Request blood for a patient</p>
                            </div>
                            <i class="fas fa-arrow-right text-gray-400 group-hover:text-white"></i>
                        </a>

                        <a href="{{ route('recipient.blood-requests.index') }}"
                            class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-600 hover:text-white transition-colors group">
                            <div
                                class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-4 group-hover:bg-blue-100">
                                <i class="fas fa-list text-blue-600 text-xl group-hover:text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 group-hover:text-white">Manage Requests</p>
                                <p class="text-sm text-gray-600 group-hover:text-white/80">View all your blood requests</p>
                            </div>
                            <i class="fas fa-arrow-right text-gray-400 group-hover:text-white"></i>
                        </a>

                        @if ($stats['pending_requests'] > 0)
                            <a href="{{ route('recipient.blood-requests.index', ['status' => 'pending']) }}"
                                class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-600 hover:text-white transition-colors group">
                                <div
                                    class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-4 group-hover:bg-yellow-100">
                                    <i class="fas fa-clock text-yellow-600 text-xl group-hover:text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 group-hover:text-white">Pending Approvals</p>
                                    <p class="text-sm text-gray-600 group-hover:text-white/80">
                                        {{ $stats['pending_requests'] }} awaiting admin review</p>
                                </div>
                                <i class="fas fa-arrow-right text-gray-400 group-hover:text-white"></i>
                            </a>
                        @endif
                    </div>

                    <!-- Emergency Contact -->
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-phone-alt text-red-600 text-xl mr-3"></i>
                                <div>
                                    <p class="font-medium text-red-800">Emergency Support</p>
                                    <p class="text-sm text-red-600">Available 24/7 for urgent cases</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="tel:+8801700000000"
                                    class="block w-full text-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                    <i class="fas fa-phone mr-2"></i> Call Emergency: +880 1700-000000
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
