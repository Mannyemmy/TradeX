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
    @include('user.partials.page-header', ['title' => 'Live Trading Chart', 'subtitle' => 'Real-time market chart powered by Dukascopy'])

    {{-- Dukascopy Chart Widget --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden">
        <div class="px-5 py-3 border-b border-surface-border flex items-center gap-2">
            <x-icon name="chart-bar" class="w-5 h-5 text-primary" />
            <h3 class="text-sm font-semibold text-content-primary">Live Market Chart</h3>
        </div>
        <div class="p-1" style="min-height: 620px;">
            <script type="text/javascript">
                DukascopyApplet = {
                    "type": "chart",
                    "params": {
                        "showUI": true,
                        "showTabs": true,
                        "showParameterToolbar": true,
                        "showOfferSide": true,
                        "allowInstrumentChange": true,
                        "allowPeriodChange": true,
                        "allowOfferSideChange": true,
                        "showAdditionalToolbar": true,
                        "showDetachButton": true,
                        "presentationType": "candle",
                        "axisX": true,
                        "axisY": true,
                        "legend": true,
                        "timeline": true,
                        "showDateSeparators": true,
                        "showZoom": true,
                        "showScrollButtons": true,
                        "showAutoShiftButton": true,
                        "crosshair": true,
                        "borders": false,
                        "theme": "Pastelle",
                        "uiColor": "#000",
                        "availableInstruments": "l:",
                        "instrument": "EUR/USD",
                        "period": "7",
                        "offerSide": "BID",
                        "timezone": 0,
                        "live": true,
                        "panLock": false,
                        "width": "100%",
                        "height": "600",
                        "adv": "popup"
                    }
                };
            </script>
            <script type="text/javascript" src="https://freeserv-static.dukascopy.com/2.0/core.js"></script>
        </div>
    </div>

@endsection
