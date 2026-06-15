@extends('layouts.admin-dash')
@section('title', $title)
@section('content')

<x-admin.page-header title="Manage Copy Trades" subtitle="View and manage all user copy trading positions" />

{{-- Stats Row --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
    <x-admin.stat-card label="Active Copies" :value="$totalActive"
        icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>' />
    <x-admin.stat-card label="Total Invested" :value="'$' . number_format($totalInvested, 2)"
        icon='<svg class="w-5 h-5 text-success" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' />
    <x-admin.stat-card label="Total Profit" :value="'$' . number_format($totalProfit, 2)"
        icon='<svg class="w-5 h-5 text-info" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" /></svg>' />
    <x-admin.stat-card label="Settled Positions" :value="$totalSettled"
        icon='<svg class="w-5 h-5 text-warning" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' />
</div>

{{-- Filters --}}
<div class="mt-6 flex flex-col sm:flex-row sm:items-center gap-4" x-data>
    {{-- Status Tabs --}}
    <div class="flex items-center gap-1 bg-surface-card border border-border rounded-lg p-1">
        @foreach(['all' => 'All', 'active' => 'Active', 'stopped' => 'Stopped', 'completed' => 'Completed', 'settled' => 'Settled'] as $key => $label)
            <a href="{{ route('admin.copy-trades.index', ['status' => $key, 'search' => $currentSearch]) }}"
               class="px-3 py-1.5 text-sm rounded-md font-medium transition-colors {{ $currentStatus === $key ? 'bg-primary text-primary-foreground' : 'text-content-muted hover:text-content' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Search --}}
    <form action="{{ route('admin.copy-trades.index') }}" method="GET" class="flex-1 max-w-sm">
        <input type="hidden" name="status" value="{{ $currentStatus }}">
        <input type="text" name="search" value="{{ $currentSearch }}" placeholder="Search user or expert..."
               class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2 text-sm text-content placeholder:text-content-muted focus:ring-2 focus:ring-primary/30 focus:border-primary">
    </form>
</div>

{{-- Bulk Action Form --}}
<form id="bulkSettleForm" action="{{ route('admin.copy-trades.bulk-settle') }}" method="POST">
    @csrf

    <div class="mt-4">
        <x-admin.table-card title="Copy Positions">
            <x-slot name="actions">
                <button type="submit" onclick="return confirm('Settle all selected positions?')" class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium">
                    Settle Selected
                </button>
            </x-slot>
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-surface-alt">
                        <th class="px-5 py-3 w-8"><input type="checkbox" id="selectAll" class="rounded"></th>
                        <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">User</th>
                        <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Expert</th>
                        <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Invested</th>
                        <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Profit</th>
                        <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Adjustment</th>
                        <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Total Payout</th>
                        <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Status</th>
                        <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Started</th>
                        <th class="text-right text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($positions as $pos)
                        <tr class="border-b border-border hover:bg-surface-alt/50">
                            <td class="px-5 py-3"><input type="checkbox" name="position_ids[]" value="{{ $pos->id }}" class="bulk-check rounded"></td>
                            <td class="px-5 py-3">
                                <div class="font-medium text-content">{{ $pos->user->name ?? 'N/A' }}</div>
                                <div class="text-xs text-content-muted">{{ $pos->user->email ?? '' }}</div>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    @if($pos->expert && $pos->expert->profile_picture)
                                        <img src="{{ asset('storage/app/public/' . $pos->expert->profile_picture) }}" class="w-6 h-6 rounded-full object-cover">
                                    @endif
                                    <span class="text-content">{{ $pos->expert->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-content">${{ number_format($pos->invested_amount, 2) }}</td>
                            <td class="px-5 py-3 text-success font-medium">${{ number_format($pos->accumulated_profit, 2) }}</td>
                            <td class="px-5 py-3 {{ $pos->admin_profit_adjustment != 0 ? 'text-warning font-medium' : 'text-content-muted' }}">
                                {{ $pos->admin_profit_adjustment != 0 ? ($pos->admin_profit_adjustment > 0 ? '+' : '') . '$' . number_format($pos->admin_profit_adjustment, 2) : '—' }}
                            </td>
                            <td class="px-5 py-3 font-medium text-content">${{ number_format($pos->totalPayout(), 2) }}</td>
                            <td class="px-5 py-3">
                                @php
                                    $badgeType = match($pos->status) { 'active' => 'success', 'stopped' => 'danger', 'completed' => 'info', 'settled' => 'warning', default => 'neutral' };
                                @endphp
                                <x-admin.badge :type="$badgeType">{{ ucfirst($pos->status) }}</x-admin.badge>
                            </td>
                            <td class="px-5 py-3 text-content-secondary text-xs">{{ $pos->started_at->format('M d, Y') }}</td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('admin.copy-trades.show', $pos->id) }}" class="text-primary hover:text-primary-hover text-sm font-medium">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="px-5 py-8 text-center text-content-muted">No copy positions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-admin.table-card>
        <div class="mt-4">{{ $positions->appends(request()->query())->links() }}</div>
    </div>
</form>

@endsection

@push('scripts')
<script>
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.bulk-check').forEach(cb => cb.checked = this.checked);
});
</script>
@endpush
