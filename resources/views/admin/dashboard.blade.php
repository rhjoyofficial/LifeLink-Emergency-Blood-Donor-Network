@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}! 👋</h2>
                    <p class="text-gray-600 mt-2">Here's what's happening in your blood donation system today.</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Current Time</p>
                    <p class="text-xl font-bold text-primary" id="currentTime">{{ now()->format('h:i A') }}</p>
                    <p class="text-sm text-gray-600">{{ now()->format('l, F j, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Users -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Users</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_users'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <span class="text-xs text-gray-500">
                        <i class="fas fa-user-shield mr-1"></i> Admins:
                        {{ $stats['total_donors'] - $stats['verified_donors'] }}
                    </span>
                </div>
            </div>

            <!-- Total Donors -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Donors</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_donors'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-hand-holding-heart text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <span class="text-xs text-green-600 font-medium">
                        <i class="fas fa-check-circle mr-1"></i> Active: {{ $stats['active_donors'] }}
                    </span>
                    <span class="text-xs text-gray-500 ml-3">
                        <i class="fas fa-user-check mr-1"></i> Verified: {{ $stats['verified_donors'] }}
                    </span>
                </div>
            </div>

            <!-- Blood Requests -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Blood Requests</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_requests'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tint text-red-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex justify-between text-xs">
                        <span class="text-yellow-600">
                            <i class="fas fa-clock mr-1"></i> Pending: {{ $stats['pending_requests'] }}
                        </span>
                        <span class="text-green-600">
                            <i class="fas fa-check mr-1"></i> Approved: {{ $stats['approved_requests'] }}
                        </span>
                        <span class="text-blue-600">
                            <i class="fas fa-check-double mr-1"></i> Fulfilled: {{ $stats['fulfilled_requests'] }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- System Health -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">System Status</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">Healthy</p>
                    </div>
                    <div class="w-12 h-12 bg-primary-light rounded-lg flex items-center justify-center">
                        <i class="fas fa-shield-alt text-primary text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center text-xs text-green-600">
                        <i class="fas fa-circle mr-2 animate-pulse"></i>
                        All services operational
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Blood Requests -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Blood Requests</h3>
                    <a href="{{ route('admin.blood-requests.index') }}"
                        class="text-sm text-primary hover:text-primary-dark font-medium">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($recentRequests as $request)
                        <div
                            class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div>
                                <div class="flex items-center">
                                    <div
                                        class="w-2 h-2 rounded-full 
                                    @if ($request->urgency_level == 'critical') bg-red-500
                                    @elseif($request->urgency_level == 'high') bg-orange-500
                                    @elseif($request->urgency_level == 'medium') bg-yellow-500
                                    @else bg-green-500 @endif mr-3">
                                    </div>
                                    <p class="font-medium text-gray-900">{{ $request->patient_name }}</p>
                                    <span
                                        class="ml-3 px-2 py-1 text-xs font-medium rounded-full 
                                    @if ($request->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($request->status == 'approved') bg-green-100 text-green-800
                                    @elseif($request->status == 'fulfilled') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-hospital mr-2"></i>{{ $request->hospital_name }}
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-map-marker-alt mr-1"></i>{{ $request->district }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">{{ $request->needed_at->format('M d, h:i A') }}</p>
                                <p class="text-xs text-gray-500">Needed by</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-3"></i>
                            <p>No recent blood requests</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>

                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('admin.blood-requests.index') }}"
                        class="group p-4 bg-primary-light rounded-lg hover:bg-primary transition-colors">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3 group-hover:bg-primary-light">
                                <i class="fas fa-tasks text-primary group-hover:text-white"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 group-hover:text-white">Review Requests</p>
                                <p class="text-xs text-gray-600 group-hover:text-white/80">{{ $stats['pending_requests'] }}
                                    pending</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.donors.index') }}"
                        class="group p-4 bg-accent-light rounded-lg hover:bg-accent transition-colors">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3 group-hover:bg-accent-light">
                                <i class="fas fa-user-check text-accent group-hover:text-white"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 group-hover:text-white">Approve Donors</p>
                                <p class="text-xs text-gray-600 group-hover:text-white/80">Manage approvals</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                        class="group p-4 bg-blue-50 rounded-lg hover:bg-blue-600 transition-colors">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-100">
                                <i class="fas fa-user-shield text-blue-600 group-hover:text-white"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 group-hover:text-white">User Management</p>
                                <p class="text-xs text-gray-600 group-hover:text-white/80">{{ $stats['total_users'] }}
                                    users</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.reports') }}"
                        class="group p-4 bg-purple-50 rounded-lg hover:bg-purple-600 transition-colors">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-100">
                                <i class="fas fa-chart-bar text-purple-600 group-hover:text-white"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 group-hover:text-white">Generate Reports</p>
                                <p class="text-xs text-gray-600 group-hover:text-white/80">Analytics & insights</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- System Alerts -->
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <h4 class="font-medium text-gray-900 mb-3">System Alerts</h4>
                    <div class="space-y-2">
                        @if ($stats['pending_requests'] > 5)
                            <div class="flex items-center p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-yellow-800">High pending requests</p>
                                    <p class="text-xs text-yellow-600">{{ $stats['pending_requests'] }} requests need
                                        attention</p>
                                </div>
                                <a href="{{ route('admin.blood-requests.index') }}"
                                    class="text-sm text-yellow-700 hover:text-yellow-800">
                                    Review
                                </a>
                            </div>
                        @endif

                        @if ($stats['active_donors'] < 10)
                            <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg">
                                <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-red-800">Low donor availability</p>
                                    <p class="text-xs text-red-600">Only {{ $stats['active_donors'] }} active donors</p>
                                </div>
                                <a href="{{ route('admin.donors.index') }}"
                                    class="text-sm text-red-700 hover:text-red-800">
                                    Check
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Update current time every minute
            function updateCurrentTime() {
                const now = new Date();
                const timeElement = document.getElementById('currentTime');
                const options = {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                };
                timeElement.textContent = now.toLocaleTimeString('en-US', options);
            }

            setInterval(updateCurrentTime, 60000);
            updateCurrentTime();
        </script>
    @endpush
@endsection
