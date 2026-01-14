@extends('layouts.app')

@section('title', 'User Management')

@section('content')
    <div class="space-y-6">
        <!-- Header with Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">User Management</h2>
                    <p class="text-gray-600 mt-1">Manage all users in the system</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-2">
                        <select name="role" onchange="this.form.submit()"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="donor" {{ request('role') == 'donor' ? 'selected' : '' }}>Donor</option>
                            <option value="recipient" {{ request('role') == 'recipient' ? 'selected' : '' }}>Recipient
                            </option>
                        </select>

                        <select name="verified" onchange="this.form.submit()"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                            <option value="">All Verification</option>
                            <option value="yes" {{ request('verified') == 'yes' ? 'selected' : '' }}>Verified</option>
                            <option value="no" {{ request('verified') == 'no' ? 'selected' : '' }}>Not Verified</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Stats Summary -->
            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-users text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Users</p>
                            <p class="text-xl font-bold text-gray-900">{{ $users->total() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user-shield text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Admins</p>
                            <p class="text-xl font-bold text-gray-900">{{ $users->where('role', 'admin')->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-primary-light rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-hand-holding-heart text-primary"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Donors</p>
                            <p class="text-xl font-bold text-gray-900">{{ $users->where('role', 'donor')->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user-injured text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Recipients</p>
                            <p class="text-xl font-bold text-gray-900">{{ $users->where('role', 'recipient')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role & Profile
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact Information
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Account Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 
                                        @if ($user->role == 'admin') bg-red-100 text-red-600
                                        @elseif($user->role == 'donor') bg-primary-light text-primary
                                        @else bg-green-100 text-green-600 @endif 
                                        rounded-full flex items-center justify-center font-medium">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            <div class="text-xs text-gray-500">
                                                Joined {{ $user->created_at->format('M d, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full 
                                        @if ($user->role == 'admin') bg-red-100 text-red-800
                                        @elseif($user->role == 'donor') bg-primary-light text-primary
                                        @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($user->role) }}
                                        </span>

                                        @if ($user->isDonor() && $user->donorProfile)
                                            <div class="text-xs text-gray-600">
                                                <i class="fas fa-tint mr-1"></i> {{ $user->donorProfile->blood_group }}
                                            </div>
                                            <div class="text-xs text-gray-600">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                {{ $user->donorProfile->district }}
                                            </div>
                                        @elseif($user->isRecipient() && $user->recipientProfile)
                                            <div class="text-xs text-gray-600">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                {{ $user->recipientProfile->district }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->phone }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        @if ($user->is_verified)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i> Verified
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i> Not Verified
                                            </span>
                                        @endif

                                        <div class="text-xs text-gray-500">
                                            @if ($user->email_verified_at)
                                                <i class="fas fa-envelope mr-1"></i> Email verified
                                            @else
                                                <i class="fas fa-envelope-open mr-1"></i> Email not verified
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.users.show', $user) }}"
                                            class="text-primary hover:text-primary-dark">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if (!$user->is_verified && !$user->isAdmin())
                                            <form action="{{ route('admin.users.verify', $user) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-600 hover:text-green-800"
                                                    onclick="return confirm('Verify this user?')">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if ($user->is_verified && !$user->isAdmin())
                                            <form action="{{ route('admin.users.unverify', $user) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-800"
                                                    onclick="return confirm('Unverify this user?')">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if (!$user->isAdmin() && $user->id !== auth()->id())
                                            <button type="button"
                                                onclick="openRoleModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->role }}')"
                                                class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-user-cog"></i>
                                            </button>

                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800"
                                                    onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-users-slash text-3xl mb-3"></i>
                                        <p>No users found</p>
                                        <p class="text-sm mt-1">No users match your current filters</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $users->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Role Update Modal -->
    <div id="roleModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Update User Role</h3>
                <p class="text-sm text-gray-600 mb-4" id="modalUserName"></p>

                <form id="roleForm" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Role</label>
                            <select name="role"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                <option value="donor">Donor</option>
                                <option value="recipient">Recipient</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-4">
                            <button type="button" onclick="closeRoleModal()"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                                Update Role
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openRoleModal(userId, userName, currentRole) {
                const modal = document.getElementById('roleModal');
                const form = document.getElementById('roleForm');
                const userNameElement = document.getElementById('modalUserName');

                // Set form action
                form.action = `/admin/users/${userId}/role`;

                // Set user name
                userNameElement.textContent = `Update role for ${userName}`;

                // Set current role
                form.querySelector('select[name="role"]').value = currentRole;

                // Show modal
                modal.classList.remove('hidden');
            }

            function closeRoleModal() {
                const modal = document.getElementById('roleModal');
                modal.classList.add('hidden');
            }

            // Close modal on outside click
            document.getElementById('roleModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeRoleModal();
                }
            });
        </script>
    @endpush
@endsection
