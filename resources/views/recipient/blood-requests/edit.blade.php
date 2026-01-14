@extends('layouts.app')

@section('title', 'Edit Blood Request')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center">
                        <a href="{{ route('recipient.blood-requests.show', $bloodRequest) }}"
                            class="text-primary hover:text-primary-dark mr-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Edit Blood Request</h2>
                            <p class="text-gray-600 mt-1">Update your blood request information</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <form action="{{ route('recipient.blood-requests.cancel', $bloodRequest) }}" method="POST"
                        class="inline">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium"
                            onclick="return confirm('Are you sure you want to cancel this request?')">
                            <i class="fas fa-times mr-2"></i> Cancel Request
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="max-w-3xl mx-auto">
                <form action="{{ route('recipient.blood-requests.update', $bloodRequest) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-8">
                        <!-- Patient Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Patient Information</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Patient Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Patient Name *</label>
                                    <input type="text" name="patient_name"
                                        value="{{ old('patient_name', $bloodRequest->patient_name) }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                        placeholder="Enter patient's full name">
                                    @error('patient_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Blood Group -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Blood Group *</label>
                                    <select name="blood_group" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                        <option value="A+"
                                            {{ old('blood_group', $bloodRequest->blood_group) == 'A+' ? 'selected' : '' }}>
                                            A+</option>
                                        <option value="A-"
                                            {{ old('blood_group', $bloodRequest->blood_group) == 'A-' ? 'selected' : '' }}>
                                            A-</option>
                                        <option value="B+"
                                            {{ old('blood_group', $bloodRequest->blood_group) == 'B+' ? 'selected' : '' }}>
                                            B+</option>
                                        <option value="B-"
                                            {{ old('blood_group', $bloodRequest->blood_group) == 'B-' ? 'selected' : '' }}>
                                            B-</option>
                                        <option value="O+"
                                            {{ old('blood_group', $bloodRequest->blood_group) == 'O+' ? 'selected' : '' }}>
                                            O+</option>
                                        <option value="O-"
                                            {{ old('blood_group', $bloodRequest->blood_group) == 'O-' ? 'selected' : '' }}>
                                            O-</option>
                                        <option value="AB+"
                                            {{ old('blood_group', $bloodRequest->blood_group) == 'AB+' ? 'selected' : '' }}>
                                            AB+</option>
                                        <option value="AB-"
                                            {{ old('blood_group', $bloodRequest->blood_group) == 'AB-' ? 'selected' : '' }}>
                                            AB-</option>
                                    </select>
                                    @error('blood_group')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Bags Required -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Bags Required *</label>
                                    <input type="number" name="bags_required"
                                        value="{{ old('bags_required', $bloodRequest->bags_required) }}" min="1"
                                        max="10" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                        placeholder="Number of bags">
                                    @error('bags_required')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Contact Phone -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone *</label>
                                    <input type="tel" name="contact_phone"
                                        value="{{ old('contact_phone', $bloodRequest->contact_phone) }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                        placeholder="Phone number for contact">
                                    @error('contact_phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Hospital Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Hospital Information</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Hospital Name -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hospital Name *</label>
                                    <input type="text" name="hospital_name"
                                        value="{{ old('hospital_name', $bloodRequest->hospital_name) }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                        placeholder="Enter hospital name">
                                    @error('hospital_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- District -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">District *</label>
                                    <input type="text" name="district"
                                        value="{{ old('district', $bloodRequest->district) }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                        placeholder="Enter district">
                                    @error('district')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Upazila -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upazila *</label>
                                    <input type="text" name="upazila"
                                        value="{{ old('upazila', $bloodRequest->upazila) }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                        placeholder="Enter upazila">
                                    @error('upazila')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Request Details -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Request Details</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Urgency Level -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Urgency Level *</label>
                                    <select name="urgency_level" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                        <option value="low"
                                            {{ old('urgency_level', $bloodRequest->urgency_level) == 'low' ? 'selected' : '' }}>
                                            Low</option>
                                        <option value="medium"
                                            {{ old('urgency_level', $bloodRequest->urgency_level) == 'medium' ? 'selected' : '' }}>
                                            Medium</option>
                                        <option value="high"
                                            {{ old('urgency_level', $bloodRequest->urgency_level) == 'high' ? 'selected' : '' }}>
                                            High</option>
                                        <option value="critical"
                                            {{ old('urgency_level', $bloodRequest->urgency_level) == 'critical' ? 'selected' : '' }}>
                                            Critical</option>
                                    </select>
                                    @error('urgency_level')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Needed At -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Blood Needed By *</label>
                                    <input type="datetime-local" name="needed_at"
                                        value="{{ old('needed_at', $bloodRequest->needed_at->format('Y-m-d\TH:i')) }}"
                                        required min="{{ now()->format('Y-m-d\TH:i') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                    @error('needed_at')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm text-gray-500">
                                        Select the date and time when blood is needed
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Request Status -->
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle text-blue-600 text-xl mr-3"></i>
                                <div>
                                    <p class="font-medium text-blue-800">Request Status:
                                        <span class="capitalize">{{ $bloodRequest->status }}</span>
                                    </p>
                                    <p class="text-sm text-blue-700 mt-1">
                                        @if ($bloodRequest->status == 'pending')
                                            Your request is pending admin approval. You can update it until it's approved.
                                        @else
                                            Only pending requests can be edited.
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Important Information -->
                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mt-1 mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-yellow-800 mb-2">Important Notes</h4>
                                    <ul class="text-sm text-yellow-700 space-y-1">
                                        <li>• Your request will need to be re-approved by admin after editing</li>
                                        <li>• Any donor responses will remain, but donors will be re-notified if blood group
                                            changes</li>
                                        <li>• Ensure all information is accurate to avoid delays in approval</li>
                                        <li>• Changes to blood group or location may affect donor matching</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('recipient.blood-requests.show', $bloodRequest) }}"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-medium">
                                Update Blood Request
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Set minimum datetime to current time
            const now = new Date();
            const minDateTime = now.toISOString().slice(0, 16);
            document.querySelector('input[name="needed_at"]').min = minDateTime;
        </script>
    @endpush
@endsection
