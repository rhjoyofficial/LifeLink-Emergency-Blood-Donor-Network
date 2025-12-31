<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @hasSection('title')
            @yield('title') - LifeLink
        @else
            LifeLink - Emergency Blood Donor Network
        @endif
    </title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <!-- Logo -->
        <div class="w-full sm:max-w-md mt-2 px-6 py-2">
            <a href="{{ url('/') }}" class="flex justify-center">
                <div class="flex items-center space-x-3">
                    <div class="h-12 w-12 bg-red-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-heartbeat text-white text-2xl"></i>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">LifeLink</span>
                </div>
            </a>
        </div>

        <!-- Card Container -->
        <div class="w-full sm:max-w-lg mt-2 px-6 py-2">
            <!-- Authentication Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <!-- Card Header -->
                @hasSection('card-header')
                    <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-5">
                        <h2 class="text-xl font-semibold text-white text-center">
                            @yield('card-header')
                        </h2>
                        @hasSection('card-subtitle')
                            <p class="mt-2 text-sm text-red-100 text-center">
                                @yield('card-subtitle')
                            </p>
                        @endif
                    </div>
                @endif

                <!-- Card Content -->
                <div class="px-6 py-8">
                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-4 p-3 rounded-md bg-green-50 border border-green-200">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-400 mr-2"></i>
                                <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="mb-4 p-3 rounded-md bg-red-50 border border-red-200">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-400 mr-2"></i>
                                <div>
                                    <p class="text-sm font-medium text-red-800">
                                        {{ __('Please fix the following errors:') }}
                                    </p>
                                    <ul class="mt-1 text-sm text-red-700">
                                        @foreach ($errors->all() as $error)
                                            <li class="flex items-center mt-1">
                                                <i class="fas fa-circle text-red-300 text-xs mr-2"></i>
                                                {{ $error }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Main Content -->
                    @yield('content')
                </div>

                <!-- Card Footer -->
                @hasSection('card-footer')
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <div class="text-center text-sm text-gray-600">
                            @yield('card-footer')
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Additional Links -->
        @hasSection('additional-links')
            <div class="w-full sm:max-w-md mt-2 px-6 py-4 text-center">
                @yield('additional-links')
            </div>
        @endif

        <!-- Back to Home -->
        <div class="w-full sm:max-w-md mt-2 px-6 py-2 text-center">
            <a href="{{ url('/') }}"
                class="inline-flex items-center text-sm text-gray-600 hover:text-red-600 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Homepage
            </a>
        </div>

    </div>

    @stack('scripts')
</body>

</html>
