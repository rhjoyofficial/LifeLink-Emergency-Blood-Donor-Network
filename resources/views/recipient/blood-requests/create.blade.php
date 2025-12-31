@extends('layouts.app')

@section('title', 'Create Blood Request')

@section('header')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">New Blood Request</h1>
        <p class="mt-1 text-sm text-gray-600">Request blood for a patient in need</p>
    </div>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form id="bloodRequestForm" action="{{ route('recipient.blood-requests.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <!-- Patient Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Patient Information</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Patient Name -->
                                <div>
                                    <label for="patient_name" class="block text-sm font-medium text-gray-700">
                                        Patient Name *
                                    </label>
                                    <input type="text" name="patient_name" id="patient_name" required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500">
                                </div>

                                <!-- Blood Group -->
                                <div>
                                    <label for="blood_group" class="block text-sm font-medium text-gray-700">
                                        Blood Group Required *
                                    </label>
                                    <select name="blood_group" id="blood_group" required
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 rounded-md">
                                        <option value="">Select Blood Group</option>
                                        @foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $group)
                                            <option value="{{ $group }}">{{ $group }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Bags Required -->
                                <div>
                                    <label for="bags_required" class="block text-sm font-medium text-gray-700">
                                        Bags Required *
                                    </label>
                                    <input type="number" name="bags_required" id="bags_required" min="1"
                                        max="10" value="1" required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500">
                                </div>

                                <!-- Urgency Level -->
                                <div>
                                    <label for="urgency_level" class="block text-sm font-medium text-gray-700">
                                        Urgency Level *
                                    </label>
                                    <select name="urgency_level" id="urgency_level" required
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 rounded-md">
                                        <option value="medium">Medium</option>
                                        <option value="low">Low</option>
                                        <option value="high">High</option>
                                        <option value="critical">Critical</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Hospital Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Hospital Information</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Hospital Name -->
                                <div class="md:col-span-2">
                                    <label for="hospital_name" class="block text-sm font-medium text-gray-700">
                                        Hospital Name *
                                    </label>
                                    <input type="text" name="hospital_name" id="hospital_name" required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500">
                                </div>

                                <!-- District -->
                                <div>
                                    <label for="district" class="block text-sm font-medium text-gray-700">
                                        District *
                                    </label>
                                    <input type="text" name="district" id="district" required
                                        value="{{ auth()->user()->recipientProfile->district ?? '' }}"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500">
                                </div>

                                <!-- Upazila -->
                                <div>
                                    <label for="upazila" class="block text-sm font-medium text-gray-700">
                                        Upazila *
                                    </label>
                                    <input type="text" name="upazila" id="upazila" required
                                        value="{{ auth()->user()->recipientProfile->upazila ?? '' }}"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500">
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Contact Phone -->
                                <div>
                                    <label for="contact_phone" class="block text-sm font-medium text-gray-700">
                                        Contact Phone *
                                    </label>
                                    <input type="tel" name="contact_phone" id="contact_phone" required
                                        value="{{ auth()->user()->phone ?? '' }}"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500">
                                    <p class="mt-1 text-sm text-gray-500">Donors will contact this number</p>
                                </div>

                                <!-- Needed Date & Time -->
                                <div>
                                    <label for="needed_at" class="block text-sm font-medium text-gray-700">
                                        Needed By *
                                    </label>
                                    <input type="datetime-local" name="needed_at" id="needed_at" required
                                        min="{{ now()->format('Y-m-d\TH:i') }}"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500">
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div>
                            <label for="additional_notes" class="block text-sm font-medium text-gray-700">
                                Additional Notes (Optional)
                            </label>
                            <textarea name="additional_notes" id="additional_notes" rows="3"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500"></textarea>
                            <p class="mt-1 text-sm text-gray-500">Any additional information for donors</p>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('recipient.blood-requests.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('bloodRequestForm');
            const neededAtInput = document.getElementById('needed_at');

            // Set default needed date to 24 hours from now
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            neededAtInput.value = tomorrow.toISOString().slice(0, 16);

            // Form validation
            form.addEventListener('submit', function(e) {
                const neededAt = new Date(neededAtInput.value);
                const now = new Date();

                if (neededAt <= now) {
                    e.preventDefault();
                    alert('Please select a future date and time for when blood is needed.');
                    neededAtInput.focus();
                    return false;
                }

                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...';
                submitBtn.disabled = true;
            });
        });
    </script>
@endpush
