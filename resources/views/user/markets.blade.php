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

    @include('user.partials.page-header', ['title' => 'Markets', 'subtitle' => 'Browse and trade available assets'])

    @php
        $currentClass = request('class', 'all');
        $classes = ['all' => 'All', 'crypto' => 'Crypto', 'forex' => 'Forex', 'stock' => 'Stocks', 'etf' => 'ETFs', 'index' => 'Indices'];
    @endphp

    {{-- Section 1: Market Summary --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @include('user.partials.stat-card-compact', [
            'label' => 'Total Assets',
            'value' => $totalAssets,
            'icon' => 'building-library',
        ])
        @include('user.partials.stat-card-compact', [
            'label' => 'Top Gainer',
            'value' => $topGainer ? $topGainer->symbol : '—',
            'subvalue' => $topGainer && $topGainer->price_change_pct_24h ? '+' . number_format($topGainer->price_change_pct_24h, 2) . '%' : '',
            'icon' => 'arrow-trending-up',
            'color' => 'gain',
        ])
        @include('user.partials.stat-card-compact', [
            'label' => 'Top Loser',
            'value' => $topLoser ? $topLoser->symbol : '—',
            'subvalue' => $topLoser && $topLoser->price_change_pct_24h ? number_format($topLoser->price_change_pct_24h, 2) . '%' : '',
            'icon' => 'arrow-trending-down',
            'color' => 'loss',
        ])
        @include('user.partials.stat-card-compact', [
            'label' => 'Active Markets',
            'value' => $classCounts->count(),
            'icon' => 'chart-bar',
        ])
    </div>

    {{-- Section 2: Search + Filter --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        {{-- Asset Class Tabs --}}
        <div class="flex flex-wrap items-center gap-2" role="tablist" aria-label="Filter by asset class">
            @foreach($classes as $key => $label)
                @php
                    $count = $key === 'all' ? $totalAssets : ($classCounts[$key] ?? 0);
                    $isActive = $currentClass === $key;
                @endphp
                <a href="{{ route('user.trades.markets', array_merge(request()->except('class'), $key !== 'all' ? ['class' => $key] : [])) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg transition-colors {{ $isActive ? 'bg-primary text-content-inverse' : 'bg-surface-overlay text-content-secondary hover:text-content-primary hover:bg-surface-overlay/80' }}"
                   role="tab"
                   aria-selected="{{ $isActive ? 'true' : 'false' }}">
                    {{ $label }}
                    <span class="px-1.5 py-0.5 text-[10px] font-bold rounded {{ $isActive ? 'bg-white/20 text-white' : 'bg-surface-base text-content-tertiary' }}">{{ $count }}</span>
                </a>
            @endforeach
        </div>

        {{-- Search --}}
        <form method="GET" action="{{ route('user.trades.markets') }}" class="relative flex-shrink-0">
            @if($currentClass !== 'all')
                <input type="hidden" name="class" value="{{ $currentClass }}">
            @endif
            <x-icon name="magnifying-glass" class="w-4 h-4 text-content-tertiary absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" />
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search by name or symbol..."
                   class="w-full sm:w-64 pl-9 pr-4 py-2 text-sm bg-surface-overlay border border-surface-border rounded-lg text-content-primary placeholder-content-tertiary focus:ring-2 focus:ring-primary focus:border-transparent focus:outline-none"
                   aria-label="Search assets">
        </form>
    </div>

    {{-- Section 3: Asset Grid --}}
    @if($assets->count() > 0)
        {{-- Desktop Table --}}
        <div class="hidden md:block bg-surface-raised border border-surface-border rounded-xl overflow-hidden mb-6">
            <table class="w-full text-sm" role="table">
                <caption class="sr-only">Available trading assets with prices and market data</caption>
                <thead>
                    <tr class="border-b border-surface-border">
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Asset</th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider">Price</th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider">24h Change</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Class</th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-border">
                    @foreach($assets as $asset)
                        <tr class="hover:bg-surface-overlay/50 transition-colors group">
                            {{-- Asset --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($asset->logo_url)
                                        <img src="{{ $asset->logo_url }}" alt="" class="w-8 h-8 rounded-full bg-surface-overlay" loading="lazy">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-surface-overlay flex items-center justify-center text-xs font-bold text-content-secondary">
                                            {{ strtoupper(substr($asset->symbol ?? $asset->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <span class="text-sm font-semibold text-content-primary">{{ $asset->name }}</span>
                                        @if($asset->symbol)
                                            <span class="text-xs text-content-tertiary ml-1">{{ $asset->symbol }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            {{-- Price --}}
                            <td class="px-4 py-3 text-right">
                                <span class="text-sm font-medium text-content-primary">
                                    {{ $asset->price ? \App\Helpers\CurrencyHelper::formatForUser($asset->price) : '—' }}
                                </span>
                            </td>
                            {{-- 24h Change --}}
                            <td class="px-4 py-3 text-right">
                                @if($asset->price_change_pct_24h !== null)
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold {{ $asset->price_change_pct_24h >= 0 ? 'text-gain' : 'text-loss' }}">
                                        <x-icon name="{{ $asset->price_change_pct_24h >= 0 ? 'arrow-trending-up' : 'arrow-trending-down' }}" class="w-3.5 h-3.5" />
                                        {{ $asset->price_change_pct_24h >= 0 ? '+' : '' }}{{ number_format($asset->price_change_pct_24h, 2) }}%
                                    </span>
                                @else
                                    <span class="text-xs text-content-tertiary">—</span>
                                @endif
                            </td>
                            {{-- Class --}}
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 text-[10px] font-medium rounded-full bg-surface-overlay text-content-secondary capitalize">
                                    {{ $asset->asset_class ?? 'unknown' }}
                                </span>
                            </td>
                            {{-- Trade Button --}}
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('trade', ['asset' => $asset->id]) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg bg-primary hover:bg-primary-dark text-content-inverse transition-colors opacity-0 group-hover:opacity-100 focus:opacity-100">
                                    Trade
                                    <x-icon name="arrow-long-right" class="w-3.5 h-3.5" />
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
            @foreach($assets as $asset)
                <a href="{{ route('trade', ['asset' => $asset->id]) }}"
                   class="bg-surface-raised border border-surface-border rounded-xl p-4 hover:bg-surface-overlay/50 transition-colors block">
                    <div class="flex items-center gap-3 mb-3">
                        @if($asset->logo_url)
                            <img src="{{ $asset->logo_url }}" alt="" class="w-10 h-10 rounded-full bg-surface-overlay" loading="lazy">
                        @else
                            <div class="w-10 h-10 rounded-full bg-surface-overlay flex items-center justify-center text-sm font-bold text-content-secondary">
                                {{ strtoupper(substr($asset->symbol ?? $asset->name, 0, 2)) }}
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <span class="text-sm font-semibold text-content-primary block truncate" title="{{ $asset->name }}">{{ $asset->name }}</span>
                            <div class="flex items-center gap-2">
                                @if($asset->symbol)
                                    <span class="text-xs text-content-tertiary">{{ $asset->symbol }}</span>
                                @endif
                                <span class="px-1.5 py-0.5 text-[10px] font-medium rounded bg-surface-overlay text-content-tertiary capitalize">{{ $asset->asset_class ?? 'unknown' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-end justify-between">
                        <span class="text-lg font-bold text-content-primary">
                            {{ $asset->price ? \App\Helpers\CurrencyHelper::formatForUser($asset->price) : '—' }}
                        </span>
                        @if($asset->price_change_pct_24h !== null)
                            <span class="inline-flex items-center gap-0.5 px-2 py-0.5 text-xs font-semibold rounded-full {{ $asset->price_change_pct_24h >= 0 ? 'bg-gain/10 text-gain' : 'bg-loss/10 text-loss' }}">
                                <x-icon name="{{ $asset->price_change_pct_24h >= 0 ? 'arrow-trending-up' : 'arrow-trending-down' }}" class="w-3 h-3" />
                                {{ $asset->price_change_pct_24h >= 0 ? '+' : '' }}{{ number_format($asset->price_change_pct_24h, 2) }}%
                            </span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @else
        @include('user.trades.partials.empty-state', [
            'icon' => 'building-library',
            'title' => 'No market data available',
            'message' => request()->hasAny(['class', 'search'])
                ? 'No assets match your search. Try a different query or browse all assets.'
                : 'No active trading assets found. Contact your administrator.',
            'actionUrl' => request()->hasAny(['class', 'search']) ? route('user.trades.markets') : null,
            'actionLabel' => request()->hasAny(['class', 'search']) ? 'View All Assets' : null,
        ])
    @endif

@endsection
