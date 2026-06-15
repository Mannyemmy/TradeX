@extends('layouts.base')

@section('title', 'About')

@section('content')

{{-- ===== HERO ===== --}}
<section class="bg-body-bg py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-5">
                <h1 class="font-serif text-3xl md:text-4xl font-bold text-body-text leading-tight">About {{ $settings->site_name }}</h1>
                <p class="text-body-muted text-lg mt-4 leading-relaxed">
                    {{ $settings->site_name }} is an online trading platform providing access to forex, cryptocurrency, commodities and stock CFD markets. We aim to offer transparent pricing, reliable order execution and a straightforward user experience.
                </p>
                <div class="grid grid-cols-3 gap-4 mt-6">
                    <div class="bg-white rounded-xl shadow-sm border border-body-border p-4 text-center">
                        <p class="text-2xl font-bold text-primary">Multi</p>
                        <p class="text-body-muted text-sm">Asset Classes</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-body-border p-4 text-center">
                        <p class="text-2xl font-bold text-primary">24/5</p>
                        <p class="text-body-muted text-sm">Support</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-body-border p-4 text-center">
                        <p class="text-2xl font-bold text-primary">SSL</p>
                        <p class="text-body-muted text-sm">Encrypted</p>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-7 relative">
                <img src="{{ asset('temp/frontpage/img/in-cirro-7-map.svg') }}" alt="Global reach" class="w-full" />
                <p class="text-center text-body-muted text-sm mt-3">Our platform serves traders across multiple time zones.</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== MISSION / VISION / VALUES ===== --}}
<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="font-serif text-3xl md:text-4xl font-bold text-body-text">What drives us</h2>
            <p class="text-body-muted text-lg mt-3">We built {{ $settings->site_name }} around three principles: transparency, reliability and accessibility.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $values = [
                    ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'title' => 'Our Mission', 'desc' => 'To provide an accessible trading platform where users can participate in global financial markets with clear pricing, fair conditions and responsive support.'],
                    ['icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z', 'title' => 'Our Vision', 'desc' => 'To make online trading straightforward and transparent. We believe users deserve clear information about fees, risks and platform capabilities before they commit any capital.'],
                    ['icon' => 'M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9', 'title' => 'Our Values', 'desc' => 'Transparency in pricing and fees. Honesty about risks — trading can result in losses. Commitment to keeping client funds segregated and platform security up to date.'],
                ];
            @endphp
            @foreach($values as $v)
            <div class="flex items-start">
                <div class="w-12 h-12 rounded-lg bg-primary flex items-center justify-center flex-shrink-0 mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $v['icon'] }}"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-body-text">{{ $v['title'] }}</h3>
                    <p class="text-body-muted text-sm mt-2 leading-relaxed">{{ $v['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== WHAT WE OFFER ===== --}}
<section class="bg-body-bg py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <h2 class="font-serif text-3xl font-bold text-body-text">What we offer</h2>
            <p class="text-body-muted mt-2">A summary of the tools, services and asset classes available on our platform.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $offerings = [
                    ['icon' => 'in-cirro-2-icon-1.svg', 'label' => 'Forex, crypto, commodities & stock CFDs'],
                    ['icon' => 'in-cirro-2-icon-2.svg', 'label' => 'Segregated client accounts & SSL encryption'],
                    ['icon' => 'in-cirro-2-icon-3.svg', 'label' => 'Real-time charts & order management'],
                    ['icon' => 'in-cirro-2-icon-4.svg', 'label' => 'Published fee schedule — no hidden charges'],
                ];
            @endphp
            @foreach($offerings as $o)
            <div class="text-center">
                <img src="{{ asset('temp/frontpage/img/' . $o['icon']) }}" alt="{{ $o['label'] }}" class="w-16 h-16 mx-auto" />
                <h6 class="text-sm font-semibold text-body-text mt-3">{{ $o['label'] }}</h6>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== RISK NOTICE + LEARN MORE ===== --}}
<section class="bg-white py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="text-center lg:text-left">
                <p class="text-body-muted font-semibold text-sm uppercase tracking-wider">Important notice</p>
                <h2 class="font-serif text-3xl font-bold text-body-text mt-2">Trading involves risk</h2>
                <p class="text-body-muted mt-3 leading-relaxed">The value of your positions can go down as well as up. You should only trade with money you can afford to lose. Please make sure you understand the risks before opening an account.</p>
                <a href="{{ route('risk') }}" class="inline-flex items-center mt-4 bg-primary hover:bg-primary-dark text-white font-semibold rounded-lg px-6 py-3 text-sm transition">
                    Read risk disclosure
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="bg-body-bg rounded-xl border border-body-border p-6">
                <h4 class="font-semibold text-body-text mb-4">Platform features</h4>
                <ul class="space-y-3">
                    @php
                        $aboutFeatures = [
                            'Web-based platform — no downloads required',
                            'Live market data from TradingView',
                            'Stop-loss and take-profit order types',
                            'Two-factor authentication (2FA)',
                            'Withdrawal requests processed within 24 hours',
                            'Email and live chat support',
                        ];
                    @endphp
                    @foreach($aboutFeatures as $af)
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="text-body-muted text-sm ml-2">{{ $af }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
@include('home.partials.cta-banner', [
    'title' => 'Ready to explore the markets?',
    'subtitle' => 'Open an account, verify your identity and start trading.',
    'buttonText' => 'Get Started',
    'buttonRoute' => 'register',
])

@endsection
