<!-- Sidebar -->
<aside class="hidden lg:flex lg:flex-shrink-0">
    <div class="flex flex-col w-64 border-r border-gray-200 bg-white">
        <!-- Logo -->
        <div class="h-16 flex items-center px-6 border-b border-gray-200">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                <div class="h-10 w-10 rounded-lg bg-red-600 flex items-center justify-center">
                    <i class="fas fa-heartbeat text-white text-xl"></i>
                </div>
                <div>
                    <span class="text-xl font-bold text-gray-900">LifeLink</span>
                    <p class="text-xs text-gray-500">Blood Donor Network</p>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-3 space-y-1">
            @php
                $user = auth()->user();
                $role = $user->role;
                $currentRoute = request()->route()->getName();
            @endphp

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
                class="{{ $currentRoute === 'dashboard' ? 'bg-red-50 text-red-700 border-red-200' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border">
                <i
                    class="fas fa-tachometer-alt {{ $currentRoute === 'dashboard' ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }} mr-3"></i>
                Dashboard
            </a>

            @if ($role === 'recipient')
                <!-- My Blood Requests -->
                <a href="{{ route('recipient.blood-requests.index') }}"
                    class="{{ str_starts_with($currentRoute, 'recipient.blood-requests') ? 'bg-red-50 text-red-700 border-red-200' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border">
                    <i
                        class="fas fa-hand-holding-heart {{ str_starts_with($currentRoute, 'recipient.blood-requests') ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }} mr-3"></i>
                    My Requests
                    @if ($pendingCount = \App\Models\BloodRequest::where('recipient_id', $user->id)->where('status', 'pending')->count())
                        <span
                            class="ml-auto inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-800">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>

                <!-- New Request -->
                <a href="{{ route('recipient.blood-requests.create') }}"
                    class="{{ $currentRoute === 'recipient.blood-requests.create' ? 'bg-red-50 text-red-700 border-red-200' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border">
                    <i
                        class="fas fa-plus-circle {{ $currentRoute === 'recipient.blood-requests.create' ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }} mr-3"></i>
                    New Request
                </a>
            @elseif($role === 'donor')
                <!-- Available Requests -->
                <a href="{{ route('donor.blood-requests.available') }}"
                    class="{{ str_starts_with($currentRoute, 'donor.blood-requests') ? 'bg-red-50 text-red-700 border-red-200' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border">
                    <i
                        class="fas fa-search {{ str_starts_with($currentRoute, 'donor.blood-requests') ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }} mr-3"></i>
                    Available Requests
                    @if ($user->donorProfile && $user->donorProfile->approved_by_admin)
                        @php
                            $availableCount = \App\Models\BloodRequest::where(
                                'blood_group',
                                $user->donorProfile->blood_group,
                            )
                                ->where('status', 'approved')
                                ->where('needed_at', '>', now())
                                ->count();
                        @endphp
                        @if ($availableCount > 0)
                            <span
                                class="ml-auto inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                {{ $availableCount }}
                            </span>
                        @endif
                    @endif
                </a>

                <!-- My Responses -->
                <a href="{{ route('donor.responses.index') }}"
                    class="{{ str_starts_with($currentRoute, 'donor.responses') ? 'bg-red-50 text-red-700 border-red-200' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border">
                    <i
                        class="fas fa-history {{ str_starts_with($currentRoute, 'donor.responses') ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }} mr-3"></i>
                    My Responses
                </a>

                <!-- Donor Profile -->
                <a href="{{ route('donor.profile.show') }}"
                    class="{{ str_starts_with($currentRoute, 'donor.profile') ? 'bg-red-50 text-red-700 border-red-200' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border">
                    <i
                        class="fas fa-id-card {{ str_starts_with($currentRoute, 'donor.profile') ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }} mr-3"></i>
                    Donor Profile
                    @if ($user->donorProfile && !$user->donorProfile->approved_by_admin)
                        <span
                            class="ml-auto inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    @endif
                </a>
            @elseif($role === 'admin')
                <!-- Blood Requests -->
                <a href="{{ route('admin.blood-requests.index') }}"
                    class="{{ str_starts_with($currentRoute, 'admin.blood-requests') ? 'bg-red-50 text-red-700 border-red-200' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border">
                    <i
                        class="fas fa-tint {{ str_starts_with($currentRoute, 'admin.blood-requests') ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }} mr-3"></i>
                    Blood Requests
                    @if ($pendingCount = \App\Models\BloodRequest::where('status', 'pending')->count())
                        <span
                            class="ml-auto inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-800">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>

                <!-- Donor Management -->
                <a href="{{ route('admin.donors.index') }}"
                    class="{{ str_starts_with($currentRoute, 'admin.donors') ? 'bg-red-50 text-red-700 border-red-200' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border">
                    <i
                        class="fas fa-users {{ str_starts_with($currentRoute, 'admin.donors') ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }} mr-3"></i>
                    Donor Management
                </a>

                <!-- User Management -->
                <a href="{{ route('admin.users.index') }}"
                    class="{{ str_starts_with($currentRoute, 'admin.users') ? 'bg-red-50 text-red-700 border-red-200' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border">
                    <i
                        class="fas fa-user-shield {{ str_starts_with($currentRoute, 'admin.users') ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }} mr-3"></i>
                    User Management
                </a>

                <!-- Reports -->
                <a href="{{ route('admin.reports') }}"
                    class="{{ str_starts_with($currentRoute, 'admin.reports') ? 'bg-red-50 text-red-700 border-red-200' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border">
                    <i
                        class="fas fa-chart-bar {{ str_starts_with($currentRoute, 'admin.reports') ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }} mr-3"></i>
                    Reports & Analytics
                </a>
            @endif

            <!-- Common Links -->
            <div class="pt-6 mt-6 border-t border-gray-200 space-y-1">
                <a href="{{ route('profile.show') }}"
                    class="{{ $currentRoute === 'profile.show' ? 'bg-red-50 text-red-700 border-red-200' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border">
                    <i
                        class="fas fa-user-circle {{ $currentRoute === 'profile.show' ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }} mr-3"></i>
                    My Profile
                </a>

                <a href="{{ route('settings') }}"
                    class="{{ $currentRoute === 'settings' ? 'bg-red-50 text-red-700 border-red-200' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border">
                    <i
                        class="fas fa-cog {{ $currentRoute === 'settings' ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }} mr-3"></i>
                    Settings
                </a>
            </div>
        </nav>

        <!-- User Info -->
        <div class="border-t border-gray-200 py-2 px-4">
            <div class="flex items-center">
                <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-user text-red-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                    <p class="text-xs text-gray-500 capitalize">{{ $role }}</p>
                </div>
                <div class="ml-auto">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="p-1 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt h-5 w-5"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</aside>
