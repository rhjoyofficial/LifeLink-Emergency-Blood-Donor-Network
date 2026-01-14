<header class="bg-white border-b border-gray-200 px-6 py-4">
    <div class="flex items-center justify-between">
        <!-- Page Title -->
        <div>
            <h1 class="text-2xl font-bold text-gray-900">@yield('title', 'Dashboard')</h1>
            @hasSection('subtitle')
                <p class="text-sm text-gray-600 mt-1">@yield('subtitle')</p>
            @endif
        </div>

        <!-- Right Actions -->
        <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <div class="relative">
                <button class="p-2 text-gray-600 hover:text-primary transition-colors relative" id="notificationButton">
                    <i class="fas fa-bell text-xl"></i>
                    <span
                        class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                </button>
            </div>

            <!-- Quick Actions -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                    class="flex items-center space-x-2 p-2 text-gray-700 hover:text-primary transition-colors">
                    <i class="fas fa-bolt"></i>
                    <span>Quick Actions</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>

                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                    @if (auth()->user()->isDonor())
                        <a href="{{ route('donor.blood-requests.available') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-light">
                            <i class="fas fa-search mr-2"></i> Find Requests
                        </a>
                        <a href="{{ route('donor.profile.edit') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-light">
                            <i class="fas fa-edit mr-2"></i> Update Profile
                        </a>
                    @elseif(auth()->user()->isRecipient())
                        <a href="{{ route('recipient.blood-requests.create') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-light">
                            <i class="fas fa-plus-circle mr-2"></i> New Request
                        </a>
                        <a href="{{ route('recipient.blood-requests.index') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-light">
                            <i class="fas fa-list mr-2"></i> View Requests
                        </a>
                    @elseif(auth()->user()->isAdmin())
                        <a href="{{ route('admin.blood-requests.index') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-light">
                            <i class="fas fa-tasks mr-2"></i> Review Requests
                        </a>
                        <a href="{{ route('admin.donors.index') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-light">
                            <i class="fas fa-user-check mr-2"></i> Approve Donors
                        </a>
                    @endif
                    <div class="border-t border-gray-100 my-1"></div>
                    <a href="{{ route('settings') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-light">
                        <i class="fas fa-cog mr-2"></i> Settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-4 py-2 text-base md:text-sm xl:text-base text-gray-700 hover:bg-accent/70 hover:text-white">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
