@extends('layouts.app')

@section('title', 'User Management')

@section('header')
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
                <p class="mt-1 text-sm text-gray-600">Manage all system users</p>
            </div>
            <div class="flex items-center space-x-3">
                <select id="roleFilter"
                    class="block w-32 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 rounded-md">
                    <option value="all">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="donor">Donor</option>
                    <option value="recipient">Recipient</option>
                </select>

                <select id="verificationFilter"
                    class="block w-40 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 rounded-md">
                    <option value="all">All Status</option>
                    <option value="verified">Verified</option>
                    <option value="unverified">Unverified</option>
                </select>

                <button onclick="applyFilters()"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:p-6">
            <!-- Stats Bar -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-gray-500">Total Users</div>
                    <div class="text-2xl font-semibold text-gray-900">{{ $users->total() }}</div>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-blue-700">Admins</div>
                    <div class="text-2xl font-semibold text-blue-700">{{ $stats['admins'] }}</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-green-700">Donors</div>
                    <div class="text-2xl font-semibold text-green-700">{{ $stats['donors'] }}</div>
                </div>
                <div class="bg-red-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-red-700">Recipients</div>
                    <div class="text-2xl font-semibold text-red-700">{{ $stats['recipients'] }}</div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role & Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Joined
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                                <i class="fas fa-user text-red-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $user->role === 'admin'
                                        ? 'bg-purple-100 text-purple-800'
                                        : ($user->role === 'donor'
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($user->role) }}
                                        </span>

                                        @if ($user->is_verified)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Verified
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>
                                                Unverified
                                            </span>
                                        @endif

                                        @if ($user->donorProfile)
                                            @if ($user->donorProfile->approved_by_admin)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-heart mr-1"></i>
                                                    Approved Donor
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->phone ?? 'Not provided' }}</div>
                                    <div class="text-sm text-gray-500">
                                        @if ($user->donorProfile)
                                            {{ $user->donorProfile->district }}, {{ $user->donorProfile->upazila }}
                                        @elseif($user->recipientProfile)
                                            {{ $user->recipientProfile->district }},
                                            {{ $user->recipientProfile->upazila }}
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('M d, Y') }}
                                    <div class="text-xs text-gray-400">
                                        {{ $user->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <a href="{{ route('admin.users.show', $user) }}"
                                            class="text-red-600 hover:text-red-900" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if (!$user->is_verified && $user->id !== auth()->id())
                                            <button onclick="verifyUser({{ $user->id }})"
                                                class="text-green-600 hover:text-green-900" title="Verify User">
                                                <i class="fas fa-user-check"></i>
                                            </button>
                                        @endif

                                        @if ($user->is_verified && $user->id !== auth()->id())
                                            <button onclick="unverifyUser({{ $user->id }})"
                                                class="text-yellow-600 hover:text-yellow-900" title="Unverify User">
                                                <i class="fas fa-user-times"></i>
                                            </button>
                                        @endif

                                        @if ($user->role !== 'admin' && $user->id !== auth()->id())
                                            <button onclick="changeRole({{ $user->id }})"
                                                class="text-blue-600 hover:text-blue-900" title="Change Role">
                                                <i class="fas fa-user-cog"></i>
                                            </button>

                                            <button onclick="deleteUser({{ $user->id }})"
                                                class="text-red-600 hover:text-red-900" title="Delete User">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function applyFilters() {
            const role = document.getElementById('roleFilter').value;
            const verification = document.getElementById('verificationFilter').value;

            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search);

            if (role !== 'all') params.set('role', role);
            else params.delete('role');

            if (verification !== 'all') {
                params.set('verified', verification === 'verified' ? '1' : '0');
            } else {
                params.delete('verified');
            }

            params.set('page', '1');
            window.location.href = url.pathname + '?' + params.toString();
        }

        function verifyUser(userId) {
            if (confirm('Verify this user?')) {
                fetch(`/admin/users/${userId}/verify`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        location.reload();
                    });
            }
        }

        function unverifyUser(userId) {
            if (confirm('Unverify this user?')) {
                fetch(`/admin/users/${userId}/unverify`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        location.reload();
                    });
            }
        }

        function changeRole(userId) {
            const newRole = prompt('Enter new role (admin, donor, recipient):');
            if (newRole && ['admin', 'donor', 'recipient'].includes(newRole.toLowerCase())) {
                fetch(`/admin/users/${userId}/role`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            role: newRole.toLowerCase()
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        location.reload();
                    });
            } else if (newRole) {
                alert('Invalid role. Must be admin, donor, or recipient.');
            }
        }

        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                fetch(`/admin/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        location.reload();
                    });
            }
        }

        // Initialize filters from URL
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const role = urlParams.get('role') || 'all';
            const verification = urlParams.get('verified');

            document.getElementById('roleFilter').value = role;

            if (verification === '1') {
                document.getElementById('verificationFilter').value = 'verified';
            } else if (verification === '0') {
                document.getElementById('verificationFilter').value = 'unverified';
            } else {
                document.getElementById('verificationFilter').value = 'all';
            }
        });
    </script>
@endpush
