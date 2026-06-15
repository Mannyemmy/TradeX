@extends('layouts.admin-dash')
@section('title', 'Wallet Connect Settings')

@section('content')
    <x-admin.page-header title="Wallet Connect Settings" subtitle="Configure wallet connection parameters">
        <x-slot name="actions">
            <a href="{{ route('mwalletconnect') }}"
               class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" /></svg>
                Back to Wallets
            </a>
        </x-slot>
    </x-admin.page-header>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert type="success" :dismissible="true" class="mt-6">
            {{ session('success') }}
        </x-admin.alert>
    @endif
    @if (session('error'))
        <x-admin.alert type="danger" :dismissible="true" class="mt-6">
            {{ session('error') }}
        </x-admin.alert>
    @endif

    <div class="mt-6 max-w-2xl mx-auto">
        <x-admin.card>
            <form method="POST" action="{{ url('admin/dashboard/mwalletconnectsave') }}" class="space-y-5">
                @csrf

                <x-admin.form-group label="Min Balance" for="min_balance" :error="$errors->first('min_balance')" :required="true">
                    <input id="min_balance" type="text" name="min_balance"
                           value="{{ $settings->min_balance }}"
                           class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors"
                           placeholder="Enter minimum balance"
                           required>
                </x-admin.form-group>

                <x-admin.form-group label="Return (Profit)" for="min_return" :error="$errors->first('min_return')" :required="true">
                    <input id="min_return" type="text" name="min_return"
                           value="{{ $settings->min_return }}"
                           class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors"
                           placeholder="Enter profit return"
                           required>
                </x-admin.form-group>

                <x-admin.form-group label="Turn On/Off" for="wallet_status" :error="$errors->first('wallet_status')" :required="true">
                    <select id="wallet_status" name="wallet_status"
                            class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors appearance-none"
                            required>
                        <option value="on" {{ $settings->wallet_status == 'on' ? 'selected' : '' }}>On</option>
                        <option value="off" {{ $settings->wallet_status == 'off' ? 'selected' : '' }}>Off</option>
                    </select>
                </x-admin.form-group>

                <div class="pt-2">
                    <button type="submit"
                            class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2.5 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30 inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Update Settings
                    </button>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
