@extends('layouts.app')

@section('title', 'Settings')

@section('header')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
        <p class="mt-1 text-sm text-gray-600">Manage your account preferences</p>
    </div>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-8">
                        <!-- Notification Settings -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Notification Preferences</h3>

                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label for="notification_email" class="text-sm font-medium text-gray-700">
                                            Email Notifications
                                        </label>
                                        <p class="text-sm text-gray-500">Receive notifications via email</p>
                                    </div>
                                    <div>
                                        <input type="checkbox" name="notification_email" id="notification_email"
                                            value="1" {{ $settings->notification_email ?? true ? 'checked' : '' }}
                                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label for="notification_sms" class="text-sm font-medium text-gray-700">
                                            SMS Notifications
                                        </label>
                                        <p class="text-sm text-gray-500">Receive urgent notifications via SMS</p>
                                    </div>
                                    <div>
                                        <input type="checkbox" name="notification_sms" id="notification_sms" value="1"
                                            {{ $settings->notification_sms ?? false ? 'checked' : '' }}
                                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    </div>
                                </div>

                                @if (auth()->user()->isRecipient())
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <label for="notification_blood_requests"
                                                class="text-sm font-medium text-gray-700">
                                                Blood Request Updates
                                            </label>
                                            <p class="text-sm text-gray-500">Get notified about your blood request status
                                            </p>
                                        </div>
                                        <div>
                                            <input type="checkbox" name="notification_blood_requests"
                                                id="notification_blood_requests" value="1"
                                                {{ $settings->notification_blood_requests ?? true ? 'checked' : '' }}
                                                class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                        </div>
                                    </div>
                                @endif

                                @if (auth()->user()->isDonor())
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <label for="notification_responses" class="text-sm font-medium text-gray-700">
                                                Donor Response Alerts
                                            </label>
                                            <p class="text-sm text-gray-500">Get notified when recipients respond to your
                                                interest</p>
                                        </div>
                                        <div>
                                            <input type="checkbox" name="notification_responses" id="notification_responses"
                                                value="1"
                                                {{ $settings->notification_responses ?? true ? 'checked' : '' }}
                                                class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Privacy Settings -->
                        <div class="border-t border-gray-200 pt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Privacy Settings</h3>

                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">
                                            Show Profile to Donors
                                        </label>
                                        <p class="text-sm text-gray-500">Allow donors to see your name when you request
                                            blood</p>
                                    </div>
                                    <div>
                                        <input type="checkbox" name="privacy_show_profile" id="privacy_show_profile"
                                            value="1" {{ $settings->privacy_show_profile ?? true ? 'checked' : '' }}
                                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">
                                            Show Contact Information
                                        </label>
                                        <p class="text-sm text-gray-500">Show your phone number to matched donors</p>
                                    </div>
                                    <div>
                                        <input type="checkbox" name="privacy_show_contact" id="privacy_show_contact"
                                            value="1" {{ $settings->privacy_show_contact ?? true ? 'checked' : '' }}
                                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Management -->
                        <div class="border-t border-gray-200 pt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Data Management</h3>

                            <div class="space-y-4">
                                <div>
                                    <button type="button" onclick="exportData()"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <i class="fas fa-download mr-2"></i>
                                        Export My Data
                                    </button>
                                    <p class="mt-1 text-sm text-gray-500">Download all your data in JSON format</p>
                                </div>

                                @if (auth()->user()->isDonor())
                                    <div>
                                        <button type="button" onclick="deleteDonorProfile()"
                                            class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <i class="fas fa-trash mr-2"></i>
                                            Delete Donor Profile
                                        </button>
                                        <p class="mt-1 text-sm text-gray-500">Permanently remove your donor profile</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-save mr-2"></i>
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function exportData() {
            if (confirm('This will export all your data in JSON format. Continue?')) {
                fetch('{{ route('settings.export') }}')
                    .then(response => response.json())
                    .then(data => {
                        const blob = new Blob([JSON.stringify(data, null, 2)], {
                            type: 'application/json'
                        });
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = `lifelink-data-${new Date().toISOString().split('T')[0]}.json`;
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        document.body.removeChild(a);
                    });
            }
        }

        function deleteDonorProfile() {
            if (confirm('Are you sure you want to delete your donor profile? This action cannot be undone.')) {
                fetch('{{ route('donor.profile.destroy') }}', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.success) {
                            window.location.href = '{{ route('dashboard') }}';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
            }
        }
    </script>
@endpush
