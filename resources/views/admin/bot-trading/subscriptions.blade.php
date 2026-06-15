@extends('layouts.admin-dash')
@section('title', $title)
@section('content')

<x-admin.page-header title="Bot Trading Subscriptions" subtitle="Manage user subscriptions to trading bots">
    <x-slot name="actions">
        <a href="{{ route('admin.bot-trading.index') }}" class="bg-surface-alt text-content border border-border hover:bg-surface-alt/80 rounded-lg px-4 py-2 text-sm font-medium inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Back to Bots
        </a>
    </x-slot>
</x-admin.page-header>

{{-- Stats Row --}}
<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mt-6">
    <x-admin.stat-card label="Active Subscriptions" :value="$totalActive"
        icon='<svg class="w-5 h-5 text-success" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' />
    <x-admin.stat-card label="Total Invested" :value="'$' . number_format($totalInvested, 2)"
        icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' />
    <x-admin.stat-card label="Total Profit" :value="'$' . number_format($totalProfit, 2)"
        icon='<svg class="w-5 h-5 text-warning" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" /></svg>' />
    <x-admin.stat-card label="Settled" :value="$totalSettled"
        icon='<svg class="w-5 h-5 text-info" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" /></svg>' />
</div>

{{-- Filters --}}
<div class="mt-6 flex items-center gap-4">
    <form method="GET" class="flex items-center gap-3">
        <select name="status" onchange="this.form.submit()" class="bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:ring-2 focus:ring-primary/30">
            <option value="all" {{ $currentStatus === 'all' ? 'selected' : '' }}>All Status</option>
            <option value="active" {{ $currentStatus === 'active' ? 'selected' : '' }}>Active</option>
            <option value="stopped" {{ $currentStatus === 'stopped' ? 'selected' : '' }}>Stopped</option>
            <option value="completed" {{ $currentStatus === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="settled" {{ $currentStatus === 'settled' ? 'selected' : '' }}>Settled</option>
        </select>
        <input type="text" name="search" value="{{ $currentSearch }}" placeholder="Search user or bot..." class="bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content placeholder:text-content-muted focus:ring-2 focus:ring-primary/30 w-64">
        <button type="submit" class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium">Filter</button>
    </form>
</div>

{{-- Subscriptions Table --}}
<div class="mt-4">
    <x-admin.table-card title="Subscriptions">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-surface-alt">
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">ID</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">User</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Bot</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Invested</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Profit</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Payout</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Expires</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Status</th>
                    <th class="text-right text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscriptions as $sub)
                    <tr class="border-b border-border hover:bg-surface-alt/50">
                        <td class="px-5 py-3 text-content-muted">#{{ $sub->id }}</td>
                        <td class="px-5 py-3">
                            <p class="font-medium text-content">{{ $sub->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-content-muted">{{ $sub->user->email ?? '' }}</p>
                        </td>
                        <td class="px-5 py-3 font-medium text-content">{{ $sub->tradingBot->name ?? 'Deleted' }}</td>
                        <td class="px-5 py-3 text-content">${{ number_format($sub->invested_amount, 2) }}</td>
                        <td class="px-5 py-3 {{ $sub->accumulated_profit >= 0 ? 'text-success' : 'text-danger' }} font-medium">${{ number_format($sub->accumulated_profit, 2) }}</td>
                        <td class="px-5 py-3 text-content font-medium">${{ number_format($sub->totalPayout(), 2) }}</td>
                        <td class="px-5 py-3 text-content-muted text-xs">{{ $sub->expires_at ? $sub->expires_at->format('M d, Y') : '—' }}</td>
                        <td class="px-5 py-3">
                            @php
                                $statusColors = [
                                    'active' => 'success',
                                    'stopped' => 'warning',
                                    'completed' => 'info',
                                    'settled' => 'neutral',
                                ];
                            @endphp
                            <x-admin.badge type="{{ $statusColors[$sub->status] ?? 'neutral' }}">{{ ucfirst($sub->status) }}</x-admin.badge>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.bot-trading.subscription', $sub->id) }}" class="text-content-secondary hover:text-content transition-colors" title="View">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </a>
                                <a href="{{ route('admin.bot-trading.subscription-edit', $sub->id) }}" class="text-content-secondary hover:text-warning transition-colors" title="Edit / Backdate">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" /></svg>
                                </a>
                                @if($sub->isSettleable())
                                    <form action="{{ route('admin.bot-trading.settle', $sub->id) }}" method="POST" class="inline" onsubmit="return confirm('Settle this subscription? ${{ number_format($sub->totalPayout(), 2) }} will be credited.')">
                                        @csrf
                                        <button type="submit" class="text-content-secondary hover:text-success transition-colors" title="Settle">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="px-5 py-8 text-center text-content-muted">No subscriptions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.table-card>
    <div class="mt-4">{{ $subscriptions->appends(request()->query())->links() }}</div>
</div>

@endsection
