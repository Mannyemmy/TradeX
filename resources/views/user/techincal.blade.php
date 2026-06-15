@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />

    {{-- Ticker Tape --}}
    @include('user.partials.ticker-tape')

    {{-- Quick Nav --}}
    @include('user.partials.quick-nav')

    {{-- Page Header --}}
    @include('user.partials.page-header', ['title' => 'Technical Analysis', 'subtitle' => 'Live forex cross rates and currency exchange data'])

    {{-- TradingView Forex Cross Rates Widget --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden">
        <div class="px-5 py-3 border-b border-surface-border flex items-center gap-2">
            <x-icon name="chart-bar" class="w-5 h-5 text-primary" />
            <h3 class="text-sm font-semibold text-content-primary">Market Cross Rates</h3>
        </div>
        <div class="p-1">
            <div class="tradingview-widget-container">
                <div class="tradingview-widget-container__widget"></div>
                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-forex-cross-rates.js" async>
                {
                    "width": "100%",
                    "height": "640",
                    "currencies": [
                        "EUR", "USD", "JPY", "GBP", "CHF", "AUD",
                        "CAD", "NZD", "CNY", "TRY", "SEK", "NOK",
                        "DKK", "ZAR", "RUB", "UYU"
                    ],
                    "isTransparent": true,
                    "colorTheme": "dark",
                    "locale": "en"
                }
                </script>
            </div>
        </div>
    </div>

@endsection
