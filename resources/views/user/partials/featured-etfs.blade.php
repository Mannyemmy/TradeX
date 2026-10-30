{{--
    Featured Stocks Partial
    Displays stock assets with logos, names, prices.
--}}
@php
    // Prefer provider-sourced quotes when duplicate rows exist for a symbol.
    $stockAssets = \App\Models\TradingAsset::where('is_active', true)
        ->where('asset_class', 'stock')->whereNotNull('price')->where('price', '>', 0)
        ->orderBy('id')
        ->get()
        ->groupBy('symbol')
        ->map(fn ($rows) => $rows->sort(function ($left, $right) {
            $sourceOrder = ($right->data_source === 'twelvedata') <=> ($left->data_source === 'twelvedata');
            if ($sourceOrder !== 0) {
                return $sourceOrder;
            }

            $leftUpdatedAt = $left->updated_at ? $left->updated_at->timestamp : 0;
            $rightUpdatedAt = $right->updated_at ? $right->updated_at->timestamp : 0;

            return $rightUpdatedAt <=> $leftUpdatedAt;
        })->first())
        ->values()
        ->take(3);
@endphp

@if ($stockAssets->isNotEmpty())
<div class="bg-surface-raised border border-surface-border rounded-xl overflow-hidden">
    <div class="px-5 py-4 border-b border-surface-border flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-primary"></span>
        <h3 class="text-sm font-semibold text-content-primary">Featured Stocks</h3>
    </div>

    <div id="featured-stock-refresh-status" role="status" aria-live="polite"
         class="hidden px-4 py-2 text-xs text-info"></div>

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
                        <p class="text-sm font-bold text-content-primary">{{ $etf->formatted_price }}</p>
                        <span class="text-[10px] font-medium {{ $isPositive ? 'text-gain' : 'text-loss' }}">
                            {{ $isPositive ? '+' : '' }}{{ round($changePct, 2) }}%
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var status = document.getElementById('featured-stock-refresh-status');
            var refreshUrl = @json(route('user.dashboard.refresh-stock-prices'));
            var csrfToken = @json(csrf_token());
            var symbols = @json($stockAssets->pluck('symbol')->values());
            var refreshInProgress = false;

            function showStatus(message, isError) {
                status.textContent = message;
                status.classList.remove('hidden', 'text-info', 'text-danger', 'bg-info/10', 'bg-danger/10');
                status.classList.add(isError ? 'text-danger' : 'text-info');
                status.classList.add(isError ? 'bg-danger/10' : 'bg-info/10');
            }

            function hideStatus() {
                status.classList.add('hidden');
            }

            function refreshPrices() {
                if (refreshInProgress) {
                    return;
                }

                refreshInProgress = true;

                fetch(refreshUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ symbols: symbols })
                })
                .then(function (response) {
                    return response.json();
                })
                .then(function (result) {
                    if (result.status === 'running') {
                        showStatus('Stock prices are updating...', false);
                        window.setTimeout(refreshPrices, 10000);
                        return;
                    }

                    if (result.status === 'fresh') {
                        hideStatus();
                        return;
                    }

                    if (result.status === 'updated') {
                        showStatus('Featured stock prices updated.', false);
                        window.location.reload();
                        return;
                    }

                    if (result.status === 'cooldown') {
                        hideStatus();
                        return;
                    }

                    throw new Error(result.message || 'Stock prices could not be refreshed.');
                })
                .catch(function (error) {
                    showStatus(error.message || 'Stock prices could not be refreshed.', true);
                })
                .finally(function () {
                    refreshInProgress = false;
                });
            }

            refreshPrices();
            window.setInterval(refreshPrices, 120000);
        });
    </script>
@endif
