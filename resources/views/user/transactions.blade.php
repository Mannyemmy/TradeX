@extends('layouts.dash1')
@section('title', $title)
@section('content')

    {{-- Ticker + Quick Nav --}}
    @include('user.partials.ticker-tape')
    @include('user.partials.quick-nav')

    @include('user.partials.page-header', ['title' => 'Transactions', 'subtitle' => 'View your deposit, withdrawal, and other transaction history'])

    {{-- Tabbed Transaction Card --}}
    <div class="bg-surface-raised border border-surface-border rounded-xl overflow-hidden" x-data="{ tab: 'deposits' }">
        {{-- Tab Headers --}}
        <div class="flex border-b border-surface-border">
            <button @click="tab = 'deposits'" :class="tab === 'deposits' ? 'text-primary border-primary' : 'text-content-tertiary border-transparent hover:text-content-secondary'"
                    class="flex-1 px-4 py-3.5 text-sm font-medium border-b-2 transition-colors text-center">
                Deposits
            </button>
            <button @click="tab = 'withdrawals'" :class="tab === 'withdrawals' ? 'text-primary border-primary' : 'text-content-tertiary border-transparent hover:text-content-secondary'"
                    class="flex-1 px-4 py-3.5 text-sm font-medium border-b-2 transition-colors text-center">
                Withdrawals
            </button>
            <button @click="tab = 'others'" :class="tab === 'others' ? 'text-primary border-primary' : 'text-content-tertiary border-transparent hover:text-content-secondary'"
                    class="flex-1 px-4 py-3.5 text-sm font-medium border-b-2 transition-colors text-center">
                Others
            </button>
        </div>

        {{-- Deposits Tab --}}
        <div x-show="tab === 'deposits'" class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-surface-border">
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Amount</th>
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Payment Mode</th>
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Status</th>
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-border">
                    @forelse ($deposits as $deposit)
                    <tr class="hover:bg-surface-overlay/50 transition-colors">
                        <td class="px-5 py-3.5 text-content-primary font-medium">@money(is_numeric($deposit->amount) ? $deposit->amount : floatval($deposit->amount))</td>
                        <td class="px-5 py-3.5 text-content-secondary">{{ $deposit->payment_mode }}</td>
                        <td class="px-5 py-3.5">
                            @if ($deposit->status == 'Processed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gain/10 text-gain">{{ $deposit->status }}</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">{{ $deposit->status }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-content-tertiary text-xs">{{ \Carbon\Carbon::parse($deposit->created_at)->toDayDateTimeString() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-content-tertiary">No deposit records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Withdrawals Tab --}}
        <div x-show="tab === 'withdrawals'" x-cloak class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-surface-border">
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Amount</th>
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">With Charges</th>
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Mode</th>
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Status</th>
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-border">
                    @forelse ($withdrawals as $withdrawal)
                    <tr class="hover:bg-surface-overlay/50 transition-colors">
                        <td class="px-5 py-3.5 text-content-primary font-medium">@money($withdrawal->amount)</td>
                        <td class="px-5 py-3.5 text-content-secondary">@money($withdrawal->to_deduct)</td>
                        <td class="px-5 py-3.5 text-content-secondary">{{ $withdrawal->payment_mode }}</td>
                        <td class="px-5 py-3.5">
                            @if ($withdrawal->status == 'Processed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gain/10 text-gain">{{ $withdrawal->status }}</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">{{ $withdrawal->status }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-content-tertiary text-xs">{{ \Carbon\Carbon::parse($withdrawal->created_at)->toDayDateTimeString() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-content-tertiary">No withdrawal records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Others Tab --}}
        <div x-show="tab === 'others'" x-cloak class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-surface-border">
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Amount</th>
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Type</th>
                        <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Plan / Narration</th>
                        {{-- <th class="text-left text-xs font-medium text-content-tertiary uppercase tracking-wider px-5 py-3">Date</th> --}}
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-border">
                    @forelse ($t_history as $history)
                    <tr class="hover:bg-surface-overlay/50 transition-colors">
                        <td class="px-5 py-3.5 text-content-primary font-medium">@money($history->amount)</td>
                        <td class="px-5 py-3.5 text-content-secondary">{{ $history->type }}</td>
                        <td class="px-5 py-3.5 text-content-secondary">{{ $history->plan }}</td>
                        {{-- <td class="px-5 py-3.5 text-content-tertiary text-xs">{{ \Carbon\Carbon::parse($history->created_at)->toDayDateTimeString() }}</td> --}}
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-content-tertiary">No other transactions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
