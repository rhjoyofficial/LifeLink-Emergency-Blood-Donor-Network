@extends('layouts.app')

@section('title', 'User Details')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center">
                        <a href="{{ route('admin.users.index') }}" class="text-primary hover:text-primary-dark mr-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">User Details</h2>
                            <p class="text-gray-600 mt-1">Complete information about this user</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    @if (!$user->isAdmin() && $user->id !== auth()->id())
                        <form action="{{ route('admin.users.verify', $user) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium"
                                onclick="return confirm('Verify this user?')">
                                <i class="fas fa-check-circle mr-2"></i> Verify User
                            </button>
                        </form>

                        <form action="{{ route('admin.users.unverify', $user) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors font-medium"
                                onclick="return confirm('Unverify this user?')">
                                <i class="fas fa-times-circle mr-2"></i> Unverify
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: User Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Basic Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Full Name</label>
                                <p class="mt-1 text-gray-900 font-medium">{{ $user->name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Email Address</label>
                                <p class="mt-1 text-gray-900">{{ $user->email }}</p>
                                <div class="mt-1">
                                    @if ($user->email_verified_at)
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Email Verified
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i> Email Not Verified
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Phone Number</label>
                                <p class="mt-1 text-gray-900">{{ $user->phone }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Account Role</label>
                                <div class="mt-1">
                                    <span
                                        class="px-3 py-1 text-sm font-medium rounded-full 
                                    @if ($user->role == 'admin') bg-red-100 text-red-800
                                    @elseif($user->role == 'donor') bg-primary-light text-primary
                                    @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                    @if (!$user->isAdmin() && $user->id !== auth()->id())
                                        <button type="button"
                                            onclick="openRoleModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->role }}')"
                                            class="ml-2 text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-edit"></i> Change
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-500">Account Status</label>
                                <div class="mt-1">
                                    @if ($user->is_verified)
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Verified Account
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i> Not Verified
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-500">Account Created</label>
                                <p class="mt-1 text-gray-900">{{ $user->created_at->format('F j, Y h:i A') }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-500">Last Updated</label>
                                <p class="mt-1 text-gray-900">{{ $user->updated_at->format('F j, Y h:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @if (!$user->isAdmin() && $user->id !== auth()->id())
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Manage user account</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium"
                                            onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                            <i class="fas fa-trash mr-2"></i> Delete Account
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Role Specific Information -->
                @if ($user->isDonor() && $user->donorProfile)
                    <!-- Donor Profile -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Donor Profile</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Blood Group</label>
                                    <div class="mt-1">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            {{ $user->donorProfile->blood_group }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Location</label>
                                    <p class="mt-1 text-gray-900">{{ $user->donorProfile->district }},
                                        {{ $user->donorProfile->upazila }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Last Donation Date</label>
                                    <p class="mt-1 text-gray-900">
                                        @if ($user->donorProfile->last_donation_date)
                                            {{ $user->donorProfile->last_donation_date->format('F j, Y') }}
                                        @else
                                            Never donated
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Profile Status</label>
                                    <div class="mt-1 space-y-2">
                                        @if ($user->donorProfile->approved_by_admin)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i> Profile Approved
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i> Pending Approval
                                            </span>
                                        @endif

                                        @if ($user->donorProfile->is_available)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-primary-light text-primary">
                                                <i class="fas fa-heart mr-1"></i> Available to Donate
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-times mr-1"></i> Currently Unavailable
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500">Eligibility Status</label>
                                    <div class="mt-1">
                                        @if ($user->donorProfile->canDonate())
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i> Eligible to donate
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-clock mr-1"></i> Not eligible (90-day rule)
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500">Profile Created</label>
                                    <p class="mt-1 text-gray-900">{{ $user->donorProfile->created_at->format('F j, Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.donors.show', $user->donorProfile) }}"
                                class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                                <i class="fas fa-eye mr-2"></i> View Full Donor Profile
                            </a>
                        </div>
                    </div>
                @elseif($user->isRecipient() && $user->recipientProfile)
                    <!-- Recipient Profile -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Recipient Profile</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Location</label>
                                <p class="mt-1 text-gray-900">{{ $user->recipientProfile->district }},
                                    {{ $user->recipientProfile->upazila }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Profile Created</label>
                                <p class="mt-1 text-gray-900">{{ $user->recipientProfile->created_at->format('F j, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Activity Summary -->
                @if ($user->bloodRequests->count() > 0 || $user->donorResponses->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Activity Summary</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if ($user->isRecipient())
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-4">Blood Requests</h4>
                                    <div class="space-y-3">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Total Requests</span>
                                            <span
                                                class="font-medium text-gray-900">{{ $user->bloodRequests->count() }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-yellow-600">Pending</span>
                                            <span
                                                class="font-medium text-gray-900">{{ $user->bloodRequests->where('status', 'pending')->count() }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-green-600">Approved</span>
                                            <span
                                                class="font-medium text-gray-900">{{ $user->bloodRequests->where('status', 'approved')->count() }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-blue-600">Fulfilled</span>
                                            <span
                                                class="font-medium text-gray-900">{{ $user->bloodRequests->where('status', 'fulfilled')->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($user->isDonor())
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-4">Donor Responses</h4>
                                    <div class="space-y-3">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Total Responses</span>
                                            <span
                                                class="font-medium text-gray-900">{{ $user->donorResponses->count() }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-yellow-600">Interested</span>
                                            <span
                                                class="font-medium text-gray-900">{{ $user->donorResponses->where('response_status', 'interested')->count() }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-blue-600">Contacted</span>
                                            <span
                                                class="font-medium text-gray-900">{{ $user->donorResponses->where('response_status', 'contacted')->count() }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-green-600">Donated</span>
                                            <span
                                                class="font-medium text-gray-900">{{ $user->donorResponses->where('response_status', 'donated')->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Quick Stats & Actions -->
            <div class="space-y-6">
                <!-- Account Statistics -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Statistics</h3>

                    <div class="space-y-4">
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <p class="text-2xl font-bold text-primary">
                                @if ($user->isDonor())
                                    {{ $user->donorResponses->count() }}
                                @elseif($user->isRecipient())
                                    {{ $user->bloodRequests->count() }}
                                @else
                                    0
                                @endif
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                @if ($user->isDonor())
                                    Total Responses
                                @elseif($user->isRecipient())
                                    Total Requests
                                @else
                                    Admin Actions
                                @endif
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-primary-light rounded-lg">
                                <p class="text-lg font-bold text-primary">
                                    {{ $user->is_verified ? 'Yes' : 'No' }}
                                </p>
                                <p class="text-xs text-gray-700 mt-1">Verified</p>
                            </div>
                            <div class="text-center p-3 bg-accent-light rounded-lg">
                                <p class="text-lg font-bold text-accent">
                                    {{ $user->email_verified_at ? 'Yes' : 'No' }}
                                </p>
                                <p class="text-xs text-gray-700 mt-1">Email Verified</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>

                    <div class="space-y-3">
                        <a href="mailto:{{ $user->email }}"
                            class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-envelope text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">Send Email</p>
                                <p class="text-xs text-gray-600">{{ $user->email }}</p>
                            </div>
                            <i class="fas fa-arrow-right text-gray-400"></i>
                        </a>

                        <a href="tel:{{ $user->phone }}"
                            class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-phone text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">Call User</p>
                                <p class="text-xs text-gray-600">{{ $user->phone }}</p>
                            </div>
                            <i class="fas fa-arrow-right text-gray-400"></i>
                        </a>

                        @if ($user->isDonor() && $user->donorProfile)
                            <a href="{{ route('admin.donors.responses', $user->donorProfile) }}"
                                class="flex items-center p-3 bg-primary-light rounded-lg hover:bg-primary hover:text-white transition-colors group">
                                <div
                                    class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3 group-hover:bg-primary-light">
                                    <i class="fas fa-history text-primary group-hover:text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 group-hover:text-white">View Responses</p>
                                    <p class="text-xs text-gray-600 group-hover:text-white/80">
                                        {{ $user->donorResponses->count() }} responses</p>
                                </div>
                                <i class="fas fa-arrow-right text-gray-400 group-hover:text-white"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Account Timeline -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Timeline</h3>

                    <div class="space-y-4">
                        <div class="border-l-2 border-primary pl-4 py-2">
                            <p class="font-medium text-gray-900">Account Created</p>
                            <p class="text-sm text-gray-600">{{ $user->created_at->format('F j, Y h:i A') }}</p>
                        </div>

                        @if ($user->email_verified_at)
                            <div class="border-l-2 border-green-500 pl-4 py-2">
                                <p class="font-medium text-gray-900">Email Verified</p>
                                <p class="text-sm text-gray-600">{{ $user->email_verified_at->format('F j, Y h:i A') }}
                                </p>
                            </div>
                        @endif

                        @if ($user->is_verified)
                            <div class="border-l-2 border-green-500 pl-4 py-2">
                                <p class="font-medium text-gray-900">Account Verified</p>
                                <p class="text-sm text-gray-600">Verified by admin</p>
                            </div>
                        @endif

                        @if ($user->isDonor() && $user->donorProfile)
                            <div class="border-l-2 border-blue-500 pl-4 py-2">
                                <p class="font-medium text-gray-900">Donor Profile Created</p>
                                <p class="text-sm text-gray-600">{{ $user->donorProfile->created_at->format('F j, Y') }}
                                </p>
                            </div>
                        @endif

                        @if ($user->isRecipient() && $user->recipientProfile)
                            <div class="border-l-2 border-green-500 pl-4 py-2">
                                <p class="font-medium text-gray-900">Recipient Profile Created</p>
                                <p class="text-sm text-gray-600">
                                    {{ $user->recipientProfile->created_at->format('F j, Y') }}</p>
                            </div>
                        @endif

                        <div class="border-l-2 border-gray-300 pl-4 py-2">
                            <p class="font-medium text-gray-900">Last Login</p>
                            <p class="text-sm text-gray-600">Recently active</p>
                        </div>
                    </div>
                </div>
            </div>
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
