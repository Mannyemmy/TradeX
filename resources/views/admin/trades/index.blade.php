@extends('layouts.admin-dash')
@section('title', 'Manage Trades')

@section('content')

    <x-admin.page-header title="Manage Client Trades">
        <x-slot name="actions">
            <a href="{{ route('admin.trades.create') }}" class="inline-flex items-center gap-2 bg-primary text-primary-foreground rounded-lg px-4 py-2 text-sm font-medium hover:bg-primary-hover transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Create Trade
            </a>
        </x-slot>
    </x-admin.page-header>

    {{-- Filter Tabs --}}
    <div class="flex gap-1 border-b border-border mt-4">
        @foreach(['all' => 'All', 'binary' => 'Binary', 'spot' => 'Spot', 'open' => 'Open', 'closed' => 'Closed', 'demo' => 'Demo'] as $key => $label)
            <a href="{{ route('admin.trades.index', ['filter' => $key]) }}"
               class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors -mb-px
                      {{ ($filter ?? 'all') === $key
                          ? 'border-primary text-primary'
                          : 'border-transparent text-content-muted hover:text-content-secondary hover:border-border-strong' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Bulk Settle Bar --}}
    <form id="bulkSettleForm" action="{{ route('admin.trades.bulkSettle') }}" method="POST"
          class="mt-4 bg-warning-light border border-warning/30 rounded-xl p-4 items-center gap-3 hidden" x-data x-ref="bulkBar">
        @csrf
        <div class="flex flex-wrap items-center gap-3">
            <span class="text-sm font-medium text-content"><strong id="selectedCount">0</strong> selected</span>
            <select name="bulk_result" class="bg-surface-card border border-border rounded-lg px-3 py-1.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30" required>
                <option value="WIN">WIN</option>
                <option value="LOSS">LOSS</option>
            </select>
            <input type="number" name="bulk_profit_loss" class="bg-surface-card border border-border rounded-lg px-3 py-1.5 text-sm text-content w-32 focus:outline-none focus:ring-2 focus:ring-primary/30" placeholder="P/L amount" step="0.01" min="0" required>
            <button type="submit" class="bg-warning text-content-inverse rounded-lg px-4 py-1.5 text-sm font-medium hover:opacity-90 transition-opacity"
                    onclick="return confirm('Settle all selected trades?')">Bulk Settle</button>
        </div>
    </form>

    {{-- Trades Table --}}
    <x-admin.table-card class="mt-4">
        <table class="w-full" id="tradesTable">
            <thead>
                <tr>
                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border w-10">
                        <input type="checkbox" id="selectAll" class="rounded border-border-strong text-primary focus:ring-primary/30">
                    </th>
                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">#</th>
                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">User</th>
                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Type</th>
                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Asset</th>
                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Action</th>
                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Amount</th>
                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Leverage</th>
                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Entry Price</th>
                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Status</th>
                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Result</th>
                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">P/L</th>
                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Settled By</th>
                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Opened</th>
                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trades as $trade)
                    <tr class="hover:bg-surface-alt/50 transition-colors">
                        <td class="px-4 py-3.5 text-sm border-b border-border">
                            @if($trade->status === 'open')
                                <input type="checkbox" class="trade-checkbox rounded border-border-strong text-primary focus:ring-primary/30" name="trade_ids[]" form="bulkSettleForm" value="{{ $trade->id }}">
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-sm text-content-secondary border-b border-border">{{ $loop->iteration + ($trades->currentPage() - 1) * $trades->perPage() }}</td>
                        <td class="px-4 py-3.5 text-sm text-content font-medium border-b border-border">{{ $trade->user->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3.5 text-sm border-b border-border">
                            <div class="flex flex-wrap gap-1">
                                <x-admin.badge :type="$trade->trade_type === 'binary' ? 'info' : 'neutral'">{{ ucfirst($trade->trade_type) }}</x-admin.badge>
                                @if($trade->is_demo)
                                    <x-admin.badge type="neutral">DEMO</x-admin.badge>
                                @endif
                                @if($trade->close_requested_at)
                                    <x-admin.badge type="warning">Close Req.</x-admin.badge>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-sm text-content-secondary border-b border-border">{{ $trade->asset_name }}</td>
                        <td class="px-4 py-3.5 text-sm border-b border-border">
                            <x-admin.badge :type="$trade->action === 'buy' ? 'success' : 'danger'">{{ strtoupper($trade->action) }}</x-admin.badge>
                        </td>
                        <td class="px-4 py-3.5 text-sm text-content font-medium border-b border-border">${{ number_format($trade->amount, 2) }}</td>
                        <td class="px-4 py-3.5 text-sm text-content-secondary border-b border-border">{{ $trade->leverage }}x</td>
                        <td class="px-4 py-3.5 text-sm text-content-secondary border-b border-border">
                            {{ $trade->entry_price ? '$' . number_format($trade->entry_price, 2) : '—' }}
                        </td>
                        <td class="px-4 py-3.5 text-sm border-b border-border">
                            <x-admin.badge :type="$trade->status === 'open' ? 'warning' : 'neutral'">{{ ucfirst($trade->status) }}</x-admin.badge>
                        </td>
                        <td class="px-4 py-3.5 text-sm border-b border-border">
                            @if($trade->result == 'PENDING' || !$trade->result)
                                <x-admin.badge type="warning">PENDING</x-admin.badge>
                            @else
                                <x-admin.badge :type="$trade->result == 'WIN' ? 'success' : 'danger'">{{ $trade->result }}</x-admin.badge>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-sm font-medium border-b border-border">
                            @if($trade->profit_loss !== null && $trade->profit_loss != 0)
                                <span class="{{ $trade->profit_loss >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $trade->profit_loss >= 0 ? '+' : '' }}${{ number_format($trade->profit_loss, 2) }}
                                </span>
                            @else
                                <span class="text-content-muted">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-xs text-content-muted border-b border-border">
                            {{ $trade->settled_by ? ucfirst($trade->settled_by) : '—' }}
                        </td>
                        <td class="px-4 py-3.5 text-xs text-content-muted border-b border-border">{{ $trade->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3.5 text-sm border-b border-border">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.trades.show', $trade->id) }}" class="inline-flex items-center gap-1 text-xs font-medium text-info hover:text-info/80 transition-colors px-2 py-1 rounded-md hover:bg-info-light">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    View
                                </a>
                                <a href="{{ route('admin.trades.edit', $trade->id) }}" class="inline-flex items-center gap-1 text-xs font-medium text-warning hover:text-warning/80 transition-colors px-2 py-1 rounded-md hover:bg-warning-light">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" /></svg>
                                    Edit
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="15" class="px-4 py-12 text-center text-content-muted text-sm">
                            <svg class="w-10 h-10 mx-auto mb-3 text-content-muted/50" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                            No trades found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.table-card>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $trades->links() }}
    </div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    function updateBulkUI() {
        var checked = $('.trade-checkbox:checked').length;
        $('#selectedCount').text(checked);
        if (checked > 0) {
            $('#bulkSettleForm').removeClass('hidden').addClass('flex');
        } else {
            $('#bulkSettleForm').addClass('hidden').removeClass('flex');
        }
    }

    $('#selectAll').on('change', function() {
        $('.trade-checkbox').prop('checked', $(this).prop('checked'));
        updateBulkUI();
    });

    $(document).on('change', '.trade-checkbox', function() {
        updateBulkUI();
    });
});
</script>
@endpush

