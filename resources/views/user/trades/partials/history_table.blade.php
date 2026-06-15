{{-- Trade History — Desktop table + Mobile card stack --}}

@if($trades->count() > 0)
    {{-- Desktop Table (hidden on mobile) --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm" role="table">
            <caption class="sr-only">Trade history table showing your binary and spot trades</caption>
            <thead>
                <tr class="border-b border-surface-border">
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Asset</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Type</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Direction</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Amount</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Leverage</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">
                        <span class="hidden lg:inline">Entry</span>
                        <span class="lg:hidden">Entry</span>
                        <x-icon name="arrow-long-right" class="w-3 h-3 inline text-content-tertiary mx-0.5" />
                        <span>Exit</span>
                    </th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Result</th>
                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider">P/L</th>
                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider">Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-border">
                @foreach($trades as $trade)
                    <tr data-trade-id="{{ $trade->id }}"
                        class="hover:bg-surface-overlay/50 transition-colors cursor-pointer group"
                        onclick="window.location='{{ route('user.trades.show', $trade->id) }}'"
                        role="link"
                        tabindex="0"
                        aria-label="View trade {{ $trade->asset_name }}">
                        {{-- Asset --}}
                        <td class="px-4 py-3">
                            @include('user.trades.partials.asset-cell', ['trade' => $trade])
                        </td>
                        {{-- Type / Demo --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <span class="inline-flex items-center px-1.5 py-0.5 text-[10px] font-semibold rounded {{ ($trade->trade_type ?? 'binary') === 'binary' ? 'bg-info/10 text-info' : 'bg-primary/10 text-primary' }}">
                                    {{ ucfirst($trade->trade_type ?? 'binary') }}
                                </span>
                                @if($trade->is_demo)
                                    <span class="inline-flex items-center px-1.5 py-0.5 text-[10px] font-semibold rounded bg-warning/10 text-warning">DEMO</span>
                                @endif
                            </div>
                        </td>
                        {{-- Direction --}}
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1 text-xs font-semibold uppercase {{ $trade->action === 'buy' ? 'text-gain' : 'text-loss' }}">
                                <x-icon name="{{ $trade->action === 'buy' ? 'arrow-trending-up' : 'arrow-trending-down' }}" class="w-3.5 h-3.5" />
                                {{ $trade->action }}
                            </span>
                        </td>
                        {{-- Amount --}}
                        <td class="px-4 py-3 text-content-primary font-medium">@money($trade->amount)</td>
                        {{-- Leverage --}}
                        <td class="px-4 py-3">
                            <span class="px-1.5 py-0.5 text-xs font-medium rounded bg-surface-overlay text-content-secondary">{{ $trade->leverage }}x</span>
                        </td>
                        {{-- Entry → Exit --}}
                        <td class="px-4 py-3 text-xs">
                            @php
                                $entryStr = $trade->entry_price ? \App\Helpers\CurrencyHelper::formatForUser($trade->entry_price) : '—';
                                $exitStr = $trade->exit_price ? \App\Helpers\CurrencyHelper::formatForUser($trade->exit_price) : '—';
                                $priceGain = $trade->exit_price && $trade->entry_price
                                    ? ($trade->action === 'buy'
                                        ? $trade->exit_price >= $trade->entry_price
                                        : $trade->exit_price <= $trade->entry_price)
                                    : null;
                            @endphp
                            <span class="text-content-secondary">{{ $entryStr }}</span>
                            <x-icon name="arrow-long-right" class="w-3 h-3 inline text-content-tertiary mx-0.5" />
                            <span class="{{ $priceGain === true ? 'text-gain' : ($priceGain === false ? 'text-loss' : 'text-content-secondary') }}">{{ $exitStr }}</span>
                        </td>
                        {{-- Status --}}
                        <td class="px-4 py-3 trade-status">
                            @if($trade->status === 'closed')
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-surface-overlay text-content-tertiary">
                                    <span class="w-1.5 h-1.5 rounded-full bg-content-tertiary mr-1.5" aria-hidden="true"></span>
                                    Closed
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-info/10 text-info">
                                    <span class="w-1.5 h-1.5 rounded-full bg-info mr-1.5 animate-pulse" aria-hidden="true"></span>
                                    Open
                                </span>
                            @endif
                        </td>
                        {{-- Result --}}
                        <td class="px-4 py-3 trade-result">
                            @if($trade->result === 'WIN')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded-full bg-gain/10 text-gain">
                                    <x-icon name="arrow-trending-up" class="w-3 h-3" />
                                    WIN
                                </span>
                            @elseif($trade->result === 'LOSS')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded-full bg-loss/10 text-loss">
                                    <x-icon name="arrow-trending-down" class="w-3 h-3" />
                                    LOSS
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded-full bg-warning/10 text-warning">
                                    <x-icon name="clock" class="w-3 h-3" />
                                    PENDING
                                </span>
                            @endif
                        </td>
                        {{-- P/L --}}
                        <td class="px-4 py-3 text-right trade-profit">
                            @include('user.trades.partials.pnl-display', ['value' => $trade->profit_loss])
                        </td>
                        {{-- Time --}}
                        <td class="px-4 py-3 text-right text-xs">
                            @if($trade->status === 'closed')
                                <span class="text-content-tertiary" title="{{ \Carbon\Carbon::parse($trade->created_at)->format('Y-m-d H:i:s') }}">
                                    {{ \Carbon\Carbon::parse($trade->created_at)->diffForHumans() }}
                                </span>
                                @if($trade->settled_by)
                                    <span class="block text-[10px] text-content-tertiary mt-0.5">by {{ ucfirst($trade->settled_by) }}</span>
                                @endif
                            @elseif(($trade->trade_type ?? 'binary') === 'spot')
                                @if($trade->close_requested_at)
                                    <span class="text-warning font-medium">Close Pending</span>
                                @else
                                    <span class="text-content-tertiary italic">No expiry</span>
                                @endif
                            @elseif($trade->expires_at)
                                <span class="countdown-timer text-warning font-medium" data-expiry="{{ \Carbon\Carbon::parse($trade->expires_at)->timestamp }}" aria-live="polite"></span>
                            @else
                                <span class="text-content-tertiary">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards (hidden on desktop) --}}
    <div class="md:hidden divide-y divide-surface-border">
        @foreach($trades as $trade)
            @include('user.trades.partials.trade-card-mobile', ['trade' => $trade])
        @endforeach
    </div>
@else
    @include('user.trades.partials.empty-state', [
        'icon' => 'chart-bar',
        'title' => 'No trades found',
        'message' => request()->hasAny(['type', 'status', 'demo', 'search'])
            ? 'No trades match your current filters. Try adjusting or clearing your filters.'
            : 'You haven\'t placed any trades yet. Start trading to see your history here.',
        'actionUrl' => request()->hasAny(['type', 'status', 'demo', 'search'])
            ? route('user.trades.history')
            : route('trade'),
        'actionLabel' => request()->hasAny(['type', 'status', 'demo', 'search'])
            ? 'Clear Filters'
            : 'Place a Trade',
    ])
@endif
