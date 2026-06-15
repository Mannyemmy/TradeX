@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />
    <x-error-alert />

    @include('user.partials.ticker-tape')
    @include('user.partials.quick-nav')

    {{-- Back Link --}}
    <div class="mb-4">
        <a href="{{ route('botTrading') }}" class="inline-flex items-center gap-1.5 text-sm text-content-tertiary hover:text-primary transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Back to Bot Trading
        </a>
    </div>

    {{-- Subscription Header --}}
    <div class="bg-surface-raised border border-surface-border rounded-xl p-6">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                    <x-icon name="cpu-chip" class="w-6 h-6 text-primary" />
                </div>
                <div>
                    <h2 class="text-lg font-bold text-content-primary">{{ $subscription->tradingBot->name ?? 'Deleted Bot' }}</h2>
                    <p class="text-xs text-content-tertiary">Subscription #{{ $subscription->id }}</p>
                </div>
            </div>
            @php
                $statusClass = match($subscription->status) {
                    'active' => 'bg-gain/10 text-gain',
                    'stopped' => 'bg-loss/10 text-loss',
                    'completed' => 'bg-primary-subtle text-primary',
                    'settled' => 'bg-warning/10 text-warning',
                    default => 'bg-surface-overlay text-content-tertiary',
                };
            @endphp
            <span class="{{ $statusClass }} text-xs font-medium px-3 py-1.5 rounded-full">{{ ucfirst($subscription->status) }}</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 pt-4 border-t border-surface-border">
            <div>
                <p class="text-xs text-content-tertiary">Invested</p>
                <p class="text-lg font-bold text-content-primary">@money($subscription->invested_amount)</p>
            </div>
            <div>
                <p class="text-xs text-content-tertiary">Accumulated Profit</p>
                <p class="text-lg font-bold {{ $subscription->accumulated_profit >= 0 ? 'text-gain' : 'text-loss' }}">@money($subscription->accumulated_profit)</p>
            </div>
            <div>
                <p class="text-xs text-content-tertiary">Daily ROI</p>
                <p class="text-lg font-bold text-gain">{{ number_format($subscription->daily_roi_snapshot, 2) }}%</p>
            </div>
            <div>
                <p class="text-xs text-content-tertiary">Started</p>
                <p class="text-sm font-medium text-content-primary">{{ $subscription->started_at->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-content-tertiary">Expires</p>
                <p class="text-sm font-medium text-content-primary">{{ $subscription->expires_at ? $subscription->expires_at->format('M d, Y') : '—' }}</p>
            </div>
        </div>

        @if($subscription->status === 'active')
            <div class="mt-4 pt-4 border-t border-surface-border flex items-center justify-between">
                <div>
                    <p class="text-xs text-content-tertiary">Total Payout if Stopped Now</p>
                    <p class="text-xl font-bold text-primary">@money($subscription->totalPayout())</p>
                </div>
                <form action="{{ route('botTrading.stop', $subscription->id) }}" method="POST" onsubmit="return confirm('Stop this subscription and withdraw your funds?')">
                    @csrf
                    <button type="submit" class="bg-loss/10 text-loss hover:bg-loss hover:text-white rounded-lg px-5 py-2.5 text-sm font-medium transition-all">Stop & Withdraw</button>
                </form>
            </div>
        @endif
    </div>

    {{-- Trades Table --}}
    <div class="bg-surface-raised border border-surface-border rounded-xl mt-6">
        <div class="px-5 py-4 border-b border-surface-border flex items-center justify-between">
            <h3 class="text-sm font-semibold text-content-primary">Simulated Trades</h3>
            <span class="text-xs text-content-tertiary">{{ $trades->total() }} total</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-surface-overlay/50">
                        <th class="text-left text-xs font-medium text-content-tertiary px-5 py-2.5">Asset</th>
                        <th class="text-left text-xs font-medium text-content-tertiary px-5 py-2.5">Action</th>
                        <th class="text-left text-xs font-medium text-content-tertiary px-5 py-2.5">Amount</th>
                        <th class="text-left text-xs font-medium text-content-tertiary px-5 py-2.5">Entry</th>
                        <th class="text-left text-xs font-medium text-content-tertiary px-5 py-2.5">Exit</th>
                        <th class="text-left text-xs font-medium text-content-tertiary px-5 py-2.5">P/L</th>
                        <th class="text-left text-xs font-medium text-content-tertiary px-5 py-2.5">Result</th>
                        <th class="text-left text-xs font-medium text-content-tertiary px-5 py-2.5">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trades as $trade)
                        <tr class="border-b border-surface-border last:border-b-0">
                            <td class="px-5 py-3">
                                <p class="font-medium text-content-primary">{{ $trade->asset_name }}</p>
                                <p class="text-[10px] text-content-tertiary">{{ $trade->asset_class }}</p>
                            </td>
                            <td class="px-5 py-3">
                                <span class="{{ $trade->action === 'buy' ? 'text-gain' : 'text-loss' }} text-xs font-semibold uppercase">{{ $trade->action }}</span>
                            </td>
                            <td class="px-5 py-3 text-content-primary">@money($trade->amount)</td>
                            <td class="px-5 py-3 text-content-primary">{{ number_format($trade->entry_price, 4) }}</td>
                            <td class="px-5 py-3 text-content-primary">{{ number_format($trade->exit_price, 4) }}</td>
                            <td class="px-5 py-3 font-medium {{ $trade->profit_loss >= 0 ? 'text-gain' : 'text-loss' }}">@money($trade->profit_loss)</td>
                            <td class="px-5 py-3">
                                <span class="{{ $trade->result === 'WIN' ? 'bg-gain/10 text-gain' : 'bg-loss/10 text-loss' }} text-xs font-medium px-2 py-0.5 rounded-full">{{ $trade->result }}</span>
                            </td>
                            <td class="px-5 py-3 text-content-tertiary text-xs">{{ $trade->executed_at->format('M d, H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-5 py-8 text-center text-content-tertiary">No trades recorded yet. The bot will start trading shortly.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($trades->hasPages())
        <div class="mt-4">{{ $trades->links() }}</div>
    @endif

@endsection
