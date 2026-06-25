@php
use App\Models\Wdmethod;
$dmethods = $paymethod = collect(); // lazy-loaded below only when deposit modals are needed
$showDepositModals = request()->is('dashboard') || request()->is('dashboard/deposits*') || request()->is('dashboard/payment*');
if ($showDepositModals) {
    $dmethods = $paymethod = Wdmethod::where(function ($query) {
        $query->where('type', '=', 'deposit')
            ->orWhere('type', '=', 'both');
    })->where('status', 'enabled')->orderByDesc('id')->get();
}
$unreadNotifCount = Auth::user()->unreadNotifications()->count();
$unreadTicketCount = \App\Models\SupportTicket::where('user_id', Auth::id())->where('status', 'answered')->count();
@endphp

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ $settings->site_name }}</title>
    <link rel="icon" href="{{ asset('storage/app/public/photos/'.$settings->favicon) }}" type="image/png">

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">

    {{-- Tailwind CSS (Play CDN) — TODO: replace with pre-built CSS for production --}}
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
                        base: '{{ $themeColors->surface_base ?? '#F4F7FA' }}',
                        raised: '{{ $themeColors->surface_raised ?? '#FFFFFF' }}',
                        overlay: '{{ $themeColors->surface_overlay ?? '#EDF2F7' }}',
                        border: '{{ $themeColors->surface_border ?? '#DCE3EC' }}',
                        'border-light': '{{ $themeColors->surface_border_light ?? '#C8D3E0' }}',
                    },
                    content: {
                        primary: '{{ $themeColors->content_primary ?? '#0F1B2D' }}',
                        secondary: '{{ $themeColors->content_secondary ?? '#475569' }}',
                        tertiary: '{{ $themeColors->content_tertiary ?? '#8593A3' }}',
                        inverse: '#FFFFFF',
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
        [x-cloak] { display: none !important; }
        html { background-color: #F4F7FA; }
        body { font-family: 'Inter', system-ui, sans-serif; color: #475569; -webkit-font-smoothing: antialiased; }
        /* Scrollbar styling */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #EDF2F7; }
        ::-webkit-scrollbar-thumb { background: #C8D3E0; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #A9B7C7; }
    }
    @layer components {
        .nav-link-active {
            @apply bg-primary-subtle text-primary font-medium border-l-2 border-primary;
        }
        .nav-link-item {
            @apply flex items-center gap-3 px-4 py-2.5 text-sm text-content-secondary hover:bg-surface-overlay hover:text-content-primary transition-colors duration-150 border-l-2 border-transparent;
        }
        .nav-group-label {
            @apply px-4 pt-5 pb-2 text-xs font-semibold uppercase tracking-wider text-content-tertiary;
        }
    }
    </style>

    {{-- SweetAlert2 (single instance) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    @livewireStyles
</head>

<body class="bg-surface-base font-sans text-content-secondary antialiased min-h-screen"
      x-data="{
          sidebarOpen: window.innerWidth >= 1024,
          mobileSidebar: false,
          userDropdown: false,
          notifDropdown: false,
      }"
      @resize.window="sidebarOpen = window.innerWidth >= 1024; if(window.innerWidth >= 1024) mobileSidebar = false"
>
    {{-- ═══════════════════════ MOBILE SIDEBAR OVERLAY ═══════════════════════ --}}
    <div x-show="mobileSidebar" x-transition.opacity class="fixed inset-0 bg-black/60 z-40 lg:hidden" @click="mobileSidebar = false"></div>

    {{-- ═══════════════════════ SIDEBAR ═══════════════════════ --}}
    <aside
        :class="mobileSidebar ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed top-0 left-0 z-50 h-full w-64 bg-surface-raised border-r border-surface-border flex flex-col transition-transform duration-200 ease-in-out -translate-x-full lg:translate-x-0"
    >
        {{-- Logo --}}
        <div class="flex items-center justify-between h-16 px-4 border-b border-surface-border shrink-0">
            <a href="{{ url('dashboard') }}" class="flex items-center">
                <img src="{{ asset('storage/app/public/' . $settings->logo) }}" alt="{{ $settings->site_name }}" class="h-8 w-auto max-w-[140px] object-contain">
            </a>
            <button @click="mobileSidebar = false" class="lg:hidden text-content-tertiary hover:text-content-primary">
                <x-icon name="x-mark" class="w-5 h-5" />
            </button>
        </div>

        {{-- User Mini Profile --}}
        <div class="px-4 py-4 border-b border-surface-border shrink-0">
            <div class="flex items-center gap-3">
                @if (Auth::user()->profile_photo_path)
                    <img src="{{ asset('storage/app/public/photos/' . Auth::user()->profile_photo_path) }}"
                         alt="{{ Auth::user()->name }}"
                         class="w-10 h-10 rounded-full object-cover bg-surface-overlay shrink-0">
                @else
                    <div class="w-10 h-10 rounded-full bg-surface-overlay flex items-center justify-center shrink-0">
                        <x-icon name="user-circle" class="w-8 h-8 text-content-tertiary" />
                    </div>
                @endif
                <div class="min-w-0">
                    <p class="text-sm font-medium text-content-primary truncate">{{ Auth::user()->name }}</p>
                    @if ($settings->enable_kyc == 'yes')
                    <p class="text-xs text-content-tertiary">
                        {{ Auth::user()->account_verify == 'Verified' ? '✓ Verified' : 'Unverified' }}
                    </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto py-2">
            {{-- Overview --}}
            <p class="nav-group-label">Overview</p>
            <a href="{{ url('dashboard') }}" class="nav-link-item {{ request()->is('dashboard') && !request()->is('dashboard/*') ? 'nav-link-active' : '' }}">
                <x-icon name="home" class="w-5 h-5" /> Dashboard
            </a>
            <a href="{{ route('user.trades.portfolio') }}" class="nav-link-item {{ request()->routeIs('user.trades.portfolio') ? 'nav-link-active' : '' }}">
                <x-icon name="briefcase" class="w-5 h-5" /> Portfolio
            </a>

            {{-- Trading --}}
            <p class="nav-group-label">Trading</p>
            @if(!empty($mod['trading']))
            <a href="{{ route('trade') }}" class="nav-link-item {{ request()->routeIs('trade') ? 'nav-link-active' : '' }}">
                <x-icon name="chart-bar" class="w-5 h-5" /> Open Trade
            </a>
            <a href="{{ route('user.trades.markets') }}" class="nav-link-item {{ request()->routeIs('user.trades.markets') ? 'nav-link-active' : '' }}">
                <x-icon name="globe-alt" class="w-5 h-5" /> Markets
            </a>
            @endif
            @if(!empty($mod['copy_trading']))
            <a href="{{ route('copyTrading') }}" class="nav-link-item {{ request()->routeIs('copyTrading*') ? 'nav-link-active' : '' }}">
                <x-icon name="copy" class="w-5 h-5" /> Copy Trading
            </a>
            @endif
            <a href="{{ route('tradinghistory') }}" class="nav-link-item {{ request()->routeIs('tradinghistory') ? 'nav-link-active' : '' }}">
                <x-icon name="clock" class="w-5 h-5" /> Trade History
            </a>

            {{-- Wallet --}}
            <p class="nav-group-label">Wallet</p>
            <a href="{{ url('dashboard/deposits') }}" class="nav-link-item {{ request()->is('dashboard/deposits*') ? 'nav-link-active' : '' }}">
                <x-icon name="arrow-down-tray" class="w-5 h-5" /> Deposits
            </a>
            @if(!empty($mod['investment']) || !empty($mod['cryptoswap']))
            <a href="{{ route('withdrawalsdeposits') }}" class="nav-link-item {{ request()->routeIs('withdrawalsdeposits') ? 'nav-link-active' : '' }}">
                <x-icon name="arrow-up-tray" class="w-5 h-5" /> Withdrawals
            </a>
            @endif
            <a href="{{ route('accounthistory') }}" class="nav-link-item {{ request()->routeIs('accounthistory') ? 'nav-link-active' : '' }}">
                <x-icon name="document-text" class="w-5 h-5" /> Transactions
            </a>
            @if(!empty($mod['loan']))
            <a href="{{ route('loans.create') }}" class="nav-link-item {{ request()->routeIs('loans.create') ? 'nav-link-active' : '' }}">
                <x-icon name="hand-raised" class="w-5 h-5" /> Loans
            </a>
            @endif

            {{-- Investments --}}
            <p class="nav-group-label">Investments</p>
            @if(!empty($mod['investment']))
            <a href="{{ route('mplans') }}" class="nav-link-item {{ request()->routeIs('mplans') ? 'nav-link-active' : '' }}">
                <x-icon name="banknotes" class="w-5 h-5" /> Investment Plans
            </a>
            @endif
            @if(!empty($mod['pre_ipo']))
            <a href="{{ route('user.pre-ipo.index') }}" class="nav-link-item {{ request()->routeIs('user.pre-ipo.*') ? 'nav-link-active' : '' }}">
                <x-icon name="building-office" class="w-5 h-5" /> Pre-IPO
            </a>
            @endif
            @if(!empty($mod['stocktrading']))
            <a href="{{ route('user.stocks.index') }}" class="nav-link-item {{ request()->routeIs('user.stocks.*') ? 'nav-link-active' : '' }}">
                <x-icon name="chart-bar" class="w-5 h-5" /> Stock Shares
            </a>
            @endif
            @if(!empty($mod['signal']))
            <div x-data="{ open: {{ request()->routeIs('user.signal.*') || request()->routeIs('tsignals') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full nav-link-item flex items-center justify-between {{ request()->routeIs('user.signal.*') ? 'nav-link-active' : '' }}">
                    <span class="flex items-center gap-3"><x-icon name="signal" class="w-5 h-5" /> Trading Signals</span>
                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </button>
                <div x-show="open" x-transition x-cloak class="ml-8 space-y-0.5 mt-0.5">
                    <a href="{{ route('user.signal.index') }}" class="block py-1.5 px-3 text-sm rounded-lg {{ request()->routeIs('user.signal.index') ? 'text-primary font-medium' : 'text-content-tertiary hover:text-content-primary' }} transition-colors">Signals</a>
                    <a href="{{ route('user.signal.plans') }}" class="block py-1.5 px-3 text-sm rounded-lg {{ request()->routeIs('user.signal.plans') ? 'text-primary font-medium' : 'text-content-tertiary hover:text-content-primary' }} transition-colors">Signal Plans</a>
                    <a href="{{ route('user.signal.subscriptions') }}" class="block py-1.5 px-3 text-sm rounded-lg {{ request()->routeIs('user.signal.subscriptions') ? 'text-primary font-medium' : 'text-content-tertiary hover:text-content-primary' }} transition-colors">My Subscriptions</a>
                </div>
            </div>
            @endif
            @if(!empty($mod['nft']))
            <div x-data="{ open: {{ request()->routeIs('nft.gallery', 'user.nfts.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full nav-link-item flex items-center justify-between {{ request()->routeIs('nft.gallery', 'user.nfts.*') ? 'nav-link-active' : '' }}">
                    <span class="flex items-center gap-3"><x-icon name="gem" class="w-5 h-5" /> NFT Market</span>
                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </button>
                <div x-show="open" x-transition x-cloak class="ml-8 space-y-0.5 mt-0.5">
                    <a href="{{ route('nft.gallery') }}" class="block py-1.5 px-3 text-sm rounded-lg {{ request()->routeIs('nft.gallery') ? 'text-primary font-medium' : 'text-content-tertiary hover:text-content-primary' }} transition-colors">Gallery</a>
                    <a href="{{ route('user.nfts.my') }}" class="block py-1.5 px-3 text-sm rounded-lg {{ request()->routeIs('user.nfts.my') ? 'text-primary font-medium' : 'text-content-tertiary hover:text-content-primary' }} transition-colors">My Collection</a>
                    <a href="{{ route('user.nfts.create') }}" class="block py-1.5 px-3 text-sm rounded-lg {{ request()->routeIs('user.nfts.create') ? 'text-primary font-medium' : 'text-content-tertiary hover:text-content-primary' }} transition-colors">Mint NFT</a>
                </div>
            </div>
            @endif

            {{-- Education --}}
            @if(!empty($mod['membership']))
            <p class="nav-group-label">Education</p>
            <a href="{{ route('user.courses') }}" class="nav-link-item {{ request()->routeIs('user.courses') ? 'nav-link-active' : '' }}">
                <x-icon name="academic-cap" class="w-5 h-5" /> Courses
            </a>
            <a href="{{ route('user.mycourses') }}" class="nav-link-item {{ request()->routeIs('user.mycourses', 'user.mycoursedetails', 'user.learning') ? 'nav-link-active' : '' }}">
                <x-icon name="book-open" class="w-5 h-5" /> My Courses
            </a>
            @endif

            {{-- Account --}}
            <p class="nav-group-label">Account</p>
            <a href="{{ route('profile') }}" class="nav-link-item {{ request()->routeIs('profile') ? 'nav-link-active' : '' }}">
                <x-icon name="cog" class="w-5 h-5" /> Profile & Settings
            </a>
            @if ($settings->enable_kyc == 'yes' && Auth::user()->account_verify != 'Verified')
            <a href="{{ route('account.verify') }}" class="nav-link-item {{ request()->routeIs('account.verify') ? 'nav-link-active' : '' }}">
                <x-icon name="shield-check" class="w-5 h-5" /> Verification
            </a>
            @endif
            <a href="{{ route('referuser') }}" class="nav-link-item {{ request()->routeIs('referuser') ? 'nav-link-active' : '' }}">
                <x-icon name="users" class="w-5 h-5" /> Referral Program
            </a>
            <a href="{{ url('dashboard/news') }}" class="nav-link-item {{ request()->is('dashboard/news') ? 'nav-link-active' : '' }}">
                <x-icon name="newspaper" class="w-5 h-5" /> Market News
            </a>
            <a href="{{ route('support') }}" class="nav-link-item {{ request()->routeIs('support') || request()->routeIs('support.*') ? 'nav-link-active' : '' }}">
                <x-icon name="chat-bubble-left-right" class="w-5 h-5" />
                <span class="flex-1">Support</span>
                @if($unreadTicketCount > 0)
                    <span class="min-w-[18px] h-[18px] flex items-center justify-center bg-loss text-white text-[10px] font-bold rounded-full px-1">{{ $unreadTicketCount > 99 ? '99+' : $unreadTicketCount }}</span>
                @endif
            </a>
        </nav>

        {{-- Logout --}}
        <div class="border-t border-surface-border p-4 shrink-0">
            <form method="POST" action="{{ route('logout') }}" id="sidebar-logout-form">
                @csrf
            </form>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();"
               class="nav-link-item !px-0 text-loss/80 hover:text-loss">
                <x-icon name="arrow-right-on-rectangle" class="w-5 h-5" /> Logout
            </a>
        </div>
    </aside>

    {{-- ═══════════════════════ TOP BAR ═══════════════════════ --}}
    <header class="fixed top-0 right-0 z-30 h-16 bg-surface-raised border-b border-surface-border transition-all duration-200 lg:left-64 left-0">
        <div class="flex items-center justify-between h-full px-4 lg:px-6">
            {{-- Left: hamburger + page title --}}
            <div class="flex items-center gap-3">
                <button @click="mobileSidebar = !mobileSidebar" class="lg:hidden text-content-tertiary hover:text-content-primary p-1">
                    <x-icon name="bars-3" class="w-6 h-6" />
                </button>
                <h1 class="text-lg font-semibold text-content-primary hidden sm:block">@yield('title', 'Dashboard')</h1>
            </div>

            {{-- Right: notification + user --}}
            <div class="flex items-center gap-2">
                {{-- Notification Bell --}}
                <div class="relative"
                     x-data="{
                         notifs: [],
                         count: {{ $unreadNotifCount }},
                         loaded: false,
                         loadNotifs() {
                             if (this.loaded) return;
                             fetch('{{ route("notifications.unread") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                                 .then(r => r.json())
                                 .then(d => { this.notifs = d.notifications; this.count = d.count; this.loaded = true; });
                         },
                         markAllRead() {
                             fetch('{{ route("notifications.readAll") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' } })
                                 .then(() => { this.notifs = []; this.count = 0; });
                         }
                     }"
                     @click.away="notifDropdown = false">
                    <button @click="notifDropdown = !notifDropdown; loadNotifs()" class="relative p-2 text-content-tertiary hover:text-content-primary rounded-lg hover:bg-surface-overlay transition-colors">
                        <x-icon name="bell" class="w-5 h-5" />
                        <span x-show="count > 0" x-cloak class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] flex items-center justify-center bg-loss text-white text-[10px] font-bold rounded-full px-1" x-text="count > 99 ? '99+' : count"></span>
                    </button>
                    <div x-show="notifDropdown" x-cloak x-transition
                         class="absolute right-0 mt-2 w-80 bg-surface-raised border border-surface-border rounded-xl shadow-xl z-50 overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-surface-border">
                            <h4 class="text-sm font-semibold text-content-primary">Notifications</h4>
                            <button x-show="count > 0" @click="markAllRead()" class="text-xs text-primary hover:text-primary transition-colors">Mark all read</button>
                        </div>
                        <div class="max-h-72 overflow-y-auto divide-y divide-surface-border">
                            <template x-if="notifs.length === 0">
                                <p class="text-sm text-content-tertiary text-center py-6">No new notifications</p>
                            </template>
                            <template x-for="n in notifs" :key="n.id">
                                <a :href="n.action_url || '#'" class="flex items-start gap-3 px-4 py-3 hover:bg-surface-overlay transition-colors">
                                    <div class="w-8 h-8 rounded-full bg-primary-subtle flex items-center justify-center shrink-0 mt-0.5">
                                        <x-icon name="bell" class="w-4 h-4 text-primary" />
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-content-primary truncate" x-text="n.title"></p>
                                        <p class="text-xs text-content-tertiary mt-0.5 line-clamp-2" x-text="n.message"></p>
                                        <p class="text-[10px] text-content-tertiary mt-1" x-text="n.time"></p>
                                    </div>
                                </a>
                            </template>
                        </div>
                        <a href="{{ route('notification') }}" class="block text-center text-xs text-primary hover:text-primary py-3 border-t border-surface-border transition-colors">View all notifications</a>
                    </div>
                </div>

                {{-- KYC Badge --}}
                @if ($settings->enable_kyc == 'yes')
                    @if (Auth::user()->account_verify == 'Verified')
                        <span class="hidden sm:inline-flex items-center gap-1 text-xs font-medium text-gain bg-gain/10 px-2.5 py-1 rounded-full">
                            <x-icon name="check-circle" class="w-3.5 h-3.5" /> Verified
                        </span>
                    @else
                        <a href="{{ route('account.verify') }}" class="hidden sm:inline-flex items-center gap-1 text-xs font-medium text-warning bg-warning/10 px-2.5 py-1 rounded-full hover:bg-warning/20 transition-colors">
                            <x-icon name="shield-check" class="w-3.5 h-3.5" /> Verify KYC
                        </a>
                    @endif
                @endif

                {{-- User Dropdown --}}
                <div class="relative" @click.away="userDropdown = false">
                    <button @click="userDropdown = !userDropdown" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-surface-overlay transition-colors">
                        @if (Auth::user()->profile_photo_path)
                            <img src="{{ asset('storage/app/public/photos/' . Auth::user()->profile_photo_path) }}"
                                 alt="{{ Auth::user()->name }}"
                                 class="w-8 h-8 rounded-full object-cover bg-surface-overlay">
                        @else
                            <div class="w-8 h-8 rounded-full bg-surface-overlay flex items-center justify-center">
                                <x-icon name="user-circle" class="w-6 h-6 text-content-tertiary" />
                            </div>
                        @endif
                        <span class="text-sm font-medium text-content-primary hidden md:block">{{ Auth::user()->name }}</span>
                        <x-icon name="chevron-down" class="w-4 h-4 text-content-tertiary hidden md:block" />
                    </button>
                    <div x-show="userDropdown" x-cloak x-transition
                         class="absolute right-0 mt-2 w-48 bg-surface-raised border border-surface-border rounded-xl shadow-xl py-1 z-50">
                        <a href="{{ route('profile') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-content-secondary hover:bg-surface-overlay hover:text-content-primary transition-colors">
                            <x-icon name="user-circle" class="w-4 h-4" /> Profile
                        </a>
                        <a href="{{ url('dashboard/deposits') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-content-secondary hover:bg-surface-overlay hover:text-content-primary transition-colors">
                            <x-icon name="arrow-down-tray" class="w-4 h-4" /> Deposit
                        </a>
                        <a href="{{ route('withdrawalsdeposits') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-content-secondary hover:bg-surface-overlay hover:text-content-primary transition-colors">
                            <x-icon name="arrow-up-tray" class="w-4 h-4" /> Withdraw
                        </a>
                        <a href="{{ route('accounthistory') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-content-secondary hover:bg-surface-overlay hover:text-content-primary transition-colors">
                            <x-icon name="document-text" class="w-4 h-4" /> Transactions
                        </a>
                        <div class="border-t border-surface-border my-1"></div>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm text-loss/80 hover:bg-surface-overlay hover:text-loss transition-colors">
                            <x-icon name="arrow-right-on-rectangle" class="w-4 h-4" /> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- ═══════════════════════ MAIN CONTENT ═══════════════════════ --}}
    <main class="transition-all duration-200 lg:ml-64 pt-16 min-h-screen">
        {{-- Toast Notifications --}}
        <div x-data="{ toasts: [] }"
             x-init="
                @if(Session::has('success'))
                    toasts.push({ id: Date.now(), message: '{{ Session::get('success') }}', type: 'success' });
                    setTimeout(() => { toasts = toasts.filter(t => t.id !== toasts[0]?.id) }, 5000);
                @endif
                @if(Session::has('message'))
                    toasts.push({ id: Date.now() + 1, message: '{{ Session::get('message') }}', type: 'error' });
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
                        'bg-gain/10 border-gain/20 text-gain': toast.type === 'success',
                        'bg-loss/10 border-loss/20 text-loss': toast.type === 'error',
                        'bg-warning/10 border-warning/20 text-warning': toast.type === 'warning',
                     }"
                     class="border rounded-lg p-4 flex items-start gap-3 shadow-lg backdrop-blur-sm">
                    <span x-text="toast.message" class="text-sm flex-1"></span>
                    <button @click="toasts = toasts.filter(t => t.id !== toast.id)" class="shrink-0 opacity-60 hover:opacity-100">
                        <x-icon name="x-mark" class="w-4 h-4" />
                    </button>
                </div>
            </template>
        </div>

        <div class="p-4 lg:p-6 space-y-6">
            @yield('content')
        </div>

        {{-- Footer --}}
        <footer class="border-t border-surface-border py-6 px-6 mt-8">
            <p class="text-sm text-content-tertiary text-center">
                &copy; {{ date('Y') }} <a href="#" class="text-primary hover:text-primary transition-colors">{{ $settings->site_name }}</a>. All rights reserved.
            </p>
        </footer>
    </main>

    {{-- ═══════════════════════ DEPOSIT MODALS (Alpine.js) ═══════════════════════ --}}
    @if($showDepositModals)
    @foreach ($dmethods as $item)
    <div x-data="{ open: false, copied: false }"
         @open-deposit-{{ $item->id }}.window="open = true"
         x-show="open" x-cloak
         class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        {{-- Backdrop --}}
        <div x-show="open" x-transition.opacity class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="open = false"></div>
        {{-- Modal --}}
        <div x-show="open" x-transition class="relative w-full max-w-md bg-surface-raised border border-surface-border rounded-xl shadow-2xl overflow-hidden">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-surface-border">
                <h3 class="text-base font-semibold text-content-primary">{{ $item->name }} Deposit</h3>
                <button @click="open = false" class="p-1 rounded-lg text-content-tertiary hover:text-content-primary hover:bg-surface-overlay transition-colors">
                    <x-icon name="x-mark" class="w-4 h-4" />
                </button>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5 space-y-5">

                {{-- QR + Address --}}
                <div class="flex items-start gap-4">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ $item->wallet_address }}&bgcolor=FFFFFF&color=0F1B2D"
                         alt="QR Code" class="w-24 h-24 rounded-lg border border-surface-border shrink-0">
                    <div class="min-w-0 flex-1">
                        <p class="text-xs text-content-tertiary mb-1.5">Send {{ $item->name }} to this address</p>
                        <div class="bg-surface-overlay rounded-lg px-3 py-2 border border-surface-border">
                            <p class="text-xs font-mono text-content-primary break-all leading-relaxed">{{ $item->wallet_address }}</p>
                        </div>
                        <button type="button"
                                @click="navigator.clipboard.writeText('{{ $item->wallet_address }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                class="mt-2 inline-flex items-center gap-1.5 text-xs font-medium text-primary hover:text-primary transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-.621-.504-1.125-1.125-1.125h-2.25" />
                            </svg>
                            <span x-show="!copied">Copy address</span>
                            <span x-show="copied" x-cloak class="text-gain">Copied!</span>
                        </button>
                    </div>
                </div>

                <div class="border-t border-surface-border/60"></div>

                {{-- Form --}}
                <form action="{{ route('savedeposit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="paymethd_method" value="{{ $item->name }}">
                    <input type="hidden" name="mode" value="{{ $item->name }}">

                    {{-- Amount --}}
                    <div>
                        <label class="text-xs font-medium text-content-tertiary mb-1.5 block">Amount (@userCurrency)</label>
                        <input type="number" name="amount" step="0.01" min="1" required placeholder="0.00"
                               class="w-full bg-surface-overlay border border-surface-border rounded-lg px-3 py-2.5 text-sm text-content-primary placeholder-content-tertiary focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors">
                    </div>

                    {{-- Proof Upload --}}
                    <div>
                        <label class="text-xs font-medium text-content-tertiary mb-1.5 block">Proof of Payment</label>
                        <label class="flex items-center justify-center gap-2 w-full px-4 py-3 border border-dashed border-surface-border-light rounded-lg cursor-pointer hover:border-primary/40 hover:bg-primary/5 transition-colors group">
                            <svg class="w-4 h-4 text-content-tertiary group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            <span class="text-sm text-content-tertiary group-hover:text-content-secondary transition-colors">Choose file or drag here</span>
                            <input type="file" name="proof" required class="sr-only">
                        </label>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3 pt-1">
                        <button type="button" @click="open = false"
                                class="flex-1 bg-surface-overlay text-content-secondary hover:text-content-primary hover:bg-surface-border rounded-lg py-2.5 text-sm font-medium transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 bg-primary hover:bg-primary-dark text-content-inverse rounded-lg py-2.5 text-sm font-medium transition-colors">
                            Submit Deposit
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    @endforeach
    @endif

    {{-- ═══════════════════════ OTHER DEPOSIT MODAL ═══════════════════════ --}}
    <div x-data="{ open: false }"
         @open-other-deposit.window="open = true"
         x-show="open" x-cloak
         class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div x-show="open" x-transition.opacity class="absolute inset-0 bg-black/60" @click="open = false"></div>
        <div x-show="open" x-transition class="relative w-full max-w-md bg-surface-raised border border-surface-border rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-content-primary">Other Deposit Method</h3>
                    <button @click="open = false" class="text-content-tertiary hover:text-content-primary"><x-icon name="x-mark" class="w-5 h-5" /></button>
                </div>
                <form method="POST" action="{{ route('otherpayment') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-xs text-content-tertiary font-medium mb-1 block">Full Name</label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}" readonly
                               class="w-full bg-surface-overlay border border-surface-border rounded-lg px-3 py-2.5 text-sm text-content-primary focus:outline-none">
                    </div>
                    <div>
                        <label class="text-xs text-content-tertiary font-medium mb-1 block">Email</label>
                        <input type="email" name="email" value="{{ Auth::user()->email }}" readonly
                               class="w-full bg-surface-overlay border border-surface-border rounded-lg px-3 py-2.5 text-sm text-content-primary focus:outline-none">
                    </div>
                    <div>
                        <label class="text-xs text-content-tertiary font-medium mb-1 block">Deposit Type</label>
                        <select name="mode" required
                                class="w-full bg-surface-overlay border border-surface-border rounded-lg px-3 py-2.5 text-sm text-content-primary focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="" disabled selected>Select method</option>
                            <option value="Litecoin">Litecoin</option>
                            <option value="BANK TRANSFER">Bank Transfer</option>
                            <option value="BITCOIN CASH">Bitcoin Cash</option>
                            <option value="USDT">USDT</option>
                            <option value="PAYPAL">PayPal</option>
                            <option value="WESTERN UNION">Western Union</option>
                            <option value="SKRILL">Skrill</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-content-tertiary font-medium mb-1 block">Amount</label>
                        <input type="number" step="0.01" name="amount" required placeholder="0.00"
                               class="w-full bg-surface-overlay border border-surface-border rounded-lg px-3 py-2.5 text-sm text-content-primary placeholder-content-tertiary focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div class="flex gap-3">
                        <button type="button" @click="open = false" class="flex-1 bg-surface-overlay text-content-secondary hover:bg-surface-border rounded-lg py-2.5 text-sm font-medium transition-colors">Cancel</button>
                        <button type="submit" name="request_deposit" class="flex-1 bg-primary hover:bg-primary-dark text-content-inverse rounded-lg py-2.5 text-sm font-medium transition-colors">Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════ MAIL SUPPORT MODAL ═══════════════════════ --}}
    <div x-data="{ open: false }"
         @open-mail-support.window="open = true"
         x-show="open" x-cloak
         class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div x-show="open" x-transition.opacity class="absolute inset-0 bg-black/60" @click="open = false"></div>
        <div x-show="open" x-transition class="relative w-full max-w-lg bg-surface-raised border border-surface-border rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-content-primary">Contact Support</h3>
                    <button @click="open = false" class="text-content-tertiary hover:text-content-primary"><x-icon name="x-mark" class="w-5 h-5" /></button>
                </div>
                <form method="POST" action="{{ route('enquiry') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="to_email" value="{{ $settings->site_name }} Support">
                    <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                    <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                    <div>
                        <label class="text-xs text-content-tertiary font-medium mb-1 block">Subject</label>
                        <input type="text" name="subject" required placeholder="How can we help?"
                               class="w-full bg-surface-overlay border border-surface-border rounded-lg px-3 py-2.5 text-sm text-content-primary placeholder-content-tertiary focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="text-xs text-content-tertiary font-medium mb-1 block">Message</label>
                        <textarea name="message" rows="5" required placeholder="Describe your issue..."
                                  class="w-full bg-surface-overlay border border-surface-border rounded-lg px-3 py-2.5 text-sm text-content-primary placeholder-content-tertiary focus:outline-none focus:ring-2 focus:ring-primary resize-none"></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" @click="open = false" class="flex-1 bg-surface-overlay text-content-secondary hover:bg-surface-border rounded-lg py-2.5 text-sm font-medium transition-colors">Cancel</button>
                        <button type="submit" name="contact" class="flex-1 bg-primary hover:bg-primary-dark text-content-inverse rounded-lg py-2.5 text-sm font-medium transition-colors">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @livewireScripts
    @yield('scripts')
    @include('layouts.livechat')
    <script src="/assistant-widget.js?v=6" defer></script>
</body>
</html>
