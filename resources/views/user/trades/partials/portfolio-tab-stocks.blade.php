{{-- Portfolio Tab: Stock Shares --}}
<div class="space-y-6">

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @include('user.partials.stat-card', ['label' => 'Total Invested', 'value' => \App\Helpers\CurrencyHelper::formatForUser($totalStockInvested), 'icon' => 'banknotes'])
        @include('user.partials.stat-card', ['label' => 'Current Value', 'value' => \App\Helpers\CurrencyHelper::formatForUser($totalStockValue), 'icon' => 'chart-bar'])
        @php $stockPL = $totalStockValue - $totalStockInvested; @endphp
        @include('user.partials.stat-card', ['label' => 'Unrealized P/L', 'value' => \App\Helpers\CurrencyHelper::formatForUser($stockPL), 'icon' => 'arrow-trending-up'])
    </div>

    {{-- Positions Table --}}
    @if($stockPositions->count() > 0)
        <div class="bg-surface-raised border border-surface-border rounded-xl overflow-hidden">
            <div class="px-5 py-3 border-b border-surface-border flex items-center justify-between">
                <h3 class="text-sm font-semibold text-content-primary">Stock Holdings</h3>
                <a href="{{ route('user.stocks.portfolio') }}" class="text-xs text-primary hover:underline">View Full Portfolio</a>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-surface-overlay">
                    <tr>
                        <th class="px-5 py-2.5 text-left text-xs font-semibold text-content-tertiary uppercase">Stock</th>
                        <th class="px-5 py-2.5 text-right text-xs font-semibold text-content-tertiary uppercase">Shares</th>
                        <th class="px-5 py-2.5 text-right text-xs font-semibold text-content-tertiary uppercase">Avg Cost</th>
                        <th class="px-5 py-2.5 text-right text-xs font-semibold text-content-tertiary uppercase">Price</th>
                        <th class="px-5 py-2.5 text-right text-xs font-semibold text-content-tertiary uppercase">Value</th>
                        <th class="px-5 py-2.5 text-right text-xs font-semibold text-content-tertiary uppercase">P/L</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-border">
                    @foreach($stockPositions as $pos)
                        <tr class="hover:bg-surface-overlay/50 transition-colors">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    @if($pos->asset && $pos->asset->logo_url)
                                        <img src="{{ $pos->asset->logo_url }}" alt="{{ $pos->asset->symbol }}" class="w-6 h-6 rounded-full object-cover">
                                    @endif
                                    <span class="font-medium text-content-primary">{{ $pos->asset->symbol ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-right text-content-primary">{{ number_format($pos->shares, 4) }}</td>
                            <td class="px-5 py-3 text-right text-content-secondary">@money($pos->avg_buy_price)</td>
                            <td class="px-5 py-3 text-right text-content-primary">@money($pos->asset->price ?? 0)</td>
                            <td class="px-5 py-3 text-right text-content-primary font-medium">@money($pos->current_value)</td>
                            <td class="px-5 py-3 text-right font-medium {{ $pos->unrealized_pnl >= 0 ? 'text-gain' : 'text-loss' }}">
                                @money($pos->unrealized_pnl)
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-surface-raised border border-surface-border rounded-xl p-8 text-center">
            <x-icon name="chart-bar" class="w-10 h-10 text-content-tertiary mx-auto mb-2" />
            <p class="text-sm text-content-tertiary">No stock positions yet.</p>
            <a href="{{ route('user.stocks.index') }}" class="text-sm text-primary hover:underline mt-1 inline-block">Browse Stocks</a>
        </div>
    @endif
</div>
