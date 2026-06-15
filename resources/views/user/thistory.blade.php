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
    @include('user.partials.page-header', ['title' => 'Transaction History', 'subtitle' => 'View all your trade transaction records'])

    {{-- History Table --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden">
        <div class="px-5 py-3 border-b border-surface-border flex items-center gap-2">
            <x-icon name="document-text" class="w-5 h-5 text-primary" />
            <h3 class="text-sm font-semibold text-content-primary">History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-surface-border">
                        <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Asset</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Amount</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Type</th>
                        {{-- <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Date</th> --}}
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-border">
                    @foreach ($t_history as $history)
                        <tr class="hover:bg-surface-overlay/50 transition-colors">
                            <td class="px-5 py-3 text-content-primary font-medium">{{ $history->plan }}</td>
                            <td class="px-5 py-3 text-content-primary">{{ $settings->currency }}{{ number_format($history->amount, 2, '.', ',') }}</td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full
                                    @if(strtolower($history->type) == 'profit' || strtolower($history->type) == 'win') bg-gain/10 text-gain
                                    @elseif(strtolower($history->type) == 'loss') bg-loss/10 text-loss
                                    @else bg-surface-overlay text-content-secondary @endif">
                                    {{ $history->type }}
                                </span>
                            </td>
                            {{-- <td class="px-5 py-3 text-content-tertiary text-xs">{{ \Carbon\Carbon::parse($history->created_at)->toDayDateTimeString() }}</td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if(method_exists($t_history, 'links'))
            <div class="px-5 py-3 border-t border-surface-border">
                {{ $t_history->links() }}
            </div>
        @endif
    </div>

@endsection
