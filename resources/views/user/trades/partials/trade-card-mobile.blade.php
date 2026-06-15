{{--
    Trade Card — Mobile-responsive card for a single trade
    Usage: @include('user.trades.partials.trade-card-mobile', ['trade' => $trade])
    Expects $trade with optional tradingAsset relationship loaded.
--}}
@php
    $asset = $trade->tradingAsset ?? null;
    $tradeType = $trade->trade_type ?? 'binary';
    $isSpot = $tradeType === 'spot';
    $isClosed = $trade->status === 'closed';
    $detailUrl = route('user.trades.show', $trade->id);
@endphp

<a href="{{ $detailUrl }}" class="block bg-surface-raised border border-surface-border rounded-xl overflow-hidden hover:border-primary/30 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-surface-base" aria-label="Trade {{ $trade->asset_name }} — {{ ucfirst($trade->action) }} — {{ ucfirst($trade->status) }}">
    {{-- Card Header --}}
    <div class="px-4 py-3 flex items-center justify-between border-b border-surface-border">
        <div class="flex items-center gap-2.5 min-w-0">
            @include('user.trades.partials.asset-cell', ['trade' => $trade])
        </div>
        <div class="flex items-center gap-1.5 shrink-0">
            <span class="px-1.5 py-0.5 text-[10px] font-semibold rounded {{ $tradeType === 'binary' ? 'bg-info/10 text-info' : 'bg-primary/10 text-primary' }}">
                {{ ucfirst($tradeType) }}
            </span>
            @if($trade->is_demo)
                <span class="px-1.5 py-0.5 text-[10px] font-semibold rounded bg-warning/10 text-warning">DEMO</span>
            @endif
            <span class="px-1.5 py-0.5 text-[10px] font-semibold rounded uppercase {{ $trade->action === 'buy' ? 'bg-gain/10 text-gain' : 'bg-loss/10 text-loss' }}">
                {{ $trade->action }}
            </span>
        </div>
    </div>

    {{-- Card Body --}}
    <div class="px-4 py-3">
        <div class="grid grid-cols-3 gap-3">
            {{-- Amount --}}
            <div>
                <p class="text-[10px] text-content-tertiary uppercase tracking-wider mb-0.5">Amount</p>
                <p class="text-sm font-semibold text-content-primary">@money($trade->amount)</p>
            </div>
            {{-- Leverage --}}
            <div>
                <p class="text-[10px] text-content-tertiary uppercase tracking-wider mb-0.5">Leverage</p>
                <p class="text-sm font-semibold text-content-primary">{{ $trade->leverage }}x</p>
            </div>
            {{-- Entry Price --}}
            <div>
                <p class="text-[10px] text-content-tertiary uppercase tracking-wider mb-0.5">Entry</p>
                <p class="text-sm text-content-secondary">
                    @if($trade->entry_price) @money($trade->entry_price) @else — @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Card Footer --}}
    <div class="px-4 py-2.5 bg-surface-overlay/30 border-t border-surface-border flex items-center justify-between">
        <div class="flex items-center gap-2">
            {{-- Status --}}
            <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $isClosed ? 'bg-surface-overlay text-content-tertiary' : 'bg-info/10 text-info' }}">
                {{ ucfirst($trade->status) }}
            </span>
            {{-- Result --}}
            @if($trade->result === 'WIN')
                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-gain/10 text-gain">WIN</span>
            @elseif($trade->result === 'LOSS')
                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-loss/10 text-loss">LOSS</span>
            @else
                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-warning/10 text-warning">PENDING</span>
            @endif
        </div>
        {{-- P/L --}}
        @include('user.trades.partials.pnl-display', ['value' => $trade->profit_loss, 'size' => 'base'])
    </div>

    {{-- Time Row --}}
    <div class="px-4 py-2 border-t border-surface-border/50 flex items-center justify-between text-xs text-content-tertiary">
        <span title="{{ \Carbon\Carbon::parse($trade->created_at)->format('Y-m-d H:i:s') }}">{{ \Carbon\Carbon::parse($trade->created_at)->diffForHumans() }}</span>
        @if($isClosed && $trade->settled_by)
            <span>Settled by {{ ucfirst($trade->settled_by) }}</span>
        @elseif($isSpot && !$isClosed)
            @if($trade->close_requested_at)
                <span class="text-warning font-medium">Close Pending</span>
            @else
                <span class="italic">Open — No expiry</span>
            @endif
        @elseif(!$isClosed && $trade->expires_at)
            <span class="countdown-timer text-warning font-medium" data-expiry="{{ \Carbon\Carbon::parse($trade->expires_at)->timestamp }}"></span>
        @endif
    </div>
</a>
