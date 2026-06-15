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
            <h2 class="text-xl font-bold text-content-primary">Trading Signals</h2>
            <p class="text-sm text-content-secondary mt-1">Live trading signals from our expert analysts</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('user.signal.plans') }}" class="px-4 py-2 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-medium transition-colors">Signal Plans</a>
            <a href="{{ route('user.signal.subscriptions') }}" class="px-4 py-2 rounded-lg bg-surface-overlay border border-surface-border text-content-secondary hover:text-content-primary text-sm font-medium transition-colors">My Plans</a>
        </div>
    </div>

    {{-- Signals Table --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden">
        @if($signals->isEmpty())
            <div class="p-8 text-center">
                <x-icon name="signal" class="w-12 h-12 text-content-tertiary mx-auto mb-3" />
                <p class="text-content-secondary">No signals available at the moment.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-surface-border">
                            <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">#</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Signal</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Entry Price</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Take Profit</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Stop Loss</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Leverage</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-border">
                        @foreach($signals as $key => $signal)
                            <tr class="hover:bg-surface-overlay/50 transition-colors">
                                <td class="px-5 py-3 text-content-tertiary">{{ $key + 1 }}</td>
                                <td class="px-5 py-3 font-medium text-content-primary">{{ $signal->name }}</td>
                                <td class="px-5 py-3 text-content-primary">{{ $settings->currency }}{{ number_format($signal->entry_price, 2) }}</td>
                                <td class="px-5 py-3 text-gain">{{ $settings->currency }}{{ number_format($signal->take_profit, 2) }}</td>
                                <td class="px-5 py-3 text-loss">{{ $settings->currency }}{{ number_format($signal->stop_loss, 2) }}</td>
                                <td class="px-5 py-3 text-content-primary">{{ $signal->leverage }}x</td>
                                <td class="px-5 py-3">
                                    @if(strtolower($signal->status) == 'active')
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gain/10 text-gain">{{ $signal->status }}</span>
                                    @elseif(strtolower($signal->status) == 'closed')
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-content-tertiary/10 text-content-tertiary">{{ $signal->status }}</span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-warning/10 text-warning">{{ $signal->status }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-content-tertiary text-xs">{{ \Carbon\Carbon::parse($signal->updated_at)->toDayDateTimeString() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection
