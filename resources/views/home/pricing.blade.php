@extends('layouts.base')

@section('title', 'Markets')

@section('content')

{{-- ===== HERO ===== --}}
<section class="bg-body-bg py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-3xl mx-auto">
            <h1 class="font-serif text-3xl md:text-4xl font-bold text-body-text">Available markets</h1>
            <p class="text-body-muted text-lg mt-4">{{ $settings->site_name }} offers access to several asset classes. Browse the categories below to see what you can trade on our platform.</p>
        </div>
    </div>
</section>

{{-- ===== SERVICE CARDS ===== --}}
<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        @php
            $services = [
                ['color' => 'emerald', 'hex' => '#059669', 'title' => 'Forex', 'desc' => 'Trade major, minor and exotic currency pairs. Spreads and fees are published on your trading dashboard before you place an order.'],
                ['color' => 'blue', 'hex' => '#2563EB', 'title' => 'Stock CFDs', 'desc' => 'Speculate on price movements of well-known stocks. You do not own the underlying shares — you trade on the price difference.'],
                ['color' => 'purple', 'hex' => '#7C3AED', 'title' => 'Commodities', 'desc' => 'Trade CFDs on gold, silver, oil and other commodities. Prices are sourced from market data providers.'],
                ['color' => 'sky', 'hex' => '#0284C7', 'title' => 'Indices', 'desc' => 'Access CFDs on popular stock indices. Track the broader market direction without trading individual stocks.'],
                ['color' => 'gray', 'hex' => '#6B7280', 'title' => 'Cryptocurrency', 'desc' => 'Trade Bitcoin, Ethereum and other digital currencies. Crypto markets can be highly volatile — trade responsibly.'],
                ['color' => 'amber', 'hex' => '#D97706', 'title' => 'ETFs & Bonds', 'desc' => 'Diversify your portfolio with exchange-traded funds and government bond CFDs.'],
            ];
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($services as $s)
            <div class="bg-white rounded-xl shadow-sm border border-body-border p-6 hover:shadow-md transition group overflow-hidden">
                <div>
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-4" style="background-color:{{ $s['hex'] }}20">
                        <svg class="w-5 h-5" style="color:{{ $s['hex'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <h4 class="font-semibold text-body-text text-lg">{{ $s['title'] }}</h4>
                    <p class="text-body-muted text-sm mt-2 leading-relaxed">{{ $s['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== WHY TRADE ===== --}}
<section class="bg-body-bg py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="font-serif text-3xl font-bold text-body-text">Platform features</h2>
                <p class="text-body-muted mt-4 leading-relaxed">Our web-based platform is designed to be simple and transparent. Here is what you can expect when you trade with {{ $settings->site_name }}.</p>
                <ul class="mt-6 space-y-3">
                    @php
                        $features = [
                            'Published spreads — visible before you place a trade',
                            'Real-time charts and market data',
                            'Stop-loss and take-profit order types',
                            'Segregated client accounts',
                            'Two-factor authentication (2FA)',
                            'Withdrawal requests processed within 24 hours',
                            'Multiple deposit methods accepted',
                            'Responsive support via email and live chat',
                        ];
                    @endphp
                    @foreach($features as $f)
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="text-body-muted text-sm ml-2">{{ $f }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="rounded-xl overflow-hidden shadow-sm border border-body-border">
                <img src="{{ asset('temp/frontpage/img/in-cirro-7-map.svg') }}" alt="Global Markets" class="w-full" />
            </div>
        </div>
    </div>
</section>

{{-- ===== EXAMPLE INSTRUMENTS TABLE ===== --}}
<section class="bg-white py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-10">
            <h2 class="font-serif text-3xl font-bold text-body-text">Example instruments</h2>
            <p class="text-body-muted mt-2">A selection of the instruments available on our platform. This is not an exhaustive list.</p>
        </div>
        <div class="bg-white rounded-xl border border-body-border shadow-sm overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-surface-base text-white">
                        <th class="text-left py-3 px-4 font-semibold">Instrument</th>
                        <th class="text-center py-3 px-4 font-semibold">Category</th>
                        <th class="text-center py-3 px-4 font-semibold">Type</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-body-border">
                    @php
                        $shares = [
                            ['name' => 'EUR/USD', 'category' => 'Forex', 'type' => 'Currency Pair'],
                            ['name' => 'BTC/USD', 'category' => 'Crypto', 'type' => 'Cryptocurrency'],
                            ['name' => 'Gold (XAU)', 'category' => 'Commodity', 'type' => 'Precious Metal'],
                            ['name' => 'S&P 500', 'category' => 'Index', 'type' => 'Stock Index CFD'],
                            ['name' => 'Apple (AAPL)', 'category' => 'Stock CFD', 'type' => 'Equity'],
                        ];
                    @endphp
                    @foreach($shares as $share)
                    <tr class="hover:bg-body-bg/50 transition">
                        <td class="py-3 px-4 font-semibold text-body-text">{{ $share['name'] }}</td>
                        <td class="py-3 px-4 text-center"><span class="bg-primary-subtle text-primary text-xs px-2 py-0.5 rounded font-medium">{{ $share['category'] }}</span></td>
                        <td class="py-3 px-4 text-center text-body-muted">{{ $share['type'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="text-body-muted text-xs mt-4 text-center">Trading CFDs involves risk. You may lose more than your initial deposit. Please read our <a href="{{ route('risk') }}" class="text-primary hover:underline">risk disclosure</a>.</p>
    </div>
</section>

{{-- ===== CTA ===== --}}
@include('home.partials.cta-banner', [
    'title' => 'Ready to explore the markets?',
    'subtitle' => 'Open an account with ' . $settings->site_name . ' and start trading.',
    'buttonText' => 'Open an Account',
    'buttonRoute' => 'register',
])

@endsection
