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

    @include('user.partials.page-header', ['title' => 'Trade Analytics', 'subtitle' => 'Performance breakdown and insights across your trading history'])

    {{-- Section 1: Performance Stats Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
        @include('user.partials.stat-card-compact', ['label' => 'Total Trades', 'value' => $totalCount, 'icon' => 'chart-bar'])
        @include('user.partials.stat-card-compact', ['label' => 'Win Rate', 'value' => $winRate . '%', 'icon' => 'trophy', 'color' => 'gain'])
        @include('user.partials.stat-card-compact', ['label' => 'Total Profit', 'value' => \App\Helpers\CurrencyHelper::formatForUser($totalProfit), 'icon' => 'arrow-trending-up', 'color' => 'gain'])
        @include('user.partials.stat-card-compact', ['label' => 'Total Loss', 'value' => \App\Helpers\CurrencyHelper::formatForUser(-$totalLoss), 'icon' => 'arrow-trending-down', 'color' => 'loss'])
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
        @include('user.partials.stat-card-compact', [
            'label' => 'Net P/L',
            'value' => \App\Helpers\CurrencyHelper::formatForUser($netPL),
            'icon' => 'banknotes',
            'color' => $netPL >= 0 ? 'gain' : 'loss',
        ])
        @include('user.partials.stat-card-compact', ['label' => 'Avg Trade Size', 'value' => \App\Helpers\CurrencyHelper::formatForUser($avgTradeSize), 'icon' => 'calculator'])
        @include('user.partials.stat-card-compact', [
            'label' => 'Best Trade',
            'value' => $bestTrade ? \App\Helpers\CurrencyHelper::formatForUser($bestTrade->profit_loss) : '—',
            'icon' => 'trophy',
            'color' => 'gain',
        ])
        @include('user.partials.stat-card-compact', [
            'label' => 'Worst Trade',
            'value' => $worstTrade ? \App\Helpers\CurrencyHelper::formatForUser($worstTrade->profit_loss) : '—',
            'icon' => 'exclamation-triangle',
            'color' => 'loss',
        ])
    </div>

    {{-- Section 2: P/L Timeline Chart --}}
    @if(count($timelineData) > 1)
        <div class="bg-surface-raised border border-surface-border rounded-xl p-5 mb-6">
            <h2 class="text-sm font-semibold text-content-primary mb-4">Cumulative P/L Over Time</h2>
            <div style="height: 250px;" aria-label="Profit and loss chart over time">
                <canvas id="plChart"></canvas>
            </div>
            {{-- Accessible data table alternative --}}
            <details class="mt-2">
                <summary class="text-xs text-content-tertiary cursor-pointer hover:text-content-secondary">View data table</summary>
                <table class="w-full text-xs mt-2 sr-only focus-within:not-sr-only">
                    <thead>
                        <tr><th class="text-left">Date</th><th class="text-right">Cumulative P/L</th></tr>
                    </thead>
                    <tbody>
                        @foreach($timelineData as $dp)
                            <tr>
                                <td>{{ $dp['date'] }}</td>
                                <td class="text-right">@money($dp['cumulative_pl'])</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </details>
        </div>
    @endif

    {{-- Section 3: Breakdown Cards --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- By Trade Type --}}
        <div class="bg-surface-raised border border-surface-border rounded-xl p-5">
            <h2 class="text-sm font-semibold text-content-primary mb-4">By Trade Type</h2>
            <div class="grid grid-cols-2 gap-4">
                @foreach(['binary' => 'Binary', 'spot' => 'Spot'] as $type => $label)
                    @php $data = $byType[$type]; @endphp
                    <div class="bg-surface-overlay rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <x-icon name="{{ $type === 'binary' ? 'bolt' : 'chart-bar' }}" class="w-5 h-5 {{ $type === 'binary' ? 'text-info' : 'text-primary' }}" />
                            <span class="text-sm font-semibold text-content-primary">{{ $label }}</span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-content-tertiary">Trades</span>
                                <span class="text-sm font-medium text-content-primary">{{ $data['count'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-content-tertiary">Win Rate</span>
                                <span class="text-sm font-medium {{ $data['win_rate'] >= 50 ? 'text-gain' : 'text-loss' }}">{{ $data['win_rate'] }}%</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-content-tertiary">P/L</span>
                                @include('user.trades.partials.pnl-display', ['value' => $data['pl'], 'size' => 'sm'])
                            </div>
                            {{-- Win rate bar --}}
                            <div class="h-1.5 rounded-full bg-surface-base overflow-hidden">
                                <div class="h-full rounded-full {{ $data['win_rate'] >= 50 ? 'bg-gain' : 'bg-loss' }}" style="width: {{ $data['win_rate'] }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- By Direction --}}
        <div class="bg-surface-raised border border-surface-border rounded-xl p-5">
            <h2 class="text-sm font-semibold text-content-primary mb-4">By Direction</h2>
            <div class="grid grid-cols-2 gap-4">
                @foreach(['buy' => 'Buy (Long)', 'sell' => 'Sell (Short)'] as $dir => $label)
                    @php $data = $byDirection[$dir]; @endphp
                    <div class="bg-surface-overlay rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <x-icon name="{{ $dir === 'buy' ? 'arrow-trending-up' : 'arrow-trending-down' }}" class="w-5 h-5 {{ $dir === 'buy' ? 'text-gain' : 'text-loss' }}" />
                            <span class="text-sm font-semibold text-content-primary">{{ $label }}</span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-content-tertiary">Trades</span>
                                <span class="text-sm font-medium text-content-primary">{{ $data['count'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-content-tertiary">Win Rate</span>
                                <span class="text-sm font-medium {{ $data['win_rate'] >= 50 ? 'text-gain' : 'text-loss' }}">{{ $data['win_rate'] }}%</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-content-tertiary">P/L</span>
                                @include('user.trades.partials.pnl-display', ['value' => $data['pl'], 'size' => 'sm'])
                            </div>
                            <div class="h-1.5 rounded-full bg-surface-base overflow-hidden">
                                <div class="h-full rounded-full {{ $data['win_rate'] >= 50 ? 'bg-gain' : 'bg-loss' }}" style="width: {{ $data['win_rate'] }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- By Asset Class --}}
    <div class="bg-surface-raised border border-surface-border rounded-xl p-5 mb-6">
        <h2 class="text-sm font-semibold text-content-primary mb-4">By Asset Class</h2>
        @if($byClass->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm" role="table">
                    <caption class="sr-only">Trading performance broken down by asset class</caption>
                    <thead>
                        <tr class="border-b border-surface-border">
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Class</th>
                            <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider">Trades</th>
                            <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider">Win Rate</th>
                            <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider">P/L</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-border">
                        @foreach($byClass as $class => $data)
                            <tr class="hover:bg-surface-overlay/50 transition-colors">
                                <td class="px-4 py-2.5">
                                    <span class="text-sm font-medium text-content-primary capitalize">{{ $class }}</span>
                                </td>
                                <td class="px-4 py-2.5 text-right text-content-secondary">{{ $data['count'] }}</td>
                                <td class="px-4 py-2.5 text-right">
                                    <span class="inline-flex items-center gap-1.5">
                                        <span class="text-sm font-medium {{ $data['win_rate'] >= 50 ? 'text-gain' : 'text-loss' }}">{{ $data['win_rate'] }}%</span>
                                        <span class="inline-block w-12 h-1.5 rounded-full bg-surface-overlay overflow-hidden">
                                            <span class="block h-full rounded-full {{ $data['win_rate'] >= 50 ? 'bg-gain' : 'bg-loss' }}" style="width: {{ $data['win_rate'] }}%"></span>
                                        </span>
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-right">
                                    @include('user.trades.partials.pnl-display', ['value' => $data['pl'], 'size' => 'sm'])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-xs text-content-tertiary text-center py-4">No closed trades to analyze</p>
        @endif
    </div>

    {{-- Section 4: Recent Trades --}}
    <div class="bg-surface-raised border border-surface-border rounded-xl p-5 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-content-primary">Recent Trades</h2>
            <a href="{{ route('user.trades.history') }}" class="text-xs text-primary hover:text-primary-light transition-colors font-medium">View Full History</a>
        </div>

        @if($recentTrades->count() > 0)
            <div class="space-y-2">
                @foreach($recentTrades as $trade)
                    @php $asset = $trade->tradingAsset; @endphp
                    <a href="{{ route('user.trades.show', $trade->id) }}"
                       class="flex items-center justify-between p-2.5 rounded-lg hover:bg-surface-overlay/50 transition-colors group">
                        <div class="flex items-center gap-3 min-w-0">
                            @if($asset && $asset->logo_url)
                                <img src="{{ $asset->logo_url }}" alt="" class="w-7 h-7 rounded-full bg-surface-overlay flex-shrink-0" loading="lazy">
                            @else
                                <div class="w-7 h-7 rounded-full bg-surface-overlay flex items-center justify-center text-[10px] font-bold text-content-secondary flex-shrink-0">
                                    {{ strtoupper(substr(($asset ? $asset->symbol : $trade->asset_name) ?? '?', 0, 2)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-content-primary truncate" title="{{ $asset ? $asset->name : $trade->asset_name }}">
                                        {{ $asset ? $asset->name : $trade->asset_name }}
                                    </span>
                                    <span class="inline-flex items-center gap-0.5 text-[10px] font-semibold uppercase {{ $trade->action === 'buy' ? 'text-gain' : 'text-loss' }}">
                                        <x-icon name="{{ $trade->action === 'buy' ? 'arrow-trending-up' : 'arrow-trending-down' }}" class="w-3 h-3" />
                                        {{ $trade->action }}
                                    </span>
                                </div>
                                <span class="text-[10px] text-content-tertiary">
                                    {{ \Carbon\Carbon::parse($trade->settled_at ?? $trade->updated_at)->diffForHumans() }}
                                    &middot; @money($trade->amount)
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            @if($trade->result === 'WIN')
                                <span class="inline-flex items-center gap-0.5 px-2 py-0.5 text-[10px] font-semibold rounded-full bg-gain/10 text-gain">
                                    <x-icon name="arrow-trending-up" class="w-3 h-3" /> WIN
                                </span>
                            @elseif($trade->result === 'LOSS')
                                <span class="inline-flex items-center gap-0.5 px-2 py-0.5 text-[10px] font-semibold rounded-full bg-loss/10 text-loss">
                                    <x-icon name="arrow-trending-down" class="w-3 h-3" /> LOSS
                                </span>
                            @endif
                            @include('user.trades.partials.pnl-display', ['value' => $trade->profit_loss, 'size' => 'sm'])
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-xs text-content-tertiary text-center py-4">No closed trades yet</p>
        @endif
    </div>

@endsection

@section('scripts')
@if(count($timelineData) > 1)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
    (function() {
        const data = @json($timelineData);
        const ctx = document.getElementById('plChart').getContext('2d');

        const gradient = ctx.createLinearGradient(0, 0, 0, 250);
        const lastValue = data[data.length - 1].cumulative_pl;
        const color = lastValue >= 0 ? '16, 185, 129' : '239, 68, 68'; // gain/loss RGB
        gradient.addColorStop(0, 'rgba(' + color + ', 0.15)');
        gradient.addColorStop(1, 'rgba(' + color + ', 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(d => d.date),
                datasets: [{
                    label: 'Cumulative P/L',
                    data: data.map(d => d.cumulative_pl),
                    borderColor: 'rgb(' + color + ')',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    pointHoverBackgroundColor: 'rgb(' + color + ')',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1C1F26',
                        titleColor: '#9CA3AF',
                        bodyColor: '#F9FAFB',
                        borderColor: '#374151',
                        borderWidth: 1,
                        padding: 10,
                        callbacks: {
                            label: function(ctx) {
                                const v = ctx.parsed.y;
                                return (v >= 0 ? '+' : '-') + '@userCurrency' + Math.abs(v).toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255,255,255,0.04)' },
                        ticks: { color: '#6B7280', maxRotation: 0, maxTicksLimit: 8 },
                        border: { display: false }
                    },
                    y: {
                        grid: { color: 'rgba(255,255,255,0.04)' },
                        ticks: {
                            color: '#6B7280',
                            callback: function(v) { return (v >= 0 ? '+' : '-') + '@userCurrency' + Math.abs(v).toFixed(0); }
                        },
                        border: { display: false }
                    }
                }
            }
        });
    })();
</script>
@endif
@endsection
