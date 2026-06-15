{{-- Portfolio Tab: Pre-IPO --}}

{{-- Stats --}}
@php $preIpoPL = $totalPreIpoValue - $totalPreIpoCost; @endphp
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @include('user.partials.stat-card-compact', ['label' => 'Holdings', 'value' => $preIpoHoldings->count(), 'icon' => 'building-office'])
    @include('user.partials.stat-card-compact', ['label' => 'Total Cost', 'value' => \App\Helpers\CurrencyHelper::formatForUser($totalPreIpoCost), 'icon' => 'chart-bar', 'color' => 'info'])
    @include('user.partials.stat-card-compact', ['label' => 'Current Value', 'value' => \App\Helpers\CurrencyHelper::formatForUser($totalPreIpoValue), 'icon' => 'banknotes'])
    @include('user.partials.stat-card-compact', ['label' => 'Unrealized P/L', 'value' => \App\Helpers\CurrencyHelper::formatForUser($preIpoPL), 'icon' => 'arrow-trending-up', 'color' => $preIpoPL >= 0 ? 'gain' : 'loss'])
</div>

@if($preIpoHoldings->count() > 0)
    {{-- Desktop Table --}}
    <div class="hidden md:block bg-surface-raised border border-surface-border rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-surface-border">
                    <th class="px-4 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Company</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider">Shares</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider">Avg Price</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider">Current Price</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider">Total Cost</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider">Current Value</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider">P/L</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-border">
                @foreach($preIpoHoldings as $holding)
                    <tr class="hover:bg-surface-overlay/50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if($holding->company && $holding->company->logo)
                                    <img src="{{ asset('storage/app/public/' . $holding->company->logo) }}" class="w-8 h-8 rounded-full bg-surface-overlay" alt="">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-surface-overlay flex items-center justify-center text-xs font-bold text-content-secondary">
                                        {{ strtoupper(substr($holding->company->symbol ?? $holding->company->name ?? '?', 0, 2)) }}
                                    </div>
                                @endif
                                <div>
                                    <span class="text-sm font-semibold text-content-primary">{{ $holding->company->name ?? 'Unknown' }}</span>
                                    @if($holding->company && $holding->company->symbol)
                                        <span class="text-xs text-content-tertiary ml-1">{{ $holding->company->symbol }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right text-sm font-medium text-content-primary">{{ number_format($holding->shares) }}</td>
                        <td class="px-4 py-3 text-right text-sm text-content-secondary">@money($holding->purchase_price)</td>
                        <td class="px-4 py-3 text-right text-sm text-content-primary">@money($holding->company->current_price ?? 0)</td>
                        <td class="px-4 py-3 text-right text-sm text-content-secondary">@money($holding->total_cost)</td>
                        <td class="px-4 py-3 text-right text-sm font-medium text-content-primary">@money($holding->current_value ?? 0)</td>
                        <td class="px-4 py-3 text-right">
                            @php $pnl = $holding->unrealized_pnl ?? 0; @endphp
                            <span class="text-sm font-medium {{ $pnl >= 0 ? 'text-gain' : 'text-loss' }}">
                                @money($pnl)
                            </span>
                            @if($holding->unrealized_pnl_percent)
                                <span class="text-[10px] {{ $pnl >= 0 ? 'text-gain' : 'text-loss' }} block">
                                    ({{ $pnl >= 0 ? '+' : '' }}{{ number_format($holding->unrealized_pnl_percent, 2) }}%)
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden space-y-3">
        @foreach($preIpoHoldings as $holding)
            @php $pnl = $holding->unrealized_pnl ?? 0; @endphp
            <div class="bg-surface-raised border border-surface-border rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        @if($holding->company && $holding->company->logo)
                            <img src="{{ asset('storage/app/public/' . $holding->company->logo) }}" class="w-8 h-8 rounded-full bg-surface-overlay" alt="">
                        @else
                            <div class="w-8 h-8 rounded-full bg-surface-overlay flex items-center justify-center text-xs font-bold text-content-secondary">
                                {{ strtoupper(substr($holding->company->symbol ?? '?', 0, 2)) }}
                            </div>
                        @endif
                        <span class="text-sm font-semibold text-content-primary">{{ $holding->company->name ?? 'Unknown' }}</span>
                    </div>
                    <span class="text-sm font-medium {{ $pnl >= 0 ? 'text-gain' : 'text-loss' }}">
                        @money($pnl)
                    </span>
                </div>
                <div class="grid grid-cols-3 gap-3 text-xs">
                    <div>
                        <span class="text-content-tertiary block">Shares</span>
                        <span class="text-content-primary font-medium">{{ number_format($holding->shares) }}</span>
                    </div>
                    <div>
                        <span class="text-content-tertiary block">Cost</span>
                        <span class="text-content-secondary">@money($holding->total_cost)</span>
                    </div>
                    <div>
                        <span class="text-content-tertiary block">Value</span>
                        <span class="text-content-primary font-medium">@money($holding->current_value ?? 0)</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    @include('user.trades.partials.empty-state', [
        'icon' => 'building-office',
        'title' => 'No Pre-IPO holdings',
        'message' => 'You haven\'t purchased any Pre-IPO shares yet.',
        'actionUrl' => route('user.pre-ipo.index'),
        'actionLabel' => 'Browse Pre-IPO',
    ])
@endif
