@extends('layouts.admin-dash')
@section('title', $title)
@section('content')

<x-admin.page-header title="Bot Subscription #{{ $subscription->id }}" subtitle="View subscription details and manage profit">
    <x-slot name="actions">
        <a href="{{ route('admin.bot-trading.subscriptions') }}" class="bg-surface-alt text-content border border-border hover:bg-surface-alt/80 rounded-lg px-4 py-2 text-sm font-medium inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Back
        </a>
        <a href="{{ route('admin.bot-trading.subscription-edit', $subscription->id) }}" class="bg-warning text-content-inverse hover:bg-warning/80 rounded-lg px-4 py-2 text-sm font-medium inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" /></svg>
            Edit / Backdate
        </a>
    </x-slot>
</x-admin.page-header>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
    {{-- Left: Subscription Details --}}
    <div class="lg:col-span-2 space-y-6">
        <x-admin.card>
            <h3 class="text-base font-semibold text-content mb-4">Subscription Details</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-content-muted">User</p>
                        <p class="text-sm font-medium text-content">{{ $subscription->user->name ?? 'N/A' }}</p>
                        <p class="text-xs text-content-muted">{{ $subscription->user->email ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-content-muted">Bot</p>
                        <p class="text-sm font-medium text-content">{{ $subscription->tradingBot->name ?? 'Deleted' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-content-muted">Status</p>
                        @php
                            $statusColors = ['active' => 'success', 'stopped' => 'warning', 'completed' => 'info', 'settled' => 'neutral'];
                        @endphp
                        <x-admin.badge type="{{ $statusColors[$subscription->status] ?? 'neutral' }}">{{ ucfirst($subscription->status) }}</x-admin.badge>
                    </div>
                </div>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-content-muted">Invested Amount</p>
                        <p class="text-sm font-semibold text-content">${{ number_format($subscription->invested_amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-content-muted">Accumulated Profit</p>
                        <p class="text-sm font-semibold {{ $subscription->accumulated_profit >= 0 ? 'text-success' : 'text-danger' }}">${{ number_format($subscription->accumulated_profit, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-content-muted">Admin Adjustment</p>
                        <p class="text-sm font-semibold text-content">${{ number_format($subscription->admin_profit_adjustment, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-content-muted">Total Payout</p>
                        <p class="text-lg font-bold text-primary">${{ number_format($subscription->totalPayout(), 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mt-4 pt-4 border-t border-border">
                <div>
                    <p class="text-xs text-content-muted">Daily ROI Snapshot</p>
                    <p class="text-sm font-medium text-content">{{ number_format($subscription->daily_roi_snapshot, 2) }}%</p>
                </div>
                <div>
                    <p class="text-xs text-content-muted">Started</p>
                    <p class="text-sm text-content">{{ $subscription->started_at->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-content-muted">Expires</p>
                    <p class="text-sm text-content">{{ $subscription->expires_at ? $subscription->expires_at->format('M d, Y H:i') : '—' }}</p>
                </div>
            </div>

            @if($subscription->admin_notes)
                <div class="mt-4 pt-4 border-t border-border">
                    <p class="text-xs text-content-muted mb-1">Admin Notes</p>
                    <p class="text-sm text-content">{{ $subscription->admin_notes }}</p>
                </div>
            @endif
        </x-admin.card>

        {{-- Simulated Trades --}}
        <x-admin.table-card title="Simulated Trades ({{ $trades->total() }})">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-surface-alt">
                        <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Asset</th>
                        <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Action</th>
                        <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Amount</th>
                        <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Entry</th>
                        <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Exit</th>
                        <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">P/L</th>
                        <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Result</th>
                        <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trades as $trade)
                        <tr class="border-b border-border">
                            <td class="px-5 py-3">
                                <p class="font-medium text-content">{{ $trade->asset_name }}</p>
                                <p class="text-xs text-content-muted">{{ $trade->asset_class }}</p>
                            </td>
                            <td class="px-5 py-3">
                                <x-admin.badge type="{{ $trade->action === 'buy' ? 'success' : 'danger' }}">{{ strtoupper($trade->action) }}</x-admin.badge>
                            </td>
                            <td class="px-5 py-3 text-content">${{ number_format($trade->amount, 2) }}</td>
                            <td class="px-5 py-3 text-content">{{ number_format($trade->entry_price, 4) }}</td>
                            <td class="px-5 py-3 text-content">{{ number_format($trade->exit_price, 4) }}</td>
                            <td class="px-5 py-3 font-medium {{ $trade->profit_loss >= 0 ? 'text-success' : 'text-danger' }}">${{ number_format($trade->profit_loss, 2) }}</td>
                            <td class="px-5 py-3">
                                <x-admin.badge type="{{ $trade->result === 'WIN' ? 'success' : 'danger' }}">{{ $trade->result }}</x-admin.badge>
                            </td>
                            <td class="px-5 py-3 text-content-muted text-xs">{{ $trade->executed_at->format('M d, H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-5 py-8 text-center text-content-muted">No trades yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-admin.table-card>
        <div class="mt-4">{{ $trades->links() }}</div>
    </div>

    {{-- Right: Actions --}}
    <div class="space-y-6">
        @if($subscription->isSettleable())
            <x-admin.card>
                <h3 class="text-base font-semibold text-content mb-4">Settle Subscription</h3>
                <p class="text-sm text-content-muted mb-4">Credit <strong class="text-content">${{ number_format($subscription->totalPayout(), 2) }}</strong> to user's balance and mark as settled.</p>
                <form action="{{ route('admin.bot-trading.settle', $subscription->id) }}" method="POST" onsubmit="return confirm('Confirm settlement?')">
                    @csrf
                    <button type="submit" class="w-full bg-success text-white hover:bg-success/90 rounded-lg px-4 py-2.5 text-sm font-medium">Settle Now</button>
                </form>
            </x-admin.card>
        @endif

        <x-admin.card>
            <h3 class="text-base font-semibold text-content mb-4">Profit Adjustment</h3>
            <form action="{{ route('admin.bot-trading.adjust', $subscription->id) }}" method="POST">
                @csrf
                <x-admin.form-group label="Adjustment Amount ($)" for="admin_profit_adjustment" :error="$errors->first('admin_profit_adjustment')">
                    <input type="number" name="admin_profit_adjustment" id="admin_profit_adjustment" value="{{ old('admin_profit_adjustment', $subscription->admin_profit_adjustment) }}" step="0.01" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </x-admin.form-group>
                <div class="mt-3">
                    <x-admin.form-group label="Notes" for="admin_notes">
                        <textarea name="admin_notes" id="admin_notes" rows="3" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:ring-2 focus:ring-primary/30 focus:border-primary resize-y">{{ old('admin_notes', $subscription->admin_notes) }}</textarea>
                    </x-admin.form-group>
                </div>
                <button type="submit" class="mt-3 w-full bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2.5 text-sm font-medium">Save Adjustment</button>
            </form>
        </x-admin.card>
    </div>
</div>

@endsection
