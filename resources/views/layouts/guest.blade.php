<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $settings->site_name }} - @yield('title')">
    <title>@yield('title') | {{ $settings->site_name }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('storage/app/public/' . $settings->favicon) }}" sizes="any">

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
                    gain: '{{ $themeColors->gain_color ?? '#1A3A7F' }}',
                    loss: '{{ $themeColors->loss_color ?? '#EF4444' }}',
                    warning: '{{ $themeColors->warning_color ?? '#F59E0B' }}',
                    info: '{{ $themeColors->info_color ?? '#3B82F6' }}',
                },
            },
        },
    }
    </script>
    <style type="text/tailwindcss">
    @layer base {
        :root {
            --color-surface-base: {{ $themeColors->surface_base ?? '#0F1115' }};
            --color-surface-raised: {{ $themeColors->surface_raised ?? '#161A1E' }};
            --color-surface-overlay: {{ $themeColors->surface_overlay ?? '#1C2127' }};
            --color-surface-border: {{ $themeColors->surface_border ?? '#2A2F36' }};
            --color-surface-border-light: {{ $themeColors->surface_border_light ?? '#363C44' }};
            --color-content-primary: {{ $themeColors->content_primary ?? '#E8EAED' }};
            --color-content-secondary: {{ $themeColors->content_secondary ?? '#9AA0AB' }};
            --color-content-tertiary: {{ $themeColors->content_tertiary ?? '#6B7280' }};
        }
        html { background-color: {{ $themeColors->surface_base ?? '#0F1115' }}; }
        body { font-family: 'Inter', system-ui, sans-serif; color: {{ $themeColors->content_secondary ?? '#9AA0AB' }}; -webkit-font-smoothing: antialiased; }
        select { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236B7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e"); background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.5em 1.5em; -webkit-appearance: none; -moz-appearance: none; appearance: none; padding-right: 2.5rem; }
    }
    </style>

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    @yield('styles')
</head>
<body class="bg-surface-base min-h-screen flex flex-col">

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center px-4 py-8">
        @yield('content')
    </main>

    <!-- Language Selector -->
    @include('layouts.lang')

    @yield('scripts')
    <script src="/assistant-widget.js?v=4" defer></script>
</body>
</html>
