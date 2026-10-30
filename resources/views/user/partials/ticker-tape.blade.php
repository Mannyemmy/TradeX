@php
    $tickerSymbols = ['BTC', 'ETH', 'SPX', 'IXIC', 'EUR/USD', 'FTSE'];
    $tickerAssetsBySymbol = \App\Models\TradingAsset::where('is_active', true)
        ->whereIn('symbol', $tickerSymbols)
        ->whereNotNull('price')
        ->where('price', '>', 0)
        ->orderBy('id')
        ->get()
        ->groupBy('symbol')
        ->map(function ($rows) {
            $preferredSource = $rows->first()->asset_class === 'crypto' ? 'coingecko' : 'twelvedata';
            $preferredRows = $rows->where('data_source', $preferredSource);
            $rows = $preferredRows->isNotEmpty() ? $preferredRows : $rows;

            return $rows->sort(function ($left, $right) {
                $leftUpdatedAt = $left->updated_at ? $left->updated_at->timestamp : 0;
                $rightUpdatedAt = $right->updated_at ? $right->updated_at->timestamp : 0;

                return $rightUpdatedAt <=> $leftUpdatedAt;
            })->first();
        });
    $tickerAssets = collect($tickerSymbols)
        ->map(function ($symbol) use ($tickerAssetsBySymbol) {
            return $tickerAssetsBySymbol->get($symbol);
        })
        ->filter();
@endphp

@if ($tickerAssets->isNotEmpty())
    <div class="w-full overflow-hidden rounded-lg border border-surface-border bg-surface-raised mb-6" aria-label="Market prices">
        <div class="market-ticker-track flex w-max items-center" aria-live="off">
            @foreach ([1, 2] as $copy)
                <div class="market-ticker-group flex shrink-0 items-center" @if ($copy === 2) aria-hidden="true" @endif>
                    @foreach ($tickerAssets as $asset)
                        @php
                            $changePct = $asset->price_change_pct_24h ?? 0;
                            $isPositive = $changePct >= 0;
                        @endphp
                        <div class="flex shrink-0 items-center gap-2 border-r border-surface-border px-4 py-2.5">
                            @if ($asset->asset_class === 'stock')
                                <x-stock-logo :asset="$asset" size="h-5 w-5" text-size="text-[8px]" />
                            @elseif ($asset->logo_url)
                                <img src="{{ $asset->logo_url }}" alt="" class="h-5 w-5 rounded-full object-cover">
                            @else
                                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-primary/10 text-[9px] font-bold text-primary">
                                    {{ strtoupper(substr($asset->symbol, 0, 2)) }}
                                </span>
                            @endif
                            <span class="text-xs font-semibold text-content-primary">{{ $asset->name }}</span>
                            <span class="text-xs text-content-secondary">{{ $asset->formatted_price }}</span>
                            <span class="text-xs {{ $isPositive ? 'text-gain' : 'text-loss' }}">
                                {{ $isPositive ? '+' : '' }}{{ number_format($changePct, 2) }}%
                            </span>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    <style>
        .market-ticker-track {
            animation: market-ticker-scroll 45s linear infinite;
        }

        .market-ticker-group {
            min-width: max-content;
        }

        .market-ticker-track:hover {
            animation-play-state: paused;
        }

        @keyframes market-ticker-scroll {
            to { transform: translateX(-50%); }
        }

        @media (prefers-reduced-motion: reduce) {
            .market-ticker-track { animation: none; }
        }
    </style>
@endif
