@extends('layouts.dash1')
@section('title', $title)
@section('content')

    {{-- Admin Dashboard Banner --}}
    @if(Auth::user()->dashboard_banner_enabled && Auth::user()->dashboard_banner_message)
        @php $bannerType = Auth::user()->dashboard_banner_type ?? 'warning'; @endphp
        <div class="rounded-xl border p-4 flex items-start gap-3
            {{ $bannerType === 'success' ? 'bg-gain/10 border-gain/20' : '' }}
            {{ $bannerType === 'warning' ? 'bg-warning/10 border-warning/20' : '' }}
            {{ $bannerType === 'danger' ? 'bg-loss/10 border-loss/20' : '' }}">
            @if($bannerType === 'success')
                <svg class="w-5 h-5 text-gain flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            @elseif($bannerType === 'danger')
                <svg class="w-5 h-5 text-loss flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
            @else
                <svg class="w-5 h-5 text-warning flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
            @endif
            <p class="text-sm font-medium {{ $bannerType === 'success' ? 'text-gain' : '' }}{{ $bannerType === 'warning' ? 'text-warning' : '' }}{{ $bannerType === 'danger' ? 'text-loss' : '' }}">
                {{ Auth::user()->dashboard_banner_message }}
            </p>
        </div>
    @endif

    {{-- Alerts --}}
    <x-danger-alert />
    <x-success-alert />
    <x-alert />

    {{-- Ticker Tape --}}
    @include('user.partials.ticker-tape')

    {{-- ═══ Two-Column Hero Section ═══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- LEFT COLUMN: Account Overview --}}
        <div class="lg:col-span-5 space-y-5">

            {{-- Signal Strength Widget --}}
            @if(Auth::user()->signal_strength_enabled)
            @include('user.partials.signal-strength')
            @endif

            {{-- Portfolio Hero --}}
            @include('user.partials.portfolio-hero')

            {{-- Stat Cards (2×2 Grid) --}}
            <div class="grid grid-cols-2 gap-3 overflow-hidden">
                @include('user.partials.stat-card', [
                    'label' => 'Total Profit',
                     'value' => \App\Helpers\CurrencyHelper::formatForUser(Auth::user()->roi),
                    'icon'  => 'arrow-trending-up',
                    'color' => 'gain',
                ])
                @include('user.partials.stat-card', [
                    'label' => 'Bonus',
                    'value' => \App\Helpers\CurrencyHelper::formatForUser(Auth::user()->bonus),
                    'icon'  => 'gift',
                    'color' => 'info',
                ])
                @include('user.partials.stat-card', [
                    'label' => 'Referral Bonus',
                    'value' => \App\Helpers\CurrencyHelper::formatForUser(Auth::user()->ref_bonus),
                    'icon'  => 'users',
                    'color' => 'warning',
                ])
                @include('user.partials.stat-card', [
                    'label' => 'Withdrawals',
                    'value' => \App\Helpers\CurrencyHelper::formatForUser($total_withdrawal),
                    'icon'  => 'arrow-up-tray',
                    'color' => 'loss',
                ])
            </div>

            {{-- Trade Now CTA --}}
            @if(!empty($mod['trading']))
            <a href="{{ route('trade') }}" class="block w-full text-center bg-primary hover:bg-primary-dark text-content-inverse font-semibold text-sm py-3 rounded-xl transition-colors">
                Trade Now
            </a>
            @endif

            {{-- Wallet Connect CTA --}}
            @if($settings->wallet_status == 'on' && Auth::user()->wallet_connect_status == 'on')
            <a href="{{ route('connect-wallet') }}"
               class="group relative block w-full overflow-hidden bg-surface-raised border border-primary/30 rounded-xl p-4 hover:border-primary/60 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-subtle group-hover:bg-primary/20 transition-colors">
                            <x-icon name="wallet" class="w-5 h-5 text-primary" />
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-content-primary">Connect Wallet</p>
                            <p class="text-xs text-content-tertiary">Earn @money($settings->min_return) daily</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="hidden sm:inline-flex items-center gap-1 text-[11px] font-medium px-2 py-0.5 rounded-full bg-gain/10 text-gain">
                            <span class="w-1.5 h-1.5 rounded-full bg-gain animate-pulse"></span>
                            Active
                        </span>
                        <svg class="w-4 h-4 text-content-tertiary group-hover:text-primary group-hover:translate-x-0.5 transition-all" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </div>
                </div>
            </a>
            @endif

            {{-- Quick Actions --}}
            @include('user.partials.quick-nav')

            {{-- Support Tickets Widget --}}
            @include('user.partials.support-widget')

            {{-- Pre-IPO Portfolio Widget --}}
            @if(!empty($mod['pre_ipo']) && $preIpoHoldings->count() > 0)
            <div class="bg-surface-raised border border-surface-border rounded-xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-content-primary">Pre-IPO Portfolio</h3>
                    <a href="{{ route('user.pre-ipo.holdings') }}" class="text-xs text-primary hover:text-primary-dark transition-colors">View All</a>
                </div>
                @php
                    $piTotalInvested = $preIpoHoldings->sum('total_cost');
                    $piTotalValue = $preIpoHoldings->sum(fn($h) => $h->current_value);
                    $piPnl = $piTotalValue - $piTotalInvested;
                @endphp
                <div class="grid grid-cols-3 gap-3 mb-4">
                    <div>
                        <p class="text-xs text-content-tertiary">Invested</p>
                        <p class="text-sm font-bold text-content-primary">@money($piTotalInvested)</p>
                    </div>
                    <div>
                        <p class="text-xs text-content-tertiary">Value</p>
                        <p class="text-sm font-bold text-content-primary">@money($piTotalValue)</p>
                    </div>
                    <div>
                        <p class="text-xs text-content-tertiary">P/L</p>
                        <p class="text-sm font-bold {{ $piPnl >= 0 ? 'text-gain' : 'text-loss' }}">
                            {{ $piPnl >= 0 ? '+' : '' }}@money($piPnl)
                        </p>
                    </div>
                </div>
                <div class="space-y-2">
                    @foreach($preIpoHoldings->take(3) as $holding)
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            @if($holding->company->logo)
                            <img src="{{ asset('storage/' . $holding->company->logo) }}" class="w-5 h-5 rounded-full" alt="">
                            @else
                            <div class="w-5 h-5 rounded-full bg-surface-overlay flex items-center justify-center text-content-tertiary text-[10px] font-bold">{{ substr($holding->company->symbol, 0, 1) }}</div>
                            @endif
                            <span class="text-content-primary font-medium">{{ $holding->company->symbol }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-content-secondary">{{ number_format($holding->shares) }} shares</span>
                        </div>
                    </div>
                    @endforeach
                    @if($preIpoHoldings->count() > 3)
                    <p class="text-xs text-content-tertiary text-center pt-1">+ {{ $preIpoHoldings->count() - 3 }} more</p>
                    @endif
                </div>
            </div>
            @endif

            {{-- Top Copy Trading Experts --}}
            @if(!empty($mod['copy_trading']))
            @include('user.partials.top-experts')
            @endif

        </div>

        {{-- RIGHT COLUMN: Market Data + Activity --}}
        <div class="lg:col-span-7 space-y-5">

            {{-- Market Overview (top assets with logos, prices, 24h change) --}}
            @include('user.partials.market-overview')

            {{-- Featured Stocks --}}
            @include('user.partials.featured-etfs')

            {{-- Featured Forex --}}
            @include('user.partials.featured-forex')

            {{-- Recent Transactions --}}
            @include('user.partials.recent-transactions')

        </div>
    </div>

@endsection
