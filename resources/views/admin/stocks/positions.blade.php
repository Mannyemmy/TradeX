@extends('layouts.admin-dash')
@section('title', $title)
@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <x-admin.page-header title="Stock Positions" :subtitle="$user->name . ' (' . $user->email . ')'">
        <x-slot name="actions">
            <a href="{{ route('admin.stocks.trades', ['user_id' => $user->id]) }}"
               class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" /></svg>
                User Trades
            </a>
        </x-slot>
    </x-admin.page-header>

    @if(session('success'))
        <x-admin.alert type="success" :dismissible="true">{{ session('success') }}</x-admin.alert>
    @endif
    @if(session('message'))
        <x-admin.alert type="danger" :dismissible="true">{{ session('message') }}</x-admin.alert>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <x-admin.stat-card label="Total Invested" :value="'$' . number_format($totalInvested, 2)" icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' />
        <x-admin.stat-card label="Current Value" :value="'$' . number_format($totalCurrentValue, 2)" icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" /></svg>' />
        <x-admin.stat-card label="Total P&L" :value="($totalPnl >= 0 ? '+$' : '-$') . number_format(abs($totalPnl), 2)" icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75z" /></svg>' />
    </div>

    {{-- Add Position Form --}}
    <x-admin.card>
        <h3 class="text-sm font-semibold text-content mb-4">Add Position (No Balance Debit)</h3>
        <form action="{{ route('admin.stocks.create-position') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-5 gap-4 items-end">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <div>
                <label class="block text-xs text-content-muted mb-1">Stock Asset</label>
                <select name="trading_asset_id" required class="w-full bg-surface text-content border border-border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="">Select stock…</option>
                    @foreach(\App\Models\TradingAsset::active()->ofClass('stock')->orderBy('symbol')->get() as $stock)
                        <option value="{{ $stock->id }}">{{ $stock->symbol }} — {{ $stock->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-content-muted mb-1">Shares</label>
                <input type="number" name="shares" step="0.00000001" min="0.00000001" required class="w-full bg-surface text-content border border-border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label class="block text-xs text-content-muted mb-1">Avg Buy Price ($)</label>
                <input type="number" name="avg_buy_price" step="0.01" min="0.01" required class="w-full bg-surface text-content border border-border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Add Position
                </button>
            </div>
        </form>
        @if($errors->any())
            <div class="mt-3 text-sm text-danger">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </x-admin.card>

    {{-- Positions Table --}}
    <x-admin.table-card title="Holdings">
        <table class="w-full">
            <thead>
                <tr class="bg-surface-alt">
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Stock</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Shares</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Avg Cost</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Current Price</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Invested</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Value</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">P&L</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($positions as $pos)
                    <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors">
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2">
                                @if($pos->asset && $pos->asset->logo_url)
                                    <img src="{{ $pos->asset->logo_url }}" alt="{{ $pos->asset->symbol }}" class="w-7 h-7 rounded object-cover border border-border">
                                @endif
                                <div>
                                    <span class="text-sm font-medium text-content">{{ $pos->asset->symbol ?? 'N/A' }}</span>
                                    <p class="text-xs text-content-muted">{{ $pos->asset->name ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-sm text-content text-right">{{ number_format($pos->shares, 4) }}</td>
                        <td class="px-4 py-3.5 text-sm text-content-secondary text-right">${{ number_format($pos->avg_buy_price, 2) }}</td>
                        <td class="px-4 py-3.5 text-sm text-content text-right">${{ number_format($pos->asset->price ?? 0, 2) }}</td>
                        <td class="px-4 py-3.5 text-sm text-content-secondary text-right">${{ number_format($pos->total_invested, 2) }}</td>
                        <td class="px-4 py-3.5 text-sm text-content text-right font-medium">${{ number_format($pos->current_value, 2) }}</td>
                        <td class="px-4 py-3.5 text-sm text-right font-medium {{ $pos->unrealized_pnl >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $pos->unrealized_pnl >= 0 ? '+' : '' }}${{ number_format($pos->unrealized_pnl, 2) }}
                            <span class="text-xs">({{ $pos->unrealized_pnl_percent >= 0 ? '+' : '' }}{{ number_format($pos->unrealized_pnl_percent, 1) }}%)</span>
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.stocks.edit-position', $pos->id) }}" class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">Edit</a>
                                <form action="{{ route('admin.stocks.delete-position', $pos->id) }}" method="POST" onsubmit="return confirm('Delete this position? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-danger/10 text-danger border border-danger/20 hover:bg-danger/20 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-content-muted">This user has no stock positions.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.table-card>

</div>
@endsection
