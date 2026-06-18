<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ $settings->site_name ?? config('app.name', 'TradexPro') }}</title>

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (Play CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                },
                colors: {
                    surface: {
                        base: '#0F1115',
                        raised: '#161A1E',
                        overlay: '#1C2127',
                        border: '#2A2F36',
                        'border-light': '#363C44',
                    },
                    content: {
                        primary: '#E8EAED',
                        secondary: '#9AA0AB',
                        tertiary: '#6B7280',
                        inverse: '#0F1115',
                    },
                    primary: {
                        DEFAULT: '#2E5C8A',
                        light: '#5DADE2',
                        dark: '#0F3A6E',
                        subtle: 'rgba(5,150,105,0.12)',
                    },
                    gain: '#1A3A7F',
                    loss: '#EF4444',
                    warning: '#F59E0B',
                    info: '#3B82F6',
                },
            },
        },
    }
    </script>
    <style type="text/tailwindcss">
    @layer base {
        html { background-color: #0F1115; }
        body { font-family: 'Inter', system-ui, sans-serif; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
    }
    </style>
</head>

<body class="bg-surface-base text-content-secondary min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-lg text-center">
        {{-- Icon --}}
        <div class="flex justify-center mb-6">
            <div class="bg-surface-raised border border-surface-border rounded-2xl p-5">
                @yield('icon')
            </div>
        </div>

        {{-- Error Code --}}
        <h1 class="text-7xl sm:text-8xl font-bold text-content-primary tracking-tight mb-2">
            @yield('code')
        </h1>

        {{-- Title --}}
        <h2 class="text-xl sm:text-2xl font-semibold text-content-primary mb-3">
            @yield('title')
        </h2>

        {{-- Message --}}
        <p class="text-content-secondary text-base sm:text-lg leading-relaxed mb-8 max-w-md mx-auto">
            @yield('message')
        </p>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ url()->previous() }}"
               class="inline-flex items-center gap-2 bg-surface-raised border border-surface-border text-content-primary hover:bg-surface-overlay hover:border-surface-border-light rounded-lg px-5 py-2.5 text-sm font-medium transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-surface-base">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Go Back
            </a>
            <a href="{{ url('/') }}"
               class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-content-inverse rounded-lg px-5 py-2.5 text-sm font-medium transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-surface-base">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955a1.126 1.126 0 0 1 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                Home
            </a>
        </div>

        {{-- Decorative border --}}
        <div class="mt-12 border-t border-surface-border pt-6">
            <p class="text-content-tertiary text-xs">
                {{ $settings->site_name ?? config('app.name', 'TradexPro') }}
            </p>
        </div>
    </div>

</body>
</html>
