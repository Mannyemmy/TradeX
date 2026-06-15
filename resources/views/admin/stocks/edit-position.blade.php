@extends('layouts.admin-dash')
@section('title', $title)
@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <x-admin.page-header title="Edit Position" :subtitle="$position->asset->symbol . ' — ' . $position->user->name">
        <x-slot name="actions">
            <a href="{{ route('admin.stocks.user-positions', $position->user_id) }}"
               class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" /></svg>
                Back to Portfolio
            </a>
        </x-slot>
    </x-admin.page-header>

    @if(session('success'))
        <x-admin.alert type="success" :dismissible="true">{{ session('success') }}</x-admin.alert>
    @endif

    {{-- Current Info --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <x-admin.stat-card label="Current Price" :value="'$' . number_format($position->asset->price ?? 0, 2)" icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' />
        <x-admin.stat-card label="Current Value" :value="'$' . number_format($position->current_value, 2)" icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" /></svg>' />
        <x-admin.stat-card label="Unrealized P&L" :value="($position->unrealized_pnl >= 0 ? '+$' : '-$') . number_format(abs($position->unrealized_pnl), 2)" icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75z" /></svg>' />
        <x-admin.stat-card label="P&L %" :value="number_format($position->unrealized_pnl_percent, 2) . '%'" icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" /></svg>' />
    </div>

    {{-- Edit Form --}}
    <x-admin.card>
        <h3 class="text-sm font-semibold text-content mb-4">Adjust Position</h3>
        <p class="text-xs text-content-muted mb-4">Changes are logged as STOCK_ADMIN_ADJUST in the transaction ledger. This does NOT affect the user's account balance.</p>

        <form action="{{ route('admin.stocks.update-position', $position->id) }}" method="POST" class="space-y-4 max-w-lg">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-medium text-content-muted mb-1">Shares</label>
                <input type="number" name="shares" step="0.00000001" min="0" required
                       value="{{ old('shares', $position->shares) }}"
                       class="w-full bg-surface text-content border border-border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary">
                @error('shares') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-content-muted mb-1">Average Buy Price ($)</label>
                <input type="number" name="avg_buy_price" step="0.00000001" min="0" required
                       value="{{ old('avg_buy_price', $position->avg_buy_price) }}"
                       class="w-full bg-surface text-content border border-border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary">
                @error('avg_buy_price') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-content-muted mb-1">Total Invested ($)</label>
                <input type="number" name="total_invested" step="0.01" min="0" required
                       value="{{ old('total_invested', $position->total_invested) }}"
                       class="w-full bg-surface text-content border border-border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary">
                @error('total_invested') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                    Update Position
                </button>
                <a href="{{ route('admin.stocks.user-positions', $position->user_id) }}" class="text-sm text-content-secondary hover:text-content">Cancel</a>
            </div>
        </form>
    </x-admin.card>

</div>
@endsection
