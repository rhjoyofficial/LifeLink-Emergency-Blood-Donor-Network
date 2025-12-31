@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <svg class="h-5 w-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                clip-rule="evenodd" />
        </svg>
        <span class="text-gray-500">Home</span>
    </li>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
                <p class="text-gray-600">Manage blood requests, donors, and users</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="refreshStats()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Refresh
                </button>
                <a href="{{ route('admin.reports') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Reports
                </a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ([['icon' => 'fas fa-tint', 'color' => 'red', 'label' => 'Total Requests', 'value' => $stats['total_requests'] ?? 0, 'link' => route('admin.blood-requests.index')], ['icon' => 'fas fa-clock', 'color' => 'yellow', 'label' => 'Pending Approval', 'value' => $stats['pending_requests'] ?? 0, 'link' => route('admin.blood-requests.index', ['status' => 'pending'])], ['icon' => 'fas fa-check-circle', 'color' => 'green', 'label' => 'Active Requests', 'value' => $stats['approved_requests'] ?? 0, 'link' => route('admin.blood-requests.index', ['status' => 'approved'])], ['icon' => 'fas fa-users', 'color' => 'blue', 'label' => 'Active Donors', 'value' => $stats['approved_donors'] ?? 0, 'link' => route('admin.donors.index', ['status' => 'approved'])]] as $stat)
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="h-12 w-12 rounded-lg bg-{{ $stat['color'] }}-100 flex items-center justify-center">
                            <i class="{{ $stat['icon'] }} text-{{ $stat['color'] }}-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">{{ $stat['label'] }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stat['value'] }}</p>
                        </div>
                    </div>
                    <a href="{{ $stat['link'] }}"
                        class="mt-4 inline-block text-sm text-red-600 hover:text-red-700 font-medium">
                        View details →
                    </a>
                </div>
            @endforeach
        </div>

        <!-- After Stats Grid, before Charts and Tables -->
        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Monthly Requests Chart -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Request Trends</h3>
                        <p class="text-sm text-gray-500">Last 6 months</p>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="updateChartTimeframe('monthly')" class="chart-timeframe-btn active"
                            data-timeframe="monthly">
                            Monthly
                        </button>
                        <button onclick="updateChartTimeframe('weekly')" class="chart-timeframe-btn"
                            data-timeframe="weekly">
                            Weekly
                        </button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="requestsChart"></canvas>
                </div>
            </div>

            <!-- Blood Group Distribution -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Blood Group Distribution</h3>
                        <p class="text-sm text-gray-500">All time requests</p>
                    </div>
                    <a href="{{ route('admin.blood-requests.index') }}" class="text-sm text-red-600 hover:text-red-700">
                        View details
                    </a>
                </div>
                <div class="chart-container">
                    <canvas id="bloodGroupChart"></canvas>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-3" id="bloodGroupLegend"></div>
            </div>
        </div>

        <!-- Status Distribution Chart - Add this inside the Quick Stats column -->
        <!-- In the Quick Stats column, add after System Status -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Request Status</h3>
            <div class="chart-container">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Charts and Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Requests -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-900">Recent Blood Requests</h2>
                            <a href="{{ route('admin.blood-requests.index') }}"
                                class="text-sm text-red-600 hover:text-red-700">
                                View all
                            </a>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($recentRequests as $request)
                            <div class="px-6 py-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span
                                            class="blood-group-badge {{ strtolower(str_replace('+', 'p', $request->blood_group)) }}">
                                            {{ $request->blood_group }}
                                        </span>
                                        <div class="ml-4">
                                            <p class="font-medium text-gray-900">{{ $request->patient_name }}</p>
                                            <p class="text-sm text-gray-500">{{ $request->hospital_name }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="urgency-badge {{ $request->urgency_level }}">
                                            {{ ucfirst($request->urgency_level) }}
                                        </span>
                                        <span class="status-badge {{ $request->status }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                        <a href="{{ route('admin.blood-requests.show', $request) }}"
                                            class="text-gray-400 hover:text-red-600">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-3 text-gray-300"></i>
                                <p>No recent requests</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.blood-requests.index', ['status' => 'pending']) }}"
                            class="flex items-center justify-between p-3 bg-red-50 rounded-lg hover:bg-red-100">
                            <div class="flex items-center">
                                <i class="fas fa-clipboard-check text-red-600 mr-3"></i>
                                <span class="font-medium text-gray-900">Review Pending Requests</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>

                        <a href="{{ route('admin.donors.index', ['status' => 'pending']) }}"
                            class="flex items-center justify-between p-3 bg-blue-50 rounded-lg hover:bg-blue-100">
                            <div class="flex items-center">
                                <i class="fas fa-user-check text-blue-600 mr-3"></i>
                                <span class="font-medium text-gray-900">Approve Donors</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>

                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center justify-between p-3 bg-green-50 rounded-lg hover:bg-green-100">
                            <div class="flex items-center">
                                <i class="fas fa-user-shield text-green-600 mr-3"></i>
                                <span class="font-medium text-gray-900">Manage Users</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </div>
                </div>

                <!-- System Status -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">System Status</h3>
                    <div class="space-y-4">
                        @foreach ([['label' => 'Total Users', 'value' => $stats['total_users'] ?? 0], ['label' => 'Today\'s Requests', 'value' => $stats['today_requests'] ?? 0], ['label' => 'Urgent Requests', 'value' => $stats['urgent_requests'] ?? 0]] as $item)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">{{ $item['label'] }}</span>
                                <span class="font-medium text-gray-900">{{ $item['value'] }}</span>
                            </div>
                        @endforeach
                        <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                            <span class="text-gray-600">System Health</span>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Operational
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function refreshStats() {
                const button = event.target.closest('button');
                const originalHtml = button.innerHTML;

                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Refreshing...';
                button.disabled = true;

                fetch('{{ route('admin.statistics') }}')
                    .then(response => response.json())
                    .then(data => {
                        // You would update your stats here
                        console.log('Stats refreshed:', data);

                        // Restore button
                        setTimeout(() => {
                            button.innerHTML = originalHtml;
                            button.disabled = false;
                        }, 1000);
                    })
                    .catch(error => {
                        console.error('Error refreshing stats:', error);
                        button.innerHTML = originalHtml;
                        button.disabled = false;
                    });
            }

            // Initialize charts if you have them
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize any charts here
            });
        </script>
    @endpush
