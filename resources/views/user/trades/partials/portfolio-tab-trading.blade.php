{{-- Portfolio Tab: Trading --}}
@php
    $allocationTotal = $tradeAllocation->sum();
    $allocationColors = [
        'crypto' => 'bg-primary', 'forex' => 'bg-info', 'stock' => 'bg-warning',
        'etf' => 'bg-gain', 'index' => 'bg-loss', 'unknown' => 'bg-content-tertiary',
    ];
@endphp

{{-- Trading Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @include('user.partials.stat-card-compact', ['label' => 'Invested', 'value' => \App\Helpers\CurrencyHelper::formatForUser($totalTradeInvested), 'icon' => 'chart-bar'])
    @include('user.partials.stat-card-compact', ['label' => 'Unrealized P/L', 'value' => \App\Helpers\CurrencyHelper::formatForUser($totalUnrealizedPL), 'icon' => 'arrow-trending-up', 'color' => $totalUnrealizedPL >= 0 ? 'gain' : 'loss'])
    @include('user.partials.stat-card-compact', ['label' => 'Realized P/L', 'value' => \App\Helpers\CurrencyHelper::formatForUser($realizedPL), 'icon' => 'arrow-trending-down', 'color' => $realizedPL >= 0 ? 'gain' : 'loss'])
    @include('user.partials.stat-card-compact', ['label' => 'Open Positions', 'value' => $openTrades->count(), 'icon' => 'briefcase'])
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Positions by Asset --}}
    <div class="lg:col-span-2 space-y-4">
        <h3 class="text-sm font-semibold text-content-secondary uppercase tracking-wider">Open Positions</h3>
        @if($positionsByAsset->count() > 0)
            @foreach($positionsByAsset as $assetId => $trades)
                @php
                    $asset = $trades->first()->tradingAsset;
                    $assetInvested = $trades->sum('amount');
                    $assetPL = $trades->sum('unrealized_pl');
                @endphp
                <div class="bg-surface-raised border border-surface-border rounded-xl overflow-hidden">
                    <div class="px-5 py-3 border-b border-surface-border flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            @if($asset && $asset->logo_url)
                                <img src="{{ $asset->logo_url }}" alt="" class="w-8 h-8 rounded-full bg-surface-overlay" loading="lazy">
                            @else
                                <div class="w-8 h-8 rounded-full bg-surface-overlay flex items-center justify-center text-xs font-bold text-content-secondary">
                                    {{ strtoupper(substr(($asset ? $asset->symbol : '?'), 0, 2)) }}
                                </div>
                            @endif
                            <div>
                                <span class="text-sm font-bold text-content-primary">{{ $asset ? $asset->name : 'Unknown' }}</span>
                                @if($asset && $asset->symbol)
                                    <span class="text-xs text-content-tertiary ml-1">{{ $asset->symbol }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-content-tertiary block">Net P/L</span>
                            @include('user.trades.partials.pnl-display', ['value' => $assetPL])
                        </div>
                    </div>
                    <div class="divide-y divide-surface-border">
                        @foreach($trades as $trade)
                            <a href="{{ route('user.trades.show', $trade->id) }}" class="flex items-center justify-between px-5 py-3 hover:bg-surface-overlay/50 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold uppercase {{ $trade->action === 'buy' ? 'text-gain' : 'text-loss' }}">
                                        <x-icon name="{{ $trade->action === 'buy' ? 'arrow-trending-up' : 'arrow-trending-down' }}" class="w-3.5 h-3.5" />
                                        {{ $trade->action }}
                                    </span>
                                    <span class="text-sm text-content-primary">@money($trade->amount)</span>
                                    <span class="px-1.5 py-0.5 text-[10px] font-medium rounded bg-surface-overlay text-content-secondary">{{ $trade->leverage }}x</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    @include('user.trades.partials.pnl-display', ['value' => $trade->unrealized_pl, 'size' => 'sm'])
                                    <x-icon name="chevron-right" class="w-4 h-4 text-content-tertiary opacity-0 group-hover:opacity-100 transition-opacity" />
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="px-5 py-2.5 bg-surface-overlay/30 text-xs text-content-tertiary">
                        {{ $trades->count() }} position{{ $trades->count() !== 1 ? 's' : '' }} &middot; @money($assetInvested) invested
                    </div>
                </div>
            @endforeach
        @else
            @include('user.trades.partials.empty-state', [
                'icon' => 'chart-bar',
                'title' => 'No open trades',
                'message' => 'You have no open live trades. Start trading to build your portfolio.',
                'actionUrl' => route('trade'),
                'actionLabel' => 'Start Trading',
            ])
        @endif
    </div>

    {{-- Sidebar: Allocation + Recent --}}
    <div class="space-y-6">
        {{-- Asset Allocation --}}
        <div class="bg-surface-raised border border-surface-border rounded-xl p-5">
            <h3 class="text-sm font-semibold text-content-primary mb-4">Asset Allocation</h3>
            @if($allocationTotal > 0)
                <div class="flex rounded-full overflow-hidden h-3 bg-surface-overlay mb-4">
                    @foreach($tradeAllocation as $class => $amount)
                        <div class="{{ $allocationColors[$class] ?? 'bg-content-tertiary' }}"
                             style="width: {{ ($amount / $allocationTotal) * 100 }}%"
                             title="{{ ucfirst($class) }}: {{ \App\Helpers\CurrencyHelper::formatForUser($amount) }}">
                        </div>
                    @endforeach
                </div>
                <div class="space-y-2">
                    @foreach($tradeAllocation as $class => $amount)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full {{ $allocationColors[$class] ?? 'bg-content-tertiary' }}"></div>
                                <span class="text-xs text-content-secondary capitalize">{{ $class }}</span>
                            </div>
                            <span class="text-xs font-medium text-content-primary">@money($amount)</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-content-tertiary text-center py-4">No trades to display</p>
            @endif
        </div>

        {{-- Recent Closed --}}
        <div class="bg-surface-raised border border-surface-border rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-content-primary">Recent Activity</h3>
                <a href="{{ route('user.trades.history') }}" class="text-xs text-primary hover:text-primary transition-colors">View All</a>
            </div>
            @if($recentClosed->count() > 0)
                <div class="space-y-3">
                    @foreach($recentClosed as $trade)
                        @php $asset = $trade->tradingAsset; @endphp
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5 min-w-0">
                                @if($asset && $asset->logo_url)
                                    <img src="{{ $asset->logo_url }}" alt="" class="w-7 h-7 rounded-full bg-surface-overlay shrink-0" loading="lazy">
                                @else
                                    <div class="w-7 h-7 rounded-full bg-surface-overlay flex items-center justify-center text-[10px] font-bold text-content-secondary shrink-0">
                                        {{ strtoupper(substr(($asset ? $asset->symbol : $trade->asset_name) ?? '?', 0, 2)) }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <span class="text-xs font-medium text-content-primary truncate block">{{ $asset ? $asset->name : $trade->asset_name }}</span>
                                    <span class="text-[10px] text-content-tertiary">{{ \Carbon\Carbon::parse($trade->settled_at ?? $trade->updated_at)->diffForHumans() }}</span>
                                </div>
                            </div>
                            @include('user.trades.partials.pnl-display', ['value' => $trade->profit_loss, 'size' => 'sm'])
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-content-tertiary text-center py-4">No closed trades yet</p>
            @endif
        </div>
    </div>
</div>
