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
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-content-primary">Signal Plans</h2>
            <p class="text-sm text-content-secondary mt-1">Subscribe to receive premium trading signals</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('user.signal.subscriptions') }}" class="px-4 py-2 rounded-lg bg-surface-overlay border border-surface-border text-content-secondary hover:text-content-primary text-sm font-medium transition-colors">My Plans</a>
            <a href="{{ route('user.signal.index') }}" class="px-4 py-2 rounded-lg bg-surface-overlay border border-surface-border text-content-secondary hover:text-content-primary text-sm font-medium transition-colors">Signals</a>
        </div>
    </div>

    {{-- Balance Card --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border p-4 mb-6 flex items-center gap-3">
        <x-icon name="wallet" class="w-5 h-5 text-primary" />
        <span class="text-sm text-content-secondary">Your Balance:</span>
        <span class="text-sm font-bold text-content-primary">{{ $settings->currency }}{{ number_format(Auth::user()->account_bal, 2) }}</span>
    </div>

    {{-- Plans Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($plans as $plan)
            <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden hover:border-primary/50 transition-colors flex flex-col">
                {{-- Plan Header --}}
                <div class="p-6 text-center border-b border-surface-border">
                    <h3 class="text-lg font-bold text-content-primary mb-2">{{ $plan->name }}</h3>
                    <div>
                        <span class="text-3xl font-bold text-primary">{{ $settings->currency }}{{ number_format($plan->price) }}</span>
                    </div>
                </div>

                {{-- Features --}}
                <div class="p-6 space-y-3 flex-1">
                    <div class="flex items-center gap-2 text-sm">
                        <x-icon name="check-circle" class="w-4 h-4 text-gain flex-shrink-0" />
                        <span class="text-content-secondary">General trading signals</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <x-icon name="check-circle" class="w-4 h-4 text-gain flex-shrink-0" />
                        <span class="text-content-secondary">{{ $plan->features }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <x-icon name="check-circle" class="w-4 h-4 text-gain flex-shrink-0" />
                        <span class="text-content-secondary">24/7 Expert support</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <x-icon name="clock" class="w-4 h-4 text-primary flex-shrink-0" />
                        <span class="text-content-secondary">Duration: {{ $plan->duration }} Weeks</span>
                    </div>
                </div>

                {{-- Subscribe --}}
                <div class="px-6 pb-6">
                    <form id="subscribe-form-{{ $plan->id }}" action="{{ route('user.signal.subscribe') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <button type="button" onclick="confirmSubscription({{ $plan->id }}, {{ $plan->price }})"
                                class="w-full py-2.5 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-semibold transition-colors">
                            Subscribe Now
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

@endsection

@section('scripts')
@parent
<script>
    function confirmSubscription(planId, price) {
        Swal.fire({
            title: 'Confirm Subscription',
            text: 'Subscribe for {{ $settings->currency }}' + price.toLocaleString() + '?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#2A2F36',
            confirmButtonText: 'Yes, Subscribe!',
            cancelButtonText: 'Cancel',
            background: '#161A1E',
            color: '#E8EAED'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('subscribe-form-' + planId).submit();
            }
        });
    }
</script>
@endsection
