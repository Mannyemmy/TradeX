<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — {{ $settings->site_name }}</title>
    <link rel="icon" href="{{ asset('storage/app/public/' . $settings->favicon) }}" type="image/png">

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS (Play CDN) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- CSS Custom Properties — Color System --}}
    @php
        $tc = $themeColors;
        $hexToRgb = [App\Models\ThemeColor::class, 'hexToRgb'];
    @endphp
    <style>
        :root {
            --primary: {{ $hexToRgb($tc->primary_color ?? '#2E5C8A') }};
            --primary-hover: {{ $hexToRgb($tc->primary_dark ?? '#0F3A6E') }};
            --primary-light: 219 234 254;
            --primary-foreground: 255 255 255;
            --secondary: 51 65 85;
            --secondary-hover: 30 41 59;
            --secondary-light: 241 245 249;
            --secondary-foreground: 255 255 255;
            --accent: {{ $hexToRgb($tc->primary_light ?? '#5DADE2') }};
            --surface: 248 250 252;
            --surface-card: 255 255 255;
            --surface-alt: 241 245 249;
            --surface-raised: 255 255 255;
            --surface-overlay: 15 23 42;
            --sidebar: 255 255 255;
            --sidebar-text: 71 85 105;
            --sidebar-muted: 133 147 163;
            --sidebar-active: 230 240 250;
            --sidebar-hover: 237 242 247;
            --sidebar-border: 220 227 236;
            --header: 255 255 255;
            --border: 226 232 240;
            --border-strong: 203 213 225;
            --content: 15 23 42;
            --content-secondary: 71 85 105;
            --content-muted: 148 163 184;
            --content-inverse: 255 255 255;
            --success: {{ $hexToRgb($tc->gain_color ?? '#1A3A7F') }};
            --success-light: 236 253 245;
            --warning: {{ $hexToRgb($tc->warning_color ?? '#F59E0B') }};
            --warning-light: 255 251 235;
            --danger: {{ $hexToRgb($tc->loss_color ?? '#EF4444') }};
            --danger-light: 254 242 242;
            --info: {{ $hexToRgb($tc->info_color ?? '#3B82F6') }};
            --info-light: 240 249 255;
            --chart-1: {{ $hexToRgb($tc->primary_color ?? '#2E5C8A') }};
            --chart-2: {{ $hexToRgb($tc->info_color ?? '#3B82F6') }};
            --chart-3: {{ $hexToRgb($tc->warning_color ?? '#F59E0B') }};
            --chart-4: {{ $hexToRgb($tc->gain_color ?? '#1A3A7F') }};
            --chart-5: {{ $hexToRgb($tc->loss_color ?? '#EF4444') }};
        }
        .dark {
            --primary: {{ $hexToRgb($tc->primary_light ?? '#5DADE2') }};
            --primary-hover: {{ $hexToRgb($tc->primary_color ?? '#2E5C8A') }};
            --primary-light: 30 58 138;
            --primary-foreground: 15 23 42;
            --surface: 15 23 42;
            --surface-card: 30 41 59;
            --surface-alt: 51 65 85;
            --surface-raised: 51 65 85;
            --header: 30 41 59;
            --border: 51 65 85;
            --border-strong: 71 85 105;
            --content: 241 245 249;
            --content-secondary: 203 213 225;
            --content-muted: 100 116 139;
            --success-light: 6 78 59;
            --warning-light: 120 53 15;
            --danger-light: 127 29 29;
            --info-light: 12 74 110;
        }
    </style>

    {{-- Tailwind Config --}}
    <script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    primary: {
                        DEFAULT: 'rgb(var(--primary) / <alpha-value>)',
                        hover: 'rgb(var(--primary-hover) / <alpha-value>)',
                        light: 'rgb(var(--primary-light) / <alpha-value>)',
                        foreground: 'rgb(var(--primary-foreground) / <alpha-value>)',
                    },
                    secondary: {
                        DEFAULT: 'rgb(var(--secondary) / <alpha-value>)',
                        hover: 'rgb(var(--secondary-hover) / <alpha-value>)',
                        light: 'rgb(var(--secondary-light) / <alpha-value>)',
                        foreground: 'rgb(var(--secondary-foreground) / <alpha-value>)',
                    },
                    accent: 'rgb(var(--accent) / <alpha-value>)',
                    surface: {
                        DEFAULT: 'rgb(var(--surface) / <alpha-value>)',
                        card: 'rgb(var(--surface-card) / <alpha-value>)',
                        alt: 'rgb(var(--surface-alt) / <alpha-value>)',
                        raised: 'rgb(var(--surface-raised) / <alpha-value>)',
                        overlay: 'rgb(var(--surface-overlay) / <alpha-value>)',
                    },
                    sidebar: {
                        DEFAULT: 'rgb(var(--sidebar) / <alpha-value>)',
                        text: 'rgb(var(--sidebar-text) / <alpha-value>)',
                        muted: 'rgb(var(--sidebar-muted) / <alpha-value>)',
                        active: 'rgb(var(--sidebar-active) / <alpha-value>)',
                        hover: 'rgb(var(--sidebar-hover) / <alpha-value>)',
                        border: 'rgb(var(--sidebar-border) / <alpha-value>)',
                    },
                    header: 'rgb(var(--header) / <alpha-value>)',
                    border: {
                        DEFAULT: 'rgb(var(--border) / <alpha-value>)',
                        strong: 'rgb(var(--border-strong) / <alpha-value>)',
                    },
                    content: {
                        DEFAULT: 'rgb(var(--content) / <alpha-value>)',
                        secondary: 'rgb(var(--content-secondary) / <alpha-value>)',
                        muted: 'rgb(var(--content-muted) / <alpha-value>)',
                        inverse: 'rgb(var(--content-inverse) / <alpha-value>)',
                    },
                    success: {
                        DEFAULT: 'rgb(var(--success) / <alpha-value>)',
                        light: 'rgb(var(--success-light) / <alpha-value>)',
                    },
                    warning: {
                        DEFAULT: 'rgb(var(--warning) / <alpha-value>)',
                        light: 'rgb(var(--warning-light) / <alpha-value>)',
                    },
                    danger: {
                        DEFAULT: 'rgb(var(--danger) / <alpha-value>)',
                        light: 'rgb(var(--danger-light) / <alpha-value>)',
                    },
                    info: {
                        DEFAULT: 'rgb(var(--info) / <alpha-value>)',
                        light: 'rgb(var(--info-light) / <alpha-value>)',
                    },
                    chart: {
                        1: 'rgb(var(--chart-1) / <alpha-value>)',
                        2: 'rgb(var(--chart-2) / <alpha-value>)',
                        3: 'rgb(var(--chart-3) / <alpha-value>)',
                        4: 'rgb(var(--chart-4) / <alpha-value>)',
                        5: 'rgb(var(--chart-5) / <alpha-value>)',
                    },
                },
                borderColor: {
                    DEFAULT: 'rgb(var(--border) / <alpha-value>)',
                },
                fontFamily: {
                    sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                },
                fontSize: {
                    'stat': ['1.75rem', { lineHeight: '2rem', fontWeight: '700', letterSpacing: '-0.025em' }],
                },
                boxShadow: {
                    'card': '0 1px 3px 0 rgb(0 0 0 / 0.04), 0 1px 2px -1px rgb(0 0 0 / 0.04)',
                    'card-hover': '0 4px 6px -1px rgb(0 0 0 / 0.06), 0 2px 4px -2px rgb(0 0 0 / 0.04)',
                },
            }
        }
    }
    </script>

    <style type="text/tailwindcss">
    @layer base {
        [x-cloak] { display: none !important; }
        html { background-color: rgb(var(--surface)); }
        body { font-family: 'Inter', system-ui, sans-serif; color: rgb(var(--content-secondary)); -webkit-font-smoothing: antialiased; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: rgb(var(--surface)); }
        ::-webkit-scrollbar-thumb { background: rgb(var(--border)); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgb(var(--border-strong)); }
    }
    </style>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Alpine.js 3.x --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>

    @stack('styles')
    @livewireStyles
