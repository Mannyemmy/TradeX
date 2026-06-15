@extends('layouts.dash1')
@section('title', $title)
@section('content')

    {{-- Alerts --}}
    <x-danger-alert />
    <x-success-alert />

    {{-- Ticker + Quick Nav --}}
    @include('user.partials.ticker-tape')
    @include('user.partials.quick-nav')

    @include('user.partials.page-header', ['title' => 'Stock Portfolio', 'subtitle' => 'Your stock share holdings and performance'])

    {{-- Action Links --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('user.stocks.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-primary hover:bg-primary-dark text-content-inverse rounded-lg transition-colors">
            <x-icon name="chart-bar" class="w-4 h-4" /> Browse Stocks
        </a>
        <a href="{{ route('user.stocks.history') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-surface-overlay text-content-secondary hover:text-content-primary hover:bg-surface-overlay/80 rounded-lg transition-colors">
            <x-icon name="clock" class="w-4 h-4" /> Trade History
        </a>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        @include('user.partials.stat-card', [
            'label' => 'Total Invested',
            'value' => \App\Helpers\CurrencyHelper::formatForUser($totalInvested),
            'icon' => 'banknotes',
        ])
        @include('user.partials.stat-card', [
            'label' => 'Current Value',
            'value' => \App\Helpers\CurrencyHelper::formatForUser($totalCurrentValue),
            'icon' => 'chart-bar',
        ])
        @include('user.partials.stat-card', [
            'label' => 'Total P/L',
            'value' => ($totalPnl >= 0 ? '+' : '-') . \App\Helpers\CurrencyHelper::formatForUser(abs($totalPnl)),
            'icon' => 'arrow-trending-up',
            'color' => $totalPnl >= 0 ? 'gain' : 'loss',
        ])
    </div>

    {{-- Positions Table --}}
    @if($positions->count() > 0)
        {{-- Desktop Table --}}
        <div class="bg-surface-raised border border-surface-border rounded-xl overflow-hidden hidden md:block">
            <table class="w-full text-sm">
                <thead class="bg-surface-overlay">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-content-tertiary uppercase tracking-wider">Stock</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-content-tertiary uppercase tracking-wider">Shares</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-content-tertiary uppercase tracking-wider">Avg Cost</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-content-tertiary uppercase tracking-wider">Current Price</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-content-tertiary uppercase tracking-wider">Market Value</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-content-tertiary uppercase tracking-wider">P/L</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-content-tertiary uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-border">
                    @foreach($positions as $pos)
                        <tr class="hover:bg-surface-overlay/50 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if($pos->asset->logo_url)
                                        <img src="{{ $pos->asset->logo_url }}" alt="{{ $pos->asset->symbol }}" class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xs font-bold">{{ substr($pos->asset->symbol, 0, 2) }}</div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-content-primary">{{ $pos->asset->symbol }}</p>
                                        <p class="text-xs text-content-tertiary">{{ $pos->asset->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right text-content-primary">{{ number_format($pos->shares, 4) }}</td>
                            <td class="px-5 py-4 text-right text-content-secondary">@money($pos->avg_buy_price)</td>
                            <td class="px-5 py-4 text-right text-content-primary">@money($pos->asset->price)</td>
                            <td class="px-5 py-4 text-right text-content-primary font-medium">@money($pos->current_value)</td>
                            <td class="px-5 py-4 text-right">
                                <span class="font-medium {{ $pos->unrealized_pnl >= 0 ? 'text-gain' : 'text-loss' }}">
                                    {{ $pos->unrealized_pnl >= 0 ? '+' : '-' }}@money(abs($pos->unrealized_pnl))
                                </span>
                                <span class="block text-xs {{ $pos->unrealized_pnl_percent >= 0 ? 'text-gain' : 'text-loss' }}">
                                    {{ $pos->unrealized_pnl_percent >= 0 ? '+' : '' }}{{ number_format($pos->unrealized_pnl_percent, 2) }}%
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('user.stocks.show', $pos->asset->id) }}" class="text-xs font-medium text-primary hover:text-primary-dark transition-colors">Trade</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="md:hidden space-y-3">
            @foreach($positions as $pos)
                <div class="bg-surface-raised border border-surface-border rounded-xl p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            @if($pos->asset->logo_url)
                                <img src="{{ $pos->asset->logo_url }}" alt="{{ $pos->asset->symbol }}" class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xs font-bold">{{ substr($pos->asset->symbol, 0, 2) }}</div>
                            @endif
                            <div>
                                <p class="text-sm font-semibold text-content-primary">{{ $pos->asset->symbol }}</p>
                                <p class="text-xs text-content-tertiary">{{ $pos->asset->name }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-semibold {{ $pos->unrealized_pnl >= 0 ? 'text-gain' : 'text-loss' }}">
                            {{ $pos->unrealized_pnl >= 0 ? '+' : '-' }}@money(abs($pos->unrealized_pnl))
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div><span class="text-content-tertiary">Shares:</span> <span class="text-content-primary">{{ number_format($pos->shares, 4) }}</span></div>
                        <div><span class="text-content-tertiary">Avg Cost:</span> <span class="text-content-primary">@money($pos->avg_buy_price)</span></div>
                        <div><span class="text-content-tertiary">Price:</span> <span class="text-content-primary">@money($pos->asset->price)</span></div>
                        <div><span class="text-content-tertiary">Value:</span> <span class="text-content-primary">@money($pos->current_value)</span></div>
                    </div>
                    <a href="{{ route('user.stocks.show', $pos->asset->id) }}" class="block text-center text-xs font-medium text-primary mt-3">Trade →</a>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-surface-raised border border-surface-border rounded-xl p-12 text-center">
            <x-icon name="chart-bar" class="w-12 h-12 text-content-tertiary mx-auto mb-3" />
            <h3 class="text-content-primary font-semibold mb-1">No positions yet</h3>
            <p class="text-sm text-content-tertiary mb-4">Start building your portfolio by buying stocks.</p>
            <a href="{{ route('user.stocks.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-primary hover:bg-primary-dark text-content-inverse rounded-lg transition-colors">
                Browse Stocks
            </a>
        </div>
    @endif

@endsection
