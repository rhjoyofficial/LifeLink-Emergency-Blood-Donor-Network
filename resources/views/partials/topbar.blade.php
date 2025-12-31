<!-- Topbar -->
<header class="border-b border-gray-200 bg-white">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Mobile menu button -->
            <div class="flex items-center lg:hidden">
                <button type="button" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100"
                    onclick="toggleMobileMenu()">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fas fa-bars h-6 w-6"></i>
                </button>
            </div>

            <!-- Search -->
            <div class="flex-1 px-4">
                <div class="max-w-lg">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input id="search" name="search"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm"
                            placeholder="Search blood requests, donors..." type="search">
                    </div>
                </div>
            </div>

            <!-- Right buttons -->
            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <button type="button"
                    class="p-2 rounded-full text-gray-400 hover:text-gray-500 hover:bg-gray-100 relative">
                    <span class="sr-only">View notifications</span>
                    <i class="fas fa-bell h-5 w-5"></i>
                    @php
                        $notificationCount = 0; // Placeholder until notifications are implemented
                    @endphp
                    @if ($notificationCount > 0)
                        <span class="absolute top-1 right-1 block h-2 w-2 rounded-full bg-red-500"></span>
                    @endif
                </button>

                <!-- User dropdown -->
                <div class="relative">
                    <button type="button" class="flex items-center space-x-3 p-1 rounded-lg hover:bg-gray-100"
                        onclick="toggleUserDropdown()">
                        <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                            <i class="fas fa-user text-red-600 text-sm"></i>
                        </div>
                        <div class="hidden md:block text-left">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="userDropdown"
                        class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                        <a href="{{ route('profile.show') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user-circle mr-2"></i>Profile
                        </a>
                        <a href="{{ route('settings') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-cog mr-2"></i>Settings
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i>Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Mobile sidebar -->
<div id="mobileSidebar" class="lg:hidden fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-gray-600 bg-opacity-75" onclick="toggleMobileMenu()"></div>
    <div class="fixed inset-y-0 left-0 flex flex-col w-64 bg-white transform transition-transform duration-300 -translate-x-full"
        id="mobileSidebarContent">
        <div class="h-16 flex items-center justify-between px-6 border-b border-gray-200">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                <div class="h-10 w-10 rounded-lg bg-red-600 flex items-center justify-center">
                    <i class="fas fa-heartbeat text-white text-xl"></i>
                </div>
                <div>
                    <span class="text-xl font-bold text-gray-900">LifeLink</span>
                    <p class="text-xs text-gray-500">Blood Donor Network</p>
                </div>
            </a>
            <button type="button" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100"
                onclick="toggleMobileMenu()">
                <i class="fas fa-times h-6 w-6"></i>
            </button>
        </div>
        <div class="flex-1 px-4 py-6 overflow-y-auto">
            <!-- Mobile navigation links would go here (same as desktop sidebar) -->
        </div>
    </div>
</div>

<script>
    function toggleUserDropdown() {
        const dropdown = document.getElementById('userDropdown');
        dropdown.classList.toggle('hidden');
    }

    function toggleMobileMenu() {
        const sidebar = document.getElementById('mobileSidebar');
        const content = document.getElementById('mobileSidebarContent');
        sidebar.classList.toggle('hidden');
        content.classList.toggle('-translate-x-full');
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        const userDropdown = document.getElementById('userDropdown');
        const userButton = event.target.closest('button[onclick="toggleUserDropdown()"]');

        if (!userButton && userDropdown && !userDropdown.classList.contains('hidden')) {
            userDropdown.classList.add('hidden');
        }
    });
</script>
