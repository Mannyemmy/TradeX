{{--
    Market Overview Partial
    Displays top tradeable assets with logos, prices, and 24h change.
--}}
@php
    $marketAssets = \App\Models\TradingAsset::where('is_active', true)
        ->whereNotNull('price')->where('price', '>', 0)
        ->orderByDesc('market_cap')
        ->take(5)->get();
@endphp

<div class="bg-surface-raised border border-surface-border rounded-xl overflow-hidden">
    <div class="px-5 py-4 border-b border-surface-border flex items-center gap-2">
        @include('components.icons.chart-bar', ['class' => 'w-5 h-5 text-primary'])
        <h3 class="text-sm font-semibold text-content-primary">Market Overview</h3>
    </div>

    <div class="p-4">
        <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-thin">
            @foreach ($marketAssets as $index => $asset)
                @php
                    $changePct = $asset->price_change_pct_24h ?? 0;
                    $isPositive = $changePct >= 0;
                    $isFirst = $index === 0;
                @endphp

                <div class="flex-shrink-0 w-[180px] rounded-lg p-3.5 border transition-colors
                    {{ $isFirst
                        ? 'bg-primary border-primary'
                        : 'bg-surface-overlay border-surface-border hover:border-surface-border-light' }}">

                    <div class="flex items-center gap-2 mb-3">
                        @if ($asset->logo_url)
                            <img src="{{ $asset->logo_url }}" alt="{{ $asset->symbol }}" class="w-7 h-7 rounded-full flex-shrink-0">
                        @else
                            <span class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 text-[10px] font-bold
                                {{ $isFirst ? 'bg-white/20 text-white' : 'bg-primary/20 text-primary' }}">
                                {{ strtoupper(substr($asset->symbol, 0, 2)) }}
                            </span>
                        @endif
                        <span class="text-xs font-semibold truncate {{ $isFirst ? 'text-white' : 'text-content-primary' }}">
                            {{ $asset->symbol }}
                        </span>
                        <span class="ml-auto flex items-center gap-0.5 text-[11px] font-medium
                            {{ $isFirst
                                ? ($isPositive ? 'text-white/80' : 'text-white/80')
                                : ($isPositive ? 'text-gain' : 'text-loss') }}">
                            {{ $isPositive ? '↑' : '↓' }}
                            {{ abs(round($changePct, 2)) }}%
                        </span>
                    </div>

                    <p class="text-lg font-bold {{ $isFirst ? 'text-white' : 'text-content-primary' }}">
                        {{ $asset->formatted_price }}
                    </p>
                </div>
            @endforeach

            @if ($marketAssets->isEmpty())
                <div class="w-full py-6 text-center">
                    <p class="text-xs text-content-tertiary">No market data available</p>
                </div>
            @endif
        </div>
    </div>
</div>
