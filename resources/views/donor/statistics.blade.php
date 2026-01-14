@extends('layouts.app')

@section('title', 'My Statistics')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">My Statistics</h2>
                    <p class="text-gray-600 mt-1">Track your donation journey and impact</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Total Donations</p>
                        @php
                            $donatedCount = auth()
                                ->user()
                                ->donorResponses()
                                ->where('response_status', 'donated')
                                ->count();
                        @endphp
                        <p class="text-xl font-bold text-primary">{{ $donatedCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Impact Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Donations -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-tint text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Donations</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $donatedCount }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-600">Lives potentially saved through your donations</p>
                </div>
            </div>

            <!-- Total Responses -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-hand-paper text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Responses</p>
                        <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->donorResponses()->count() }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-600">Times you've offered to help</p>
                </div>
            </div>

            <!-- Success Rate -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-chart-line text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Success Rate</p>
                        <p class="text-2xl font-bold text-primary">
                            @php
                                $totalResponses = auth()->user()->donorResponses()->count();
                                $successRate = $totalResponses > 0 ? round(($donatedCount / $totalResponses) * 100) : 0;
                            @endphp
                            {{ $successRate }}%
                        </p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-600">Of responses resulted in donation</p>
                </div>
            </div>

            <!-- Availability -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-primary-light rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-heart text-primary text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Availability</p>
                        <p class="text-xl font-bold text-primary">
                            @if (auth()->user()->donorProfile && auth()->user()->donorProfile->is_available)
                                Available
                            @else
                                Unavailable
                            @endif
                        </p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-600">Current donation status</p>
                </div>
            </div>
        </div>

        <!-- Detailed Statistics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Response Breakdown -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Response Breakdown</h3>

                @php
                    $responses = auth()->user()->donorResponses()->with('bloodRequest')->get();
                    $statusCounts = [
                        'interested' => $responses->where('response_status', 'interested')->count(),
                        'contacted' => $responses->where('response_status', 'contacted')->count(),
                        'donated' => $responses->where('response_status', 'donated')->count(),
                    ];
                    $total = $responses->count();
                @endphp

                <div class="space-y-4">
                    @foreach ($statusCounts as $status => $count)
                        @php
                            $percentage = $total > 0 ? ($count / $total) * 100 : 0;
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

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Most Common Blood Group</p>
                        @if (auth()->user()->donorProfile)
                            <p class="text-2xl font-bold text-primary">{{ auth()->user()->donorProfile->blood_group }}</p>
                        @else
                            <p class="text-2xl font-bold text-gray-400">N/A</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Monthly Activity Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Monthly Activity ({{ date('Y') }})</h3>

                <div class="space-y-4">
                    @php
                        $currentYear = date('Y');
                        $monthlyData = [];
                        for ($i = 1; $i <= 12; $i++) {
                            $count = auth()
                                ->user()
                                ->donorResponses()
                                ->whereYear('created_at', $currentYear)
                                ->whereMonth('created_at', $i)
                                ->count();
                            $monthlyData[] = [
                                'month' => date('M', mktime(0, 0, 0, $i, 1)),
                                'count' => $count,
                            ];
                        }
                        $maxCount = max(array_column($monthlyData, 'count')) ?: 1;
                    @endphp

                    @foreach ($monthlyData as $data)
                        <div class="flex items-center">
                            <div class="w-10 text-sm text-gray-600">{{ $data['month'] }}</div>
                            <div class="flex-1 mx-4">
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-primary h-3 rounded-full"
                                        style="width: {{ ($data['count'] / $maxCount) * 100 }}%"></div>
                                </div>
                            </div>
                            <div class="w-6 text-right text-sm font-medium text-gray-900">{{ $data['count'] }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Total This Year</p>
                        <p class="text-2xl font-bold text-primary">{{ array_sum(array_column($monthlyData, 'count')) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                <a href="{{ route('donor.responses.index') }}"
                    class="text-sm text-primary hover:text-primary-dark font-medium">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            @php
                $recentResponses = auth()->user()->donorResponses()->with('bloodRequest')->latest()->limit(5)->get();
            @endphp

            <div class="space-y-4">
                @forelse($recentResponses as $response)
                    <div
                        class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                                <span
                                    class="text-sm font-bold text-red-600">{{ $response->bloodRequest->blood_group }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $response->bloodRequest->patient_name }}
                                </p>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span class="text-xs text-gray-600">
                                        <i class="fas fa-hospital mr-1"></i>{{ $response->bloodRequest->hospital_name }}
                                    </span>
                                    <span class="text-xs text-gray-600">
                                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $response->bloodRequest->district }}
                                    </span>
                                </div>
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
                            <p class="text-xs text-gray-600 mt-1">{{ $response->created_at->format('M d, Y') }}</p>
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
                <!-- First Donation -->
                <div
                    class="text-center p-4 
                @if ($donatedCount >= 1) bg-green-50 border border-green-200
                @else bg-gray-50 border border-gray-200 @endif rounded-lg">
                    <div
                        class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center
                    @if ($donatedCount >= 1) bg-green-100 text-green-600
                    @else bg-gray-100 text-gray-400 @endif">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <p
                        class="font-medium 
                    @if ($donatedCount >= 1) text-green-800
                    @else text-gray-600 @endif">
                        First Donation</p>
                    <p
                        class="text-xs 
                    @if ($donatedCount >= 1) text-green-600
                    @else text-gray-500 @endif mt-1">
                        @if ($donatedCount >= 1)
                            Unlocked
                        @else
                            Donate once
                        @endif
                    </p>
                </div>

                <!-- Regular Donor -->
                <div
                    class="text-center p-4 
                @if ($donatedCount >= 3) bg-blue-50 border border-blue-200
                @else bg-gray-50 border border-gray-200 @endif rounded-lg">
                    <div
                        class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center
                    @if ($donatedCount >= 3) bg-blue-100 text-blue-600
                    @else bg-gray-100 text-gray-400 @endif">
                        <i class="fas fa-star"></i>
                    </div>
                    <p
                        class="font-medium 
                    @if ($donatedCount >= 3) text-blue-800
                    @else text-gray-600 @endif">
                        Regular Donor</p>
                    <p
                        class="text-xs 
                    @if ($donatedCount >= 3) text-blue-600
                    @else text-gray-500 @endif mt-1">
                        @if ($donatedCount >= 3)
                            Unlocked
                        @else
                            Donate 3 times
                        @endif
                    </p>
                </div>

                <!-- Life Saver -->
                <div
                    class="text-center p-4 
                @if ($donatedCount >= 5) bg-red-50 border border-red-200
                @else bg-gray-50 border border-gray-200 @endif rounded-lg">
                    <div
                        class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center
                    @if ($donatedCount >= 5) bg-red-100 text-red-600
                    @else bg-gray-100 text-gray-400 @endif">
                        <i class="fas fa-heart"></i>
                    </div>
                    <p
                        class="font-medium 
                    @if ($donatedCount >= 5) text-red-800
                    @else text-gray-600 @endif">
                        Life Saver</p>
                    <p
                        class="text-xs 
                    @if ($donatedCount >= 5) text-red-600
                    @else text-gray-500 @endif mt-1">
                        @if ($donatedCount >= 5)
                            Unlocked
                        @else
                            Donate 5 times
                        @endif
                    </p>
                </div>

                <!-- Quick Responder -->
                <div
                    class="text-center p-4 
                @if ($responses->count() >= 10) bg-purple-50 border border-purple-200
                @else bg-gray-50 border border-gray-200 @endif rounded-lg">
                    <div
                        class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center
                    @if ($responses->count() >= 10) bg-purple-100 text-purple-600
                    @else bg-gray-100 text-gray-400 @endif">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <p
                        class="font-medium 
                    @if ($responses->count() >= 10) text-purple-800
                    @else text-gray-600 @endif">
                        Quick Responder</p>
                    <p
                        class="text-xs 
                    @if ($responses->count() >= 10) text-purple-600
                    @else text-gray-500 @endif mt-1">
                        @if ($responses->count() >= 10)
                            Unlocked
                        @else
                            10+ responses
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
