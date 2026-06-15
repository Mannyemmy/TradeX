@extends('layouts.dash1')
@section('title', $title)
@section('content')

    {{-- Alerts --}}
    <x-danger-alert />
    <x-success-alert />
    <x-alert />

    {{-- Ticker + Quick Nav --}}
    @include('user.partials.ticker-tape')
    @include('user.partials.quick-nav')

    @php
        $asset = $trade->tradingAsset;
        $isBinary = ($trade->trade_type ?? 'binary') === 'binary';
        $isOpen = $trade->status === 'open';
        $isBuy = $trade->action === 'buy';
        $direction = $isBuy ? 1 : -1;
        $currentPrice = $asset ? $asset->price : null;
        $entryPrice = $trade->entry_price;
        $exitPrice = $trade->exit_price;

        // Unrealized P/L for open trades
        $unrealizedPL = null;
        if ($isOpen && $currentPrice && $entryPrice > 0) {
            $priceChange = ($currentPrice - $entryPrice) / $entryPrice;
            $unrealizedPL = round($trade->amount * $priceChange * $direction, 2);
        }

        $displayPrice = $isOpen ? $currentPrice : $exitPrice;
        $displayPL = $isOpen ? $unrealizedPL : $trade->profit_loss;

        // Price movement direction
        $priceUp = null;
        if ($displayPrice && $entryPrice) {
            $priceUp = $isBuy
                ? ($displayPrice >= $entryPrice)
                : ($displayPrice <= $entryPrice);
        }

        // Duration formatting
        if ($isBinary && $trade->duration) {
            if ($trade->duration >= 1440) {
                $durationLabel = ($trade->duration / 1440) . 'd';
            } elseif ($trade->duration >= 60) {
                $durationLabel = ($trade->duration / 60) . 'h';
            } else {
                $durationLabel = $trade->duration . 'm';
            }
        } else {
            $durationLabel = null;
        }
    @endphp

    {{-- Breadcrumb --}}
    <nav aria-label="Breadcrumb" class="mb-4">
        <ol class="flex items-center gap-2 text-sm text-content-tertiary">
            <li><a href="{{ route('user.trades.history') }}" class="hover:text-primary transition-colors">Trade History</a></li>
            <li><x-icon name="chevron-right" class="w-3.5 h-3.5" /></li>
            <li class="text-content-primary font-medium" aria-current="page">Trade #{{ $trade->id }}</li>
        </ol>
    </nav>

    {{-- Section 1: Trade Header --}}
    <div class="bg-surface-raised border border-surface-border rounded-xl p-5 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                @if($asset && $asset->logo_url)
                    <img src="{{ $asset->logo_url }}" alt="{{ $asset->symbol }}" class="w-10 h-10 rounded-full bg-surface-overlay" loading="lazy">
                @else
                    <div class="w-10 h-10 rounded-full bg-surface-overlay flex items-center justify-center text-sm font-bold text-content-secondary">
                        {{ $asset ? strtoupper(substr($asset->symbol ?? $asset->name, 0, 2)) : '?' }}
                    </div>
                @endif
                <div>
                    <h1 class="text-lg font-bold text-content-primary">
                        {{ $asset ? $asset->name : ($trade->asset_name ?? 'Unknown Asset') }}
                        @if($asset && $asset->symbol)
                            <span class="text-content-tertiary font-normal">{{ $asset->symbol }}</span>
                        @endif
                    </h1>
                    <div class="flex flex-wrap items-center gap-1.5 mt-1">
                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-semibold rounded {{ $isBinary ? 'bg-info/10 text-info' : 'bg-primary/10 text-primary' }}">
                            {{ ucfirst($trade->trade_type ?? 'binary') }}
                        </span>
                        @if($trade->is_demo)
                            <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-semibold rounded bg-warning/10 text-warning">DEMO</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-semibold rounded bg-gain/10 text-gain">LIVE</span>
                        @endif
                        @if($isOpen)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded bg-info/10 text-info">
                                <span class="w-1.5 h-1.5 rounded-full bg-info animate-pulse" aria-hidden="true"></span>
                                Open
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded bg-surface-overlay text-content-tertiary">
                                <span class="w-1.5 h-1.5 rounded-full bg-content-tertiary" aria-hidden="true"></span>
                                Closed
                            </span>
                        @endif
                        @if(!$isOpen && $trade->result)
                            @if($trade->result === 'WIN')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded bg-gain/10 text-gain">
                                    <x-icon name="arrow-trending-up" class="w-3 h-3" /> WIN
                                </span>
                            @elseif($trade->result === 'LOSS')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded bg-loss/10 text-loss">
                                    <x-icon name="arrow-trending-down" class="w-3 h-3" /> LOSS
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2">
                @if($isOpen && !$isBinary && !$trade->close_requested_at)
                    <form method="POST" action="{{ route('trades.requestClose') }}" onsubmit="return confirm('Are you sure you want to close this position?')">
                        @csrf
                        <input type="hidden" name="trade_id" value="{{ $trade->id }}">
                        <button type="submit" class="bg-loss/10 hover:bg-loss/20 text-loss px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Request Close
                        </button>
                    </form>
                @elseif($isOpen && !$isBinary && $trade->close_requested_at)
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-warning bg-warning/10 rounded-lg">
                        <x-icon name="clock" class="w-4 h-4" />
                        Close Pending
                    </span>
                @endif
                <a href="{{ route('trade') }}" class="bg-primary hover:bg-primary-dark text-content-inverse px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Trade Again
                </a>
            </div>
        </div>
    </div>

    {{-- Section 2: Price Action Card --}}
    <div class="bg-surface-raised border border-surface-border rounded-xl p-6 mb-6">
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-8">
            {{-- Entry Price --}}
            <div class="text-center">
                <span class="text-xs text-content-tertiary uppercase tracking-wider block mb-1">Entry Price</span>
                <span class="text-2xl font-bold text-content-primary">
                    {{ $entryPrice ? \App\Helpers\CurrencyHelper::formatForUser($entryPrice) : '—' }}
                </span>
            </div>

            {{-- Arrow --}}
            <div class="flex flex-col items-center gap-1">
                <x-icon name="arrow-long-right" class="w-8 h-8 {{ $priceUp === true ? 'text-gain' : ($priceUp === false ? 'text-loss' : 'text-content-tertiary') }}" />
                @if($displayPL !== null)
                    <span class="text-lg font-bold {{ $displayPL >= 0 ? 'text-gain' : 'text-loss' }}" aria-label="Profit or loss: {{ \App\Helpers\CurrencyHelper::formatForUser($displayPL) }}">
                        @money($displayPL)
                    </span>
                    @if($isOpen)
                        <span class="text-[10px] text-content-tertiary">(Unrealized)</span>
                    @endif
                @endif
            </div>

            {{-- Exit / Current Price --}}
            <div class="text-center">
                <span class="text-xs text-content-tertiary uppercase tracking-wider block mb-1" aria-live="{{ $isOpen ? 'polite' : 'off' }}">
                    {{ $isOpen ? 'Current Price' : 'Exit Price' }}
                </span>
                <span class="text-2xl font-bold {{ $priceUp === true ? 'text-gain' : ($priceUp === false ? 'text-loss' : 'text-content-primary') }}">
                    {{ $displayPrice ? \App\Helpers\CurrencyHelper::formatForUser($displayPrice) : '—' }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Section 3: Trade Parameters --}}
        <div class="lg:col-span-2 bg-surface-raised border border-surface-border rounded-xl p-5">
            <h2 class="text-sm font-semibold text-content-primary mb-4">Trade Parameters</h2>
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                {{-- Direction --}}
                <div class="bg-surface-overlay rounded-lg p-3">
                    <span class="text-[10px] text-content-tertiary uppercase tracking-wider block mb-1">Direction</span>
                    <span class="inline-flex items-center gap-1.5 text-sm font-semibold uppercase {{ $isBuy ? 'text-gain' : 'text-loss' }}">
                        <x-icon name="{{ $isBuy ? 'arrow-trending-up' : 'arrow-trending-down' }}" class="w-4 h-4" />
                        {{ $trade->action }}
                    </span>
                </div>
                {{-- Amount --}}
                <div class="bg-surface-overlay rounded-lg p-3">
                    <span class="text-[10px] text-content-tertiary uppercase tracking-wider block mb-1">Amount</span>
                    <span class="text-sm font-semibold text-content-primary">@money($trade->amount)</span>
                </div>
                {{-- Leverage --}}
                <div class="bg-surface-overlay rounded-lg p-3">
                    <span class="text-[10px] text-content-tertiary uppercase tracking-wider block mb-1">Leverage</span>
                    <span class="text-sm font-semibold text-content-primary">{{ $trade->leverage }}x</span>
                </div>
                {{-- Duration / Time Open --}}
                <div class="bg-surface-overlay rounded-lg p-3">
                    <span class="text-[10px] text-content-tertiary uppercase tracking-wider block mb-1">
                        {{ $isBinary ? 'Duration' : 'Time Open' }}
                    </span>
                    <span class="text-sm font-semibold text-content-primary">
                        @if($isBinary && $durationLabel)
                            {{ $durationLabel }}
                        @elseif(!$isBinary)
                            {{ \Carbon\Carbon::parse($trade->created_at)->diffForHumans(null, true) }}
                        @else
                            —
                        @endif
                    </span>
                </div>
                {{-- P/L --}}
                <div class="bg-surface-overlay rounded-lg p-3">
                    <span class="text-[10px] text-content-tertiary uppercase tracking-wider block mb-1">
                        {{ $isOpen ? 'Unrealized P/L' : 'Realized P/L' }}
                    </span>
                    @include('user.trades.partials.pnl-display', ['value' => $displayPL])
                </div>
                {{-- Take Profit / Stop Loss --}}
                @if($trade->take_profit || $trade->stop_loss)
                    <div class="bg-surface-overlay rounded-lg p-3">
                        <span class="text-[10px] text-content-tertiary uppercase tracking-wider block mb-1">TP / SL</span>
                        <span class="text-sm text-content-primary">
                            <span class="text-gain">{{ $trade->take_profit ? \App\Helpers\CurrencyHelper::formatForUser($trade->take_profit) : '—' }}</span>
                            /
                            <span class="text-loss">{{ $trade->stop_loss ? \App\Helpers\CurrencyHelper::formatForUser($trade->stop_loss) : '—' }}</span>
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Section 4: Timeline --}}
        <div class="bg-surface-raised border border-surface-border rounded-xl p-5">
            <h2 class="text-sm font-semibold text-content-primary mb-4">Timeline</h2>
            <div class="relative" role="list" aria-label="Trade timeline">
                @php
                    $steps = [];
                    $steps[] = [
                        'label' => 'Trade Opened',
                        'time' => $trade->created_at,
                        'done' => true,
                    ];
                    if ($isBinary && $trade->expires_at) {
                        $expired = \Carbon\Carbon::parse($trade->expires_at)->isPast();
                        $steps[] = [
                            'label' => $expired ? 'Expired' : 'Expires At',
                            'time' => $trade->expires_at,
                            'done' => $expired,
                            'countdown' => !$expired && $isOpen,
                        ];
                    }
                    if (!$isBinary && $trade->close_requested_at) {
                        $steps[] = [
                            'label' => 'Close Requested',
                            'time' => $trade->close_requested_at,
                            'done' => true,
                        ];
                    } elseif (!$isBinary && $isOpen) {
                        $steps[] = [
                            'label' => 'Close Request',
                            'time' => null,
                            'done' => false,
                        ];
                    }
                    if (!$isOpen) {
                        $steps[] = [
                            'label' => 'Trade Settled',
                            'time' => $trade->settled_at ?? $trade->updated_at,
                            'done' => true,
                            'extra' => $trade->settled_by ? 'by ' . ucfirst($trade->settled_by) : null,
                        ];
                    } else {
                        $steps[] = [
                            'label' => 'Settlement',
                            'time' => null,
                            'done' => false,
                        ];
                    }
                @endphp

                @foreach($steps as $index => $step)
                    <div class="flex gap-3 {{ !$loop->last ? 'pb-5' : '' }}" role="listitem">
                        {{-- Dot + Line --}}
                        <div class="flex flex-col items-center">
                            <div class="w-3 h-3 rounded-full mt-0.5 flex-shrink-0 {{ $step['done'] ? 'bg-primary' : 'bg-surface-overlay border-2 border-surface-border' }}" aria-hidden="true"></div>
                            @if(!$loop->last)
                                <div class="w-0.5 flex-1 mt-1 {{ $step['done'] ? 'bg-primary/30' : 'bg-surface-border' }}" aria-hidden="true"></div>
                            @endif
                        </div>
                        {{-- Content --}}
                        <div class="flex-1 min-w-0 pb-1">
                            <p class="text-sm font-medium {{ $step['done'] ? 'text-content-primary' : 'text-content-tertiary' }}">
                                {{ $step['label'] }}
                            </p>
                            @if($step['time'])
                                <p class="text-xs text-content-tertiary mt-0.5" title="{{ \Carbon\Carbon::parse($step['time'])->format('Y-m-d H:i:s') }}">
                                    {{ \Carbon\Carbon::parse($step['time'])->format('M d, Y H:i') }}
                                    <span class="text-content-tertiary">&middot; {{ \Carbon\Carbon::parse($step['time'])->diffForHumans() }}</span>
                                </p>
                                @if(!empty($step['countdown']))
                                    <p class="text-xs text-warning font-medium mt-0.5 countdown-timer" data-expiry="{{ \Carbon\Carbon::parse($step['time'])->timestamp }}" aria-live="polite"></p>
                                @endif
                            @elseif(!$step['done'])
                                <p class="text-xs text-content-tertiary italic mt-0.5">Pending</p>
                            @endif
                            @if(!empty($step['extra']))
                                <p class="text-[10px] text-content-tertiary mt-0.5">{{ $step['extra'] }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Section 5: TradingView Chart --}}
    @if($asset && $asset->symbol)
        <div class="bg-surface-raised border border-surface-border rounded-xl overflow-hidden mb-6">
            <div class="px-5 py-3 border-b border-surface-border">
                <h2 class="text-sm font-semibold text-content-primary">Price Chart</h2>
            </div>
            <div class="tradingview-widget-container" style="height:350px;">
                <div id="tradingview_chart" style="height:100%;"></div>
            </div>
        </div>
    @endif

@endsection

@section('scripts')
<script>
    // Countdown timer for binary trades
    document.querySelectorAll('.countdown-timer').forEach(function(el) {
        const expiry = parseInt(el.dataset.expiry);
        function tick() {
            const now = Math.floor(Date.now() / 1000);
            const diff = expiry - now;
            if (diff <= 0) {
                el.textContent = 'Expired';
                return;
            }
            const h = Math.floor(diff / 3600);
            const m = Math.floor((diff % 3600) / 60);
            const s = diff % 60;
            el.textContent = (h > 0 ? h + ':' : '') + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
            if (diff <= 60) el.classList.add('animate-pulse');
            requestAnimationFrame(tick);
        }
        tick();
    });

    @if($asset && $asset->symbol)
    // TradingView widget
    (function() {
        const script = document.createElement('script');
        script.src = 'https://s3.tradingview.com/tv.js';
        script.onload = function() {
            new TradingView.widget({
                "autosize": true,
                "symbol": "{{ $asset->symbol }}",
                "interval": "15",
                "timezone": "Etc/UTC",
                "theme": "dark",
                "style": "1",
                "locale": "en",
                "toolbar_bg": "#0F1115",
                "enable_publishing": false,
                "hide_side_toolbar": true,
                "allow_symbol_change": false,
                "container_id": "tradingview_chart",
                "backgroundColor": "#0F1115",
                "gridColor": "rgba(255,255,255,0.04)"
            });
        };
        document.head.appendChild(script);
    })();
    @endif
</script>
@endsection
