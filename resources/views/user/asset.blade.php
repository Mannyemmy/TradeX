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
    @include('user.partials.page-header', ['title' => 'Swap Crypto', 'subtitle' => 'Earn even more when you swap your account balance to and from crypto'])

    {{-- Balance Cards Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 mb-6">
        {{-- Account Balance --}}
        <div class="rounded-xl bg-surface-raised border border-surface-border p-4">
            <p class="text-xs text-content-tertiary mb-1">Account Balance</p>
            <p class="text-lg font-bold text-primary">@money(Auth::user()->account_bal)</p>
        </div>

        @if ($moresettings->btc == 'enabled')
            <div class="rounded-xl bg-surface-raised border border-surface-border p-4">
                <div class="flex items-center gap-2 mb-1">
                    <img class="w-5 h-5" src="https://img.icons8.com/color/48/000000/bitcoin--v1.png" alt="BTC" />
                    <span class="text-xs text-content-tertiary">BTC</span>
                </div>
                <p class="text-sm font-semibold text-content-primary">{{ round($cbalance->btc, 8) }}</p>
                <small class="text-xs text-content-tertiary usdelement" id="btc"></small>
            </div>
        @endif

        @if ($moresettings->eth == 'enabled')
            <div class="rounded-xl bg-surface-raised border border-surface-border p-4">
                <div class="flex items-center gap-2 mb-1">
                    <img class="w-5 h-5" src="https://img.icons8.com/fluency/48/000000/ethereum.png" alt="ETH" />
                    <span class="text-xs text-content-tertiary">ETH</span>
                </div>
                <p class="text-sm font-semibold text-content-primary">{{ round($cbalance->eth, 8) }}</p>
                <small class="text-xs text-content-tertiary usdelement" id="eth"></small>
            </div>
        @endif

        @if ($moresettings->ltc == 'enabled')
            <div class="rounded-xl bg-surface-raised border border-surface-border p-4">
                <div class="flex items-center gap-2 mb-1">
                    <img class="w-5 h-5" src="https://img.icons8.com/fluency/48/000000/litecoin.png" alt="LTC" />
                    <span class="text-xs text-content-tertiary">LTC</span>
                </div>
                <p class="text-sm font-semibold text-content-primary">{{ round($cbalance->ltc, 8) }}</p>
                <small class="text-xs text-content-tertiary usdelement" id="ltc"></small>
            </div>
        @endif

        @if ($moresettings->link == 'enabled')
            <div class="rounded-xl bg-surface-raised border border-surface-border p-4">
                <div class="flex items-center gap-2 mb-1">
                    <img class="w-5 h-5" src="https://img.icons8.com/cotton/64/000000/chainlink.png" alt="LINK" />
                    <span class="text-xs text-content-tertiary">LINK</span>
                </div>
                <p class="text-sm font-semibold text-content-primary">{{ round($cbalance->link, 8) }}</p>
                <small class="text-xs text-content-tertiary usdelement" id="link"></small>
            </div>
        @endif

        @if ($moresettings->bnb == 'enabled')
            <div class="rounded-xl bg-surface-raised border border-surface-border p-4">
                <div class="flex items-center gap-2 mb-1">
                    <img class="w-5 h-5" src="https://s2.coinmarketcap.com/static/img/coins/64x64/1839.png" alt="BNB" />
                    <span class="text-xs text-content-tertiary">BNB</span>
                </div>
                <p class="text-sm font-semibold text-content-primary">{{ round($cbalance->bnb, 8) }}</p>
                <small class="text-xs text-content-tertiary usdelement" id="bnb"></small>
            </div>
        @endif

        @if ($moresettings->ada == 'enabled')
            <div class="rounded-xl bg-surface-raised border border-surface-border p-4">
                <div class="flex items-center gap-2 mb-1">
                    <img class="w-5 h-5" src="https://s2.coinmarketcap.com/static/img/coins/64x64/2010.png" alt="ADA" />
                    <span class="text-xs text-content-tertiary">ADA</span>
                </div>
                <p class="text-sm font-semibold text-content-primary">{{ round($cbalance->ada, 8) }}</p>
                <small class="text-xs text-content-tertiary usdelement" id="ada"></small>
            </div>
        @endif

        @if ($moresettings->aave == 'enabled')
            <div class="rounded-xl bg-surface-raised border border-surface-border p-4">
                <div class="flex items-center gap-2 mb-1">
                    <img class="w-5 h-5" src="https://dynamic-assets.coinbase.com/6ad513d3c9108b163cf0a4c9fd3441cadcb9cf656ea7b9fb333eb7e4a94cd503528e0a94188285d31aedfc392f0793fd4161f7ad4e04d5f6b82e4d70a314d295/asset_icons/80f3d2256652f5ccd680fc48702d130dd01f1bd7c9737fac560a02949efac3b9.png" alt="AAVE" />
                    <span class="text-xs text-content-tertiary">AAVE</span>
                </div>
                <p class="text-sm font-semibold text-content-primary">{{ round($cbalance->aave, 8) }}</p>
                <small class="text-xs text-content-tertiary usdelement" id="aave"></small>
            </div>
        @endif

        @if ($moresettings->usdt == 'enabled')
            <div class="rounded-xl bg-surface-raised border border-surface-border p-4">
                <div class="flex items-center gap-2 mb-1">
                    <img class="w-5 h-5" src="https://img.icons8.com/color/48/000000/tether--v2.png" alt="USDT" />
                    <span class="text-xs text-content-tertiary">USDT</span>
                </div>
                <p class="text-sm font-semibold text-content-primary">{{ round($cbalance->usdt, 8) }}</p>
                <small class="text-xs text-content-tertiary">@money(round($cbalance->usdt))</small>
            </div>
        @endif

        @if ($moresettings->bch == 'enabled')
            <div class="rounded-xl bg-surface-raised border border-surface-border p-4">
                <div class="flex items-center gap-2 mb-1">
                    <img class="w-5 h-5" src="https://img.icons8.com/material-sharp/24/000000/bitcoin.png" alt="BCH" />
                    <span class="text-xs text-content-tertiary">BCH</span>
                </div>
                <p class="text-sm font-semibold text-content-primary">{{ round($cbalance->bch, 8) }}</p>
                <small class="text-xs text-content-tertiary usdelement" id="bch"></small>
            </div>
        @endif

        @if ($moresettings->xrp == 'enabled')
            <div class="rounded-xl bg-surface-raised border border-surface-border p-4">
                <div class="flex items-center gap-2 mb-1">
                    <img class="w-5 h-5" src="https://img.icons8.com/fluency/48/000000/ripple.png" alt="XRP" />
                    <span class="text-xs text-content-tertiary">XRP</span>
                </div>
                <p class="text-sm font-semibold text-content-primary">{{ round($cbalance->xrp, 8) }}</p>
                <small class="text-xs text-content-tertiary usdelement" id="xrp"></small>
            </div>
        @endif

        @if ($moresettings->xlm == 'enabled')
            <div class="rounded-xl bg-surface-raised border border-surface-border p-4">
                <div class="flex items-center gap-2 mb-1">
                    <img class="w-5 h-5" src="https://img.icons8.com/ios/50/000000/stellar.png" alt="XLM" />
                    <span class="text-xs text-content-tertiary">XLM</span>
                </div>
                <p class="text-sm font-semibold text-content-primary">{{ round($cbalance->xlm, 8) }}</p>
                <small class="text-xs text-content-tertiary usdelement" id="xlm"></small>
            </div>
        @endif
    </div>

    {{-- Chart + Swap Form --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- TradingView Chart --}}
        <div class="lg:col-span-2 rounded-xl bg-surface-raised border border-surface-border overflow-hidden">
            <div class="px-5 py-3 border-b border-surface-border flex items-center gap-2">
                <x-icon name="chart-bar" class="w-5 h-5 text-primary" />
                <h3 class="text-sm font-semibold text-content-primary">Trading Chart</h3>
            </div>
            <div id="tradingview_f933e" style="min-height: 420px;"></div>
        </div>

        {{-- Swap Form --}}
        <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden">
            <div class="px-5 py-3 border-b border-surface-border flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <x-icon name="arrows-right-left" class="w-5 h-5 text-primary" />
                    <h3 class="text-sm font-semibold text-content-primary">Swap</h3>
                </div>
                <a href="{{ route('swaphistory') }}" class="text-xs text-primary hover:text-primary-light transition-colors">
                    History &rarr;
                </a>
            </div>
            <div class="p-5">
                <form method="POST" action="javascript:void(0)" id="exchnageform">
                    @csrf
                    <div class="space-y-4">
                        {{-- Source --}}
                        <div>
                            <label class="block text-xs font-medium text-content-secondary mb-1.5">Source Account</label>
                            <select name="source" id="sourceasset"
                                    class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors">
                                @if ($moresettings->btc == 'enabled') <option value="btc">BTC</option> @endif
                                @if ($moresettings->link == 'enabled') <option value="link">LINK</option> @endif
                                @if ($moresettings->bnb == 'enabled') <option value="bnb">BNB</option> @endif
                                @if ($moresettings->ada == 'enabled') <option value="ada">ADA</option> @endif
                                @if ($moresettings->aave == 'enabled') <option value="aave">AAVE</option> @endif
                                @if ($moresettings->xlm == 'enabled') <option value="xlm">XLM</option> @endif
                                @if ($moresettings->xrp == 'enabled') <option value="xrp">XRP</option> @endif
                                @if ($moresettings->ltc == 'enabled') <option value="ltc">LTC</option> @endif
                                @if ($moresettings->bch == 'enabled') <option value="bch">BCH</option> @endif
                                @if ($moresettings->eth == 'enabled') <option value="eth">ETH</option> @endif
                                @if ($moresettings->usdt == 'enabled') <option value="usdt">USDT</option> @endif
                                <option value="usd">USD</option>
                            </select>
                        </div>

                        {{-- Destination --}}
                        <div>
                            <label class="block text-xs font-medium text-content-secondary mb-1.5">Destination Account</label>
                            <select name="destination" id="destinationasset"
                                    class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors">
                                <option value="usd">USD</option>
                                @if ($moresettings->btc == 'enabled') <option value="btc">BTC</option> @endif
                                @if ($moresettings->link == 'enabled') <option value="link">LINK</option> @endif
                                @if ($moresettings->bnb == 'enabled') <option value="bnb">BNB</option> @endif
                                @if ($moresettings->ada == 'enabled') <option value="ada">ADA</option> @endif
                                @if ($moresettings->aave == 'enabled') <option value="aave">AAVE</option> @endif
                                @if ($moresettings->xlm == 'enabled') <option value="xlm">XLM</option> @endif
                                @if ($moresettings->xrp == 'enabled') <option value="xrp">XRP</option> @endif
                                @if ($moresettings->ltc == 'enabled') <option value="ltc">LTC</option> @endif
                                @if ($moresettings->bch == 'enabled') <option value="bch">BCH</option> @endif
                                @if ($moresettings->eth == 'enabled') <option value="eth">ETH</option> @endif
                                @if ($moresettings->usdt == 'enabled') <option value="usdt">USDT</option> @endif
                            </select>
                            <p class="text-[10px] text-content-tertiary mt-1">Note: USD is your account balance.</p>
                        </div>

                        {{-- Amount --}}
                        <div>
                            <label class="block text-xs font-medium text-content-secondary mb-1.5">Amount</label>
                            <input type="text" name="amount" id="amount"
                                   class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm placeholder-content-tertiary focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors"
                                   placeholder="Enter amount">
                        </div>

                        {{-- You Will Get --}}
                        <div>
                            <label class="block text-xs font-medium text-content-secondary mb-1.5">You Will Get</label>
                            <input type="text" id="quantity" readonly
                                   class="w-full px-3 py-2.5 rounded-lg bg-surface-base border border-surface-border text-content-primary text-sm"
                                   placeholder="Converted quantity">
                            <input type="hidden" id="realquantity" name="quantity">
                        </div>

                        {{-- Fee --}}
                        <div class="flex items-center gap-2 p-3 rounded-lg bg-warning/10 border border-warning/20">
                            <x-icon name="information-circle" class="w-4 h-4 text-warning flex-shrink-0" />
                            <span class="text-xs text-warning font-medium">Swap Fee: {{ $moresettings->fee }}%</span>
                        </div>

                        {{-- Submit --}}
                        <button type="submit" class="w-full py-2.5 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-semibold transition-colors">
                            Swap Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- TradingView Widget Script --}}
    <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
    <script type="text/javascript">
        new TradingView.widget({
            "width": "100%",
            "height": "420",
            "symbol": "COINBASE:BTCUSD",
            "interval": "1",
            "timezone": "Etc/UTC",
            "theme": "dark",
            "style": "9",
            "locale": "en",
            "toolbar_bg": "#161A1E",
            "enable_publishing": false,
            "hide_side_toolbar": false,
            "allow_symbol_change": true,
            "calendar": false,
            "studies": ["BB@tv-basicstudies"],
            "container_id": "tradingview_f933e"
        });
    </script>

    @include('user.exchangescript')

@endsection
