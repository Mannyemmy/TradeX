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
            <h2 class="text-xl font-bold text-content-primary">My Signal Subscriptions</h2>
            <p class="text-sm text-content-secondary mt-1">View your active signal plan subscriptions</p>
        </div>
        <a href="{{ route('user.signal.plans') }}" class="px-4 py-2 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-medium transition-colors">Browse Plans</a>
    </div>

    {{-- Subscriptions Table --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden">
        @if($subscriptions->isEmpty())
            <div class="p-8 text-center">
                <x-icon name="signal" class="w-12 h-12 text-content-tertiary mx-auto mb-3" />
                <p class="text-content-secondary mb-3">You have no active subscriptions.</p>
                <a href="{{ route('user.signal.plans') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-medium transition-colors">
                    Browse Plans
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-surface-border">
                            <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">#</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Plan</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Price</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Expires</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-border">
                        @foreach($subscriptions as $key => $subscription)
                            <tr class="hover:bg-surface-overlay/50 transition-colors">
                                <td class="px-5 py-3 text-content-tertiary">{{ $key + 1 }}</td>
                                <td class="px-5 py-3 font-medium text-content-primary">{{ $subscription->plan->name }}</td>
                                <td class="px-5 py-3 text-content-primary">{{ $settings->currency }}{{ number_format($subscription->plan->price, 2) }}</td>
                                <td class="px-5 py-3">
                                    @if(\Carbon\Carbon::parse($subscription->expires_at)->gt(now()))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gain/10 text-gain">Active</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-loss/10 text-loss">Expired</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-content-tertiary text-xs">{{ \Carbon\Carbon::parse($subscription->expires_at)->toDayDateTimeString() }}</td>
                                <td class="px-5 py-3">
                                    <form id="renew-form-{{ $subscription->id }}" action="{{ route('renewsignals', $subscription->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="button" onclick="confirmRenewal({{ $subscription->id }}, '{{ $subscription->plan->name }}', {{ $subscription->plan->price }})"
                                                class="px-3 py-1.5 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-xs font-medium transition-colors">
                                            Renew
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection

@section('scripts')
@parent
<script>
    function confirmRenewal(subscriptionId, planName, price) {
        Swal.fire({
            title: 'Renew Subscription',
            text: 'Renew ' + planName + ' for {{ $settings->currency }}' + price.toLocaleString() + '?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#2A2F36',
            confirmButtonText: 'Yes, Renew!',
            cancelButtonText: 'Cancel',
            background: '#161A1E',
            color: '#E8EAED'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('renew-form-' + subscriptionId).submit();
            }
        });
    }
</script>
@endsection
