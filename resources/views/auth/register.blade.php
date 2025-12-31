@extends('layouts.guest')

@section('title', 'Register')

@section('card-header', 'Join LifeLink Network')
@section('card-subtitle', 'Create your account to save lives')

@section('content')
    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        <!-- Name -->
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('Full Name') }}
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-user text-gray-400"></i>
                </div>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    autocomplete="name"
                    class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150 ease-in-out"
                    placeholder="Full Name">
            </div>
        </div>

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('Email Address') }}
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    autocomplete="email"
                    class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150 ease-in-out"
                    placeholder="yourmail@example.com">
            </div>
        </div>

        <!-- Phone -->
        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('Phone Number') }} *
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-phone text-gray-400"></i>
                </div>
                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required autocomplete="tel"
                    class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150 ease-in-out"
                    placeholder="017XXXXXXXX">
            </div>
            <p class="mt-1 text-xs text-gray-500">This will be used to contact you for blood requests</p>
        </div>

        <!-- Role Selection -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('I want to join as a:') }}
            </label>
            <div class="grid grid-cols-2 gap-3">
                <label
                    class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-red-50 transition-colors duration-200 has-[:checked]:border-red-500 has-[:checked]:bg-red-50">
                    <input type="radio" name="role" value="recipient" required
                        class="h-4 w-4 text-red-600 focus:ring-0 focus:outline-none"
                        {{ old('role') === 'recipient' ? 'checked' : '' }}>
                    <div class="ml-3">
                        <span class="block text-sm font-medium text-gray-900">
                            <i class="fas fa-hand-holding-heart text-red-600 mr-1"></i>
                            Recipient
                        </span>
                        <span class="block text-xs text-gray-500 mt-1">Need blood for patients</span>
                    </div>
                </label>

                <label
                    class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-red-50 transition-colors duration-200 has-[:checked]:border-red-500 has-[:checked]:bg-red-50">
                    <input type="radio" name="role" value="donor"
                        class="h-4 w-4 text-red-600 focus:ring-0 focus:outline-none"
                        {{ old('role') === 'donor' ? 'checked' : '' }}>
                    <div class="ml-3">
                        <span class="block text-sm font-medium text-gray-900">
                            <i class="fas fa-heart text-red-600 mr-1"></i>
                            Donor
                        </span>
                        <span class="block text-xs text-gray-500 mt-1">Donate blood to save lives</span>
                    </div>
                </label>
            </div>
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('Password') }}
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150 ease-in-out"
                    placeholder="••••••••">
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                </button>
            </div>
            <div id="passwordRequirements" class="mt-2 hidden">
                <p class="text-xs font-medium text-gray-700 mb-1">Password must contain:</p>
                <ul class="text-xs text-gray-600 space-y-1">
                    <li id="reqLength" class="flex items-center">
                        <i class="fas fa-times text-red-400 mr-1 text-xs"></i>
                        At least 8 characters
                    </li>
                    <li id="reqUpper" class="flex items-center">
                        <i class="fas fa-times text-red-400 mr-1 text-xs"></i>
                        One uppercase letter
                    </li>
                    <li id="reqLower" class="flex items-center">
                        <i class="fas fa-times text-red-400 mr-1 text-xs"></i>
                        One lowercase letter
                    </li>
                    <li id="reqNumber" class="flex items-center">
                        <i class="fas fa-times text-red-400 mr-1 text-xs"></i>
                        One number
                    </li>
                </ul>
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('Confirm Password') }}
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    autocomplete="new-password"
                    class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150 ease-in-out"
                    placeholder="••••••••">
                <button type="button" id="toggleConfirmPassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                </button>
            </div>
            <div id="passwordMatch" class="mt-2 hidden">
                <p class="text-xs">
                    <span id="matchText" class="font-medium"></span>
                </p>
            </div>
        </div>

        <!-- Terms Agreement -->
        <div class="mb-6">
            <label class="flex items-start">
                <input type="checkbox" name="terms" required
                    class="h-4 w-4 mt-1 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                <span class="ml-2 text-sm text-gray-600">
                    I agree to the
                    <a href="{{ route('terms') }}" class="text-red-600 hover:text-red-800 hover:underline"
                        target="_blank">
                        Terms of Service
                    </a>
                    and
                    <a href="{{ route('privacy') }}" class="text-red-600 hover:text-red-800 hover:underline"
                        target="_blank">
                        Privacy Policy
                    </a>
                </span>
            </label>
        </div>

        <!-- Register Button -->
        <div>
            <button type="submit" id="registerButton"
                class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white py-3 rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 flex items-center justify-center">
                <i class="fas fa-user-plus mr-2"></i>
                {{ __('Create Account') }}
            </button>
        </div>

        <!-- Login Link -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                {{ __('Already have an account?') }}
                <a href="{{ route('login') }}" class="font-medium text-red-600 hover:text-red-800 hover:underline">
                    {{ __('Sign in here') }}
                </a>
            </p>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            const requirements = document.getElementById('passwordRequirements');
            const matchDiv = document.getElementById('passwordMatch');
            const registerButton = document.getElementById('registerButton');

            // Toggle password visibility
            document.getElementById('togglePassword').addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ?
                    '<i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>' :
                    '<i class="fas fa-eye-slash text-gray-400 hover:text-gray-600"></i>';
            });

            document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
                const type = confirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ?
                    '<i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>' :
                    '<i class="fas fa-eye-slash text-gray-400 hover:text-gray-600"></i>';
            });

            // Password validation
            passwordInput.addEventListener('input', function() {
                const password = this.value;

                // Show requirements
                requirements.classList.remove('hidden');

                // Check requirements
                const hasLength = password.length >= 8;
                const hasUpper = /[A-Z]/.test(password);
                const hasLower = /[a-z]/.test(password);
                const hasNumber = /\d/.test(password);

                // Update requirement icons
                updateRequirement('reqLength', hasLength);
                updateRequirement('reqUpper', hasUpper);
                updateRequirement('reqLower', hasLower);
                updateRequirement('reqNumber', hasNumber);

                // Update password field styling
                const isValid = hasLength && hasUpper && hasLower && hasNumber;
                this.classList.remove('border-red-300', 'border-green-300');
                this.classList.add(isValid ? 'border-green-300' : 'border-red-300');

                // Check confirmation match
                checkPasswordMatch();
            });

            // Password confirmation validation
            confirmInput.addEventListener('input', checkPasswordMatch);

            function checkPasswordMatch() {
                const password = passwordInput.value;
                const confirm = confirmInput.value;

                if (confirm) {
                    matchDiv.classList.remove('hidden');
                    const match = password === confirm;
                    const matchText = document.getElementById('matchText');

                    if (match) {
                        matchText.textContent = 'Passwords match ✓';
                        matchText.className = 'text-green-600 font-medium';
                        confirmInput.classList.remove('border-red-300');
                        confirmInput.classList.add('border-green-300');
                    } else {
                        matchText.textContent = 'Passwords do not match ✗';
                        matchText.className = 'text-red-600 font-medium';
                        confirmInput.classList.remove('border-green-300');
                        confirmInput.classList.add('border-red-300');
                    }
                } else {
                    matchDiv.classList.add('hidden');
                    confirmInput.classList.remove('border-red-300', 'border-green-300');
                }
            }

            function updateRequirement(elementId, isValid) {
                const element = document.getElementById(elementId);
                const icon = element.querySelector('i');

                if (isValid) {
                    icon.className = 'fas fa-check text-green-500 mr-1 text-xs';
                    element.classList.remove('text-gray-600');
                    element.classList.add('text-green-600');
                } else {
                    icon.className = 'fas fa-times text-red-400 mr-1 text-xs';
                    element.classList.remove('text-green-600');
                    element.classList.add('text-gray-600');
                }
            }

            // Form submission
            form.addEventListener('submit', function(e) {
                // Basic validation
                const password = passwordInput.value;
                const confirm = confirmInput.value;
                const terms = document.querySelector('input[name="terms"]').checked;
                const role = document.querySelector('input[name="role"]:checked');

                if (!role) {
                    e.preventDefault();
                    alert('Please select whether you want to join as a Recipient or Donor.');
                    return false;
                }

                if (!terms) {
                    e.preventDefault();
                    alert('You must agree to the Terms of Service and Privacy Policy.');
                    return false;
                }

                if (password !== confirm) {
                    e.preventDefault();
                    alert('Passwords do not match. Please check your password confirmation.');
                    confirmInput.focus();
                    return false;
                }

                // Show loading state
                registerButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Account...';
                registerButton.disabled = true;
            });

            // Auto-focus name field
            document.getElementById('name').focus();
        });
    </script>
@endpush
