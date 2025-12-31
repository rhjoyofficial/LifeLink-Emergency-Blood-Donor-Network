@extends('layouts.app')

@section('title', 'Recipient Dashboard')

@section('header')
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Welcome, {{ auth()->user()->name }}</h1>
                <p class="mt-1 text-sm text-gray-600">Manage your blood requests and find donors</p>
            </div>
            <div>
                <a href="{{ route('recipient.blood-requests.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-plus mr-2"></i>
                    New Blood Request
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Active Requests -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                        <i class="fas fa-tint text-red-600 h-6 w-6"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Active Requests
                            </dt>
                            <dd class="text-lg font-semibold text-gray-900">
                                {{ $stats['active_requests'] ?? '0' }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Approval -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                        <i class="fas fa-clock text-yellow-600 h-6 w-6"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Pending Approval
                            </dt>
                            <dd class="text-lg font-semibold text-gray-900">
                                {{ $stats['pending_requests'] ?? '0' }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fulfilled Requests -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                        <i class="fas fa-heart text-green-600 h-6 w-6"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Fulfilled Requests
                            </dt>
                            <dd class="text-lg font-semibold text-gray-900">
                                {{ $stats['fulfilled_requests'] ?? '0' }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Donors Found -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                        <i class="fas fa-users text-blue-600 h-6 w-6"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Donors Found
                            </dt>
                            <dd class="text-lg font-semibold text-gray-900">
                                {{ $stats['donors_contacted'] ?? '0' }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Requests -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Recent Blood Requests</h3>
                        <a href="{{ route('recipient.blood-requests.index') }}"
                            class="text-sm font-medium text-red-600 hover:text-red-500">
                            View all
                        </a>
                    </div>
                </div>
                <div class="flow-root">
                    <ul role="list" class="divide-y divide-gray-200">
                        @forelse($recentRequests as $request)
                            <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span
                                            class="blood-group-badge {{ strtolower(str_replace('+', 'p', $request->blood_group)) }}">
                                            {{ $request->blood_group }}
                                        </span>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $request->patient_name }}
                                            </p>
                                            <div class="flex items-center text-sm text-gray-500">
                                                <span class="mr-3">
                                                    <i class="fas fa-hospital mr-1"></i>
                                                    {{ $request->hospital_name }}
                                                </span>
                                                <span>
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    {{ $request->needed_at->format('M d, Y') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="urgency-badge {{ $request->urgency_level }}">
                                            {{ ucfirst($request->urgency_level) }}
                                        </span>
                                        <span class="status-badge {{ $request->status }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                        @if ($request->status === 'approved' && $request->donorResponses->count() > 0)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-user-check mr-1"></i>
                                                {{ $request->donorResponses->count() }}
                                            </span>
                                        @endif
                                        <a href="{{ route('recipient.blood-requests.show', $request) }}"
                                            class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2 text-gray-300"></i>
                                <p>No blood requests yet.</p>
                                <a href="{{ route('recipient.blood-requests.create') }}"
                                    class="text-red-600 hover:text-red-500 mt-2 inline-block">
                                    Create your first request
                                </a>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Available Donors -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Available Donors in
                        {{ auth()->user()->recipientProfile->district ?? 'Your Area' }}</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach ($donorsByBloodGroup as $bloodGroup => $count)
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-medium text-gray-700">{{ $bloodGroup }}</span>
                                    <span class="text-gray-500">{{ $count }} donors</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-600 h-2 rounded-full"
                                        style="width: {{ ($count / max($donorsByBloodGroup->max(), 1)) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if ($donorsByBloodGroup->isEmpty())
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-users text-3xl mb-2 text-gray-300"></i>
                            <p>No donors available in your area</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Urgent Requests -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Urgent Requests Nearby</h3>
                </div>
                <div class="p-4">
                    <div class="space-y-4">
                        @forelse($urgentRequests as $request)
                            <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="flex items-center mb-2">
                                            <span
                                                class="blood-group-badge {{ strtolower(str_replace('+', 'p', $request->blood_group)) }}">
                                                {{ $request->blood_group }}
                                            </span>
                                            <span class="ml-2 text-sm font-medium text-gray-900">
                                                {{ $request->patient_name }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-hospital mr-1"></i>
                                            {{ $request->hospital_name }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-clock mr-1"></i>
                                            Needed by {{ $request->needed_at->format('M d, h:i A') }}
                                        </p>
                                    </div>
                                    <span class="urgency-badge critical">Critical</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-gray-500">
                                <i class="fas fa-check-circle text-green-500 text-3xl mb-2"></i>
                                <p>No urgent requests in your area</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
