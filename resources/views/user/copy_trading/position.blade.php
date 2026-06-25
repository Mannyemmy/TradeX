@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />
    <x-error-alert />

    @include('user.partials.ticker-tape')
    @include('user.partials.quick-nav')

    {{-- Back Link --}}
    <div class="mb-4">
        <a href="{{ route('copyTrading') }}" class="inline-flex items-center gap-1.5 text-sm text-content-secondary hover:text-primary transition-colors">
            <x-icon name="arrow-left" class="w-4 h-4" />
            Back to Copy Trading
        </a>
    </div>

    @include('user.partials.page-header', ['title' => 'Position Details', 'subtitle' => 'Tracking your copy of ' . ($position->expert->name ?? 'Unknown Expert')])

    {{-- Position Header Card --}}
    <div class="bg-surface-raised border border-surface-border rounded-xl p-5 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                @if($position->expert && $position->expert->profile_picture)
                    <img src="{{ asset('storage/app/public/' . $position->expert->profile_picture) }}" class="w-12 h-12 rounded-full object-cover">
                @else
                    <div class="w-12 h-12 rounded-full bg-surface-overlay flex items-center justify-center">
                        <x-icon name="user" class="w-6 h-6 text-content-tertiary" />
                    </div>
                @endif
                <div>
                    <h3 class="font-semibold text-content-primary">{{ $position->expert->name ?? 'Unknown' }}</h3>
                    <a href="{{ route('copyTrading.expert', $position->expert_id) }}" class="text-xs text-primary hover:text-primary-dark">View Expert Profile</a>
                </div>
            </div>
            @php
                $statusClass = match($position->status) {
                    'active' => 'bg-gain/10 text-gain',
                    'stopped' => 'bg-loss/10 text-loss',
                    'completed' => 'bg-primary-subtle text-primary',
                    'settled' => 'bg-warning/10 text-warning',
                    default => 'bg-surface-overlay text-content-tertiary',
                };
            @endphp
            <span class="text-xs px-2.5 py-1 rounded-full {{ $statusClass }}">{{ ucfirst($position->status) }}</span>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-5">
            <div class="bg-surface-overlay rounded-lg p-3 text-center">
                <p class="text-xs text-content-tertiary mb-0.5">Invested</p>
                <p class="text-sm font-bold text-content-primary">@money($position->invested_amount)</p>
            </div>
            <div class="bg-surface-overlay rounded-lg p-3 text-center">
                <p class="text-xs text-content-tertiary mb-0.5">Profit</p>
                <p class="text-sm font-bold text-gain">@money($position->accumulated_profit)</p>
            </div>
            <div class="bg-surface-overlay rounded-lg p-3 text-center">
                <p class="text-xs text-content-tertiary mb-0.5">Daily ROI</p>
                <p class="text-sm font-bold text-gain">{{ number_format($position->daily_roi_snapshot, 2) }}%</p>
            </div>
            <div class="bg-surface-overlay rounded-lg p-3 text-center">
                <p class="text-xs text-content-tertiary mb-0.5">Total Payout</p>
                <p class="text-sm font-bold text-content-primary">@money($position->totalPayout())</p>
            </div>
            <div class="bg-surface-overlay rounded-lg p-3 text-center">
                <p class="text-xs text-content-tertiary mb-0.5">Started</p>
                <p class="text-sm font-bold text-content-primary">{{ $position->started_at->format('M d, Y') }}</p>
            </div>
            <div class="bg-surface-overlay rounded-lg p-3 text-center">
                <p class="text-xs text-content-tertiary mb-0.5">Expires</p>
                <p class="text-sm font-bold text-content-primary">{{ $position->expires_at->format('M d, Y') }}</p>
            </div>
        </div>

        {{-- Progress Bar --}}
        @php
            $totalDays = $position->started_at->diffInDays($position->expires_at);
            $elapsed = $position->started_at->diffInDays(now());
            $pct = $totalDays > 0 ? min(100, round(($elapsed / $totalDays) * 100)) : 100;
            $remaining = max(0, $totalDays - $elapsed);
        @endphp
        <div class="mb-5">
            <div class="flex items-center justify-between text-xs text-content-tertiary mb-1.5">
                <span>Day {{ min($elapsed, $totalDays) }} of {{ $totalDays }}</span>
                <span>{{ $pct }}% complete &mdash; {{ $remaining }} days remaining</span>
            </div>
            <div class="bg-surface-overlay rounded-full h-2.5">
                <div class="bg-primary rounded-full h-2.5 transition-all" style="width: {{ $pct }}%"></div>
            </div>
        </div>

        {{-- Action --}}
        @if($position->status === 'active')
            <form id="stop-position-form" action="{{ route('copyTrading.stop', $position->id) }}" method="POST">
                @csrf
                <button type="button" id="stopPositionBtn" class="py-2 px-6 rounded-lg bg-loss/10 hover:bg-loss/20 text-loss text-sm font-semibold transition-colors">
                    Stop Copying
                </button>
            </form>
        @endif

        @if($position->settled_at)
            <p class="text-xs text-content-tertiary mt-3">
                Settled on {{ $position->settled_at->format('M d, Y H:i') }} by {{ $position->settled_by ?? 'system' }}
                @if($position->admin_profit_adjustment)
                    &mdash; Admin adjustment: @money($position->admin_profit_adjustment)
                @endif
            </p>
        @endif
    </div>

    {{-- Simulated Trades Table --}}
    <div class="bg-surface-raised border border-surface-border rounded-xl overflow-hidden">
        <div class="p-5 border-b border-surface-border flex items-center justify-between">
            <h3 class="text-base font-semibold text-content-primary"> Trades</h3>
            <span class="text-xs text-content-tertiary">{{ $trades->total() }} trades</span>
        </div>
        @if($trades->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-content-tertiary text-xs border-b border-surface-border">
                            <th class="text-left px-5 py-3 font-medium">Asset</th>
                            <th class="text-left px-5 py-3 font-medium">Class</th>
                            <th class="text-left px-5 py-3 font-medium">Action</th>
                            <th class="text-right px-5 py-3 font-medium">Entry</th>
                            <th class="text-right px-5 py-3 font-medium">Exit</th>
                            <th class="text-right px-5 py-3 font-medium">Amount</th>
                            <th class="text-right px-5 py-3 font-medium">P/L</th>
                            <th class="text-left px-5 py-3 font-medium">Result</th>
                            <th class="text-left px-5 py-3 font-medium">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-border">
                        @foreach($trades as $trade)
                            <tr class="text-content-secondary hover:bg-surface-overlay/50">
                                <td class="px-5 py-3 font-medium text-content-primary">
                                    <div class="flex items-center gap-2">
                                        @if($trade->tradingAsset && $trade->tradingAsset->logo_url)
                                            <img src="{{ $trade->tradingAsset->logo_url }}" alt="{{ $trade->asset_name }}" class="w-6 h-6 rounded-full bg-surface-overlay flex-shrink-0" loading="lazy">
                                        @else
                                            <span class="w-6 h-6 rounded-full bg-primary/20 text-primary text-[10px] font-bold flex items-center justify-center flex-shrink-0">{{ strtoupper(substr($trade->asset_name, 0, 2)) }}</span>
                                        @endif
                                        <span>{{ $trade->asset_name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-xs">{{ $trade->asset_class }}</td>
                                <td class="px-5 py-3">
                                    <span class="{{ $trade->action === 'buy' ? 'text-gain' : 'text-loss' }}">{{ strtoupper($trade->action) }}</span>
                                </td>
                                <td class="px-5 py-3 text-right">@money($trade->entry_price)</td>
                                <td class="px-5 py-3 text-right">@money($trade->exit_price)</td>
                                <td class="px-5 py-3 text-right">@money($trade->amount)</td>
                                <td class="px-5 py-3 text-right font-semibold {{ $trade->profit_loss >= 0 ? 'text-gain' : 'text-loss' }}">
                                    {{ $trade->profit_loss >= 0 ? '+' : '' }}@money($trade->profit_loss)
                                </td>
                                <td class="px-5 py-3">
                                    <span class="{{ $trade->result === 'WIN' ? 'text-gain' : 'text-loss' }}">{{ $trade->result }}</span>
                                </td>
                                <td class="px-5 py-3 text-content-tertiary text-xs">{{ $trade->executed_at->format('M d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-5 border-t border-surface-border">{{ $trades->links() }}</div>
        @else
            <div class="p-8 text-center">
                <x-icon name="chart-bar" class="w-10 h-10 text-content-tertiary mx-auto mb-2" />
                <p class="text-content-secondary text-sm">No trades yet. Trades are generated automatically.</p>
            </div>
        @endif
    </div>

@endsection

@section('scripts')
@parent
<script>
const stopBtn = document.getElementById('stopPositionBtn');
if (stopBtn) {
    stopBtn.addEventListener('click', function() {
        Swal.fire({
            title: 'Stop Copying?',
            text: 'Your invested amount plus accumulated profit will be credited to your balance.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Yes, Stop',
            cancelButtonText: 'Cancel',
            background: '#FFFFFF',
            color: '#0F1B2D'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('stop-position-form').submit();
        });
    });
}
</script>
@endsection
