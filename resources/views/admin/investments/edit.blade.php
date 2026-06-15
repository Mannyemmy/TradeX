@extends('layouts.admin-dash')
@section('title', $title)

@section('content')
<div class="space-y-6">

    <x-admin.page-header title="Edit Investment #{{ $investment->id }}" subtitle="Update user investment details or backdate the transaction">
        <x-slot name="actions">
            <a href="{{ url()->previous() }}"
               class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Back
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
                <p><span class="font-medium text-content">User:</span> {{ optional($investment->duser)->name }} ({{ optional($investment->duser)->email }})</p>
                <p><span class="font-medium text-content">Plan:</span> {{ optional($investment->dplan)->name }}</p>
            </div>

            <form action="{{ route('admin.investments.update', $investment->id) }}" method="POST" class="space-y-5">
                @csrf

                <x-admin.form-group label="Amount (USD)" for="amount" :required="true">
                    <input type="number" id="amount" name="amount" step="0.01" min="0"
                           value="{{ old('amount', $investment->amount) }}" required
                           class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                </x-admin.form-group>

                <x-admin.form-group label="Status" for="active" :required="true">
                    <select id="active" name="active" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                        <option value="yes" {{ old('active', $investment->active) === 'yes' ? 'selected' : '' }}>Active</option>
                        <option value="no" {{ old('active', $investment->active) === 'no' ? 'selected' : '' }}>Expired / Inactive</option>
                    </select>
                </x-admin.form-group>

                <x-admin.form-group label="Expiry Date" for="expire_date">
                    <input type="datetime-local" id="expire_date" name="expire_date"
                           value="{{ old('expire_date', $investment->expire_date ? \Carbon\Carbon::parse($investment->expire_date)->format('Y-m-d\TH:i') : '') }}"
                           class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                </x-admin.form-group>

                <x-admin.form-group label="Date Created (Backdate)" for="created_at"
                    helper="Change the original transaction date. Leave blank to keep current.">
                    <input type="datetime-local" id="created_at" name="created_at"
                           value="{{ old('created_at', $investment->created_at ? \Carbon\Carbon::parse($investment->created_at)->format('Y-m-d\TH:i') : '') }}"
                           class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                </x-admin.form-group>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-5 py-2.5 text-sm font-medium transition-colors">
                        Save Changes
                    </button>
                    <a href="{{ url()->previous() }}"
                       class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-5 py-2.5 text-sm font-medium transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </x-admin.card>
    </div>

</div>
@endsection
