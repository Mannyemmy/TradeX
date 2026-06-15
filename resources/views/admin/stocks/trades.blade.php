@extends('layouts.admin-dash')
@section('title', $title)
@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <x-admin.page-header title="All Stock Trades" subtitle="View all user stock buy/sell activity">
        <x-slot name="actions">
            <a href="{{ route('admin.stocks.index') }}"
               class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" /></svg>
                Back to Stocks
            </a>
        </x-slot>
    </x-admin.page-header>

    @if(session('success'))
        <x-admin.alert type="success" :dismissible="true">{{ session('success') }}</x-admin.alert>
    @endif

    {{-- Filter --}}
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.stocks.trades') }}"
           class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors {{ $filter === 'all' ? 'bg-primary text-primary-foreground' : 'bg-surface-alt text-content-secondary hover:text-content' }}">
            All
        </a>
        <a href="{{ route('admin.stocks.trades', ['type' => 'buy']) }}"
           class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors {{ $filter === 'buy' ? 'bg-success text-white' : 'bg-surface-alt text-content-secondary hover:text-content' }}">
            Buys
        </a>
        <a href="{{ route('admin.stocks.trades', ['type' => 'sell']) }}"
           class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors {{ $filter === 'sell' ? 'bg-danger text-white' : 'bg-surface-alt text-content-secondary hover:text-content' }}">
            Sells
        </a>
    </div>

    {{-- Trades Table --}}
    <x-admin.table-card title="Trade History">
        <table class="w-full">
            <thead>
                <tr class="bg-surface-alt">
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">User</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Stock</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-center">Type</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Shares</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Price</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Total</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Fee</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Date</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trades as $trade)
                    <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors">
                        <td class="px-4 py-3.5">
                            <div>
                                <span class="text-sm font-medium text-content">{{ $trade->user->name ?? 'N/A' }}</span>
                                <p class="text-xs text-content-muted">{{ $trade->user->email ?? '' }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2">
                                @if($trade->asset && $trade->asset->logo_url)
                                    <img src="{{ $trade->asset->logo_url }}" alt="{{ $trade->asset->symbol }}" class="w-6 h-6 rounded object-cover">
                                @endif
                                <span class="text-sm font-medium text-content">{{ $trade->asset->symbol ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <x-admin.badge :type="$trade->type === 'buy' ? 'success' : 'danger'">{{ strtoupper($trade->type) }}</x-admin.badge>
                        </td>
                        <td class="px-4 py-3.5 text-sm text-content text-right">{{ number_format($trade->shares, 4) }}</td>
                        <td class="px-4 py-3.5 text-sm text-content-secondary text-right">${{ number_format($trade->price_per_share, 2) }}</td>
                        <td class="px-4 py-3.5 text-sm text-content text-right font-medium">${{ number_format($trade->total_amount, 2) }}</td>
                        <td class="px-4 py-3.5 text-sm text-content-muted text-right">${{ number_format($trade->fee_amount, 2) }}</td>
                        <td class="px-4 py-3.5 text-sm text-content-muted text-right">{{ $trade->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-4 py-3.5 text-right">
                            <a href="{{ route('admin.stocks.user-positions', $trade->user_id) }}" class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">Portfolio</a>
                            <a href="{{ route('admin.stocks.edit-trade', $trade->id) }}" class="bg-warning text-content-inverse hover:bg-warning/80 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors inline-flex items-center gap-1" title="Edit / Backdate">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" /></svg>
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-12 text-center text-content-muted">No stock trades found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($trades->hasPages())
            <div class="px-4 py-3 border-t border-border">
                {{ $trades->withQueryString()->links() }}
            </div>
        @endif
    </x-admin.table-card>

</div>
@endsection
