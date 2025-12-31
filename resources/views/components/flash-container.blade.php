@props(['maxMessages' => 5])

<div id="flash-messages-container"
    class="fixed top-4 right-4 z-[9999] space-y-3 w-96 max-w-[calc(100vw-2rem)] pointer-events-none">
    <!-- Messages will be inserted here by JavaScript -->
</div>

@if (session()->has('flash_message'))
    @php
        $flash = session('flash_message');
    @endphp
    <script>
        // Store server-side flash messages for JavaScript to pick up
        if (!window.flashMessages) {
            window.flashMessages = [];
        }
        window.flashMessages.push({
            message: @json($flash['message']),
            type: @json($flash['type'] ?? 'success'),
            duration: @json($flash['duration'] ?? 5000),
            description: @json($flash['description'] ?? '')
        });
    </script>
@endif

@push('styles')
    <style>
        .flash-message {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(100%);
            opacity: 0;
        }

        .flash-message.show {
            transform: translateX(0);
            opacity: 1;
        }

        .flash-message.hide {
            transform: translateX(100%);
            opacity: 0;
        }

        .flash-progress {
            transition: width 0.05s linear;
        }

        @media (max-width: 640px) {
            #flash-messages-container {
                width: calc(100% - 2rem);
                right: 1rem;
                left: 1rem;
                max-width: none;
            }
        }
    </style>
@endpush
