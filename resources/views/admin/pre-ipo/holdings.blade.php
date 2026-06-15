@extends('layouts.admin-dash')
@section('title', $title)
@section('content')
<div class="space-y-6">

    <x-admin.page-header
        title="{{ $company ? 'Holdings: ' . $company->name : 'All Pre-IPO Holdings' }}"
        subtitle="{{ $company ? $company->symbol . ' — ' . number_format($company->shares_sold) . ' shares sold' : 'Cross-company shareholder overview' }}"
    >
        <x-slot name="actions">
            @if($company)
                <a href="{{ route('admin.pre-ipo.show', $company->id) }}"
                   class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" /></svg>
                    Back to Company
                </a>
            @endif
            <a href="{{ route('admin.pre-ipo.index') }}"
               class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6z" /></svg>
                All Companies
            </a>
        </x-slot>
    </x-admin.page-header>

    <x-admin.table-card title="Holdings ({{ $holdings->total() }})">
        <table class="w-full">
            <thead>
                <tr class="bg-surface-alt">
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">User</th>
                    @if(!$company)
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Company</th>
                    @endif
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Shares</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Avg Cost</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Total Invested</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Current Value</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">P/L</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($holdings as $holding)
                    <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors">
                        <td class="px-4 py-3.5">
                            <div class="text-sm font-medium text-content">{{ $holding->user->name ?? 'N/A' }}</div>
                            <div class="text-xs text-content-muted">{{ $holding->user->email ?? '' }}</div>
                        </td>
                        @if(!$company)
                            <td class="px-4 py-3.5">
                                <a href="{{ route('admin.pre-ipo.show', $holding->pre_ipo_company_id) }}" class="text-sm text-primary hover:underline">
                                    {{ $holding->company->name ?? 'N/A' }}
                                </a>
                            </td>
                        @endif
                        <td class="px-4 py-3.5 text-sm font-medium text-content text-right">{{ number_format($holding->shares) }}</td>
                        <td class="px-4 py-3.5 text-sm text-content-secondary text-right">${{ number_format($holding->purchase_price, 2) }}</td>
                        <td class="px-4 py-3.5 text-sm text-content-secondary text-right">${{ number_format($holding->total_cost, 2) }}</td>
                        <td class="px-4 py-3.5 text-sm font-medium text-content text-right">${{ number_format($holding->current_value, 2) }}</td>
                        <td class="px-4 py-3.5 text-sm font-medium text-right {{ $holding->unrealized_pnl >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $holding->unrealized_pnl >= 0 ? '+' : '' }}${{ number_format($holding->unrealized_pnl, 2) }}
                            <span class="text-xs">({{ $holding->unrealized_pnl_percent >= 0 ? '+' : '' }}{{ $holding->unrealized_pnl_percent }}%)</span>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <x-admin.badge :type="$holding->status === 'active' ? 'success' : 'info'">{{ ucfirst($holding->status) }}</x-admin.badge>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $company ? 7 : 8 }}" class="py-12 text-center text-content-muted">
                            No holdings found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($holdings->hasPages())
            <div class="px-4 py-3 border-t border-border">{{ $holdings->links() }}</div>
        @endif
    </x-admin.table-card>

</div>
@endsection
