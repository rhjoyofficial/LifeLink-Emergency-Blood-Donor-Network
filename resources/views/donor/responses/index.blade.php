@extends('layouts.app')

@section('title', 'My Responses')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">My Responses</h2>
                    <p class="text-gray-600 mt-1">History of your responses to blood requests</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Total Responses</p>
                        <p class="text-xl font-bold text-primary">{{ $responses->total() }}</p>
                    </div>
                </div>
            </div>

            <!-- Stats Summary -->
            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                @php
                    $interestedCount = $responses->where('response_status', 'interested')->count();
                    $contactedCount = $responses->where('response_status', 'contacted')->count();
                    $donatedCount = $responses->where('response_status', 'donated')->count();
                @endphp

                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Interested</p>
                    <p class="text-xl font-bold text-yellow-600">{{ $interestedCount }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Contacted</p>
                    <p class="text-xl font-bold text-blue-600">{{ $contactedCount }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Donated</p>
                    <p class="text-xl font-bold text-green-600">{{ $donatedCount }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Success Rate</p>
                    <p class="text-xl font-bold text-primary">
                        {{ $responses->total() > 0 ? round(($donatedCount / $responses->total()) * 100) : 0 }}%
                    </p>
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
                                Your Response
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Request Status
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
                                            <div class="text-sm text-gray-500">{{ $response->bloodRequest->district }}</div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ $response->created_at->format('M d, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $response->bloodRequest->patient_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $response->bloodRequest->bags_required }} bag(s)
                                    </div>
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
                                    <div class="space-y-1">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full 
                                        @if ($response->response_status == 'interested') bg-yellow-100 text-yellow-800
                                        @elseif($response->response_status == 'contacted') bg-blue-100 text-blue-800
                                        @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($response->response_status) }}
                                        </span>
                                        <div class="text-xs text-gray-500">
                                            {{ $response->created_at->format('M d, h:i A') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full 
                                        @if ($response->bloodRequest->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($response->bloodRequest->status == 'approved') bg-green-100 text-green-800
                                        @elseif($response->bloodRequest->status == 'fulfilled') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($response->bloodRequest->status) }}
                                        </span>
                                        <div class="text-xs text-gray-500">
                                            @if ($response->bloodRequest->needed_at->isFuture())
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ $response->bloodRequest->needed_at->diffForHumans() }}
                                            @else
                                                <i class="fas fa-check mr-1"></i> Deadline passed
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('donor.blood-requests.show', $response->bloodRequest) }}"
                                            class="text-primary hover:text-primary-dark">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if (
                                            $response->response_status == 'interested' &&
                                                $response->bloodRequest->status == 'approved' &&
                                                $response->bloodRequest->needed_at->isFuture())
                                            <button type="button" onclick="openUpdateModal('{{ $response->id }}')"
                                                class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-hand-paper text-3xl mb-3"></i>
                                        <p>No responses yet</p>
                                        <p class="text-sm mt-1">You haven't responded to any blood requests yet</p>
                                        <a href="{{ route('donor.blood-requests.available') }}"
                                            class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors mt-4">
                                            <i class="fas fa-search mr-2"></i> Find Requests
                                        </a>
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

        <!-- Response Statistics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Monthly Activity -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Monthly Activity</h3>

                @php
                    $currentYear = date('Y');
                    $monthlyResponses = [];
                    for ($i = 1; $i <= 12; $i++) {
                        $month = sprintf('%02d', $i);
                        $count = auth()
                            ->user()
                            ->donorResponses()
                            ->whereYear('created_at', $currentYear)
                            ->whereMonth('created_at', $i)
                            ->count();
                        $monthlyResponses[] = [
                            'month' => date('M', mktime(0, 0, 0, $i, 1)),
                            'count' => $count,
                        ];
                    }
                    $maxCount = max(array_column($monthlyResponses, 'count')) ?: 1;
                @endphp

                <div class="space-y-4">
                    @foreach ($monthlyResponses as $data)
                        <div class="flex items-center">
                            <div class="w-12 text-sm text-gray-600">{{ $data['month'] }}</div>
                            <div class="flex-1 ml-4">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-primary h-2 rounded-full"
                                        style="width: {{ ($data['count'] / $maxCount) * 100 }}%"></div>
                                </div>
                            </div>
                            <div class="w-8 text-right text-sm font-medium text-gray-900">{{ $data['count'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Donation Impact -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Your Donation Impact</h3>

                <div class="space-y-6">
                    <div class="text-center">
                        <p class="text-4xl font-bold text-primary">{{ $donatedCount }}</p>
                        <p class="text-sm text-gray-600 mt-2">Lives Saved</p>
                        <p class="text-xs text-gray-500 mt-1">Through your successful donations</p>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Total Responses</span>
                            <span class="font-medium text-gray-900">{{ $responses->total() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-green-600">Successful Donations</span>
                            <span class="font-medium text-gray-900">{{ $donatedCount }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-blue-600">Contacted Status</span>
                            <span class="font-medium text-gray-900">{{ $contactedCount }}</span>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Donation Success Rate</p>
                            <p class="text-2xl font-bold text-primary">
                                {{ $responses->total() > 0 ? round(($donatedCount / $responses->total()) * 100) : 0 }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div id="updateModal"
        class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Update Response Status</h3>
                <p class="text-sm text-gray-600 mb-4">Update your response status for this blood request</p>

                <form id="updateForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Status</label>
                            <select name="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                <option value="contacted">Contacted - I've been in touch with the hospital</option>
                                <option value="donated">Donated - I successfully donated blood</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-4">
                            <button type="button" onclick="closeUpdateModal()"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                                Update Status
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openUpdateModal(responseId) {
                const modal = document.getElementById('updateModal');
                const form = document.getElementById('updateForm');

                // Set form action
                form.action = `/donor/responses/${responseId}/status`;

                // Show modal
                modal.classList.remove('hidden');
            }

            function closeUpdateModal() {
                const modal = document.getElementById('updateModal');
                modal.classList.add('hidden');
            }

            // Close modal on outside click
            document.getElementById('updateModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeUpdateModal();
                }
            });
        </script>
    @endpush
@endsection
