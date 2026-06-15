{{--
    Featured Stocks Partial
    Displays stock assets with logos, names, prices.
--}}
@php
    $stockAssets = \App\Models\TradingAsset::where('is_active', true)
        ->where('asset_class', 'stock')->whereNotNull('price')->where('price', '>', 0)
        ->take(3)->get();
@endphp

@if ($stockAssets->isNotEmpty())
<div class="bg-surface-raised border border-surface-border rounded-xl overflow-hidden">
    <div class="px-5 py-4 border-b border-surface-border flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-primary"></span>
        <h3 class="text-sm font-semibold text-content-primary">Featured Stocks</h3>
    </div>

    <div class="p-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            @foreach ($stockAssets as $etf)
                @php
                    $changePct = $etf->price_change_pct_24h ?? 0;
                    $isPositive = $changePct >= 0;
                @endphp
                <div class="flex items-center gap-3 rounded-lg bg-surface-overlay border border-surface-border p-3 hover:border-surface-border-light transition-colors">
                    @if ($etf->logo_url)
                        <img src="{{ $etf->logo_url }}" alt="{{ $etf->symbol }}" class="w-8 h-8 rounded-lg flex-shrink-0 bg-white/5 p-0.5">
                    @else
                        <span class="w-8 h-8 rounded-lg bg-primary/20 text-primary text-[10px] font-bold flex items-center justify-center flex-shrink-0">
                            {{ strtoupper(substr($etf->symbol, 0, 3)) }}
                        </span>
                    @endif

                    <div class="min-w-0 flex-1">
                        <p class="text-xs text-content-primary font-medium truncate">{{ $etf->name }}</p>
                        <span class="text-[10px] text-content-tertiary uppercase">{{ $etf->symbol }}</span>
                    </div>

                    <div class="text-right flex-shrink-0">
                        <p class="text-sm font-bold text-content-primary">${{ $etf->formatted_price }}</p>
                        <span class="text-[10px] font-medium {{ $isPositive ? 'text-gain' : 'text-loss' }}">
                            {{ $isPositive ? '+' : '' }}{{ round($changePct, 2) }}%
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
