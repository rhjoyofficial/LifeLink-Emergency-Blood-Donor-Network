<!-- Breadcrumb -->
@hasSection('breadcrumb')
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a>
            </li>
            @yield('breadcrumb')
        </ol>
    </nav>
@endif
