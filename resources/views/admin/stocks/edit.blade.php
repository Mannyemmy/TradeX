@extends('layouts.admin-dash')
@section('title', $title)

@section('content')
<div class="space-y-6">

    <x-admin.page-header title="Edit Stock Trade #{{ $trade->id }}" subtitle="Update stock trade details or backdate the transaction">
        <x-slot name="actions">
            <a href="{{ route('admin.stocks.trades') }}"
               class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Back to Trades
            </a>
        </x-slot>
    </x-admin.page-header>

    @if(session('success'))
        <x-admin.alert type="success" :dismissible="true">{{ session('success') }}</x-admin.alert>
    @endif
    @if($errors->any())
        <x-admin.alert type="danger" :dismissible="true">
            <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </x-admin.alert>
    @endif

    <div class="max-w-xl">
        <x-admin.card>
            <div class="mb-4 p-3 bg-surface-alt rounded-lg text-sm text-content-secondary space-y-1">
                <p><span class="font-medium text-content">User:</span> {{ optional($trade->user)->name }} ({{ optional($trade->user)->email }})</p>
                <p><span class="font-medium text-content">Stock:</span> {{ optional($trade->asset)->symbol }} — {{ optional($trade->asset)->name }}</p>
                <p><span class="font-medium text-content">Type:</span> <span class="{{ $trade->type === 'buy' ? 'text-success' : 'text-danger' }} font-semibold uppercase">{{ $trade->type }}</span></p>
            </div>

            <form action="{{ route('admin.stocks.update-trade', $trade->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-admin.form-group label="Shares" for="shares" :required="true">
                        <input type="number" id="shares" name="shares" step="0.0001" min="0.0001"
                               value="{{ old('shares', $trade->shares) }}" required
                               class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                    </x-admin.form-group>

                    <x-admin.form-group label="Price Per Share (USD)" for="price_per_share" :required="true">
                        <input type="number" id="price_per_share" name="price_per_share" step="0.01" min="0"
                               value="{{ old('price_per_share', $trade->price_per_share) }}" required
                               class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                    </x-admin.form-group>
                </div>

                <x-admin.form-group label="Total Amount (USD)" for="total_amount" :required="true">
                    <input type="number" id="total_amount" name="total_amount" step="0.01" min="0"
                           value="{{ old('total_amount', $trade->total_amount) }}" required
                           class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                </x-admin.form-group>

                <x-admin.form-group label="Status" for="status" :required="true">
                    <select id="status" name="status" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                        <option value="completed" {{ old('status', $trade->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ old('status', $trade->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="cancelled" {{ old('status', $trade->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </x-admin.form-group>

                <x-admin.form-group label="Date Created (Backdate)" for="created_at"
                    helper="Change the original transaction date. Leave blank to keep current.">
                    <input type="datetime-local" id="created_at" name="created_at"
                           value="{{ old('created_at', $trade->created_at ? $trade->created_at->format('Y-m-d\TH:i') : '') }}"
                           class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                </x-admin.form-group>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-5 py-2.5 text-sm font-medium transition-colors">
                        Save Changes
                    </button>
                    <a href="{{ route('admin.stocks.trades') }}"
                       class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-5 py-2.5 text-sm font-medium transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </x-admin.card>
    </div>

</div>
@endsection
