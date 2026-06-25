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

    {{-- Expert Profile Header --}}
    <div class="bg-surface-raised border border-surface-border rounded-xl p-6 mb-6">
        <div class="flex items-start gap-5">
            {{-- Avatar --}}
            <div class="relative flex-shrink-0">
                @if($expert->profile_picture)
                    <img src="{{ asset('storage/app/public/' . $expert->profile_picture) }}" alt="{{ $expert->name }}" class="w-20 h-20 rounded-2xl object-cover ring-2 ring-surface-border">
                @else
                    <div class="w-20 h-20 rounded-2xl bg-surface-overlay flex items-center justify-center ring-2 ring-surface-border">
                        <x-icon name="user-circle" class="w-10 h-10 text-content-tertiary" />
                    </div>
                @endif
                <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-gain rounded-full border-2 border-surface-raised" title="Active"></span>
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3 flex-wrap">
                    <h2 class="text-xl font-bold text-content-primary">{{ $expert->name }}</h2>
                    <span class="bg-primary/10 text-primary text-xs font-medium px-2 py-0.5 rounded">{{ $expert->area_of_expertise }}</span>
                </div>
                @if($expert->bio)
                    <p class="text-sm text-content-secondary leading-relaxed mt-2 line-clamp-2">{{ $expert->bio }}</p>
                @endif
                <div class="flex items-center gap-4 mt-3 text-xs text-content-tertiary">
                    <span class="flex items-center gap-1">
                        <x-icon name="users" class="w-3.5 h-3.5" />
                        {{ number_format($expert->followers_count) }} followers
                    </span>
                    <span class="flex items-center gap-1">
                        <x-icon name="clock" class="w-3.5 h-3.5" />
                        {{ $expert->duration_days }}-day plan
                    </span>
                    <span class="flex items-center gap-1">
                        <x-icon name="chart-bar" class="w-3.5 h-3.5" />
                        {{ number_format($expert->win_rate ?? 0) }}% win rate
                    </span>
                </div>
            </div>

            {{-- Headline Stat --}}
            <div class="hidden sm:flex flex-col items-end flex-shrink-0">
                <p class="text-2xl font-bold text-gain">{{ number_format($expert->daily_roi, 2) }}%</p>
                <p class="text-[11px] text-content-tertiary">daily ROI</p>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-3 sm:grid-cols-6 gap-3 mb-6">
        <div class="bg-surface-raised border border-surface-border rounded-xl p-3.5 text-center">
            <p class="text-[10px] text-content-tertiary uppercase tracking-wide mb-1">Daily ROI</p>
            <p class="text-base font-bold text-gain">{{ number_format($expert->daily_roi, 2) }}%</p>
        </div>
        <div class="bg-surface-raised border border-surface-border rounded-xl p-3.5 text-center">
            <p class="text-[10px] text-content-tertiary uppercase tracking-wide mb-1">Duration</p>
            <p class="text-base font-bold text-content-primary">{{ $expert->duration_days }}d</p>
        </div>
        <div class="bg-surface-raised border border-surface-border rounded-xl p-3.5 text-center">
            <p class="text-[10px] text-content-tertiary uppercase tracking-wide mb-1">Min Capital</p>
            <p class="text-base font-bold text-content-primary">@money($expert->min_startup_capital)</p>
        </div>
        <div class="bg-surface-raised border border-surface-border rounded-xl p-3.5 text-center">
            <p class="text-[10px] text-content-tertiary uppercase tracking-wide mb-1">Max Capital</p>
            <p class="text-base font-bold text-content-primary">{{ $expert->max_capital ? \App\Helpers\CurrencyHelper::formatForUser($expert->max_capital) : '—' }}</p>
        </div>
        <div class="bg-surface-raised border border-surface-border rounded-xl p-3.5 text-center">
            <p class="text-[10px] text-content-tertiary uppercase tracking-wide mb-1">Followers</p>
            <p class="text-base font-bold text-content-primary">{{ number_format($expert->followers_count) }}</p>
        </div>
        <div class="bg-surface-raised border border-surface-border rounded-xl p-3.5 text-center">
            <p class="text-[10px] text-content-tertiary uppercase tracking-wide mb-1">Total ROI</p>
            <p class="text-base font-bold text-gain">{{ number_format($expert->total_roi, 2) }}%</p>
        </div>
    </div>

    {{-- Copy Form or Active Position --}}
    @if($activePosition)
        <div class="bg-surface-raised border border-primary/30 rounded-xl p-5 mb-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="bg-gain/10 text-gain text-xs px-2.5 py-1 rounded-full">Active</span>
                <h3 class="text-base font-semibold text-content-primary">Your Active Position</h3>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                <div>
                    <p class="text-xs text-content-tertiary">Invested</p>
                    <p class="text-sm font-semibold text-content-primary">@money($activePosition->invested_amount)</p>
                </div>
                <div>
                    <p class="text-xs text-content-tertiary">Profit</p>
                    <p class="text-sm font-semibold text-gain">@money($activePosition->accumulated_profit)</p>
                </div>
                <div>
                    <p class="text-xs text-content-tertiary">ROI Locked</p>
                    <p class="text-sm font-semibold text-gain">{{ number_format($activePosition->daily_roi_snapshot, 2) }}%</p>
                </div>
                <div>
                    <p class="text-xs text-content-tertiary">Expires</p>
                    <p class="text-sm font-semibold text-content-primary">{{ $activePosition->expires_at->format('M d, Y') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('copyTrading.position', $activePosition->id) }}" class="py-2 px-5 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-semibold transition-colors">
                    View Position
                </a>
                <form id="stop-expert-form" action="{{ route('copyTrading.stop', $activePosition->id) }}" method="POST">
                    @csrf
                    <button type="button" id="stopExpertBtn" class="py-2 px-5 rounded-lg bg-loss/10 hover:bg-loss/20 text-loss text-sm font-semibold transition-colors">
                        Stop Copying
                    </button>
                </form>
            </div>
        </div>
    @else
        <div class="bg-surface-raised border border-surface-border rounded-xl p-5 mb-6" x-data="{ amount: '' }">
            <h3 class="text-base font-semibold text-content-primary mb-4">Start Copying {{ $expert->name }}</h3>
            <form action="{{ route('copyTrading.start', $expert->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm text-content-secondary mb-1.5">Investment Amount (@userCurrencyCode)</label>
                    <input type="number" name="invested_amount" x-model="amount" step="0.01"
                           min="{{ round(Auth::user()->convertToUserCurrency($expert->min_startup_capital), 2) }}"
                           @if($expert->max_capital) max="{{ round(Auth::user()->convertToUserCurrency($expert->max_capital), 2) }}" @endif
                           class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm"
                           placeholder="Min {{ \App\Helpers\CurrencyHelper::formatForUser($expert->min_startup_capital) }}" required>
                    @error('invested_amount')
                        <p class="text-loss text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center justify-between text-sm text-content-secondary mb-4">
                    <span>Estimated Daily Profit:</span>
                    <span class="font-semibold text-gain" x-text="amount ? '@userCurrency' + (parseFloat(amount) * {{ $expert->daily_roi }} / 100).toFixed(2) : '@userCurrency' + '0.00'"></span>
                </div>
                <button type="submit" class="w-full py-2.5 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-semibold transition-colors">
                    Start Copying — {{ $expert->duration_days }} Day Plan
                </button>
            </form>
        </div>
    @endif

    {{-- Recent Simulated Trades --}}
    @if($recentTrades->count() > 0)
        <div class="bg-surface-raised border border-surface-border rounded-xl overflow-hidden">
            <div class="p-5 border-b border-surface-border">
                <h3 class="text-base font-semibold text-content-primary">Recent Simulated Trades</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-content-tertiary text-xs border-b border-surface-border">
                            <th class="text-left px-5 py-3 font-medium">Asset</th>
                            <th class="text-left px-5 py-3 font-medium">Action</th>
                            <th class="text-right px-5 py-3 font-medium">Entry</th>
                            <th class="text-right px-5 py-3 font-medium">Exit</th>
                            <th class="text-right px-5 py-3 font-medium">P/L</th>
                            <th class="text-left px-5 py-3 font-medium">Result</th>
                            <th class="text-left px-5 py-3 font-medium">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-border">
                        @foreach($recentTrades as $trade)
                            <tr class="text-content-secondary hover:bg-surface-overlay/50">
                                <td class="px-5 py-3 font-medium text-content-primary">{{ $trade->asset_name }}</td>
                                <td class="px-5 py-3">
                                    <span class="{{ $trade->action === 'buy' ? 'text-gain' : 'text-loss' }}">{{ strtoupper($trade->action) }}</span>
                                </td>
                                <td class="px-5 py-3 text-right">@money($trade->entry_price)</td>
                                <td class="px-5 py-3 text-right">@money($trade->exit_price)</td>
                                <td class="px-5 py-3 text-right font-semibold {{ $trade->profit_loss >= 0 ? 'text-gain' : 'text-loss' }}">
                                    {{ $trade->profit_loss >= 0 ? '+' : '' }}@money($trade->profit_loss)
                                </td>
                                <td class="px-5 py-3">
                                    <span class="{{ $trade->result === 'WIN' ? 'text-gain' : 'text-loss' }}">{{ $trade->result }}</span>
                                </td>
                                <td class="px-5 py-3 text-content-tertiary text-xs">{{ $trade->executed_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection

@section('scripts')
@parent
<script>
const stopBtn = document.getElementById('stopExpertBtn');
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
            if (result.isConfirmed) document.getElementById('stop-expert-form').submit();
        });
    });
}
</script>
@endsection
