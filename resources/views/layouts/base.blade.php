<!DOCTYPE html>
<html lang="en">
<head>
    {{-- Meta --}}
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="{{ $settings->site_name }} - Unlock the Power of Your Finance" />
    <meta property="og:image" content="{{ asset('storage/app/public/' . $settings->logo) }}" />
    <meta property="og:url" content="{{ $settings->site_address }}">
    <meta name="theme-color" content="{{ $themeColors->primary_color ?? '#2E5C8A' }}" />
    <link rel="shortcut icon" href="{{ asset('storage/app/public/' . $settings->favicon) }}" type="image/x-icon">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('storage/app/public/' . $settings->favicon) }}" />

    {{-- Tailwind CSS (Play CDN) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Poppins', 'system-ui', 'sans-serif'],
                    serif: ['Merriweather', 'Georgia', 'serif'],
                },
                colors: {
                    surface: {
                        base: '{{ $themeColors->surface_base ?? '#0F1115' }}',
                        raised: '{{ $themeColors->surface_raised ?? '#161A1E' }}',
                        overlay: '{{ $themeColors->surface_overlay ?? '#1C2127' }}',
                        border: '{{ $themeColors->surface_border ?? '#2A2F36' }}',
                        'border-light': '{{ $themeColors->surface_border_light ?? '#363C44' }}',
                    },
                    content: {
                        primary: '{{ $themeColors->content_primary ?? '#E8EAED' }}',
                        secondary: '{{ $themeColors->content_secondary ?? '#9AA0AB' }}',
                        tertiary: '{{ $themeColors->content_tertiary ?? '#6B7280' }}',
                        inverse: '{{ $themeColors->surface_base ?? '#0F1115' }}',
                    },
                    primary: {
                        DEFAULT: '{{ $themeColors->primary_color ?? '#2E5C8A' }}',
                        light: '{{ $themeColors->primary_light ?? '#5DADE2' }}',
                        dark: '{{ $themeColors->primary_dark ?? '#0F3A6E' }}',
                        subtle: '{!! \App\Models\ThemeColor::hexToRgba($themeColors->primary_color ?? '#2E5C8A', 0.12) !!}',
                    },
                    body: {
                        bg: '{{ $themeColors->body_bg ?? '#F5F7F9' }}',
                        text: '{{ $themeColors->body_text ?? '#1F2937' }}',
                        muted: '{{ $themeColors->body_muted ?? '#6B7280' }}',
                        border: '{{ $themeColors->body_border ?? '#E5E7EB' }}',
                    },
                    gain: '{{ $themeColors->gain_color ?? '#1A3A7F' }}',
                    loss: '{{ $themeColors->loss_color ?? '#EF4444' }}',
                    warning: '{{ $themeColors->warning_color ?? '#F59E0B' }}',
                    info: '{{ $themeColors->info_color ?? '#3B82F6' }}',
                },
            },
        },
    }
    </script>

    {{-- Fonts: Poppins + Merriweather --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700;900&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <title>{{ $settings->site_name }} | @yield('title', 'Home')</title>

    {{-- GTranslate hide banner --}}
    <style>
        iframe.goog-te-banner-frame, iframe.skiptranslate { display: none !important; }
        body { position: static !important; top: 0px !important; }
        [x-cloak] { display: none !important; }
    </style>

    @stack('head')
</head>

<body class="font-sans antialiased bg-body-bg text-body-text">
    {{-- Page Loader --}}
    <div id="page-loader" class="fixed inset-0 z-[9999] bg-white flex items-center justify-center">
        <div class="flex space-x-1.5">
            <div class="w-2.5 h-2.5 bg-primary rounded-full animate-bounce" style="animation-delay: 0ms"></div>
            <div class="w-2.5 h-2.5 bg-primary rounded-full animate-bounce" style="animation-delay: 150ms"></div>
            <div class="w-2.5 h-2.5 bg-primary rounded-full animate-bounce" style="animation-delay: 300ms"></div>
        </div>
    </div>

    {{-- Navbar --}}
    @include('home.partials.navbar')

    {{-- Page Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('home.partials.footer')

    {{-- Back to Top --}}
    <div x-data="{ show: false }" @scroll.window="show = window.scrollY > 400" class="hidden md:block">
        <button x-show="show" @click="window.scrollTo({ top: 0, behavior: 'smooth' })" x-transition
            class="fixed bottom-6 right-6 z-40 bg-primary hover:bg-primary-dark text-white rounded-full p-3 shadow-lg transition"
            aria-label="Back to top">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
        </button>
    </div>

    {{-- TradingView Widget JS (used by index + safety) --}}
    <script src="{{ asset('temp/frontpage/js/vendors/tradingview-widget.min.js') }}"></script>

    {{-- Loader dismiss --}}
    <script>
        setTimeout(function() {
            var loader = document.getElementById('page-loader');
            if (loader) loader.style.display = 'none';
        }, 800);
    </script>

    {{-- GTranslate --}}
    @include('layouts.lang')

    {{-- WhatsApp / Livechat --}}
    @include('layouts.livechat')

    @stack('scripts')
    <script src="/assistant-widget.js?v=6" defer></script>
</body>
</html>
