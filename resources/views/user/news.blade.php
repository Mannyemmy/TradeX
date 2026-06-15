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
    @include('user.partials.page-header', ['title' => 'Market News', 'subtitle' => 'Latest financial, forex, and stock market news'])

    {{-- Dukascopy News Widget --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden">
        <div class="px-5 py-3 border-b border-surface-border flex items-center gap-2">
            <x-icon name="document-text" class="w-5 h-5 text-primary" />
            <h3 class="text-sm font-semibold text-content-primary">Top News</h3>
        </div>
        <div class="p-2" style="min-height: 520px;">
            <script type="text/javascript">
                DukascopyApplet = {
                    "type": "online_news",
                    "params": {
                        "header": false,
                        "borders": false,
                        "defaultLanguage": "en",
                        "availableLanguages": ["ar","bg","cs","de","en","es","fa","fr","he","hu","it","ja","ms","pl","pt","ro","ru","sk","sv","th","uk","zh"],
                        "newsCategories": ["finance","forex","stocks","company_news","commodities"],
                        "width": "100%",
                        "height": "500",
                        "adv": "popup"
                    }
                };
            </script>
            <script type="text/javascript" src="https://freeserv-static.dukascopy.com/2.0/core.js"></script>
        </div>
    </div>

@endsection
