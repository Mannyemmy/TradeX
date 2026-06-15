@extends('layouts.dash1')
@section('title', $title)
@section('content')

    {{-- Alerts --}}
    <x-danger-alert />
    <x-success-alert />

    {{-- Ticker + Quick Nav --}}
    @include('user.partials.ticker-tape')
    @include('user.partials.quick-nav')

    @include('user.partials.page-header', ['title' => 'Stock Trade History', 'subtitle' => 'Your buy and sell activity'])

    {{-- Action Links --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('user.stocks.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-surface-overlay text-content-secondary hover:text-content-primary hover:bg-surface-overlay/80 rounded-lg transition-colors">
            <x-icon name="chart-bar" class="w-4 h-4" /> Browse Stocks
        </a>
        <a href="{{ route('user.stocks.portfolio') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-surface-overlay text-content-secondary hover:text-content-primary hover:bg-surface-overlay/80 rounded-lg transition-colors">
            <x-icon name="chart-bar" class="w-4 h-4" /> My Portfolio
        </a>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex items-center gap-1 mb-6">
        <a href="{{ route('user.stocks.history') }}"
           class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $filter === 'all' ? 'bg-primary text-content-inverse' : 'bg-surface-overlay text-content-secondary hover:text-content-primary' }}">
            All
        </a>
        <a href="{{ route('user.stocks.history', ['type' => 'buy']) }}"
           class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $filter === 'buy' ? 'bg-gain text-white' : 'bg-surface-overlay text-content-secondary hover:text-content-primary' }}">
            Buys
        </a>
        <a href="{{ route('user.stocks.history', ['type' => 'sell']) }}"
           class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $filter === 'sell' ? 'bg-loss text-white' : 'bg-surface-overlay text-content-secondary hover:text-content-primary' }}">
            Sells
        </a>
    </div>

    @if($trades->count() > 0)
        {{-- Desktop Table --}}
        <div class="bg-surface-raised border border-surface-border rounded-xl overflow-hidden hidden md:block">
            <table class="w-full text-sm">
                <thead class="bg-surface-overlay">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-content-tertiary uppercase tracking-wider">Stock</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-content-tertiary uppercase tracking-wider">Type</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-content-tertiary uppercase tracking-wider">Shares</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-content-tertiary uppercase tracking-wider">Price/Share</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-content-tertiary uppercase tracking-wider">Total</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-content-tertiary uppercase tracking-wider">Fee</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-content-tertiary uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-border">
                    @foreach($trades as $trade)
                        <tr class="hover:bg-surface-overlay/50 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if($trade->asset && $trade->asset->logo_url)
                                        <img src="{{ $trade->asset->logo_url }}" alt="{{ $trade->asset->symbol }}" class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xs font-bold">{{ $trade->asset ? substr($trade->asset->symbol, 0, 2) : '??' }}</div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-content-primary">{{ $trade->asset->symbol ?? 'N/A' }}</p>
                                        <p class="text-xs text-content-tertiary">{{ $trade->asset->name ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="px-2 py-0.5 text-xs font-medium rounded {{ $trade->type === 'buy' ? 'bg-gain/10 text-gain' : 'bg-loss/10 text-loss' }}">
                                    {{ strtoupper($trade->type) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right text-content-primary">{{ number_format($trade->shares, 4) }}</td>
                            <td class="px-5 py-4 text-right text-content-secondary">@money($trade->price_per_share)</td>
                            <td class="px-5 py-4 text-right text-content-primary font-medium">@money($trade->total_amount)</td>
                            <td class="px-5 py-4 text-right text-content-tertiary">@money($trade->fee_amount)</td>
                            <td class="px-5 py-4 text-right text-content-tertiary">{{ $trade->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="md:hidden space-y-3">
            @foreach($trades as $trade)
                <div class="bg-surface-raised border border-surface-border rounded-xl p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            @if($trade->asset && $trade->asset->logo_url)
                                <img src="{{ $trade->asset->logo_url }}" alt="{{ $trade->asset->symbol }}" class="w-7 h-7 rounded-full object-cover">
                            @endif
                            <span class="text-sm font-semibold text-content-primary">{{ $trade->asset->symbol ?? 'N/A' }}</span>
                        </div>
                        <span class="px-2 py-0.5 text-xs font-medium rounded {{ $trade->type === 'buy' ? 'bg-gain/10 text-gain' : 'bg-loss/10 text-loss' }}">
                            {{ strtoupper($trade->type) }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div><span class="text-content-tertiary">Shares:</span> <span class="text-content-primary">{{ number_format($trade->shares, 4) }}</span></div>
                        <div><span class="text-content-tertiary">Price:</span> <span class="text-content-primary">@money($trade->price_per_share)</span></div>
                        <div><span class="text-content-tertiary">Total:</span> <span class="text-content-primary font-medium">@money($trade->total_amount)</span></div>
                        <div><span class="text-content-tertiary">Date:</span> <span class="text-content-primary">{{ $trade->created_at->format('M d, Y') }}</span></div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $trades->withQueryString()->links() }}
        </div>
    @else
        <div class="bg-surface-raised border border-surface-border rounded-xl p-12 text-center">
            <x-icon name="clock" class="w-12 h-12 text-content-tertiary mx-auto mb-3" />
            <h3 class="text-content-primary font-semibold mb-1">No trades yet</h3>
            <p class="text-sm text-content-tertiary mb-4">Your stock trade history will appear here.</p>
            <a href="{{ route('user.stocks.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-primary hover:bg-primary-dark text-content-inverse rounded-lg transition-colors">
                Browse Stocks
            </a>
        </div>
    @endif

@endsection
