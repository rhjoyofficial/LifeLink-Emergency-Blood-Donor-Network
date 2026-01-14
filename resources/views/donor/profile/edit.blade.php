@extends('layouts.app')

@section('title', 'Edit Donor Profile')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit Donor Profile</h2>
                    <p class="text-gray-600 mt-1">Update your donor information</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('donor.profile.show') }}"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Cancel
                    </a>
                </div>
            </div>
        </div>

        @if (!$profile)
            <!-- Create Profile Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="max-w-2xl mx-auto">
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-600 text-xl mr-3"></i>
                            <div>
                                <p class="font-medium text-blue-800">Create Your Donor Profile</p>
                                <p class="text-sm text-blue-700 mt-1">
                                    You need to create a donor profile before you can respond to blood requests.
                                    Once submitted, your profile will be reviewed by an admin.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('donor.profile.store') }}" method="POST">
                        @csrf

                        <div class="space-y-6">
                            <!-- Blood Group -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Blood Group *</label>
                                <select name="blood_group" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="">Select Blood Group</option>
                                    <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
                                    <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                                    <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
                                    <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                                    <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                                    <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                    <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                </select>
                                @error('blood_group')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Location -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">District *</label>
                                    <input type="text" name="district" value="{{ old('district') }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                        placeholder="Enter your district">
                                    @error('district')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upazila *</label>
                                    <input type="text" name="upazila" value="{{ old('upazila') }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                        placeholder="Enter your upazila">
                                    @error('upazila')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Last Donation Date -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Last Donation Date</label>
                                <input type="date" name="last_donation_date" value="{{ old('last_donation_date') }}"
                                    max="{{ date('Y-m-d', strtotime('-1 day')) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                <p class="mt-1 text-sm text-gray-500">
                                    Leave empty if you have never donated before
                                </p>
                                @error('last_donation_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Important Notes -->
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-yellow-800">Important Information</p>
                                        <ul class="mt-2 text-sm text-yellow-700 space-y-1">
                                            <li>• Your profile will be reviewed by an admin before approval</li>
                                            <li>• You cannot respond to requests until your profile is approved</li>
                                            <li>• You must wait at least 90 days between donations</li>
                                            <li>• Ensure all information is accurate and up-to-date</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                                <a href="{{ route('donor.profile.show') }}"
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                    Cancel
                                </a>
                                <button type="submit"
                                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-medium">
                                    Create Donor Profile
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <!-- Edit Profile Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="max-w-2xl mx-auto">
                    <form action="{{ route('donor.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Blood Group -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Blood Group</label>
                                <select name="blood_group"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="A+"
                                        {{ old('blood_group', $profile->blood_group) == 'A+' ? 'selected' : '' }}>A+
                                    </option>
                                    <option value="A-"
                                        {{ old('blood_group', $profile->blood_group) == 'A-' ? 'selected' : '' }}>A-
                                    </option>
                                    <option value="B+"
                                        {{ old('blood_group', $profile->blood_group) == 'B+' ? 'selected' : '' }}>B+
                                    </option>
                                    <option value="B-"
                                        {{ old('blood_group', $profile->blood_group) == 'B-' ? 'selected' : '' }}>B-
                                    </option>
                                    <option value="O+"
                                        {{ old('blood_group', $profile->blood_group) == 'O+' ? 'selected' : '' }}>O+
                                    </option>
                                    <option value="O-"
                                        {{ old('blood_group', $profile->blood_group) == 'O-' ? 'selected' : '' }}>O-
                                    </option>
                                    <option value="AB+"
                                        {{ old('blood_group', $profile->blood_group) == 'AB+' ? 'selected' : '' }}>AB+
                                    </option>
                                    <option value="AB-"
                                        {{ old('blood_group', $profile->blood_group) == 'AB-' ? 'selected' : '' }}>AB-
                                    </option>
                                </select>
                                @error('blood_group')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">
                                    Changing your blood group will require admin re-approval
                                </p>
                            </div>

                            <!-- Location -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">District</label>
                                    <input type="text" name="district" value="{{ old('district', $profile->district) }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                        placeholder="Enter your district">
                                    @error('district')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upazila</label>
                                    <input type="text" name="upazila" value="{{ old('upazila', $profile->upazila) }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                        placeholder="Enter your upazila">
                                    @error('upazila')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Last Donation Date -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Last Donation Date</label>
                                <input type="date" name="last_donation_date"
                                    value="{{ old('last_donation_date', $profile->last_donation_date ? $profile->last_donation_date->format('Y-m-d') : '') }}"
                                    max="{{ date('Y-m-d', strtotime('-1 day')) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                <p class="mt-1 text-sm text-gray-500">
                                    Leave empty if you have never donated before
                                </p>
                                @error('last_donation_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Profile Status -->
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Profile Status</p>
                                        <p class="font-medium text-gray-900">
                                            @if ($profile->approved_by_admin)
                                                <span class="text-green-600">Approved</span>
                                            @else
                                                <span class="text-yellow-600">Pending Approval</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Availability</p>
                                        <p class="font-medium text-gray-900">
                                            @if ($profile->is_available)
                                                <span class="text-primary">Available</span>
                                            @else
                                                <span class="text-gray-600">Unavailable</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Important Notes -->
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-yellow-800">Important Notes</p>
                                        <ul class="mt-2 text-sm text-yellow-700 space-y-1">
                                            @if (!$profile->approved_by_admin)
                                                <li>• Your profile is currently pending admin approval</li>
                                            @endif
                                            @if ($profile->approved_by_admin)
                                                <li>• Changing your blood group will require re-approval</li>
                                            @endif
                                            <li>• Accurate information helps save lives</li>
                                            <li>• Update your last donation date for eligibility tracking</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                                <a href="{{ route('donor.profile.show') }}"
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                    Cancel
                                </a>
                                <button type="submit"
                                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-medium">
                                    Update Profile
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
