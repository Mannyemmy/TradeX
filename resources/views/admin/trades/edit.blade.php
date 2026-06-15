@extends('layouts.admin-dash')
@section('title', 'Edit Trade #' . $trade->id)

@section('content')

    <x-admin.page-header title="Edit Trade #{{ $trade->id }}">
        <x-slot name="actions">
            <a href="{{ route('admin.trades.index') }}" class="inline-flex items-center gap-2 bg-secondary-light text-content-secondary rounded-lg px-4 py-2 text-sm font-medium hover:bg-surface-alt transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Back to Trades
            </a>
        </x-slot>
    </x-admin.page-header>

    <x-admin.card class="mt-6 max-w-4xl">
        <form action="{{ route('admin.trades.update', $trade->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Row 1: User, Type, Mode --}}
            <div class="grid grid-cols-1 md:grid-cols-6 gap-5">
                <div class="md:col-span-3">
                    <x-admin.form-group label="User" for="user_id">
                        <select name="user_id" id="user_id"
                                class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $trade->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </x-admin.form-group>
                </div>
                <div class="md:col-span-2">
                    <x-admin.form-group label="Trade Type">
                        <input type="text" value="{{ ucfirst($trade->trade_type ?? 'binary') }}" disabled
                               class="w-full bg-surface-alt border border-border rounded-lg px-3 py-2 text-sm text-content-muted cursor-not-allowed">
                    </x-admin.form-group>
                </div>
                <div class="md:col-span-1">
                    <x-admin.form-group label="Mode">
                        <input type="text" value="{{ $trade->is_demo ? 'Demo' : 'Live' }}" disabled
                               class="w-full bg-surface-alt border border-border rounded-lg px-3 py-2 text-sm text-content-muted cursor-not-allowed">
                    </x-admin.form-group>
                </div>
            </div>

            {{-- Asset Selector --}}
            <x-admin.form-group label="Asset" for="trading_asset_id">
                <select name="trading_asset_id" id="trading_asset_id"
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    <option value="">— Manual (type below) —</option>
                    @php $grouped = $assets->groupBy('asset_class'); @endphp
                    @foreach($grouped as $class => $classAssets)
                        <optgroup label="{{ ucfirst($class) }}">
                            @foreach($classAssets as $asset)
                                <option value="{{ $asset->id }}" {{ $trade->trading_asset_id == $asset->id ? 'selected' : '' }}>
                                    {{ $asset->symbol }} — {{ $asset->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </x-admin.form-group>

            {{-- Asset Type & Name --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-admin.form-group label="Asset Type" for="asset_type" required>
                    <select name="asset_type" id="asset_type" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        @foreach(['crypto', 'forex', 'stock', 'etf', 'index'] as $cls)
                            <option value="{{ $cls }}" {{ $trade->asset_type == $cls ? 'selected' : '' }}>{{ ucfirst($cls) }}</option>
                        @endforeach
                    </select>
                </x-admin.form-group>
                <x-admin.form-group label="Asset Name" for="asset_name" required>
                    <input type="text" name="asset_name" id="asset_name" value="{{ $trade->asset_name }}" required
                           class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </x-admin.form-group>
            </div>

            {{-- Action, Amount, Leverage --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <x-admin.form-group label="Action" for="action">
                    <select name="action" id="action"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        <option value="buy" {{ $trade->action == 'buy' ? 'selected' : '' }}>Buy</option>
                        <option value="sell" {{ $trade->action == 'sell' ? 'selected' : '' }}>Sell</option>
                    </select>
                </x-admin.form-group>
                <x-admin.form-group label="Amount" for="amount" required>
                    <input type="number" name="amount" id="amount" value="{{ $trade->amount }}" step="0.01" required
                           class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </x-admin.form-group>
                <x-admin.form-group label="Leverage" for="leverage" required>
                    <select name="leverage" id="leverage" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        @foreach([2, 5, 10, 25, 50, 100] as $lev)
                            <option value="{{ $lev }}" {{ $trade->leverage == $lev ? 'selected' : '' }}>{{ $lev }}x</option>
                        @endforeach
                    </select>
                </x-admin.form-group>
            </div>

            {{-- Duration, Status, Result --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <x-admin.form-group label="Duration (minutes)" for="duration" required>
                    <input type="number" name="duration" id="duration" value="{{ $trade->duration }}" required
                           class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </x-admin.form-group>
                <x-admin.form-group label="Status" for="status">
                    <select name="status" id="status"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        <option value="open" {{ $trade->status == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ $trade->status == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </x-admin.form-group>
                <x-admin.form-group label="Result" for="result">
                    <select name="result" id="result"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        <option value="PENDING" {{ ($trade->result ?? 'PENDING') == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                        <option value="WIN" {{ $trade->result == 'WIN' ? 'selected' : '' }}>WIN</option>
                        <option value="LOSS" {{ $trade->result == 'LOSS' ? 'selected' : '' }}>LOSS</option>
                    </select>
                </x-admin.form-group>
            </div>

            {{-- Profit/Loss --}}
            <x-admin.form-group label="Profit/Loss (USD)" for="profit_loss"
                helper="{{ $trade->amount > 0 && $trade->leverage > 0 ? 'Suggested P/L (symmetric formula): $' . number_format($trade->amount * ($trade->leverage / 100), 2) : '' }}">
                <input type="number" step="0.01" name="profit_loss" id="profit_loss" value="{{ $trade->profit_loss }}"
                       class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
            </x-admin.form-group>

            {{-- Backdate: Created At --}}
            <x-admin.form-group label="Date Created (Backdate)" for="created_at"
                helper="Change the original transaction date. Leave blank to keep current date.">
                <input type="datetime-local" name="created_at" id="created_at"
                       value="{{ $trade->created_at ? $trade->created_at->format('Y-m-d\TH:i') : '' }}"
                       class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
            </x-admin.form-group>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-primary text-primary-foreground rounded-lg px-6 py-2.5 text-sm font-medium hover:bg-primary-hover transition-colors">
                    Update Trade
                </button>
                <a href="{{ route('admin.trades.index') }}" class="bg-secondary-light text-content-secondary rounded-lg px-6 py-2.5 text-sm font-medium hover:bg-surface-alt transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </x-admin.card>

@endsection