</head>

<body class="bg-surface font-sans text-content-secondary antialiased min-h-screen"
      x-data="{
          sidebarOpen: window.innerWidth >= 1024,
          mobileSidebar: false,
          userDropdown: false,
          notifDropdown: false,
          darkMode: localStorage.getItem('admin-dark-mode') === 'true',
      }"
      x-init="if(darkMode) document.documentElement.classList.add('dark')"
      @resize.window="sidebarOpen = window.innerWidth >= 1024; if(window.innerWidth >= 1024) mobileSidebar = false"
>

    {{-- ═══════════════════════ MOBILE SIDEBAR OVERLAY ═══════════════════════ --}}
    <div x-show="mobileSidebar" x-transition.opacity class="fixed inset-0 bg-surface-overlay/60 z-40 lg:hidden" @click="mobileSidebar = false"></div>

    {{-- ═══════════════════════ SIDEBAR ═══════════════════════ --}}
    @include('admin.sidebar-new')

    {{-- ═══════════════════════ TOP BAR ═══════════════════════ --}}
    @include('admin.topmenu-new')

    {{-- ═══════════════════════ MAIN CONTENT ═══════════════════════ --}}
    <main class="transition-all duration-200 lg:ml-64 pt-16 min-h-screen">
        {{-- Toast Notifications --}}
        <div x-data="{ toasts: [] }"
             x-init="
                @if(Session::has('success'))
                    toasts.push({ id: Date.now(), message: '{{ addslashes(Session::get('success')) }}', type: 'success' });
                    setTimeout(() => { toasts = toasts.filter(t => t.id !== toasts[0]?.id) }, 5000);
                @endif
                @if(Session::has('message'))
                    toasts.push({ id: Date.now() + 1, message: '{{ addslashes(Session::get('message')) }}', type: 'error' });
                    setTimeout(() => { toasts = toasts.filter(t => t.id !== toasts[0]?.id) }, 5000);
                @endif
             "
             class="fixed top-20 right-4 z-50 space-y-2 w-80">
            <template x-for="toast in toasts" :key="toast.id">
                <div x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="translate-x-full opacity-0"
                     x-transition:enter-end="translate-x-0 opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="translate-x-0 opacity-100"
                     x-transition:leave-end="translate-x-full opacity-0"
                     :class="{
                        'bg-success-light border-success text-success': toast.type === 'success',
                        'bg-danger-light border-danger text-danger': toast.type === 'error',
                        'bg-warning-light border-warning text-warning': toast.type === 'warning',
                     }"
                     class="border rounded-lg p-4 flex items-start gap-3 shadow-lg">
                    <span x-text="toast.message" class="text-sm flex-1"></span>
                    <button @click="toasts = toasts.filter(t => t.id !== toast.id)" class="shrink-0 opacity-60 hover:opacity-100">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </template>
        </div>

        <div class="p-4 lg:p-6 space-y-6">
            @yield('content')
        </div>

        {{-- Footer --}}
        <footer class="border-t border-border py-6 px-6 mt-8">
            <p class="text-sm text-content-muted text-center">
                &copy; {{ date('Y') }} {{ $settings->site_name }}. All rights reserved.
            </p>
        </footer>
    </main>

    {{-- DataTables + jQuery (loaded for admin compatibility) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.8/b-2.4.2/b-html5-2.4.2/r-2.5.0/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.13.8/b-2.4.2/b-html5-2.4.2/r-2.5.0/datatables.min.js"></script>

    {{-- DataTables Tailwind Override --}}
    <style>
        .dataTables_wrapper { @apply text-sm text-content-secondary; }
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            @apply bg-surface-card border border-border rounded-lg px-3 py-1.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            @apply !bg-transparent !text-content-secondary !border-border hover:!bg-surface-alt hover:!text-content rounded-lg !px-3 !py-1 !ml-1 text-sm;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            @apply !bg-primary !text-primary-foreground !border-primary hover:!bg-primary-hover;
        }
        .dataTables_wrapper .dataTables_info { @apply text-content-muted text-xs; }
        table.dataTable { @apply w-full; }
        table.dataTable thead th { @apply bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border; }
        table.dataTable tbody td { @apply px-4 py-3.5 text-sm text-content-secondary border-b border-border; }
        table.dataTable tbody tr:hover { @apply bg-surface-alt/50; }
        table.dataTable tbody tr:last-child td { @apply border-0; }
    </style>

    {{-- Flatpickr --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @stack('scripts')
    @livewireScripts
</body>
</html>
