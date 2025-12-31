@extends('layouts.guest')

@section('title', 'Reset Password - LifeLink')

@section('card-header', 'Reset Your Password')
@section('card-subtitle', 'We\'ll send you a link to reset your password')

@section('content')
    <div class="mb-6 text-sm text-gray-600">
        <p class="mb-2">
            <i class="fas fa-key mr-2 text-red-500"></i>
            {{ __('Forgot your password? Enter your email address and we\'ll send you a password reset link.') }}
        </p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 p-3 rounded-md bg-green-50 border border-green-200">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-400 mr-2"></i>
                <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" id="resetForm">
        @csrf

        <!-- Email Address -->
        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('Email Address') }}
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150 ease-in-out"
                    placeholder="Enter your registered email">
            </div>
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" id="resetButton"
                class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white py-3 rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 flex items-center justify-center">
                <i class="fas fa-paper-plane mr-2"></i>
                {{ __('Send Reset Link') }}
            </button>
        </div>

        <!-- Back to Login -->
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}"
                class="inline-flex items-center text-sm text-gray-600 hover:text-red-600 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Back to Login') }}
            </a>
        </div>
    </form>
@endsection

@section('card-footer')
    <p class="text-sm text-gray-600 text-center">
        <i class="fas fa-info-circle mr-1"></i>
        {{ __("Didn't receive the email? Check your spam folder or") }}
        <button type="button" onclick="resendResetLink()"
            class="text-red-600 hover:text-red-800 hover:underline font-medium cursor-pointer">
            {{ __('request another link') }}
        </button>
    </p>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('resetForm');
            const resetButton = document.getElementById('resetButton');

            // Form submission
            form.addEventListener('submit', function(e) {
                // Show loading state
                resetButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
                resetButton.disabled = true;
            });

            // Auto-focus email field
            document.getElementById('email').focus();
        });

        function resendResetLink() {
            const email = document.getElementById('email').value;
            const resetButton = document.getElementById('resetButton');

            if (!email) {
                alert('Please enter your email address first.');
                document.getElementById('email').focus();
                return;
            }

            if (confirm('Send another reset link to ' + email + '?')) {
                // Submit the form again
                document.getElementById('resetForm').submit();
            }
        }
    </script>
@endpush
