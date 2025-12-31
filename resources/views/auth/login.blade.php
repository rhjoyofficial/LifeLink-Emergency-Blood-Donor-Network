@extends('layouts.guest')

@section('title', 'Login')

@section('card-header', 'Welcome Back')
@section('card-subtitle', 'Sign in to your LifeLink account')

@section('content')
    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('Email Address') }}
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    autocomplete="email"
                    class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150 ease-in-out"
                    placeholder="you@example.com">
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
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150 ease-in-out"
                    placeholder="••••••••">
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                </button>
            </div>
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mb-6">
            <label for="remember_me" class="flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember"
                    class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    class="text-sm text-red-600 hover:text-red-800 hover:underline font-medium">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <div>
            <button type="submit" id="loginButton"
                class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white py-3 rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 flex items-center justify-center">
                <i class="fas fa-sign-in-alt mr-2"></i>
                {{ __('Sign In') }}
            </button>
        </div>

        <!-- Register Link -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                {{ __("Don't have an account?") }}
                <a href="{{ route('register') }}" class="font-medium text-red-600 hover:text-red-800 hover:underline">
                    {{ __('Sign up now') }}
                </a>
            </p>
        </div>
    </form>
@endsection

@section('additional-links')
    <div class="flex items-center justify-center gap-3">
        <a href="{{ route('about') }}"
            class="block text-sm text-gray-600 hover:text-red-600 transition-colors duration-200">
            <i class="fas fa-info-circle mr-2"></i>
            About LifeLink
        </a>
        <a href="{{ route('how-it-works') }}"
            class="block text-sm text-gray-600 hover:text-red-600 transition-colors duration-200">
            <i class="fas fa-question-circle mr-2"></i>
            How it works
        </a>
        <a href="{{ route('eligibility') }}"
            class="block text-sm text-gray-600 hover:text-red-600 transition-colors duration-200">
            <i class="fas fa-check-circle mr-2"></i>
            Donor Eligibility
        </a>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const loginButton = document.getElementById('loginButton');

            // Toggle password visibility
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ?
                    '<i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>' :
                    '<i class="fas fa-eye-slash text-gray-400 hover:text-gray-600"></i>';
            });

            // Form submission
            form.addEventListener('submit', function(e) {
                // Show loading state
                loginButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Signing in...';
                loginButton.disabled = true;

                // You can add additional validation here if needed
            });

            // Auto-focus email field
            document.getElementById('email').focus();
        });
    </script>
@endpush
