{{-- Navbar partial — Dark bg-surface-base with emerald accent --}}
<header x-data="{ mobileOpen: false }" class="sticky top-0 z-50 bg-surface-base border-b border-surface-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex-shrink-0">
                <img src="{{ asset('storage/app/public/' . $settings->logo) }}" alt="{{ $settings->site_name }}" class="h-10 w-auto" />
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden lg:flex items-center space-x-1">
                <a href="{{ route('home') }}" class="px-3 py-2 text-sm font-medium text-content-secondary hover:text-content-primary transition">Home</a>
                <a href="{{ route('pricing') }}" class="px-3 py-2 text-sm font-medium text-content-secondary hover:text-content-primary transition">Markets</a>

                {{-- Company Dropdown --}}
                <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                    <button @click="open = !open" class="inline-flex items-center px-3 py-2 text-sm font-medium text-content-secondary hover:text-content-primary transition">
                        Company
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1" class="absolute left-0 mt-1 w-48 bg-surface-raised rounded-lg shadow-xl border border-surface-border py-2" x-cloak>
                        <a href="{{ route('about') }}" class="block px-4 py-2 text-sm text-content-secondary hover:text-content-primary hover:bg-surface-overlay transition">About</a>
                        <a href="{{ route('service') }}" class="block px-4 py-2 text-sm text-content-secondary hover:text-content-primary hover:bg-surface-overlay transition">Careers</a>
                    </div>
                </div>

                {{-- Resources Dropdown --}}
                <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                    <button @click="open = !open" class="inline-flex items-center px-3 py-2 text-sm font-medium text-content-secondary hover:text-content-primary transition">
                        Resources
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1" class="absolute left-0 mt-1 w-64 bg-surface-raised rounded-lg shadow-xl border border-surface-border py-2" x-cloak>
                        <a href="{{ route('contact') }}" class="block px-4 py-2 text-sm text-content-secondary hover:text-content-primary hover:bg-surface-overlay transition">Help Center</a>
                        <a href="{{ route('faq') }}" class="block px-4 py-2 text-sm text-content-secondary hover:text-content-primary hover:bg-surface-overlay transition">
                            Legal Docs
                            <svg class="inline w-4 h-4 ml-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </a>
                        <a href="{{ route('safety') }}" class="block px-4 py-2 text-sm text-content-secondary hover:text-content-primary hover:bg-surface-overlay transition">Security</a>
                        <a href="{{ route('trading') }}" class="block px-4 py-2 text-sm text-content-secondary hover:text-content-primary hover:bg-surface-overlay transition">WebTrader</a>
                    </div>
                </div>
            </nav>

            {{-- Desktop Auth Buttons --}}
            <div class="hidden lg:flex items-center space-x-3">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-content-secondary hover:text-content-primary transition">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Logout
                        </button>
                    </form>
                    <a href="{{ route('dashboard') }}" class="bg-primary hover:bg-primary-dark text-white font-semibold rounded-lg px-5 py-2.5 text-sm transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-content-secondary hover:text-content-primary transition">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-primary hover:bg-primary-dark text-white font-semibold rounded-lg px-5 py-2.5 text-sm transition">Get Started</a>
                @endauth
            </div>

            {{-- Mobile Hamburger --}}
            <button @click="mobileOpen = !mobileOpen" class="lg:hidden text-content-secondary hover:text-content-primary p-2">
                <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="lg:hidden border-t border-surface-border pb-4" x-cloak>
            <div class="pt-3 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-sm text-content-secondary hover:text-content-primary hover:bg-surface-overlay rounded-lg transition">Home</a>
                <a href="{{ route('pricing') }}" class="block px-3 py-2 text-sm text-content-secondary hover:text-content-primary hover:bg-surface-overlay rounded-lg transition">Markets</a>
                <a href="{{ route('about') }}" class="block px-3 py-2 text-sm text-content-secondary hover:text-content-primary hover:bg-surface-overlay rounded-lg transition">About</a>
                <a href="{{ route('service') }}" class="block px-3 py-2 text-sm text-content-secondary hover:text-content-primary hover:bg-surface-overlay rounded-lg transition">Careers</a>
                <a href="{{ route('contact') }}" class="block px-3 py-2 text-sm text-content-secondary hover:text-content-primary hover:bg-surface-overlay rounded-lg transition">Help Center</a>
                <a href="{{ route('faq') }}" class="block px-3 py-2 text-sm text-content-secondary hover:text-content-primary hover:bg-surface-overlay rounded-lg transition">Legal Docs</a>
                <a href="{{ route('safety') }}" class="block px-3 py-2 text-sm text-content-secondary hover:text-content-primary hover:bg-surface-overlay rounded-lg transition">Security</a>
                <a href="{{ route('trading') }}" class="block px-3 py-2 text-sm text-content-secondary hover:text-content-primary hover:bg-surface-overlay rounded-lg transition">WebTrader</a>
            </div>
            <div class="pt-4 px-3 space-y-2 border-t border-surface-border mt-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="block w-full text-center bg-primary hover:bg-primary-dark text-white font-semibold rounded-lg px-5 py-2.5 text-sm transition">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-center text-sm text-content-secondary hover:text-content-primary py-2 transition">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center text-sm text-content-secondary hover:text-content-primary py-2 transition">Login</a>
                    <a href="{{ route('register') }}" class="block w-full text-center bg-primary hover:bg-primary-dark text-white font-semibold rounded-lg px-5 py-2.5 text-sm transition">Get Started</a>
                @endauth
            </div>
        </div>
    </div>
</header>
