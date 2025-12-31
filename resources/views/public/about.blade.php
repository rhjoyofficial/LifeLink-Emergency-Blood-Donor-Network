@extends('layouts.app')

@section('title', 'About LifeLink')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-red-50 to-white">
        <!-- Hero Section -->
        <div class="relative bg-red-600">
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-gradient-to-r from-red-600 to-red-800 mix-blend-multiply"></div>
            </div>
            <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
                <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                    About LifeLink
                </h1>
                <p class="mt-6 max-w-3xl text-xl text-red-100">
                    Connecting blood donors with recipients in emergency situations across Bangladesh.
                </p>
            </div>
        </div>

        <!-- Mission & Vision -->
        <div class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:grid lg:grid-cols-2 lg:gap-8">
                    <div>
                        <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                            Our Mission
                        </h2>
                        <p class="mt-3 text-lg text-gray-500">
                            To create a reliable, efficient, and accessible blood donation network that saves lives by
                            connecting donors with recipients in their time of need.
                        </p>
                    </div>
                    <div class="mt-10 lg:mt-0">
                        <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                            Our Vision
                        </h2>
                        <p class="mt-3 text-lg text-gray-500">
                            A Bangladesh where no one dies waiting for blood, and every eligible citizen is empowered to be
                            a lifesaver.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="bg-red-600">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:py-16 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto text-center">
                    <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                        Making an Impact
                    </h2>
                    <p class="mt-3 text-xl text-red-200">
                        Join thousands of lifesavers across Bangladesh
                    </p>
                </div>
                <dl class="mt-10 text-center sm:max-w-3xl sm:mx-auto sm:grid sm:grid-cols-3 sm:gap-8">
                    <div class="flex flex-col">
                        <dt class="order-2 mt-2 text-lg leading-6 font-medium text-red-200">
                            Lives Saved
                        </dt>
                        <dd class="order-1 text-5xl font-extrabold text-white">
                            10,000+
                        </dd>
                    </div>
                    <div class="flex flex-col mt-10 sm:mt-0">
                        <dt class="order-2 mt-2 text-lg leading-6 font-medium text-red-200">
                            Active Donors
                        </dt>
                        <dd class="order-1 text-5xl font-extrabold text-white">
                            5,000+
                        </dd>
                    </div>
                    <div class="flex flex-col mt-10 sm:mt-0">
                        <dt class="order-2 mt-2 text-lg leading-6 font-medium text-red-200">
                            Districts Covered
                        </dt>
                        <dd class="order-1 text-5xl font-extrabold text-white">
                            64
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- How It Works -->
        <div class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                        How LifeLink Works
                    </h2>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                        A simple three-step process to connect donors with those in need
                    </p>
                </div>

                <div class="mt-10">
                    <div class="grid grid-cols-1 gap-10 sm:grid-cols-3">
                        <div class="text-center">
                            <div
                                class="flex items-center justify-center h-12 w-12 rounded-md bg-red-500 text-white mx-auto">
                                <i class="fas fa-user-plus text-xl"></i>
                            </div>
                            <div class="mt-5">
                                <h3 class="text-lg font-medium text-gray-900">1. Register</h3>
                                <p class="mt-2 text-base text-gray-500">
                                    Sign up as a donor or recipient. Donors provide their blood type and location.
                                </p>
                            </div>
                        </div>

                        <div class="text-center">
                            <div
                                class="flex items-center justify-center h-12 w-12 rounded-md bg-red-500 text-white mx-auto">
                                <i class="fas fa-tint text-xl"></i>
                            </div>
                            <div class="mt-5">
                                <h3 class="text-lg font-medium text-gray-900">2. Request/Respond</h3>
                                <p class="mt-2 text-base text-gray-500">
                                    Recipients post blood requests. Donors receive notifications and can respond.
                                </p>
                            </div>
                        </div>

                        <div class="text-center">
                            <div
                                class="flex items-center justify-center h-12 w-12 rounded-md bg-red-500 text-white mx-auto">
                                <i class="fas fa-handshake text-xl"></i>
                            </div>
                            <div class="mt-5">
                                <h3 class="text-lg font-medium text-gray-900">3. Connect</h3>
                                <p class="mt-2 text-base text-gray-500">
                                    Matched donors and recipients connect directly to arrange donation.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-gray-50">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    <span class="block">Ready to make a difference?</span>
                    <span class="block text-red-600">Join our community today.</span>
                </h2>
                <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                    <div class="inline-flex rounded-md shadow">
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                            Sign Up Now
                        </a>
                    </div>
                    <div class="ml-3 inline-flex rounded-md shadow">
                        <a href="{{ route('how-it-works') }}"
                            class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-red-600 bg-white hover:bg-gray-50">
                            Learn More
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
