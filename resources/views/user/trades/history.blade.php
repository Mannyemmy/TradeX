@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />

    {{-- Ticker Tape --}}
    @include('user.partials.ticker-tape')

    {{-- Quick Nav --}}
    @include('user.partials.quick-nav')

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold text-content-primary">Trade History</h2>
            <p class="text-sm text-content-secondary mt-1">Review all your open and closed trades</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('user.trades.positions') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-surface-overlay border border-surface-border text-content-secondary hover:text-content-primary text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary">
                <x-icon name="bolt" class="w-4 h-4" />
                <span class="hidden sm:inline">Positions</span>
            </a>
            <a href="{{ route('trade') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-surface-base">
                <x-icon name="chart-bar" class="w-4 h-4" />
                Trade Now
            </a>
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        @include('user.partials.stat-card-compact', ['label' => 'Total Trades', 'value' => $stats['total'], 'icon' => 'chart-bar', 'color' => 'info'])
        @include('user.partials.stat-card-compact', ['label' => 'Open', 'value' => $stats['open_count'], 'icon' => 'folder-open', 'color' => 'primary'])
        @include('user.partials.stat-card-compact', ['label' => 'Closed', 'value' => $stats['closed_count'], 'icon' => 'folder', 'color' => 'warning'])
        @include('user.partials.stat-card-compact', ['label' => 'Wins', 'value' => $stats['wins'], 'icon' => 'arrow-trending-up', 'color' => 'gain'])
        @include('user.partials.stat-card-compact', ['label' => 'Losses', 'value' => $stats['losses'], 'icon' => 'arrow-trending-down', 'color' => 'loss'])
        @include('user.partials.stat-card-compact', [
            'label' => 'Net P/L',
            'value' => \App\Helpers\CurrencyHelper::formatForUser($stats['net_pl']),
            'icon' => 'banknotes',
            'color' => $stats['net_pl'] >= 0 ? 'gain' : 'loss'
        ])
    </div>

    {{-- Filters --}}
    @include('user.trades.partials.trade-filters', [
        'currentType' => request('type', 'all'),
        'currentStatus' => request('status', 'all'),
        'currentDemo' => request('demo', 'all'),
        'currentSearch' => request('search', ''),
        'baseUrl' => route('user.trades.history'),
        'stats' => $stats,
    ])

    {{-- Trade List --}}
    <div class="mt-6 rounded-xl bg-surface-raised border border-surface-border overflow-hidden">
        <div class="px-5 py-3 border-b border-surface-border flex items-center justify-between">
            <div class="flex items-center gap-2">
                <x-icon name="clock" class="w-5 h-5 text-primary" />
                <h3 class="text-sm font-semibold text-content-primary">
                    Trades
                    <span class="text-content-tertiary font-normal">({{ $trades->total() }})</span>
                </h3>
            </div>
            <p class="text-xs text-content-tertiary hidden sm:block">
                Showing {{ $trades->firstItem() ?? 0 }}–{{ $trades->lastItem() ?? 0 }} of {{ $trades->total() }}
            </p>
        </div>

        <div id="trade-history-container">
            @include('user.trades.partials.history_table')
        </div>

        {{-- Pagination --}}
        @if($trades->hasPages())
            <div class="px-5 py-3 border-t border-surface-border">
                {{ $trades->links() }}
            </div>
        @endif
    </div>

@endsection

@section('scripts')
@parent
<script>
    function updateCountdowns() {
        document.querySelectorAll('.countdown-timer').forEach((el) => {
            const expiryTimestamp = parseInt(el.getAttribute('data-expiry')) * 1000;
            const now = Date.now();
            const timeLeft = expiryTimestamp - now;

            if (timeLeft > 0) {
                const hours = Math.floor(timeLeft / 3600000);
                const minutes = Math.floor((timeLeft % 3600000) / 60000);
                const seconds = Math.floor((timeLeft % 60000) / 1000);
                el.textContent = hours > 0 ? `${hours}h ${minutes}m ${seconds}s` : `${minutes}m ${seconds}s`;

                // Pulse when <= 1 minute
                if (timeLeft <= 60000) {
                    el.classList.add('animate-pulse');
                }
            } else {
                el.textContent = "Settling...";
                el.classList.remove('text-warning', 'animate-pulse');
                el.classList.add('text-content-tertiary');

                const row = el.closest('[data-trade-id]');
                const tradeId = row?.getAttribute('data-trade-id');
                if (tradeId && !el.dataset.processing) {
                    el.dataset.processing = '1';
                    processExpiredTrade(tradeId, row);
                }
            }
        });
    }

    function processExpiredTrade(tradeId, row) {
        fetch("{{ route('trades.process') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ trade_id: tradeId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Refresh the page to show updated data
                window.location.reload();
            }
        })
        .catch(err => console.error('Trade processing error:', err));
    }

    setInterval(updateCountdowns, 1000);
    updateCountdowns();
</script>
@endsection
