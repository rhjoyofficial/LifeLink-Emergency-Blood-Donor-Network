@extends('layouts.app')

@section('title', 'My Statistics')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">My Statistics</h2>
                    <p class="text-gray-600 mt-1">Track your blood request history and impact</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Total Requests</p>
                        <p class="text-xl font-bold text-primary">{{ $monthlyRequests->sum('total') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $user = auth()->user();
                $bloodRequests = $user->bloodRequests;
                $totalRequests = $bloodRequests->count();
                $pendingRequests = $bloodRequests->where('status', 'pending')->count();
                $approvedRequests = $bloodRequests->where('status', 'approved')->count();
                $fulfilledRequests = $bloodRequests->where('status', 'fulfilled')->count();
            @endphp

            <!-- Total Requests -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-file-medical text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Requests</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalRequests }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-600">All blood requests you've made</p>
                </div>
            </div>

            <!-- Pending Requests -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Pending</p>
                        <p class="text-2xl font-bold text-yellow-900">{{ $pendingRequests }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-600">Awaiting admin approval</p>
                </div>
            </div>

            <!-- Approved Requests -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-check text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Approved</p>
                        <p class="text-2xl font-bold text-green-900">{{ $approvedRequests }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-600">Ready for donor responses</p>
                </div>
            </div>

            <!-- Fulfilled Requests -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-primary-light rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-heart text-primary text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Fulfilled</p>
                        <p class="text-2xl font-bold text-primary-dark">{{ $fulfilledRequests }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-600">Successfully completed</p>
                </div>
            </div>
        </div>

        <!-- Monthly Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Monthly Requests Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Monthly Activity ({{ date('Y') }})</h3>

                <div class="space-y-4">
                    @php
                        $maxCount = $monthlyRequests->max('total') ?: 1;
                        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    @endphp

                    @foreach ($months as $index => $month)
                        @php
                            $monthData = $monthlyRequests->firstWhere(
                                'month',
                                date('Y') . '-' . sprintf('%02d', $index + 1),
                            );
                            $count = $monthData ? $monthData->total : 0;
                            $percentage = ($count / $maxCount) * 100;
                        @endphp
                        <div class="flex items-center">
                            <div class="w-10 text-sm text-gray-600">{{ $month }}</div>
                            <div class="flex-1 mx-4">
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-primary h-3 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                            <div class="w-6 text-right text-sm font-medium text-gray-900">{{ $count }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Total This Year</p>
                        <p class="text-2xl font-bold text-primary">{{ $monthlyRequests->sum('total') }}</p>
                    </div>
                </div>
            </div>

            <!-- Request Status Distribution -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Request Status Distribution</h3>

                @php
                    $cancelledRequests = $bloodRequests->where('status', 'cancelled')->count();
                    $statuses = [
                        'pending' => ['count' => $pendingRequests, 'color' => 'bg-yellow-500', 'text' => 'Pending'],
                        'approved' => ['count' => $approvedRequests, 'color' => 'bg-green-500', 'text' => 'Approved'],
                        'fulfilled' => ['count' => $fulfilledRequests, 'color' => 'bg-blue-500', 'text' => 'Fulfilled'],
                        'cancelled' => ['count' => $cancelledRequests, 'color' => 'bg-gray-500', 'text' => 'Cancelled'],
                    ];
                @endphp

                <div class="space-y-6">
                    <!-- Pie Chart Visualization -->
                    <div class="relative h-48">
                        <canvas id="statusChart"></canvas>
                    </div>

                    <!-- Legend -->
                    <div class="space-y-2">
                        @foreach ($statuses as $status => $data)
                            @php
                                $percentage = $totalRequests > 0 ? ($data['count'] / $totalRequests) * 100 : 0;
                            @endphp
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full {{ $data['color'] }} mr-2"></div>
                                    <span>{{ $data['text'] }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium text-gray-900">{{ $data['count'] }}</span>
                                    <span class="text-gray-600">{{ round($percentage) }}%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Donor Response Statistics -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Donor Response Statistics</h3>

            @php
                $totalDonorResponses = 0;
                $totalDonors = 0;
                $responsesByStatus = [
                    'interested' => 0,
                    'contacted' => 0,
                    'donated' => 0,
                ];

                foreach ($bloodRequests as $request) {
                    $totalDonorResponses += $request->donorResponses->count();
                    foreach ($request->donorResponses as $response) {
                        $responsesByStatus[$response->response_status] =
                            ($responsesByStatus[$response->response_status] ?? 0) + 1;
                    }
                }

                // Count unique donors
                $donorIds = [];
                foreach ($bloodRequests as $request) {
                    foreach ($request->donorResponses as $response) {
                        $donorIds[$response->donor_id] = true;
                    }
                }
                $totalDonors = count($donorIds);
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-6 bg-primary-light rounded-lg">
                    <p class="text-3xl font-bold text-primary">{{ $totalDonorResponses }}</p>
                    <p class="text-sm text-gray-700 mt-2">Total Responses</p>
                    <p class="text-xs text-gray-600 mt-1">From all your requests</p>
                </div>

                <div class="text-center p-6 bg-accent-light rounded-lg">
                    <p class="text-3xl font-bold text-accent">{{ $totalDonors }}</p>
                    <p class="text-sm text-gray-700 mt-2">Unique Donors</p>
                    <p class="text-xs text-gray-600 mt-1">Helped your patients</p>
                </div>

                <div class="text-center p-6 bg-green-50 rounded-lg">
                    <p class="text-3xl font-bold text-green-600">{{ $responsesByStatus['donated'] }}</p>
                    <p class="text-sm text-green-800 mt-2">Successful Donations</p>
                    <p class="text-xs text-green-600 mt-1">Lives saved</p>
                </div>
            </div>

            <!-- Response Breakdown -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h4 class="font-medium text-gray-900 mb-4">Response Status Breakdown</h4>

                <div class="space-y-4">
                    @foreach ($responsesByStatus as $status => $count)
                        @php
                            $percentage = $totalDonorResponses > 0 ? ($count / $totalDonorResponses) * 100 : 0;
                            $colorClass = [
                                'interested' => 'bg-yellow-100 text-yellow-800',
                                'contacted' => 'bg-blue-100 text-blue-800',
                                'donated' => 'bg-green-100 text-green-800',
                            ][$status];
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-sm mb-1">
                                <div class="flex items-center">
                                    <span
                                        class="w-3 h-3 rounded-full 
                                    @if ($status == 'interested') bg-yellow-500
                                    @elseif($status == 'contacted') bg-blue-500
                                    @else bg-green-500 @endif 
                                    mr-2"></span>
                                    <span class="capitalize">{{ $status }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium text-gray-900">{{ $count }}</span>
                                    <span class="text-gray-600">{{ round($percentage) }}%</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full 
                                @if ($status == 'interested') bg-yellow-500
                                @elseif($status == 'contacted') bg-blue-500
                                @else bg-green-500 @endif"
                                    style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                <a href="{{ route('recipient.blood-requests.index') }}"
                    class="text-sm text-primary hover:text-primary-dark font-medium">
                    View All Requests <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            @php
                $recentRequests = $bloodRequests->sortByDesc('created_at')->take(3);
            @endphp

            <div class="space-y-4">
                @forelse($recentRequests as $request)
                    <div
                        class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                                <span class="text-sm font-bold text-red-600">{{ $request->blood_group }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $request->patient_name }}</p>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full 
                                    @if ($request->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($request->status == 'approved') bg-green-100 text-green-800
                                    @elseif($request->status == 'fulfilled') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                    <span class="text-xs text-gray-600">
                                        <i class="fas fa-hospital mr-1"></i>{{ $request->hospital_name }}
                                    </span>
                                    <span class="text-xs text-gray-600">
                                        <i class="fas fa-users mr-1"></i>{{ $request->donorResponses->count() }} donors
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">{{ $request->created_at->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-clock mr-1"></i> {{ $request->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-history text-xl mb-2"></i>
                        <p class="text-sm">No recent activity</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Achievement Badges -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Your Achievements</h3>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- First Request -->
                <div
                    class="text-center p-4 
                @if ($totalRequests >= 1) bg-blue-50 border border-blue-200
                @else bg-gray-50 border border-gray-200 @endif rounded-lg">
                    <div
                        class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center
                    @if ($totalRequests >= 1) bg-blue-100 text-blue-600
                    @else bg-gray-100 text-gray-400 @endif">
                        <i class="fas fa-star"></i>
                    </div>
                    <p
                        class="font-medium 
                    @if ($totalRequests >= 1) text-blue-800
                    @else text-gray-600 @endif">
                        First Request</p>
                    <p
                        class="text-xs 
                    @if ($totalRequests >= 1) text-blue-600
                    @else text-gray-500 @endif mt-1">
                        @if ($totalRequests >= 1)
                            Unlocked
                        @else
                            Create first request
                        @endif
                    </p>
                </div>

                <!-- Regular Requester -->
                <div
                    class="text-center p-4 
                @if ($totalRequests >= 5) bg-green-50 border border-green-200
                @else bg-gray-50 border border-gray-200 @endif rounded-lg">
                    <div
                        class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center
                    @if ($totalRequests >= 5) bg-green-100 text-green-600
                    @else bg-gray-100 text-gray-400 @endif">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <p
                        class="font-medium 
                    @if ($totalRequests >= 5) text-green-800
                    @else text-gray-600 @endif">
                        Regular Requester</p>
                    <p
                        class="text-xs 
                    @if ($totalRequests >= 5) text-green-600
                    @else text-gray-500 @endif mt-1">
                        @if ($totalRequests >= 5)
                            Unlocked
                        @else
                            5+ requests
                        @endif
                    </p>
                </div>

                <!-- Life Saver -->
                <div
                    class="text-center p-4 
                @if ($fulfilledRequests >= 1) bg-red-50 border border-red-200
                @else bg-gray-50 border border-gray-200 @endif rounded-lg">
                    <div
                        class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center
                    @if ($fulfilledRequests >= 1) bg-red-100 text-red-600
                    @else bg-gray-100 text-gray-400 @endif">
                        <i class="fas fa-heart"></i>
                    </div>
                    <p
                        class="font-medium 
                    @if ($fulfilledRequests >= 1) text-red-800
                    @else text-gray-600 @endif">
                        Life Saver</p>
                    <p
                        class="text-xs 
                    @if ($fulfilledRequests >= 1) text-red-600
                    @else text-gray-500 @endif mt-1">
                        @if ($fulfilledRequests >= 1)
                            Unlocked
                        @else
                            1+ fulfilled
                        @endif
                    </p>
                </div>

                <!-- Donor Magnet -->
                <div
                    class="text-center p-4 
                @if ($totalDonorResponses >= 10) bg-purple-50 border border-purple-200
                @else bg-gray-50 border border-gray-200 @endif rounded-lg">
                    <div
                        class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center
                    @if ($totalDonorResponses >= 10) bg-purple-100 text-purple-600
                    @else bg-gray-100 text-gray-400 @endif">
                        <i class="fas fa-magnet"></i>
                    </div>
                    <p
                        class="font-medium 
                    @if ($totalDonorResponses >= 10) text-purple-800
                    @else text-gray-600 @endif">
                        Donor Magnet</p>
                    <p
                        class="text-xs 
                    @if ($totalDonorResponses >= 10) text-purple-600
                    @else text-gray-500 @endif mt-1">
                        @if ($totalDonorResponses >= 10)
                            Unlocked
                        @else
                            10+ responses
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Status Distribution Chart
            const ctx = document.getElementById('statusChart').getContext('2d');

            @php
                $chartData = [
                    'labels' => array_column($statuses, 'text'),
                    'datasets' => [
                        [
                            'data' => array_column($statuses, 'count'),
                            'backgroundColor' => ['#f59e0b', '#10b981', '#3b82f6', '#6b7280'],
                            'borderColor' => '#ffffff',
                            'borderWidth' => 2,
                        ],
                    ],
                ];
            @endphp

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode(array_column($statuses, 'text')) !!},
                    datasets: [{
                        data: {!! json_encode(array_column($statuses, 'count')) !!},
                        backgroundColor: ['#f59e0b', '#10b981', '#3b82f6', '#6b7280'],
                        borderColor: '#ffffff',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = {{ $totalRequests }};
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
