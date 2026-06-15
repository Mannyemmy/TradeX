@extends('layouts.admin-dash')

@section('title', $title)

@section('content')
    <div class="space-y-6">
        {{-- Page Header --}}
        <x-admin.page-header :title="$title" subtitle="Configure loan plan parameters">
            <x-slot name="actions">
                <a href="{{ route('admin.loans.index', ['tab' => 'plans']) }}"
                   class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                    Back to Plans
                </a>
            </x-slot>
        </x-admin.page-header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <x-admin.alert type="success" :dismissible="true">{{ session('success') }}</x-admin.alert>
        @endif
        @if(session('error'))
            <x-admin.alert type="danger" :dismissible="true">{{ session('error') }}</x-admin.alert>
        @endif
        @if($errors->any())
            <x-admin.alert type="danger" :dismissible="true">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-admin.alert>
        @endif

        {{-- Form --}}
        <div class="max-w-3xl">
            <x-admin.card>
                <form action="{{ $plan ? route('admin.loan-plans.update', $plan) : route('admin.loan-plans.store') }}" method="POST" class="space-y-6">
                    @csrf
                    @if($plan) @method('PUT') @endif

                    <x-admin.form-group label="Plan Name" for="name" :required="true">
                        <input type="text" id="name" name="name" value="{{ old('name', $plan->name ?? '') }}"
                               class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors"
                               required>
                    </x-admin.form-group>

                    <x-admin.form-group label="Description" for="description">
                        <textarea id="description" name="description" rows="3"
                                  class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors min-h-[80px] resize-y">{{ old('description', $plan->description ?? '') }}</textarea>
                    </x-admin.form-group>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-admin.form-group label="Minimum Amount ({{ $settings->currency }})" for="min_amount" :required="true">
                            <input type="number" step="0.01" id="min_amount" name="min_amount" value="{{ old('min_amount', $plan->min_amount ?? '100') }}"
                                   class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors"
                                   required>
                        </x-admin.form-group>

                        <x-admin.form-group label="Maximum Amount ({{ $settings->currency }})" for="max_amount" :required="true">
                            <input type="number" step="0.01" id="max_amount" name="max_amount" value="{{ old('max_amount', $plan->max_amount ?? '50000') }}"
                                   class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors"
                                   required>
                        </x-admin.form-group>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-admin.form-group label="Interest Rate (APR %)" for="interest_rate" :required="true">
                            <input type="number" step="0.01" id="interest_rate" name="interest_rate" value="{{ old('interest_rate', $plan->interest_rate ?? '5') }}"
                                   class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors"
                                   required>
                        </x-admin.form-group>

                        <x-admin.form-group label="Interest Type" for="interest_type" :required="true">
                            <select id="interest_type" name="interest_type"
                                    class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors appearance-none"
                                    required>
                                <option value="simple" {{ old('interest_type', $plan->interest_type ?? '') === 'simple' ? 'selected' : '' }}>Simple</option>
                                <option value="compound" {{ old('interest_type', $plan->interest_type ?? '') === 'compound' ? 'selected' : '' }}>Compound</option>
                            </select>
                        </x-admin.form-group>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-admin.form-group label="Min Duration (months)" for="min_duration" :required="true">
                            <input type="number" id="min_duration" name="min_duration" value="{{ old('min_duration', $plan->min_duration ?? '1') }}"
                                   class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors"
                                   required>
                        </x-admin.form-group>

                        <x-admin.form-group label="Max Duration (months)" for="max_duration" :required="true">
                            <input type="number" id="max_duration" name="max_duration" value="{{ old('max_duration', $plan->max_duration ?? '60') }}"
                                   class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors"
                                   required>
                        </x-admin.form-group>
                    </div>

                    {{-- Eligibility Section --}}
                    <div class="border-t border-border pt-5">
                        <h3 class="text-lg font-semibold text-content mb-4">Eligibility & Limits</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <x-admin.form-group label="Max Active Loans Per User" for="max_active_loans" :required="true">
                                <input type="number" id="max_active_loans" name="max_active_loans" value="{{ old('max_active_loans', $plan->max_active_loans ?? '1') }}"
                                       class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors"
                                       required>
                            </x-admin.form-group>

                            <x-admin.form-group label="Min Account Balance ({{ $settings->currency }})" for="min_account_balance" :required="true">
                                <input type="number" step="0.01" id="min_account_balance" name="min_account_balance" value="{{ old('min_account_balance', $plan->min_account_balance ?? '0') }}"
                                       class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors"
                                       required>
                            </x-admin.form-group>
                        </div>
                    </div>

                    {{-- Fees Section --}}
                    <div class="border-t border-border pt-5">
                        <h3 class="text-lg font-semibold text-content mb-4">Fees & Penalties</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <x-admin.form-group label="Processing Fee (%)" for="processing_fee" :required="true">
                                <input type="number" step="0.01" id="processing_fee" name="processing_fee" value="{{ old('processing_fee', $plan->processing_fee ?? '0') }}"
                                       class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors"
                                       required>
                            </x-admin.form-group>

                            <x-admin.form-group label="Grace Period (days)" for="grace_period_days" :required="true">
                                <input type="number" id="grace_period_days" name="grace_period_days" value="{{ old('grace_period_days', $plan->grace_period_days ?? '0') }}"
                                       class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors"
                                       required>
                            </x-admin.form-group>

                            <x-admin.form-group label="Late Fee (%)" for="late_fee_percentage" :required="true">
                                <input type="number" step="0.01" id="late_fee_percentage" name="late_fee_percentage" value="{{ old('late_fee_percentage', $plan->late_fee_percentage ?? '0') }}"
                                       class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors"
                                       required>
                            </x-admin.form-group>
                        </div>
                    </div>

                    {{-- Collateral Section --}}
                    <div class="border-t border-border pt-5">
                        <h3 class="text-lg font-semibold text-content mb-4">Collateral</h3>

                        <div class="flex items-center gap-3 mb-4">
                            <input type="checkbox" name="requires_collateral" value="1" id="collateralCheck"
                                   class="w-4 h-4 rounded border-border text-primary focus:ring-primary/30"
                                {{ old('requires_collateral', $plan->requires_collateral ?? false) ? 'checked' : '' }}>
                            <label for="collateralCheck" class="text-sm font-medium text-content">Requires Collateral</label>
                        </div>

                        <x-admin.form-group label="Collateral Percentage (%)" for="collateral_percentage" helper="Percentage of loan amount frozen from user's balance">
                            <input type="number" step="0.01" id="collateral_percentage" name="collateral_percentage" value="{{ old('collateral_percentage', $plan->collateral_percentage ?? '') }}"
                                   class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                        </x-admin.form-group>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-3 pt-2 border-t border-border">
                        <button type="submit"
                                class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30">
                            {{ $plan ? 'Update Plan' : 'Create Plan' }}
                        </button>
                        <a href="{{ route('admin.loans.index', ['tab' => 'plans']) }}"
                           class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </x-admin.card>
        </div>
    </div>
@endsection
