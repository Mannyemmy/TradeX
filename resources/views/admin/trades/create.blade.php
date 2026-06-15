@extends('layouts.admin-dash')
@section('title', 'Create Trade')

@section('content')

    <x-admin.page-header title="Create Trade for User">
        <x-slot name="actions">
            <a href="{{ route('admin.trades.index') }}" class="inline-flex items-center gap-2 bg-secondary-light text-content-secondary rounded-lg px-4 py-2 text-sm font-medium hover:bg-surface-alt transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Back to Trades
            </a>
        </x-slot>
    </x-admin.page-header>

    <x-admin.card class="mt-6 max-w-3xl">
        <form action="{{ route('admin.trades.store') }}" method="POST" class="space-y-5">
            @csrf

            <x-admin.form-group label="Select User" for="user_id" required>
                <select name="user_id" id="user_id" required
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </x-admin.form-group>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-admin.form-group label="Trade Type" for="trade_type" required>
                    <select name="trade_type" id="trade_type" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        <option value="binary">Binary</option>
                        <option value="spot">Spot</option>
                    </select>
                </x-admin.form-group>

                <x-admin.form-group label="Mode">
                    <label class="flex items-center gap-2 mt-1.5 cursor-pointer">
                        <input type="checkbox" name="is_demo" value="1"
                               class="rounded border-border-strong text-primary focus:ring-primary/30">
                        <span class="text-sm text-content-secondary">Demo Trade (uses demo balance)</span>
                    </label>
                </x-admin.form-group>
            </div>

            <x-admin.form-group label="Asset" for="trading_asset_id" required>
                <select name="trading_asset_id" id="trading_asset_id" required
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    <option value="">— Select Asset —</option>
                    @php $grouped = $assets->groupBy('asset_class'); @endphp
                    @foreach($grouped as $class => $classAssets)
                        <optgroup label="{{ ucfirst($class) }}">
                            @foreach($classAssets as $asset)
                                <option value="{{ $asset->id }}">{{ $asset->symbol }} — {{ $asset->name }} (${{ number_format($asset->price, 2) }})</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </x-admin.form-group>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-admin.form-group label="Action" for="action" required>
                    <select name="action" id="action" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        <option value="buy">Buy</option>
                        <option value="sell">Sell</option>
                    </select>
                </x-admin.form-group>

                <x-admin.form-group label="Leverage" for="leverage" required>
                    <select name="leverage" id="leverage" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        @foreach([2, 5, 10, 25, 50, 100] as $lev)
                            <option value="{{ $lev }}" {{ $lev == 5 ? 'selected' : '' }}>{{ $lev }}x</option>
                        @endforeach
                    </select>
                </x-admin.form-group>
            </div>

            <div id="durationGroup">
                <x-admin.form-group label="Duration (Binary only)" for="duration">
                    <select name="duration" id="duration"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        @foreach([1 => '1 Minute', 5 => '5 Minutes', 15 => '15 Minutes', 30 => '30 Minutes', 60 => '1 Hour', 240 => '4 Hours', 1440 => '1 Day'] as $val => $label)
                            <option value="{{ $val }}" {{ $val == 15 ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </x-admin.form-group>
            </div>

            <x-admin.form-group label="Amount ($)" for="amount" required>
                <input type="number" step="0.01" name="amount" id="amount" required
                       class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                       placeholder="0.00">
            </x-admin.form-group>

            <div class="pt-2">
                <button type="submit" class="bg-primary text-primary-foreground rounded-lg px-6 py-2.5 text-sm font-medium hover:bg-primary-hover transition-colors">
                    Create Trade
                </button>
            </div>
        </form>
    </x-admin.card>

@endsection

@push('scripts')
<script>
    document.getElementById('trade_type').addEventListener('change', function() {
        document.getElementById('durationGroup').style.display = this.value === 'binary' ? '' : 'none';
    });
</script>
@endpush
