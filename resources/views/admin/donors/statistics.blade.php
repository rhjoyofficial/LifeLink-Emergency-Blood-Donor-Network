@extends('layouts.app')

@section('title', 'Donor Statistics')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Donor Statistics</h2>
                    <p class="text-gray-600 mt-1">Comprehensive donor analysis and insights</p>
                </div>
            </div>

            <!-- Summary Stats -->
            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-blue-800">Total Donors</p>
                    <p class="text-xl font-bold text-blue-900">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-sm text-green-800">Approved</p>
                    <p class="text-xl font-bold text-green-900">{{ $stats['approved'] }}</p>
                </div>
                <div class="bg-primary-light p-4 rounded-lg">
                    <p class="text-sm text-primary">Available</p>
                    <p class="text-xl font-bold text-primary-dark">{{ $stats['available'] }}</p>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <p class="text-sm text-purple-800">Approval Rate</p>
                    <p class="text-xl font-bold text-purple-900">
                        {{ $stats['total'] > 0 ? round(($stats['approved'] / $stats['total']) * 100) : 0 }}%
                    </p>
                </div>
            </div>
        </div>

        <!-- Blood Group Distribution -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Blood Group Distribution</h3>

            @php
                $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
                $donorProfiles = \App\Models\DonorProfile::all();
                $bloodGroupCounts = [];
                foreach ($bloodGroups as $group) {
                    $bloodGroupCounts[$group] = $donorProfiles->where('blood_group', $group)->count();
                }
                $totalDonors = $donorProfiles->count();
            @endphp

            <div class="space-y-4">
                @foreach ($bloodGroups as $group)
                    @php
                        $count = $bloodGroupCounts[$group];
                        $percentage = $totalDonors > 0 ? ($count / $totalDonors) * 100 : 0;
                    @endphp
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center">
                                <span
                                    class="w-12 text-center px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded mr-3">
                                    {{ $group }}
                                </span>
                                <span class="text-gray-700">{{ $count }} donors</span>
                            </div>
                            <span class="text-gray-600">{{ round($percentage) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- District Distribution -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Donor Distribution by District</h3>

            @php
                $districts = \App\Models\DonorProfile::select('district', \DB::raw('count(*) as total'))
                    ->groupBy('district')
                    ->orderByDesc('total')
                    ->limit(10)
                    ->get();
            @endphp

            <div class="space-y-4">
                @foreach ($districts as $district)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-primary mr-3"></i>
                            <span class="text-gray-900">{{ $district->district }}</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600">{{ $district->total }} donors</span>
                            <span class="text-sm text-gray-500">
                                {{ $totalDonors > 0 ? round(($district->total / $totalDonors) * 100) : 0 }}%
                            </span>
                        </div>
                    </div>
                @endforeach

                @if ($districts->count() == 0)
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-map-marked-alt text-xl mb-2"></i>
                        <p class="text-sm">No district data available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Donor Activity -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recent Donor Activity</h3>
                <a href="{{ route('admin.donors.index') }}"
                    class="text-sm text-primary hover:text-primary-dark font-medium">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            @php
                $recentDonors = \App\Models\DonorProfile::with('user')->latest()->limit(5)->get();
            @endphp

            <div class="space-y-4">
                @foreach ($recentDonors as $donor)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-medium mr-3">
                                {{ substr($donor->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $donor->user->name }}</p>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                        {{ $donor->blood_group }}
                                    </span>
                                    <span class="text-xs text-gray-600">{{ $donor->district }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full 
                            @if ($donor->approved_by_admin) bg-green-100 text-green-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                                {{ $donor->approved_by_admin ? 'Approved' : 'Pending' }}
                            </span>
                            <p class="text-xs text-gray-600 mt-1">{{ $donor->created_at->format('M d') }}</p>
                        </div>
                    </div>
                @endforeach

                @if ($recentDonors->count() == 0)
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-users-slash text-xl mb-2"></i>
                        <p class="text-sm">No recent donor activity</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
