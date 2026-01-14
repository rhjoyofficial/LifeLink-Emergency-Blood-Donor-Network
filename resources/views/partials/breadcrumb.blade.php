<nav class="mb-6" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-2 text-sm text-gray-600">
        <li>
            <a href="{{ route('dashboard') }}" class="hover:text-primary transition-colors">
                <i class="fas fa-home"></i>
            </a>
        </li>

        @php
            $segments = Request::segments();
        @endphp

        @foreach ($segments as $segment)
            @if (!is_numeric($segment))
                <li class="flex items-center">
                    <i class="fas fa-chevron-right mx-2 text-xs"></i>
                    @if ($loop->last)
                        <span class="font-medium text-gray-900 capitalize">
                            {{ str_replace(['-', '_'], ' ', $segment) }}
                        </span>
                    @else
                        <a href="{{ url(implode('/', array_slice($segments, 0, $loop->iteration))) }}"
                            class="hover:text-primary transition-colors capitalize">
                            {{ str_replace(['-', '_'], ' ', $segment) }}
                        </a>
                    @endif
                </li>
            @endif
        @endforeach
    </ol>
</nav>
