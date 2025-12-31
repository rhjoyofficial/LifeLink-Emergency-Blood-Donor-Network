@extends('layouts.guest')

@section('title', 'Verify Email')

@section('card-header', 'Verify Your Email Address')

@section('content')
    <div class="mb-6 text-center">
        <div class="mx-auto w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-envelope text-primary text-2xl"></i>
        </div>

        <p class="text-sm text-gray-600 mb-4">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?') }}
        </p>

        <p class="text-sm text-gray-600 mb-6">
            {{ __('If you didn\'t receive the email, we will gladly send you another.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 border border-green-200 rounded-md p-3">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="flex flex-col space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                class="w-full bg-primary hover:bg-primary-dark text-white py-3 rounded-lg font-medium transition-colors duration-200 shadow-sm hover:shadow flex items-center justify-center">
                <i class="fas fa-redo mr-2"></i>
                {{ __('Resend Verification Email') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                <i class="fas fa-sign-out-alt mr-2"></i>
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
@endsection

@section('additional-links')
    <div class="mt-6 text-center">
        <a href="{{ url('/') }}" class="text-sm text-gray-600 hover:text-primary transition-colors duration-200">
            <i class="fas fa-home mr-2"></i>
            Return to Homepage
        </a>
    </div>
@endsection
