<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

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
<body class="bg-surface-base text-content-secondary min-h-screen">
    <div class="md:flex min-h-screen">
        {{-- Left: Error Info --}}
        <div class="w-full md:w-1/2 flex items-center justify-center px-6 py-12">
            <div class="max-w-md">
                <div class="text-content-primary text-6xl md:text-8xl font-bold tracking-tight">
                    @yield('code', __('Oh no'))
                </div>

                <div class="w-16 h-1 bg-primary rounded-full my-4 md:my-6"></div>

                <p class="text-content-secondary text-xl md:text-2xl font-light mb-8 leading-relaxed">
                    @yield('message')
                </p>

                <a href="{{ app('router')->has('home') ? route('home') : url('/') }}"
                   class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-content-inverse rounded-lg px-5 py-2.5 text-sm font-medium transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-surface-base">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955a1.126 1.126 0 0 1 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    {{ __('Go Home') }}
                </a>
            </div>
        </div>

        {{-- Right: Illustration / Image --}}
        <div class="relative w-full md:w-1/2 flex items-center justify-center bg-surface-raised">
            @yield('image')
        </div>
    </div>
</body>
</html>
