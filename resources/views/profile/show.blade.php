@extends('layouts.app')

@section('title', 'My Profile')

@section('header')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
        <p class="mt-1 text-sm text-gray-600">Manage your account information</p>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Information -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Personal Information</h3>

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    Full Name *
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                    required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500">
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">
                                    Email Address *
                                </label>
                                <input type="email" name="email" id="email" value="{{ $user->email }}" disabled
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-gray-50">
                                <p class="mt-1 text-sm text-gray-500">Email cannot be changed</p>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">
                                    Phone Number *
                                </label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                    required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500">
                            </div>

                            <!-- Role -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Account Type
                                </label>
                                <div class="mt-1">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    {{ $user->role === 'admin'
                                        ? 'bg-purple-100 text-purple-800'
                                        : ($user->role === 'donor'
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Role-specific Profile -->
                        @if ($user->isDonor() && $user->donorProfile)
                            <div class="mt-8 pt-8 border-t border-gray-200">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Donor Profile</h4>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">
                                            Blood Group
                                        </label>
                                        <div class="mt-1">
                                            <span
                                                class="blood-group-badge {{ strtolower(str_replace('+', 'p', $user->donorProfile->blood_group)) }}">
                                                {{ $user->donorProfile->blood_group }}
                                            </span>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">
                                            Location
                                        </label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ $user->donorProfile->district }}, {{ $user->donorProfile->upazila }}
                                        </p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">
                                            Status
                                        </label>
                                        <div class="mt-1 space-x-2">
                                            @if ($user->donorProfile->approved_by_admin)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Approved
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Pending
                                                </span>
                                            @endif

                                            @if ($user->donorProfile->is_available)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-heart mr-1"></i>
                                                    Available
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($user->donorProfile->last_donation_date)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">
                                                Last Donation
                                            </label>
                                            <p class="mt-1 text-sm text-gray-900">
                                                {{ $user->donorProfile->last_donation_date->format('F d, Y') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-6">
                                    <a href="{{ route('donor.profile.edit') }}"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <i class="fas fa-edit mr-2"></i>
                                        Edit Donor Profile
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if ($user->isRecipient() && $user->recipientProfile)
                            <div class="mt-8 pt-8 border-t border-gray-200">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Recipient Information</h4>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">
                                            Default Blood Group
                                        </label>
                                        @if ($user->recipientProfile->blood_group)
                                            <div class="mt-1">
                                                <span
                                                    class="blood-group-badge {{ strtolower(str_replace('+', 'p', $user->recipientProfile->blood_group)) }}">
                                                    {{ $user->recipientProfile->blood_group }}
                                                </span>
                                            </div>
                                        @else
                                            <p class="mt-1 text-sm text-gray-500">Not specified</p>
                                        @endif
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">
                                            Location
                                        </label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ $user->recipientProfile->district }},
                                            {{ $user->recipientProfile->upazila }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Form Actions -->
                        <div class="mt-8 flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="fas fa-save mr-2"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Account Information & Actions -->
        <div>
            <!-- Account Status -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Account Status</h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Email Verified</span>
                            @if ($user->email_verified_at)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>
                                    Verified
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Pending
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Account Verified</span>
                            @if ($user->is_verified)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>
                                    Verified
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Pending
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Member Since</span>
                            <span class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Last Updated</span>
                            <span class="text-sm text-gray-900">{{ $user->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Change Password -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>

                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700">
                                    Current Password *
                                </label>
                                <input type="password" name="current_password" id="current_password" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500">
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    New Password *
                                </label>
                                <input type="password" name="password" id="password" required minlength="8"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500">
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                    Confirm New Password *
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500">
                            </div>

                            <div class="mt-6">
                                <button type="submit"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <i class="fas fa-key mr-2"></i>
                                    Change Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Show password requirements
        document.getElementById('password').addEventListener('focus', function() {
            const requirements = document.createElement('div');
            requirements.className = 'mt-2 text-sm text-gray-500';
            requirements.innerHTML = `
        <p class="font-medium">Password must contain:</p>
        <ul class="list-disc pl-5 mt-1">
            <li>At least 8 characters</li>
            <li>One uppercase letter</li>
            <li>One lowercase letter</li>
            <li>One number</li>
        </ul>
    `;

            if (!this.parentNode.querySelector('.password-requirements')) {
                const div = document.createElement('div');
                div.className = 'password-requirements';
                div.appendChild(requirements);
                this.parentNode.appendChild(div);
            }
        });

        // Password validation
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const confirmField = document.getElementById('password_confirmation');

            // Check password strength
            const hasUpper = /[A-Z]/.test(password);
            const hasLower = /[a-z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasLength = password.length >= 8;

            const isValid = hasUpper && hasLower && hasNumber && hasLength;

            if (password) {
                this.classList.remove('border-red-300', 'border-green-300');
                this.classList.add(isValid ? 'border-green-300' : 'border-red-300');
            }

            // Check confirmation
            if (confirmField.value) {
                const match = password === confirmField.value;
                confirmField.classList.remove('border-red-300', 'border-green-300');
                confirmField.classList.add(match ? 'border-green-300' : 'border-red-300');
            }
        });

        // Password confirmation validation
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirm = this.value;

            if (confirm) {
                const match = password === confirm;
                this.classList.remove('border-red-300', 'border-green-300');
                this.classList.add(match ? 'border-green-300' : 'border-red-300');
            }
        });
    </script>
@endpush
