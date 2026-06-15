@extends('layouts.admin-dash')
@section('title', 'Trade #' . $trade->id)

@section('content')

    <x-admin.page-header title="Trade #{{ $trade->id }} Details">
        <x-slot name="actions">
            <a href="{{ route('admin.trades.index') }}" class="inline-flex items-center gap-2 bg-secondary-light text-content-secondary rounded-lg px-4 py-2 text-sm font-medium hover:bg-surface-alt transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Back to Trades
            </a>
            <a href="{{ route('admin.trades.edit', $trade->id) }}" class="inline-flex items-center gap-2 bg-primary text-primary-foreground rounded-lg px-4 py-2 text-sm font-medium hover:bg-primary-hover transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" /></svg>
                Edit Trade
            </a>
        </x-slot>
    </x-admin.page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

        {{-- Trade Information Card --}}
        <div class="lg:col-span-2">
            <x-admin.card>
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base font-semibold text-content">Trade Information</h3>
                    <div class="flex items-center gap-2">
                        @if(($trade->trade_type ?? 'binary') == 'binary')
                            <x-admin.badge type="info">Binary</x-admin.badge>
                        @else
                            <x-admin.badge type="neutral">Spot</x-admin.badge>
                        @endif
                        @if($trade->is_demo)
                            <x-admin.badge type="warning">DEMO</x-admin.badge>
                        @else
                            <x-admin.badge type="success">LIVE</x-admin.badge>
                        @endif
                    </div>
                </div>

                <div class="divide-y divide-border">
                    <div class="flex justify-between py-3">
                        <span class="text-sm text-content-muted">User</span>
                        <span class="text-sm font-medium text-content">{{ $trade->user->name ?? 'N/A' }} ({{ $trade->user->email ?? '' }})</span>
                    </div>
                    <div class="flex justify-between py-3">
                        <span class="text-sm text-content-muted">Asset</span>
                        <span class="text-sm text-content">{{ $trade->asset_name }}</span>
                    </div>
                    <div class="flex justify-between py-3">
                        <span class="text-sm text-content-muted">Asset Type</span>
                        <span class="text-sm text-content">{{ ucfirst($trade->asset_type) }}</span>
                    </div>
                    <div class="flex justify-between py-3">
                        <span class="text-sm text-content-muted">Action</span>
                        <x-admin.badge :type="$trade->action == 'buy' ? 'success' : 'danger'">{{ ucfirst($trade->action) }}</x-admin.badge>
                    </div>
                    <div class="flex justify-between py-3">
                        <span class="text-sm text-content-muted">Amount</span>
                        <span class="text-sm font-medium text-content">${{ number_format($trade->amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-3">
                        <span class="text-sm text-content-muted">Leverage</span>
                        <span class="text-sm text-content">{{ $trade->leverage }}x</span>
                    </div>
                    <div class="flex justify-between py-3">
                        <span class="text-sm text-content-muted">Entry Price</span>
                        <span class="text-sm text-content">{{ $trade->entry_price ? '$' . number_format($trade->entry_price, 2) : '—' }}</span>
                    </div>
                    <div class="flex justify-between py-3">
                        <span class="text-sm text-content-muted">Exit Price</span>
                        <span class="text-sm text-content">{{ $trade->exit_price ? '$' . number_format($trade->exit_price, 2) : '—' }}</span>
                    </div>
                    <div class="flex justify-between py-3">
                        <span class="text-sm text-content-muted">Status</span>
                        <x-admin.badge :type="$trade->status == 'open' ? 'info' : 'neutral'">{{ ucfirst($trade->status) }}</x-admin.badge>
                    </div>
                    <div class="flex justify-between py-3">
                        <span class="text-sm text-content-muted">Result</span>
                        @if($trade->result == 'WIN')
                            <x-admin.badge type="success">WIN</x-admin.badge>
                        @elseif($trade->result == 'LOSS')
                            <x-admin.badge type="danger">LOSS</x-admin.badge>
                        @else
                            <x-admin.badge type="warning">{{ $trade->result ?? 'PENDING' }}</x-admin.badge>
                        @endif
                    </div>
                    <div class="flex justify-between py-3">
                        <span class="text-sm text-content-muted">Profit/Loss</span>
                        @if($trade->profit_loss !== null)
                            <span class="text-sm font-semibold {{ $trade->profit_loss >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $trade->profit_loss >= 0 ? '+' : '-' }}${{ number_format(abs($trade->profit_loss), 2) }}
                            </span>
                        @else
                            <span class="text-sm text-content-muted">—</span>
                        @endif
                    </div>
                    <div class="flex justify-between py-3">
                        <span class="text-sm text-content-muted">Opened At</span>
                        <span class="text-sm text-content">{{ $trade->created_at->format('Y-m-d H:i:s') }}</span>
                    </div>
                    <div class="flex justify-between py-3">
                        <span class="text-sm text-content-muted">Expires At</span>
                        <span class="text-sm text-content">{{ $trade->expires_at ? $trade->expires_at->format('Y-m-d H:i:s') : 'No expiry (spot)' }}</span>
                    </div>
                    @if($trade->settled_by)
                    <div class="flex justify-between py-3">
                        <span class="text-sm text-content-muted">Settled By</span>
                        <span class="text-sm text-content">{{ ucfirst($trade->settled_by) }} {{ $trade->settled_at ? '— ' . $trade->settled_at->format('Y-m-d H:i') : '' }}</span>
                    </div>
                    @endif
                    @if($trade->close_requested_at)
                    <div class="flex justify-between py-3">
                        <span class="text-sm text-content-muted">Close Requested</span>
                        <span class="text-sm text-warning font-medium">{{ \Carbon\Carbon::parse($trade->close_requested_at)->format('Y-m-d H:i') }}</span>
                    </div>
                    @endif
                </div>
            </x-admin.card>
        </div>

        {{-- Settle / Settlement Info Panel --}}
        <div>
            @if($trade->status === 'open')
                <x-admin.card>
                    <h3 class="text-base font-semibold text-content mb-4">Settle Trade</h3>

                    @if($trade->close_requested_at)
                        <x-admin.alert type="warning" class="mb-4">
                            <strong>Close Requested</strong> by user on {{ \Carbon\Carbon::parse($trade->close_requested_at)->format('M d, H:i') }}
                        </x-admin.alert>
                    @endif

                    <form action="{{ route('admin.trades.updateProfitLoss', $trade->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <x-admin.form-group label="Result" for="result" required>
                            <select name="result" id="result" required
                                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                <option value="">Select</option>
                                <option value="WIN">WIN</option>
                                <option value="LOSS">LOSS</option>
                            </select>
                        </x-admin.form-group>
                        <x-admin.form-group label="Profit/Loss (USD)" for="profit_loss" required
                            helper="Suggested ({{ $trade->leverage }}x on ${{ number_format($trade->amount, 2) }}): ${{ number_format($trade->amount * ($trade->leverage / 100), 2) }}">
                            <input type="number" step="0.01" name="profit_loss" id="profit_loss" required
                                   class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        </x-admin.form-group>
                        <button type="submit" class="w-full bg-primary text-primary-foreground rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-primary-hover transition-colors">
                            Settle Trade
                        </button>
                    </form>
                </x-admin.card>
            @else
                <x-admin.card>
                    <h3 class="text-base font-semibold text-content mb-4">Settlement Info</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-content-muted">Result</span>
                            <span class="text-sm font-semibold {{ $trade->result == 'WIN' ? 'text-success' : 'text-danger' }}">{{ $trade->result }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-content-muted">P/L</span>
                            <span class="text-sm font-semibold {{ ($trade->profit_loss ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ ($trade->profit_loss ?? 0) >= 0 ? '+' : '-' }}${{ number_format(abs($trade->profit_loss ?? 0), 2) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-content-muted">Settled By</span>
                            <span class="text-sm text-content">{{ ucfirst($trade->settled_by ?? 'system') }}</span>
                        </div>
                        @if($trade->settled_at)
                        <div class="flex justify-between">
                            <span class="text-sm text-content-muted">Settled At</span>
                            <span class="text-sm text-content">{{ $trade->settled_at->format('Y-m-d H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </x-admin.card>
            @endif
        </div>
    </div>

@endsection
