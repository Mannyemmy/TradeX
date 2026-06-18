@extends('layouts.base')

@section('title', 'Web Trader')

@section('content')

{{-- ===== HERO ===== --}}
<section class="bg-body-bg py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <h1 class="font-serif text-3xl md:text-4xl font-bold text-body-text leading-tight">
            Trade <span class="text-primary">multiple asset classes</span> from one account.
        </h1>
        <p class="text-body-muted text-lg mt-2">Forex, crypto, commodities and stock CFDs — all on a single web-based platform.</p>
        <p class="text-body-muted mt-2 max-w-3xl">{{ $settings->site_name }} gives you access to live charts, real-time pricing and straightforward order management. Open, monitor and close positions from any browser — no downloads required.</p>
    </div>
</section>

{{-- ===== SERVICE CARDS ===== --}}
<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        @php
            $cards = [
                ['hex' => '#2E5C8A', 'title' => 'Forex', 'desc' => 'Trade major, minor and exotic currency pairs with published spreads and no hidden fees.', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['hex' => '#2563EB', 'title' => 'Cryptocurrency', 'desc' => 'Access Bitcoin, Ethereum and other popular cryptocurrencies. Markets available around the clock.', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
                ['hex' => '#7C3AED', 'title' => 'Commodities', 'desc' => 'Trade gold, silver, oil and other commodity CFDs with real-time price feeds.', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                ['hex' => '#1E3A5F', 'title' => 'Stock CFDs', 'desc' => 'Speculate on the price movements of popular stocks without owning the underlying shares.', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ['hex' => '#6B7280', 'title' => 'Investment Plans', 'desc' => 'Choose from a range of investment plans designed around different time horizons and risk appetites.', 'icon' => 'M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z'],
                ['hex' => '#D97706', 'title' => 'Copy Trading', 'desc' => 'Follow experienced traders and automatically replicate their positions in your own account.', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ];
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($cards as $card)
            <div class="rounded-xl overflow-hidden shadow-sm border border-body-border hover:shadow-md transition relative" style="background: linear-gradient(135deg, {{ $card['hex'] }}15, {{ $card['hex'] }}05)">
                <div class="p-6">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background-color: {{ $card['hex'] }}20">
                        <svg class="w-5 h-5" style="color: {{ $card['hex'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
                    </div>
                    <h4 class="font-semibold text-body-text text-lg">{{ $card['title'] }}</h4>
                    <hr class="my-3 border-body-border" />
                    <p class="text-body-muted text-sm">{{ $card['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== WHY TRADE ===== --}}
<section class="bg-body-bg py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <div class="lg:col-span-8">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 rounded-xl bg-primary flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-body-text">Why trade with {{ $settings->site_name }}?</h3>
                        <p class="text-body-muted mt-2">We focus on providing a straightforward platform with transparent pricing and responsive support.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 mt-6">
                    @php
                        $tradeFeatures = [
                            'Web-based — trade from any browser',
                            'Real-time charts powered by TradingView',
                            'Published spreads and fee schedule',
                            'Stop-loss and take-profit orders',
                            'SSL-encrypted connections',
                            'Segregated client accounts',
                            'Multiple deposit and withdrawal methods',
                            'Email and live chat support',
                        ];
                    @endphp
                    @foreach($tradeFeatures as $f)
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="text-body-muted text-sm ml-2">{{ $f }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="lg:col-span-4">
                <h4 class="font-semibold text-body-text mb-4">Example instruments</h4>
                <div class="bg-white rounded-xl border border-body-border overflow-hidden">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-surface-base text-white">
                                <th class="text-center py-2.5 px-3 font-semibold">Asset</th>
                                <th class="text-center py-2.5 px-3 font-semibold">Type</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-body-border">
                            <tr class="hover:bg-body-bg/50"><td class="text-center py-2.5 px-3">EUR/USD</td><td class="text-center py-2.5 px-3">Forex</td></tr>
                            <tr class="hover:bg-body-bg/50"><td class="text-center py-2.5 px-3">BTC/USD</td><td class="text-center py-2.5 px-3">Crypto</td></tr>
                            <tr class="hover:bg-body-bg/50"><td class="text-center py-2.5 px-3">Gold</td><td class="text-center py-2.5 px-3">Commodity</td></tr>
                        </tbody>
                    </table>
                </div>
                <a href="https://www.tradingview.com/markets/" target="_blank" class="inline-flex items-center text-primary text-sm font-medium mt-3 hover:underline">
                    View live markets on TradingView
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ===== RISK NOTICE ===== --}}
<section class="bg-white py-10 border-t border-body-border">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">
        <p class="text-body-muted text-sm leading-relaxed"><strong class="text-body-text">Risk warning:</strong> Trading CFDs, forex and cryptocurrencies involves significant risk of loss. You should only trade with money you can afford to lose. Please read our <a href="{{ route('risk') }}" class="text-primary hover:underline">risk disclosure</a> before opening an account.</p>
    </div>
</section>

{{-- ===== CTA ===== --}}
@include('home.partials.cta-banner', [
    'title' => 'Ready to start trading?',
    'subtitle' => 'Create an account, complete verification and place your first trade.',
    'buttonText' => 'Open an Account',
    'buttonRoute' => 'register',
])

@endsection
