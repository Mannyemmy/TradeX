@extends('layouts.admin-dash')
@section('title', $title)
@section('content')

<x-admin.page-header title="Edit Subscription #{{ $subscription->id }}" subtitle="Update subscription details and backdate created date">
    <x-slot name="actions">
        <a href="{{ route('admin.bot-trading.subscription', $subscription->id) }}"
           class="bg-surface-alt text-content border border-border hover:bg-surface-alt/80 rounded-lg px-4 py-2 text-sm font-medium inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Back
        </a>
    </x-slot>
</x-admin.page-header>

@if(session('success'))
    <x-admin.alert type="success" :dismissible="true">{{ session('success') }}</x-admin.alert>
@endif
@if($errors->any())
    <x-admin.alert type="danger" :dismissible="true">{{ $errors->first() }}</x-admin.alert>
@endif

<div class="mt-6 max-w-2xl">
    <x-admin.card>

        {{-- Info block --}}
        <div class="mb-6 p-4 bg-surface-alt rounded-lg space-y-1 text-sm">
            <p><span class="font-medium text-content">User:</span>
               <span class="text-content-secondary">{{ $subscription->user->name ?? 'N/A' }} — {{ $subscription->user->email ?? '' }}</span></p>
            <p><span class="font-medium text-content">Bot:</span>
               <span class="text-content-secondary">{{ $subscription->tradingBot->name ?? 'Deleted' }}</span></p>
            <p><span class="font-medium text-content">Current Status:</span>
               <span class="text-content-secondary">{{ ucfirst($subscription->status) }}</span></p>
            <p><span class="font-medium text-content">Subscribed On:</span>
               <span class="text-content-secondary">{{ $subscription->created_at->format('M d, Y H:i') }}</span></p>
        </div>

        <form action="{{ route('admin.bot-trading.subscription-update', $subscription->id) }}" method="POST" class="space-y-5">
            @csrf

            <x-admin.form-group label="Invested Amount (USD)" for="invested_amount" :required="true">
                <input type="number" step="0.01" min="0" name="invested_amount" id="invested_amount"
                       value="{{ old('invested_amount', $subscription->invested_amount) }}"
                       class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary" required>
            </x-admin.form-group>

            <x-admin.form-group label="Status" for="status" :required="true">
                <select name="status" id="status"
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                    @foreach(['active','stopped','completed','settled'] as $s)
                        <option value="{{ $s }}" {{ old('status', $subscription->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </x-admin.form-group>

            <x-admin.form-group label="Started At" for="started_at" helper="Leave blank to keep current value.">
                <input type="datetime-local" name="started_at" id="started_at"
                       value="{{ old('started_at', $subscription->started_at ? $subscription->started_at->format('Y-m-d\TH:i') : '') }}"
                       class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
            </x-admin.form-group>

            <x-admin.form-group label="Expires At" for="expires_at" helper="Leave blank to keep current value.">
                <input type="datetime-local" name="expires_at" id="expires_at"
                       value="{{ old('expires_at', $subscription->expires_at ? $subscription->expires_at->format('Y-m-d\TH:i') : '') }}"
                       class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
            </x-admin.form-group>

            <x-admin.form-group label="Date Created (Backdate)" for="created_at"
                helper="Change the original subscription date. Leave blank to keep current.">
                <input type="datetime-local" name="created_at" id="created_at"
                       value="{{ old('created_at', $subscription->created_at ? $subscription->created_at->format('Y-m-d\TH:i') : '') }}"
                       class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
            </x-admin.form-group>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.bot-trading.subscription', $subscription->id) }}"
                   class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-primary hover:bg-primary-hover text-primary-foreground rounded-lg px-5 py-2 text-sm font-medium transition-colors">
                    Save Changes
                </button>
            </div>
        </form>
    </x-admin.card>
</div>

@endsection
