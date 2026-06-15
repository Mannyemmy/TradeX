@extends('layouts.dash1')
@section('title', $title)
@section('content')

    {{-- Alerts --}}
    <x-danger-alert />
    <x-success-alert />

    {{-- Ticker --}}
    @include('user.partials.ticker-tape')

    {{-- Back link --}}
    <div class="mb-4">
        <a href="{{ route('user.stocks.index') }}" class="inline-flex items-center gap-1 text-sm text-content-tertiary hover:text-content-primary transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
            Back to Stocks
        </a>
    </div>

    {{-- Stock Header --}}
    <div class="bg-surface-raised border border-surface-border rounded-xl p-6 mb-6">
        <div class="flex items-center gap-4 mb-4">
            @if($asset->logo_url)
                <img src="{{ $asset->logo_url }}" alt="{{ $asset->symbol }}" class="w-14 h-14 rounded-full object-cover bg-surface-overlay">
            @else
                <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-lg">
                    {{ substr($asset->symbol, 0, 2) }}
                </div>
            @endif
            <div>
                <h1 class="text-xl font-bold text-content-primary">{{ $asset->name }}</h1>
                <p class="text-sm text-content-tertiary">{{ $asset->symbol }}</p>
            </div>
        </div>
        <div class="flex items-end gap-3">
            <span class="text-3xl font-bold text-content-primary">@money($asset->price)</span>
            @if($asset->price_change_pct_24h)
                <span class="text-sm font-medium px-2 py-0.5 rounded {{ $asset->price_change_pct_24h >= 0 ? 'bg-gain/10 text-gain' : 'bg-loss/10 text-loss' }}">
                    {{ $asset->price_change_pct_24h >= 0 ? '+' : '' }}{{ number_format($asset->price_change_pct_24h, 2) }}%
                    @if($asset->price_change_24h)
                        (@money(abs($asset->price_change_24h)))
                    @endif
                </span>
            @endif
        </div>
        @if($asset->high_24h || $asset->low_24h || $asset->volume_24h)
        <div class="flex items-center gap-6 mt-3 text-xs text-content-tertiary">
            @if($asset->high_24h) <span>High: @money($asset->high_24h)</span> @endif
            @if($asset->low_24h) <span>Low: @money($asset->low_24h)</span> @endif
            @if($asset->volume_24h) <span>Vol: {{ number_format($asset->volume_24h) }}</span> @endif
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Trade Form --}}
        <div class="lg:col-span-2">
            <div x-data="{ tab: 'buy', buyAmount: '', sellShares: '' }" class="bg-surface-raised border border-surface-border rounded-xl p-6">
                {{-- Tab Switch --}}
                <div class="flex gap-1 mb-6 bg-surface-overlay rounded-lg p-1">
                    <button @click="tab = 'buy'" :class="tab === 'buy' ? 'bg-gain text-white' : 'text-content-secondary hover:text-content-primary'"
                            class="flex-1 py-2 text-sm font-medium rounded-md transition-colors">Buy</button>
                    <button @click="tab = 'sell'" :class="tab === 'sell' ? 'bg-loss text-white' : 'text-content-secondary hover:text-content-primary'"
                            class="flex-1 py-2 text-sm font-medium rounded-md transition-colors">Sell</button>
                </div>

                {{-- Buy Form --}}
                <div x-show="tab === 'buy'" x-cloak>
                    <form action="{{ route('user.stocks.buy') }}" method="POST">
                        @csrf
                        <input type="hidden" name="trading_asset_id" value="{{ $asset->id }}">

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-content-secondary mb-1.5">Amount (@userCurrencyCode)</label>
                            <input type="number" name="amount" x-model="buyAmount" step="0.01" min="1" required
                                   class="w-full bg-surface-overlay border border-surface-border rounded-lg px-3 py-2.5 text-sm text-content-primary focus:outline-none focus:ring-2 focus:ring-primary"
                                   placeholder="Enter dollar amount..." />
                        </div>

                        <div class="bg-surface-overlay rounded-lg p-4 mb-4">
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-content-tertiary">You will receive</span>
                                <span class="text-content-primary font-medium" x-text="buyAmount > 0 ? (buyAmount / {{ $asset->price }}).toFixed(6) + ' shares' : '—'"></span>
                            </div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-content-tertiary">Price per share</span>
                                <span class="text-content-primary">@money($asset->price)</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-content-tertiary">Available balance</span>
                                <span class="text-content-primary">@money(Auth::user()->account_bal - (Auth::user()->frozen_bal ?? 0))</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-gain hover:bg-gain/90 text-white rounded-lg py-2.5 text-sm font-medium transition-colors">
                            Buy {{ $asset->symbol }}
                        </button>
                    </form>
                </div>

                {{-- Sell Form --}}
                <div x-show="tab === 'sell'" x-cloak>
                    @if($position && $position->shares > 0)
                        <form action="{{ route('user.stocks.sell') }}" method="POST">
                            @csrf
                            <input type="hidden" name="trading_asset_id" value="{{ $asset->id }}">

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-content-secondary mb-1.5">Shares to sell</label>
                                <div class="relative">
                                    <input type="number" name="shares" x-model="sellShares" step="0.00000001" min="0.00000001" max="{{ $position->shares }}" required
                                           class="w-full bg-surface-overlay border border-surface-border rounded-lg px-3 py-2.5 text-sm text-content-primary focus:outline-none focus:ring-2 focus:ring-primary pr-16"
                                           placeholder="Enter shares..." />
                                    <button type="button" @click="sellShares = {{ $position->shares }}"
                                            class="absolute right-2 top-1/2 -translate-y-1/2 text-xs font-medium text-primary hover:text-primary-dark px-2 py-1">MAX</button>
                                </div>
                                <p class="text-xs text-content-tertiary mt-1">Available: {{ number_format($position->shares, 6) }} shares</p>
                            </div>

                            <div class="bg-surface-overlay rounded-lg p-4 mb-4">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-content-tertiary">You will receive</span>
                                    <span class="text-content-primary font-medium" x-text="sellShares > 0 ? '@userCurrency' + (sellShares * {{ Auth::user()->convertToUserCurrency($asset->price) }}).toFixed(2) : '—'"></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-content-tertiary">Current price</span>
                                    <span class="text-content-primary">@money($asset->price)</span>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-loss hover:bg-loss/90 text-white rounded-lg py-2.5 text-sm font-medium transition-colors">
                                Sell {{ $asset->symbol }}
                            </button>
                        </form>
                    @else
                        <div class="text-center py-8">
                            <p class="text-content-tertiary text-sm">You don't hold any shares of {{ $asset->symbol }}.</p>
                            <p class="text-content-tertiary text-xs mt-1">Buy some shares first to start selling.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Position Card --}}
        <div>
            @if($position && $position->shares > 0)
                <div class="bg-surface-raised border border-surface-border rounded-xl p-5 mb-6">
                    <h3 class="text-sm font-semibold text-content-primary mb-4">Your Position</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-content-tertiary">Shares</span>
                            <span class="text-content-primary font-medium">{{ number_format($position->shares, 6) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-content-tertiary">Avg Cost</span>
                            <span class="text-content-primary">@money($position->avg_buy_price)</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-content-tertiary">Total Invested</span>
                            <span class="text-content-primary">@money($position->total_invested)</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-content-tertiary">Market Value</span>
                            <span class="text-content-primary font-medium">@money($position->current_value)</span>
                        </div>
                        <div class="border-t border-surface-border pt-3 flex justify-between text-sm">
                            <span class="text-content-tertiary">Unrealized P/L</span>
                            <span class="font-semibold {{ $position->unrealized_pnl >= 0 ? 'text-gain' : 'text-loss' }}">
                                {{ $position->unrealized_pnl >= 0 ? '+' : '-' }}@money(abs($position->unrealized_pnl))
                                ({{ $position->unrealized_pnl_percent >= 0 ? '+' : '' }}{{ number_format($position->unrealized_pnl_percent, 2) }}%)
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Recent Trades --}}
            @if($recentTrades->count() > 0)
                <div class="bg-surface-raised border border-surface-border rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-content-primary mb-4">Recent Trades</h3>
                    <div class="space-y-2">
                        @foreach($recentTrades as $trade)
                            <div class="flex items-center justify-between text-xs py-2 border-b border-surface-border last:border-0">
                                <div class="flex items-center gap-2">
                                    <span class="px-1.5 py-0.5 rounded font-medium {{ $trade->type === 'buy' ? 'bg-gain/10 text-gain' : 'bg-loss/10 text-loss' }}">
                                        {{ strtoupper($trade->type) }}
                                    </span>
                                    <span class="text-content-secondary">{{ number_format($trade->shares, 4) }} @ @money($trade->price_per_share)</span>
                                </div>
                                <span class="text-content-tertiary">{{ $trade->created_at->format('M d') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
