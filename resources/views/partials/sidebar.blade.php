<aside class="w-64 bg-white border-r border-gray-200 flex flex-col">
    <!-- Logo -->
    <div class="p-6 border-b border-gray-100">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-danger" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C8 7 4 11 4 16C4 20 8 22 12 22C16 22 20 20 20 16C20 11 16 7 12 2Z" />
                    <path d="M12 8C14 8 16 10 16 12C16 14 14 16 12 16C10 16 8 14 8 12C8 10 10 8 12 8Z" fill="white"
                        opacity="0.3" />
                </svg>
            </div>
            <span class="text-xl font-bold text-gray-900">LifeLink</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4 overflow-y-auto">
        <ul class="space-y-2">
            <!-- Common Navigation -->
            <li>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center space-x-3 p-3 rounded-lg hover:bg-primary-light transition-colors {{ request()->routeIs('dashboard') ? 'bg-primary-light text-primary font-medium' : 'text-gray-700' }}">
                    <i class="fas fa-home w-5"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            @auth
                <!-- Profile -->
                <li>
                    <a href="{{ route('profile.show') }}"
                        class="flex items-center space-x-3 p-3 rounded-lg hover:bg-primary-light transition-colors {{ request()->routeIs('profile.*') ? 'bg-primary-light text-primary font-medium' : 'text-gray-700' }}">
                        <i class="fas fa-user w-5"></i>
                        <span>Profile</span>
                    </a>
                </li>

                <!-- Role Specific Navigation -->
                @if (auth()->user()->isAdmin())
                    <!-- Admin Navigation -->
                    <li class="pt-4">
                        <span class="text-xs uppercase tracking-wider text-gray-500 font-semibold px-3">Admin</span>
                    </li>
                    <li>
                        <a href="{{ route('admin.blood-requests.index') }}"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-primary-light transition-colors {{ request()->routeIs('admin.blood-requests.*') ? 'bg-primary-light text-primary font-medium' : 'text-gray-700' }}">
                            <i class="fas fa-tint w-5"></i>
                            <span>Blood Requests</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.donors.index') }}"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-primary-light transition-colors {{ request()->routeIs('admin.donors.*') ? 'bg-primary-light text-primary font-medium' : 'text-gray-700' }}">
                            <i class="fas fa-users w-5"></i>
                            <span>Donors</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-primary-light transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-primary-light text-primary font-medium' : 'text-gray-700' }}">
                            <i class="fas fa-user-shield w-5"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reports') }}"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-primary-light transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-primary-light text-primary font-medium' : 'text-gray-700' }}">
                            <i class="fas fa-chart-bar w-5"></i>
                            <span>Reports</span>
                        </a>
                    </li>
                @elseif(auth()->user()->isDonor())
                    <!-- Donor Navigation -->
                    <li class="pt-4">
                        <span class="text-xs uppercase tracking-wider text-gray-500 font-semibold px-3">Donor</span>
                    </li>
                    <li>
                        <a href="{{ route('donor.blood-requests.available') }}"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-primary-light transition-colors {{ request()->routeIs('donor.blood-requests.*') ? 'bg-primary-light text-primary font-medium' : 'text-gray-700' }}">
                            <i class="fas fa-tint w-5"></i>
                            <span>Available Requests</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('donor.responses.index') }}"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-primary-light transition-colors {{ request()->routeIs('donor.responses.*') ? 'bg-primary-light text-primary font-medium' : 'text-gray-700' }}">
                            <i class="fas fa-hand-paper w-5"></i>
                            <span>My Responses</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('donor.profile.show') }}"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-primary-light transition-colors {{ request()->routeIs('donor.profile.*') ? 'bg-primary-light text-primary font-medium' : 'text-gray-700' }}">
                            <i class="fas fa-id-card w-5"></i>
                            <span>Donor Profile</span>
                        </a>
                    </li>
                @elseif(auth()->user()->isRecipient())
                    <!-- Recipient Navigation -->
                    <li class="pt-4">
                        <span class="text-xs uppercase tracking-wider text-gray-500 font-semibold px-3">Recipient</span>
                    </li>
                    <li>
                        <a href="{{ route('recipient.blood-requests.index') }}"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-primary-light transition-colors {{ request()->routeIs('recipient.blood-requests.*') ? 'bg-primary-light text-primary font-medium' : 'text-gray-700' }}">
                            <i class="fas fa-tint w-5"></i>
                            <span>My Blood Requests</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('recipient.blood-requests.create') }}"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-primary-light transition-colors {{ request()->routeIs('recipient.blood-requests.create') ? 'bg-primary-light text-primary font-medium' : 'text-gray-700' }}">
                            <i class="fas fa-plus-circle w-5"></i>
                            <span>New Request</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('recipient.statistics') }}"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-primary-light transition-colors {{ request()->routeIs('recipient.statistics') ? 'bg-primary-light text-primary font-medium' : 'text-gray-700' }}">
                            <i class="fas fa-chart-line w-5"></i>
                            <span>Statistics</span>
                        </a>
                    </li>
                @endif

                <!-- Settings -->
                <li class="pt-4">
                    <a href="{{ route('settings') }}"
                        class="flex items-center space-x-3 p-3 rounded-lg hover:bg-primary-light transition-colors {{ request()->routeIs('settings') ? 'bg-primary-light text-primary font-medium' : 'text-gray-700' }}">
                        <i class="fas fa-cog w-5"></i>
                        <span>Settings</span>
                    </a>
                </li>
            @endauth
        </ul>
    </nav>

    <!-- User Profile -->
    @auth
        <div class="p-4 border-t border-gray-100">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-semibold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
        </div>
    @endauth
</aside>
