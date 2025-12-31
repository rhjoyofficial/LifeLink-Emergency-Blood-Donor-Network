@extends('layouts.app')

@section('title', 'Create Donor Profile')

@section('header')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Create Donor Profile</h1>
        <p class="mt-1 text-sm text-gray-600">Join our network of lifesavers</p>
    </div>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto">
        <!-- Steps Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-8 h-8 bg-red-600 rounded-full">
                        <span class="text-white text-sm font-medium">1</span>
                    </div>
                    <div class="ml-2 text-sm font-medium text-red-600">Basic Info</div>
                </div>
                <div class="flex-1 h-0.5 bg-gray-200 mx-4"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-full">
                        <span class="text-gray-500 text-sm font-medium">2</span>
                    </div>
                    <div class="ml-2 text-sm font-medium text-gray-500">Health Info</div>
                </div>
                <div class="flex-1 h-0.5 bg-gray-200 mx-4"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-full">
                        <span class="text-gray-500 text-sm font-medium">3</span>
                    </div>
                    <div class="ml-2 text-sm font-medium text-gray-500">Location</div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form id="donorProfileForm" action="{{ route('donor.profile.store') }}" method="POST">
                    @csrf

                    <div class="space-y-8">
                        <!-- Blood Group & Availability -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Blood Donation Information</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Blood Group -->
                                <div>
                                    <label for="blood_group" class="block text-sm font-medium text-gray-700">
                                        Your Blood Group *
                                    </label>
                                    <select name="blood_group" id="blood_group" required
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 rounded-md">
                                        <option value="">Select your blood group</option>
                                        @foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $group)
                                            <option value="{{ $group }}">{{ $group }}</option>
                                        @endforeach
                                    </select>
                                    <p class="mt-2 text-sm text-gray-500">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Knowing your exact blood type is crucial for saving lives
                                    </p>
                                </div>

                                <!-- Last Donation Date -->
                                <div>
                                    <label for="last_donation_date" class="block text-sm font-medium text-gray-700">
                                        Last Donation Date
                                    </label>
                                    <input type="date" name="last_donation_date" id="last_donation_date"
                                        max="{{ now()->format('Y-m-d') }}"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500">
                                    <p class="mt-2 text-sm text-gray-500">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Optional. Helps us track donation eligibility (must be 90+ days since last donation)
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Location Information</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- District -->
                                <div>
                                    <label for="district" class="block text-sm font-medium text-gray-700">
                                        District *
                                    </label>
                                    <input type="text" name="district" id="district" required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500">
                                    <p class="mt-2 text-sm text-gray-500">
                                        We'll show you requests from your district first
                                    </p>
                                </div>

                                <!-- Upazila -->
                                <div>
                                    <label for="upazila" class="block text-sm font-medium text-gray-700">
                                        Upazila *
                                    </label>
                                    <input type="text" name="upazila" id="upazila" required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500">
                                </div>
                            </div>
                        </div>

                        <!-- Availability -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Availability</h3>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_available" id="is_available" value="1" checked
                                    class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                <label for="is_available" class="ml-2 block text-sm text-gray-900">
                                    I am currently available to donate blood when needed
                                </label>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                You can change this anytime from your dashboard
                            </p>
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="border-t border-gray-200 pt-6">
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <input type="checkbox" name="terms" id="terms" required
                                        class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded mt-1">
                                    <label for="terms" class="ml-2 block text-sm text-gray-900">
                                        I agree to the
                                        <a href="#" class="text-red-600 hover:text-red-500">Terms of Service</a>
                                        and
                                        <a href="#" class="text-red-600 hover:text-red-500">Privacy Policy</a>
                                    </label>
                                </div>

                                <div class="flex items-start">
                                    <input type="checkbox" name="health_declaration" id="health_declaration" required
                                        class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded mt-1">
                                    <label for="health_declaration" class="ml-2 block text-sm text-gray-900">
                                        I declare that I am in good health, meet the donor eligibility criteria,
                                        and will inform LifeLink if my health status changes
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('donor.dashboard') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-heart mr-2"></i>
                            Join as Donor
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Information Box -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400 h-5 w-5"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Important Information</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Your profile will be reviewed by our admin team before activation</li>
                            <li>You must be between 18-65 years old to donate</li>
                            <li>Minimum weight requirement: 50 kg</li>
                            <li>You should be in good health with no infectious diseases</li>
                            <li>You can update your availability anytime</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('donorProfileForm');
            const lastDonationInput = document.getElementById('last_donation_date');

            // Calculate eligibility date
            function calculateEligibility() {
                if (lastDonationInput.value) {
                    const lastDonation = new Date(lastDonationInput.value);
                    const nextEligible = new Date(lastDonation);
                    nextEligible.setDate(nextEligible.getDate() + 90);

                    const today = new Date();
                    if (nextEligible > today) {
                        const daysRemaining = Math.ceil((nextEligible - today) / (1000 * 60 * 60 * 24));
                        alert(
                            `Based on your last donation, you will be eligible to donate in ${daysRemaining} days.`);
                    }
                }
            }

            lastDonationInput.addEventListener('change', calculateEligibility);

            // Form validation
            form.addEventListener('submit', function(e) {
                const bloodGroup = document.getElementById('blood_group').value;
                const terms = document.getElementById('terms').checked;
                const healthDeclaration = document.getElementById('health_declaration').checked;

                if (!bloodGroup) {
                    e.preventDefault();
                    alert('Please select your blood group.');
                    return false;
                }

                if (!terms) {
                    e.preventDefault();
                    alert('You must agree to the Terms of Service and Privacy Policy.');
                    return false;
                }

                if (!healthDeclaration) {
                    e.preventDefault();
                    alert('You must complete the health declaration.');
                    return false;
                }

                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Profile...';
                submitBtn.disabled = true;
            });
        });
    </script>
@endpush
