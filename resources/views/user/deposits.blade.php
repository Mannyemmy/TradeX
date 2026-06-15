@extends('layouts.dash1')
@section('title', $title)
@section('content')

    {{-- Alerts --}}
    <x-danger-alert />
    <x-success-alert />
    <x-alert />

    {{-- Ticker + Quick Nav --}}
    @include('user.partials.ticker-tape')
    @include('user.partials.quick-nav')

    @include('user.partials.page-header', ['title' => 'Deposit Funds', 'subtitle' => 'Select a payment method to fund your account'])

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Crypto Deposit Methods --}}
        <div class="bg-surface-raised border border-surface-border rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-surface-border">
                <h3 class="text-base font-semibold text-content-primary">Crypto Deposits</h3>
            </div>
            <div class="divide-y divide-surface-border">
                @foreach ($dmethods as $item)
                <div class="p-5 hover:bg-surface-overlay/50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            @if($item->img_url)
                            <img src="{{ asset($item->img_url) }}" alt="{{ $item->name }}" class="w-10 h-10 rounded-lg object-contain bg-surface-overlay p-1">
                            @else
                            <div class="w-10 h-10 rounded-lg bg-surface-overlay flex items-center justify-center">
                                <x-icon name="banknotes" class="w-5 h-5 text-content-tertiary" />
                            </div>
                            @endif
                            <div>
                                <h4 class="text-sm font-semibold text-content-primary mb-1">{{ $item->name }}</h4>
                                <p class="text-xs text-primary">Upload payment proof for quick verification</p>
                            </div>
                        </div>
                        <button @click="$dispatch('open-deposit-{{ $item->id }}')"
                                class="bg-primary hover:bg-primary-dark text-content-inverse px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Deposit
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Other Deposit Options --}}
        <div class="bg-surface-raised border border-surface-border rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-surface-border">
                <h3 class="text-base font-semibold text-content-primary">Other Deposit Options</h3>
            </div>
            <div class="p-5">
                <div class="bg-surface-overlay border border-surface-border rounded-lg p-4 mb-4">
                    <p class="text-sm text-warning mb-2">Flexible payment methods available</p>
                    <p class="text-xs text-content-secondary leading-relaxed">
                        Once payment is made, send your proof to
                        <a href="mailto:{{ $settings->contact_email }}" class="text-primary hover:text-primary-light">{{ $settings->contact_email }}</a>.
                        You will receive payment details via support email.
                    </p>
                </div>
                <button @click="$dispatch('open-other-deposit')"
                        class="w-full bg-primary hover:bg-primary-dark text-content-inverse py-3 rounded-lg text-sm font-medium transition-colors">
                    Request Deposit
                </button>
            </div>
        </div>
    </div>

@endsection
