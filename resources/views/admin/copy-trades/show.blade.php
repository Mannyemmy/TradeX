@extends('layouts.admin-dash')
@section('title', $title)
@section('content')

<x-admin.page-header title="Copy Position #{{ $position->id }}" subtitle="Manage copy trading position details">
    <x-slot name="actions">
        <a href="{{ route('admin.copy-trades.index') }}" class="bg-surface-alt text-content border border-border hover:bg-surface-alt/80 rounded-lg px-4 py-2 text-sm font-medium">
            <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Back to Copy Trades
        </a>
    </x-slot>
</x-admin.page-header>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    {{-- Left: Position Details --}}
    <x-admin.card>
        <h3 class="text-base font-semibold text-content mb-4">Position Details</h3>

        <div class="space-y-3">
            <div class="flex justify-between text-sm">
                <span class="text-content-secondary">User</span>
                <div class="text-right">
                    <div class="font-medium text-content">{{ $position->user->name ?? 'N/A' }}</div>
                    <div class="text-xs text-content-muted">{{ $position->user->email ?? '' }}</div>
                </div>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-content-secondary">Expert</span>
                <div class="flex items-center gap-2">
                    @if($position->expert && $position->expert->profile_picture)
                        <img src="{{ asset('storage/app/public/' . $position->expert->profile_picture) }}" class="w-6 h-6 rounded-full object-cover">
                    @endif
                    <span class="font-medium text-content">{{ $position->expert->name ?? 'N/A' }}</span>
                </div>
            </div>
            @if($position->expert)
            <div class="flex justify-between text-sm">
                <span class="text-content-secondary">Expertise</span>
                <x-admin.badge>{{ $position->expert->area_of_expertise }}</x-admin.badge>
            </div>
            @endif
            <div class="flex justify-between text-sm">
                <span class="text-content-secondary">Status</span>
                @php
                    $badgeType = match($position->status) { 'active' => 'success', 'stopped' => 'danger', 'completed' => 'info', 'settled' => 'warning', default => 'neutral' };
                @endphp
                <x-admin.badge :type="$badgeType">{{ ucfirst($position->status) }}</x-admin.badge>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-content-secondary">Started</span>
                <span class="text-content">{{ $position->started_at->format('M d, Y H:i') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-content-secondary">Expires</span>
                <span class="text-content">{{ $position->expires_at->format('M d, Y H:i') }}</span>
            </div>
            @php
                $totalDays = $position->started_at->diffInDays($position->expires_at);
                $elapsedDays = $position->started_at->diffInDays(now());
                $daysRemaining = max(0, $totalDays - $elapsedDays);
                $progressPct = $totalDays > 0 ? min(100, round(($elapsedDays / $totalDays) * 100)) : 100;
            @endphp
            <div class="flex justify-between text-sm">
                <span class="text-content-secondary">Days Remaining</span>
                <span class="text-content font-medium">{{ $daysRemaining }} of {{ $totalDays }} days</span>
            </div>
        </div>
    </x-admin.card>

    {{-- Right: Financial Summary --}}
    <x-admin.card>
        <h3 class="text-base font-semibold text-content mb-4">Financial Summary</h3>

        <div class="space-y-3">
            <div class="flex justify-between text-sm">
                <span class="text-content-secondary">Invested Amount</span>
                <span class="font-medium text-content">${{ number_format($position->invested_amount, 2) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-content-secondary">Daily ROI</span>
                <span class="text-success font-medium">{{ number_format($position->daily_roi_snapshot, 2) }}%</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-content-secondary">Accumulated Profit</span>
                <span class="text-success font-medium">${{ number_format($position->accumulated_profit, 2) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-content-secondary">Admin Adjustment</span>
                <span class="{{ $position->admin_profit_adjustment != 0 ? 'text-warning font-medium' : 'text-content-muted' }}">
                    {{ $position->admin_profit_adjustment != 0 ? ($position->admin_profit_adjustment > 0 ? '+' : '') . '$' . number_format($position->admin_profit_adjustment, 2) : '—' }}
                </span>
            </div>
            <hr class="border-border">
            <div class="flex justify-between">
                <span class="text-content font-medium">Total Payout</span>
                <span class="text-stat text-content font-bold">${{ number_format($position->totalPayout(), 2) }}</span>
            </div>
        </div>
    </x-admin.card>
</div>

{{-- Admin Actions --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    {{-- Adjust Profit --}}
    <x-admin.card>
        <h3 class="text-base font-semibold text-content mb-4">Adjust Profit</h3>
        <form action="{{ route('admin.copy-trades.adjust', $position->id) }}" method="POST">
            @csrf
            <x-admin.form-group label="Profit Adjustment ($)" for="admin_profit_adjustment" :error="$errors->first('admin_profit_adjustment')" helper="Positive to add, negative to deduct">
                <input type="number" name="admin_profit_adjustment" id="admin_profit_adjustment" value="{{ old('admin_profit_adjustment', $position->admin_profit_adjustment) }}" step="0.01" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:ring-2 focus:ring-primary/30 focus:border-primary">
            </x-admin.form-group>
            <div class="mt-4">
                <x-admin.form-group label="Admin Notes" for="admin_notes" :error="$errors->first('admin_notes')">
                    <textarea name="admin_notes" id="admin_notes" rows="3" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:ring-2 focus:ring-primary/30 focus:border-primary resize-y">{{ old('admin_notes', $position->admin_notes) }}</textarea>
                </x-admin.form-group>
            </div>
            <button type="submit" class="mt-4 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium">Save Adjustment</button>
        </form>
    </x-admin.card>

    {{-- Settle / Stop --}}
    <x-admin.card>
        <h3 class="text-base font-semibold text-content mb-4">Position Actions</h3>

        @if($position->isSettleable())
            <form action="{{ route('admin.copy-trades.settle', $position->id) }}" method="POST" class="mb-4" onsubmit="return confirm('Settle this position? ${{ number_format($position->totalPayout(), 2) }} will be credited to the user.')">
                @csrf
                <button type="submit" class="w-full bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2.5 text-sm font-medium">
                    Settle & Credit User — ${{ number_format($position->totalPayout(), 2) }}
                </button>
            </form>
        @endif

        @if($position->status === 'active')
            <form action="{{ route('admin.copy-trades.stop', $position->id) }}" method="POST" onsubmit="return confirm('Force stop this active position?')">
                @csrf
                <button type="submit" class="w-full bg-danger text-white hover:bg-danger/90 rounded-lg px-4 py-2.5 text-sm font-medium">
                    Force Stop
                </button>
            </form>
        @endif

        @if($position->status === 'settled')
            <p class="text-sm text-content-muted">This position has been settled by <strong>{{ $position->settled_by }}</strong> on {{ $position->settled_at?->format('M d, Y H:i') }}.</p>
        @endif
    </x-admin.card>
</div>

{{-- Simulated Trades Table --}}
<div class="mt-6">
    <x-admin.table-card title="Simulated Trades">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-surface-alt">
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Asset</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Class</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Direction</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Entry</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Exit</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Amount</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">P/L</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Result</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trades as $trade)
                    <tr class="border-b border-border hover:bg-surface-alt/50">
                        <td class="px-5 py-3 font-medium text-content">{{ $trade->asset_name }}</td>
                        <td class="px-5 py-3"><x-admin.badge>{{ ucfirst($trade->asset_class) }}</x-admin.badge></td>
                        <td class="px-5 py-3 font-medium {{ $trade->action === 'buy' ? 'text-success' : 'text-danger' }}">{{ strtoupper($trade->action) }}</td>
                        <td class="px-5 py-3 text-content">{{ number_format($trade->entry_price, 2) }}</td>
                        <td class="px-5 py-3 text-content">{{ number_format($trade->exit_price, 2) }}</td>
                        <td class="px-5 py-3 text-content">${{ number_format($trade->amount, 2) }}</td>
                        <td class="px-5 py-3 font-medium {{ $trade->profit_loss >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $trade->profit_loss >= 0 ? '+' : '' }}${{ number_format($trade->profit_loss, 2) }}
                        </td>
                        <td class="px-5 py-3">
                            <x-admin.badge :type="$trade->result === 'WIN' ? 'success' : 'danger'">{{ $trade->result }}</x-admin.badge>
                        </td>
                        <td class="px-5 py-3 text-content-secondary text-xs">{{ $trade->executed_at->format('M d, H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="px-5 py-8 text-center text-content-muted">No simulated trades yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.table-card>
    <div class="mt-4">{{ $trades->links() }}</div>
</div>

@endsection
