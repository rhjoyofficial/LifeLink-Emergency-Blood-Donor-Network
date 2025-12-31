@extends('layouts.guest')

@section('title', 'Verify Email - LifeLink')

@section('card-header', 'Verify Your Email Address')
@section('card-subtitle', 'One last step to complete your registration')

@section('content')
    <div class="mb-6 text-center">
        <div class="mx-auto w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-envelope-open-text text-red-600 text-2xl"></i>
        </div>

        <p class="text-sm text-gray-600 mb-4">
            {{ __('Thanks for joining LifeLink! Before you can start saving lives, we need to verify your email address.') }}
        </p>

        <p class="text-sm text-gray-600 mb-6">
            {{ __('We\'ve sent a verification link to your email address. Please click the link to verify your account.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 rounded-md bg-green-50 border border-green-200">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-400 mr-2"></i>
                <p class="text-sm font-medium text-green-800">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </p>
            </div>
        </div>
    @endif

    <div class="space-y-4">
        <!-- Resend Verification Button -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white py-3 rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 flex items-center justify-center">
                <i class="fas fa-redo mr-2"></i>
                {{ __('Resend Verification Email') }}
            </button>
        </form>

        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                <i class="fas fa-sign-out-alt mr-2"></i>
                {{ __('Log Out') }}
            </button>
        </form>
    </div>

    <!-- Email Tips -->
    <div class="mt-6 p-3 bg-blue-50 border border-blue-100 rounded-lg">
        <p class="text-sm font-medium text-blue-800 mb-2">
            <i class="fas fa-lightbulb mr-1"></i>
            Not seeing the email?
        </p>
        <ul class="text-xs text-blue-700 space-y-1">
            <li class="flex items-center">
                <i class="fas fa-check-circle text-blue-500 mr-2 text-xs"></i>
                Check your spam or junk folder
            </li>
            <li class="flex items-center">
                <i class="fas fa-check-circle text-blue-500 mr-2 text-xs"></i>
                Ensure you entered the correct email
            </li>
            <li class="flex items-center">
                <i class="fas fa-check-circle text-blue-500 mr-2 text-xs"></i>
                Wait a few minutes and try again
            </li>
        </ul>
    </div>
@endsection

@section('additional-links')
    <div class="space-y-2">
        <a href="{{ route('login') }}"
            class="block text-sm text-gray-600 hover:text-red-600 transition-colors duration-200">
            <i class="fas fa-sign-in-alt mr-2"></i>
            Return to Login
        </a>
        <a href="{{ route('support') }}"
            class="block text-sm text-gray-600 hover:text-red-600 transition-colors duration-200">
            <i class="fas fa-headset mr-2"></i>
            Need Help? Contact Support
        </a>
    </div>
@endsection

@section('card-footer')
    <p class="text-sm text-gray-600 text-center">
        <i class="fas fa-shield-alt mr-1"></i>
        Verification ensures security and prevents unauthorized access to your account
    </p>
@endsection
