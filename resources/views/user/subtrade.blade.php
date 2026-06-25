@php
    $sub_link = 'https://trade.mql5.com/trade';
@endphp
@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />

    {{-- Ticker Tape --}}
    @include('user.partials.ticker-tape')

    {{-- Quick Nav --}}
    @include('user.partials.quick-nav')

    {{-- Page Header --}}
    @include('user.partials.page-header', ['title' => 'Trading Accounts', 'subtitle' => 'Manage your trading accounts and subscriptions'])

    {{-- Info Card --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border p-6 mb-6">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                <x-icon name="chart-bar" class="w-5 h-5 text-primary" />
            </div>
            <div>
                <h3 class="text-lg font-bold text-content-primary mb-2">{{ $settings->site_name }} Account Manager</h3>
                <p class="text-sm text-content-secondary mb-3">
                    Don't have time to trade or learn how to trade? Our Account Management Service is the best profitable trading option for you. We can help manage your account in the financial market with a simple subscription model.
                </p>
                <p class="text-xs text-content-tertiary mb-4">Terms and Conditions apply. Reach us at {{ $settings->contact_email }} for more info.</p>
                <button data-toggle="modal" data-target="#submitmt4modal"
                        class="px-4 py-2 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-semibold transition-colors">
                    Subscribe Now
                </button>
            </div>
        </div>
    </div>

    {{-- My Accounts Grid --}}
    <h3 class="text-sm font-semibold text-content-primary mb-4">My Trading Accounts</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        @forelse ($subscriptions as $sub)
            <div class="rounded-xl bg-surface-raised border border-surface-border p-5">
                <h4 class="text-sm font-semibold text-content-primary mb-3">{{ $sub->mt4_id }} / {{ $sub->account_type }}</h4>
                <div class="space-y-2 text-sm">
                    @foreach(['Currency' => $sub->currency, 'Leverage' => $sub->leverage, 'Server' => $sub->server, 'Duration' => $sub->duration, 'Password' => 'xxxxxxx', 'Status' => $sub->status] as $label => $val)
                        <div class="flex justify-between">
                            <span class="text-content-tertiary">{{ $label }}</span>
                            <span class="text-content-primary font-medium">{{ $val }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-3 pt-3 border-t border-surface-border space-y-1 text-xs text-content-tertiary">
                    <p>Submitted: {{ \Carbon\Carbon::parse($sub->created_at)->toDayDateTimeString() }}</p>
                    <p>Started: {{ !empty($sub->start_date) ? \Carbon\Carbon::parse($sub->start_date)->toDayDateTimeString() : 'Not started yet' }}</p>
                    <p>Expire: {{ !empty($sub->end_date) ? \Carbon\Carbon::parse($sub->end_date)->toDayDateTimeString() : 'Not started yet' }}</p>
                </div>

                <div class="mt-4 flex gap-2">
                    <button onclick="deletemt4()" class="px-3 py-1.5 rounded-lg bg-loss/10 text-loss text-xs font-medium hover:bg-loss/20 transition-colors">Cancel</button>
                    @php
                        $remindAt = \Carbon\Carbon::parse($sub->reminded_at);
                    @endphp
                    @if (($sub->status != 'Pending' && now()->isSameDay($remindAt)) || $sub->status == 'Expired')
                        <a href="{{ route('renewsub', $sub->id) }}" class="px-3 py-1.5 rounded-lg bg-gain/10 text-gain text-xs font-medium hover:bg-gain/20 transition-colors">Renew</a>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-xl bg-surface-raised border border-surface-border p-8 text-center">
                <p class="text-content-tertiary">You do not have a trading account at the moment.</p>
            </div>
        @endforelse
    </div>

    {{-- Web Trader --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden">
        <div class="px-5 py-3 border-b border-surface-border">
            <h3 class="text-sm font-semibold text-content-primary">Web Trader</h3>
        </div>
        <iframe src="{{ $sub_link }}" name="WebTrader" title="{{ $title }}" frameborder="0"
                class="w-full" style="height: 76vh;"></iframe>
    </div>

    @include('user.modals')

@endsection

@section('scripts')
@parent
<script>
    function deletemt4() {
        Swal.fire({
            title: 'Cancel Account',
            text: 'Send an email to {{ $settings->contact_email }} to have your MT4 details cancelled.',
            icon: 'info',
            background: '#FFFFFF',
            color: '#0F1B2D',
            confirmButtonColor: '#2E5C8A',
            confirmButtonText: 'Okay'
        });
    }
</script>
@endsection