@endsection

@push('styles')
    <style>
        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }

        .chart-timeframe-btn {
            @apply px-3 py-1 text-sm rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50;
        }

        .chart-timeframe-btn.active {
            @apply bg-red-50 border-red-300 text-red-700;
        }

        .blood-group-badge {
            @apply inline-flex items-center px-3 py-1 rounded-full text-sm font-medium;
        }

        .blood-group-badge.a\\+ {
            @apply bg-red-100 text-red-800;
        }

        .blood-group-badge.a\\- {
            @apply bg-red-200 text-red-900;
        }

        .blood-group-badge.b\\+ {
            @apply bg-blue-100 text-blue-800;
        }

        .blood-group-badge.b\\- {
            @apply bg-blue-200 text-blue-900;
        }

        .blood-group-badge.ab\\+ {
            @apply bg-purple-100 text-purple-800;
        }

        .blood-group-badge.ab\\- {
            @apply bg-purple-200 text-purple-900;
        }

        .blood-group-badge.o\\+ {
            @apply bg-green-100 text-green-800;
        }

        .blood-group-badge.o\\- {
            @apply bg-green-200 text-green-900;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let requestsChart = null;
        let bloodGroupChart = null;
        let statusChart = null;

        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            setupChartResize();
        });

        function initializeCharts() {
            // Destroy existing charts if they exist
            if (requestsChart) requestsChart.destroy();
            if (bloodGroupChart) bloodGroupChart.destroy();
            if (statusChart) statusChart.destroy();

            // 1. Monthly Requests Chart
            const requestsCtx = document.getElementById('requestsChart').getContext('2d');
            requestsChart = new Chart(requestsCtx, {
                type: 'line',
                data: {
                    labels: @json($monthlyRequests['labels']),
                    datasets: [{
                        label: 'Blood Requests',
                        data: @json($monthlyRequests['data']),
                        borderColor: '#DC2626',
                        backgroundColor: 'rgba(220, 38, 38, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#DC2626',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
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
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#DC2626',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.parsed.y} requests`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    if (Number.isInteger(value)) {
                                        return value;
                                    }
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // 2. Blood Group Distribution Chart
            const bloodGroupData = @json($bloodGroupDistribution);
            const bloodGroupCtx = document.getElementById('bloodGroupChart').getContext('2d');

            const bloodGroupColors = {
                'A+': '#EF4444',
                'A-': '#F87171',
                'B+': '#3B82F6',
                'B-': '#60A5FA',
                'AB+': '#8B5CF6',
                'AB-': '#A78BFA',
                'O+': '#10B981',
                'O-': '#34D399'
            };

            bloodGroupChart = new Chart(bloodGroupCtx, {
                type: 'doughnut',
                data: {
                    labels: bloodGroupData.map(item => item.group),
                    datasets: [{
                        data: bloodGroupData.map(item => item.count),
                        backgroundColor: bloodGroupData.map(item => bloodGroupColors[item.group]),
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((context.parsed / total) * 100);
                                    return `${context.label}: ${context.parsed} requests (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // Update legend
            updateBloodGroupLegend(bloodGroupData, bloodGroupColors);

            // 3. Status Distribution Chart
            const statusData = @json($statusDistribution);
            const statusCtx = document.getElementById('statusChart').getContext('2d');

            const statusColors = {
                'pending': '#F59E0B',
                'approved': '#3B82F6',
                'fulfilled': '#10B981',
                'rejected': '#EF4444',
                'cancelled': '#6B7280'
            };

            statusChart = new Chart(statusCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(statusData).map(key => key.charAt(0).toUpperCase() + key.slice(1)),
                    datasets: [{
                        data: Object.values(statusData),
                        backgroundColor: Object.keys(statusData).map(key => statusColors[key]),
                        borderWidth: 0,
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: false
                            },
                            ticks: {
                                callback: function(value) {
                                    if (Number.isInteger(value)) {
                                        return value;
                                    }
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        function updateBloodGroupLegend(data, colors) {
            const legendContainer = document.getElementById('bloodGroupLegend');
            if (!legendContainer) return;

            legendContainer.innerHTML = '';

            data.forEach(item => {
                const total = data.reduce((sum, i) => sum + i.count, 0);
                const percentage = Math.round((item.count / total) * 100);

                const legendItem = document.createElement('div');
                legendItem.className = 'flex items-center justify-between p-2 bg-gray-50 rounded-lg';
                legendItem.innerHTML = `
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full mr-2" style="background-color: ${colors[item.group]}"></div>
                    <span class="text-sm font-medium text-gray-900">${item.group}</span>
                </div>
                <div class="text-right">
                    <div class="text-sm font-bold text-gray-900">${item.count}</div>
                    <div class="text-xs text-gray-500">${percentage}%</div>
                </div>
            `;
                legendContainer.appendChild(legendItem);
            });
        }

        function updateChartTimeframe(timeframe) {
            // Update active button
            document.querySelectorAll('.chart-timeframe-btn').forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.timeframe === timeframe) {
                    btn.classList.add('active');
                }
            });

            // Show loading state
            const chartContainer = document.querySelector('#requestsChart').closest('.chart-container');
            chartContainer.innerHTML =
                '<div class="flex items-center justify-center h-full"><i class="fas fa-spinner fa-spin text-2xl text-red-600"></i></div>';

            // Fetch new data
            fetch(`{{ route('admin.statistics') }}?timeframe=${timeframe}`)
                .then(response => response.json())
                .then(data => {
                    // Update chart data
                    requestsChart.data.labels = data.labels;
                    requestsChart.data.datasets[0].data = data.data;
                    requestsChart.update();

                    // Restore chart
                    chartContainer.innerHTML = '<canvas id="requestsChart"></canvas>';
                    const ctx = document.getElementById('requestsChart').getContext('2d');
                    requestsChart.ctx = ctx;
                    requestsChart.canvas = document.getElementById('requestsChart');
                })
                .catch(error => {
                    console.error('Error updating chart:', error);
                    location.reload();
                });
        }

        function setupChartResize() {
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (requestsChart) requestsChart.resize();
                    if (bloodGroupChart) bloodGroupChart.resize();
                    if (statusChart) statusChart.resize();
                }, 250);
            });
        }

        function refreshStats() {
            const button = event.target.closest('button');
            const originalHtml = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Refreshing...';
            button.disabled = true;

            fetch('{{ route('admin.statistics') }}')
                .then(response => response.json())
                .then(data => {
                    // Update all charts
                    initializeCharts();

                    // Restore button
                    setTimeout(() => {
                        button.innerHTML = originalHtml;
                        button.disabled = false;
                    }, 1000);
                })
                .catch(error => {
                    console.error('Error refreshing stats:', error);
                    button.innerHTML = originalHtml;
                    button.disabled = false;
                });
        }
    </script>
@endpush
