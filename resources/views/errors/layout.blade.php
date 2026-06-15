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
                        DEFAULT: '#059669',
                        light: '#34D399',
                        dark: '#047857',
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
        body { font-family: 'Inter', system-ui, sans-serif; -webkit-font-smoothing: antialiased; }
    }
    </style>
</head>
<body class="bg-surface-base text-content-secondary min-h-screen flex items-center justify-center px-4">
    <div class="text-center">
        <p class="text-content-primary text-xl sm:text-2xl font-medium">
            @yield('message')
        </p>
    </div>
</body>
</html>
