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
            <h2 class="text-xl font-bold text-content-primary">Swap History</h2>
            <p class="text-sm text-content-secondary mt-1">Your crypto swap transaction history</p>
        </div>
        <a href="{{ route('assetbalance') }}" class="px-4 py-2 rounded-lg bg-surface-overlay border border-surface-border text-content-secondary hover:text-content-primary text-sm font-medium transition-colors">
            <x-icon name="arrow-left" class="w-4 h-4 inline-block mr-1" />
            Back
        </a>
    </div>

    {{-- Transactions Table --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-surface-border">
                        <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Source</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Destination</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Amount (src)</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Quantity (dest)</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-border">
                    @forelse($transactions as $tran)
                        <tr class="hover:bg-surface-overlay/50 transition-colors">
                            <td class="px-5 py-3 font-medium text-content-primary">{{ $tran->source }}</td>
                            <td class="px-5 py-3 text-content-primary">{{ $tran->dest }}</td>
                            <td class="px-5 py-3 text-content-primary">{{ round(number_format($tran->amount, 2, '.', ','), 6) }}</td>
                            <td class="px-5 py-3 text-content-primary">{{ round($tran->quantity, 6) }}</td>
                            <td class="px-5 py-3 text-content-tertiary text-xs">{{ \Carbon\Carbon::parse($tran->created_at)->toDayDateTimeString() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-content-tertiary">No record available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-surface-border">{{ $transactions->links() }}</div>
    </div>

@endsection
