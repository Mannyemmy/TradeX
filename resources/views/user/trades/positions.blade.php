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

    @include('user.partials.page-header', ['title' => 'Open Positions', 'subtitle' => 'Manage your currently active trades'])

    {{-- Section 1: Summary Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @include('user.partials.stat-card-compact', [
            'label' => 'Total Open',
            'value' => $stats['total'],
            'icon' => 'briefcase',
        ])
        @include('user.partials.stat-card-compact', [
            'label' => 'Binary Open',
            'value' => $stats['binary'],
            'icon' => 'bolt',
        ])
        @include('user.partials.stat-card-compact', [
            'label' => 'Spot Open',
            'value' => $stats['spot'],
            'icon' => 'chart-bar',
        ])
        @include('user.partials.stat-card-compact', [
            'label' => 'Capital at Risk',
            'value' => \App\Helpers\CurrencyHelper::formatForUser($stats['capital_at_risk']),
            'icon' => 'exclamation-triangle',
        ])
    </div>

    {{-- Section 2: Filter Tabs --}}
    @php
        $currentType = request('type', 'all');
        $currentDemo = request('demo', 'all');
    @endphp
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div class="flex items-center gap-2" role="group" aria-label="Filter by trade type">
            @foreach(['all' => 'All Positions', 'binary' => 'Binary', 'spot' => 'Spot'] as $key => $label)
                @php
                    $count = $key === 'all' ? $stats['total'] : ($key === 'binary' ? $stats['binary'] : $stats['spot']);
                    $isActive = $currentType === $key;
                @endphp
                <a href="{{ route('user.trades.positions', array_merge(request()->except('type'), $key !== 'all' ? ['type' => $key] : [])) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg transition-colors {{ $isActive ? 'bg-primary text-content-inverse' : 'bg-surface-overlay text-content-secondary hover:text-content-primary hover:bg-surface-overlay/80' }}"
                   aria-pressed="{{ $isActive ? 'true' : 'false' }}">
                    {{ $label }}
                    <span class="px-1.5 py-0.5 text-[10px] font-bold rounded {{ $isActive ? 'bg-white/20 text-white' : 'bg-surface-base text-content-tertiary' }}">{{ $count }}</span>
                </a>
            @endforeach
        </div>
        <div class="flex items-center gap-2" role="group" aria-label="Filter demo or live">
            @foreach(['all' => 'All', 'live' => 'Live', 'demo' => 'Demo'] as $key => $label)
                @php $isActive = $currentDemo === $key; @endphp
                <a href="{{ route('user.trades.positions', array_merge(request()->except('demo'), $key !== 'all' ? ['demo' => $key] : [])) }}"
                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ $isActive ? 'bg-surface-overlay text-content-primary ring-1 ring-primary' : 'bg-surface-overlay text-content-tertiary hover:text-content-secondary' }}"
                   aria-pressed="{{ $isActive ? 'true' : 'false' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    @if($openTrades->count() > 0)

        {{-- Section 3a: Spot Positions --}}
        @if($spotTrades->count() > 0 && ($currentType === 'all' || $currentType === 'spot'))
            <div class="mb-6">
                <h2 class="text-sm font-semibold text-content-secondary uppercase tracking-wider mb-3 flex items-center gap-2">
                    <x-icon name="chart-bar" class="w-4 h-4 text-primary" />
                    Spot Positions
                    <span class="px-1.5 py-0.5 text-[10px] font-bold rounded bg-primary/10 text-primary">{{ $spotTrades->count() }}</span>
                </h2>
                <div class="space-y-3">
                    @foreach($spotTrades as $trade)
                        @php
                            $asset = $trade->tradingAsset;
                            $currentPrice = $asset ? $asset->price : null;
                            $isBuy = $trade->action === 'buy';
                            $priceUp = $currentPrice && $trade->entry_price
                                ? ($isBuy ? $currentPrice >= $trade->entry_price : $currentPrice <= $trade->entry_price)
                                : null;
                        @endphp
                        <article class="bg-surface-raised border border-surface-border rounded-xl overflow-hidden {{ $trade->close_requested_at ? 'border-l-4 border-l-warning' : '' }}"
                                 aria-label="Spot position for {{ $asset ? $asset->name : $trade->asset_name }}">
                            <div class="p-4">
                                {{-- Header: Asset + Direction + Demo --}}
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                                    <div class="flex items-center gap-3">
                                        @if($asset && $asset->logo_url)
                                            <img src="{{ $asset->logo_url }}" alt="" class="w-10 h-10 rounded-full bg-surface-overlay" loading="lazy">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-surface-overlay flex items-center justify-center text-sm font-bold text-content-secondary">
                                                {{ strtoupper(substr(($asset ? $asset->symbol : $trade->asset_name) ?? '?', 0, 2)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-bold text-content-primary">{{ $asset ? $asset->name : $trade->asset_name }}</span>
                                                @if($asset && $asset->symbol)
                                                    <span class="text-xs text-content-tertiary">{{ $asset->symbol }}</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                <span class="inline-flex items-center gap-1 text-xs font-semibold uppercase {{ $isBuy ? 'text-gain' : 'text-loss' }}">
                                                    <x-icon name="{{ $isBuy ? 'arrow-trending-up' : 'arrow-trending-down' }}" class="w-3.5 h-3.5" />
                                                    {{ $trade->action }}
                                                </span>
                                                @if($trade->is_demo)
                                                    <span class="px-1.5 py-0.5 text-[10px] font-semibold rounded bg-warning/10 text-warning">DEMO</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Unrealized P/L --}}
                                    <div class="text-left sm:text-right" aria-live="polite">
                                        <span class="text-xs text-content-tertiary block">Unrealized P/L</span>
                                        <span class="text-lg font-bold {{ $trade->unrealized_pl >= 0 ? 'text-gain' : 'text-loss' }}">
                                            @money($trade->unrealized_pl)
                                        </span>
                                    </div>
                                </div>

                                {{-- Price Row --}}
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                                    <div class="bg-surface-overlay rounded-lg p-2.5">
                                        <span class="text-[10px] text-content-tertiary uppercase tracking-wider block mb-0.5">Entry</span>
                                        <span class="text-sm font-medium text-content-primary">{{ $trade->entry_price ? \App\Helpers\CurrencyHelper::formatForUser($trade->entry_price) : '—' }}</span>
                                    </div>
                                    <div class="bg-surface-overlay rounded-lg p-2.5">
                                        <span class="text-[10px] text-content-tertiary uppercase tracking-wider block mb-0.5">Current</span>
                                        <span class="text-sm font-medium {{ $priceUp === true ? 'text-gain' : ($priceUp === false ? 'text-loss' : 'text-content-primary') }}">
                                            {{ $currentPrice ? \App\Helpers\CurrencyHelper::formatForUser($currentPrice) : '—' }}
                                        </span>
                                    </div>
                                    <div class="bg-surface-overlay rounded-lg p-2.5">
                                        <span class="text-[10px] text-content-tertiary uppercase tracking-wider block mb-0.5">Amount</span>
                                        <span class="text-sm font-medium text-content-primary">@money($trade->amount)</span>
                                    </div>
                                    <div class="bg-surface-overlay rounded-lg p-2.5">
                                        <span class="text-[10px] text-content-tertiary uppercase tracking-wider block mb-0.5">Leverage</span>
                                        <span class="text-sm font-medium text-content-primary">{{ $trade->leverage }}x</span>
                                    </div>
                                </div>

                                {{-- Footer: Time + Actions --}}
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pt-3 border-t border-surface-border">
                                    <span class="text-xs text-content-tertiary">
                                        Opened {{ \Carbon\Carbon::parse($trade->created_at)->diffForHumans() }}
                                        &middot; {{ \Carbon\Carbon::parse($trade->created_at)->diffForHumans(null, true) }} ago
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('user.trades.show', $trade->id) }}" class="text-xs text-primary hover:text-primary-light transition-colors font-medium">
                                            View Details
                                        </a>
                                        @if(!$trade->close_requested_at)
                                            <form method="POST" action="{{ route('trades.requestClose') }}" onsubmit="return confirm('Close this spot position?')">
                                                @csrf
                                                <input type="hidden" name="trade_id" value="{{ $trade->id }}">
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg bg-loss/10 text-loss hover:bg-loss/20 transition-colors">
                                                    <x-icon name="x-circle" class="w-3.5 h-3.5" />
                                                    Request Close
                                                </button>
                                            </form>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg bg-warning/10 text-warning">
                                                <x-icon name="clock" class="w-3.5 h-3.5" />
                                                Close Pending
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Section 3b: Binary Positions --}}
        @if($binaryTrades->count() > 0 && ($currentType === 'all' || $currentType === 'binary'))
            <div class="mb-6">
                <h2 class="text-sm font-semibold text-content-secondary uppercase tracking-wider mb-3 flex items-center gap-2">
                    <x-icon name="bolt" class="w-4 h-4 text-info" />
                    Binary Positions
                    <span class="px-1.5 py-0.5 text-[10px] font-bold rounded bg-info/10 text-info">{{ $binaryTrades->count() }}</span>
                </h2>

                {{-- Desktop Table --}}
                <div class="hidden md:block bg-surface-raised border border-surface-border rounded-xl overflow-hidden">
                    <table class="w-full text-sm" role="table">
                        <caption class="sr-only">Open binary trades with countdown timers</caption>
                        <thead>
                            <tr class="border-b border-surface-border">
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Asset</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Direction</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Leverage</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Entry</th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-content-tertiary uppercase tracking-wider">Time Left</th>
                                <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider">Unrealized P/L</th>
                                <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-border">
                            @foreach($binaryTrades as $trade)
                                <tr class="hover:bg-surface-overlay/50 transition-colors cursor-pointer"
                                    onclick="window.location='{{ route('user.trades.show', $trade->id) }}'"
                                    role="link" tabindex="0"
                                    data-trade-id="{{ $trade->id }}">
                                    <td class="px-4 py-3">
                                        @include('user.trades.partials.asset-cell', ['trade' => $trade])
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold uppercase {{ $trade->action === 'buy' ? 'text-gain' : 'text-loss' }}">
                                            <x-icon name="{{ $trade->action === 'buy' ? 'arrow-trending-up' : 'arrow-trending-down' }}" class="w-3.5 h-3.5" />
                                            {{ $trade->action }}
                                        </span>
                                        @if($trade->is_demo)
                                            <span class="ml-1 px-1 py-0.5 text-[10px] font-semibold rounded bg-warning/10 text-warning">DEMO</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-content-primary font-medium">@money($trade->amount)</td>
                                    <td class="px-4 py-3">
                                        <span class="px-1.5 py-0.5 text-xs font-medium rounded bg-surface-overlay text-content-secondary">{{ $trade->leverage }}x</span>
                                    </td>
                                    <td class="px-4 py-3 text-content-secondary text-xs">{{ $trade->entry_price ? \App\Helpers\CurrencyHelper::formatForUser($trade->entry_price) : '—' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        @if($trade->expires_at)
                                            <span class="countdown-timer text-base font-bold text-warning" data-expiry="{{ \Carbon\Carbon::parse($trade->expires_at)->timestamp }}" aria-live="polite"></span>
                                        @else
                                            <span class="text-content-tertiary">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        @include('user.trades.partials.pnl-display', ['value' => $trade->unrealized_pl])
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('user.trades.show', $trade->id) }}" class="text-xs text-primary hover:text-primary-light font-medium" onclick="event.stopPropagation()">
                                            <x-icon name="eye" class="w-4 h-4" />
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="md:hidden space-y-3">
                    @foreach($binaryTrades as $trade)
                        @include('user.trades.partials.trade-card-mobile', ['trade' => $trade])
                    @endforeach
                </div>
            </div>
        @endif

    @else
        @include('user.trades.partials.empty-state', [
            'icon' => 'briefcase',
            'title' => 'No open positions',
            'message' => 'You don\'t have any active trades right now. Open a position to get started.',
            'actionUrl' => route('trade'),
            'actionLabel' => 'Start Trading',
        ])
    @endif

@endsection

@section('scripts')
<script>
    // Countdown timers for binary trades
    document.querySelectorAll('.countdown-timer').forEach(function(el) {
        const expiry = parseInt(el.dataset.expiry);
        const tradeRow = el.closest('[data-trade-id]');

        function tick() {
            const now = Math.floor(Date.now() / 1000);
            const diff = expiry - now;
            if (diff <= 0) {
                el.textContent = 'Settling...';
                el.classList.add('animate-pulse');
                // Auto-settle
                if (tradeRow) {
                    const tradeId = tradeRow.dataset.tradeId;
                    fetch('{{ route("trades.process") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ trade_id: tradeId })
                    }).then(function() {
                        setTimeout(function() { window.location.reload(); }, 1500);
                    });
                }
                return;
            }

            const h = Math.floor(diff / 3600);
            const m = Math.floor((diff % 3600) / 60);
            const s = diff % 60;
            el.textContent = (h > 0 ? h + ':' : '') + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');

            if (diff <= 60) {
                el.classList.add('animate-pulse', 'text-loss');
            }
            requestAnimationFrame(tick);
        }
        tick();
    });
</script>
@endsection
