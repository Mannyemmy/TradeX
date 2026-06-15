@extends('layouts.admin-dash')
@section('title', $title)
@section('content')

<x-admin.page-header title="Create Trading Bot" subtitle="Configure a new AI trading bot">
    <x-slot name="actions">
        <a href="{{ route('admin.bot-trading.index') }}" class="bg-surface-alt text-content border border-border hover:bg-surface-alt/80 rounded-lg px-4 py-2 text-sm font-medium">Cancel</a>
    </x-slot>
</x-admin.page-header>

<form action="{{ route('admin.bot-trading.store') }}" method="POST" class="mt-6">
    @csrf

    @if($errors->any())
        <x-admin.alert type="danger">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-admin.alert>
    @endif

    <x-admin.card>
        {{-- Basic Info --}}
        <fieldset class="mb-8">
            <legend class="text-base font-semibold text-content mb-4">Basic Information</legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-admin.form-group label="Bot Name" for="name" :error="$errors->first('name')" required>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="e.g. Quantum Trader AI" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </x-admin.form-group>

                <x-admin.form-group label="Strategy Type" for="strategy_type" :error="$errors->first('strategy_type')" required>
                    <select name="strategy_type" id="strategy_type" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        <option value="scalping" {{ old('strategy_type') === 'scalping' ? 'selected' : '' }}>Scalping</option>
                        <option value="day_trading" {{ old('strategy_type', 'day_trading') === 'day_trading' ? 'selected' : '' }}>Day Trading</option>
                        <option value="swing" {{ old('strategy_type') === 'swing' ? 'selected' : '' }}>Swing Trading</option>
                    </select>
                </x-admin.form-group>
            </div>

            <div class="mt-5">
                <x-admin.form-group label="Description" for="description" :error="$errors->first('description')">
                    <textarea name="description" id="description" rows="4" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:ring-2 focus:ring-primary/30 focus:border-primary min-h-[120px] resize-y" placeholder="Describe the bot's trading strategy...">{{ old('description') }}</textarea>
                </x-admin.form-group>
            </div>
        </fieldset>

        {{-- Performance Configuration --}}
        <fieldset class="mb-8">
            <legend class="text-base font-semibold text-content mb-4">Performance Configuration</legend>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <x-admin.form-group label="Win Rate (%)" for="win_rate" :error="$errors->first('win_rate')" required helper="Recommended: 60-90%">
                    <input type="number" name="win_rate" id="win_rate" value="{{ old('win_rate', 70) }}" step="0.01" min="1" max="100" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </x-admin.form-group>

                <x-admin.form-group label="Expected Daily ROI (%)" for="expected_roi" :error="$errors->first('expected_roi')" required>
                    <input type="number" name="expected_roi" id="expected_roi" value="{{ old('expected_roi', 2.5) }}" step="0.01" min="0.01" max="100" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </x-admin.form-group>

                <x-admin.form-group label="Trade Interval (minutes)" for="trade_interval_minutes" :error="$errors->first('trade_interval_minutes')" required>
                    <input type="number" name="trade_interval_minutes" id="trade_interval_minutes" value="{{ old('trade_interval_minutes', 5) }}" min="1" max="1440" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </x-admin.form-group>
            </div>
        </fieldset>

        {{-- Investment Limits --}}
        <fieldset class="mb-8">
            <legend class="text-base font-semibold text-content mb-4">Investment Limits</legend>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <x-admin.form-group label="Min Investment ($)" for="min_investment" :error="$errors->first('min_investment')" required>
                    <input type="number" name="min_investment" id="min_investment" value="{{ old('min_investment', 100) }}" step="0.01" min="1" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </x-admin.form-group>

                <x-admin.form-group label="Max Investment ($)" for="max_investment" :error="$errors->first('max_investment')" required>
                    <input type="number" name="max_investment" id="max_investment" value="{{ old('max_investment', 50000) }}" step="0.01" min="1" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </x-admin.form-group>

                <x-admin.form-group label="Max Duration (days)" for="max_duration_days" :error="$errors->first('max_duration_days')" required>
                    <input type="number" name="max_duration_days" id="max_duration_days" value="{{ old('max_duration_days', 90) }}" min="1" max="365" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </x-admin.form-group>
            </div>
        </fieldset>

        {{-- Profit/Loss Ranges --}}
        <fieldset class="mb-8">
            <legend class="text-base font-semibold text-content mb-4">Profit/Loss Ranges (per-trade %)</legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="bg-success-light/30 border border-success/20 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-success mb-3">Profit Range</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <x-admin.form-group label="Min Profit (%)" for="profit_min_pct" :error="$errors->first('profit_min_pct')" required>
                            <input type="number" name="profit_min_pct" id="profit_min_pct" value="{{ old('profit_min_pct', 0.5) }}" step="0.01" min="0.01" max="100" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                        </x-admin.form-group>
                        <x-admin.form-group label="Max Profit (%)" for="profit_max_pct" :error="$errors->first('profit_max_pct')" required>
                            <input type="number" name="profit_max_pct" id="profit_max_pct" value="{{ old('profit_max_pct', 3.0) }}" step="0.01" min="0.01" max="100" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                        </x-admin.form-group>
                    </div>
                </div>
                <div class="bg-danger-light/30 border border-danger/20 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-danger mb-3">Loss Range</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <x-admin.form-group label="Min Loss (%)" for="loss_min_pct" :error="$errors->first('loss_min_pct')" required>
                            <input type="number" name="loss_min_pct" id="loss_min_pct" value="{{ old('loss_min_pct', 0.2) }}" step="0.01" min="0.01" max="100" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                        </x-admin.form-group>
                        <x-admin.form-group label="Max Loss (%)" for="loss_max_pct" :error="$errors->first('loss_max_pct')" required>
                            <input type="number" name="loss_max_pct" id="loss_max_pct" value="{{ old('loss_max_pct', 1.5) }}" step="0.01" min="0.01" max="100" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                        </x-admin.form-group>
                    </div>
                </div>
            </div>
        </fieldset>

        {{-- Status --}}
        <fieldset class="mb-6">
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 rounded border-border text-primary focus:ring-primary/30">
                <label for="is_active" class="text-sm font-medium text-content">Active (visible to users)</label>
            </div>
        </fieldset>

        <div class="flex items-center gap-3 pt-4 border-t border-border">
            <button type="submit" class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-6 py-2.5 text-sm font-medium">Create Bot</button>
            <a href="{{ route('admin.bot-trading.index') }}" class="text-content-secondary hover:text-content text-sm">Cancel</a>
        </div>
    </x-admin.card>
</form>

@endsection
