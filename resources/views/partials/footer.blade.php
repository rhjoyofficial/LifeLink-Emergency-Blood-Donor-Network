<footer class="bg-white border-t border-gray-200 px-6 py-4">
    <div class="flex items-center justify-between text-sm text-gray-600">
        <div class="flex items-center space-x-4">
            <span>&copy; {{ date('Y') }} LifeLink - Emergency Blood Donor Network</span>
            <span class="text-gray-300">|</span>
            <a href="{{ route('about') }}" class="hover:text-primary transition-colors">About</a>
            <a href="{{ route('contact') }}" class="hover:text-primary transition-colors">Contact</a>
            <a href="{{ route('privacy') }}" class="hover:text-primary transition-colors">Privacy</a>
            <a href="{{ route('terms') }}" class="hover:text-primary transition-colors">Terms</a>
        </div>
        <div>
            <span class="text-primary font-medium">Need help?</span>
            <a href="tel:+8801700000000" class="ml-2 hover:text-primary transition-colors">
                <i class="fas fa-phone mr-1"></i> +880 1700-000000
            </a>
        </div>
    </div>
</footer>
