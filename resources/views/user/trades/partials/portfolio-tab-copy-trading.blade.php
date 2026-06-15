{{-- Portfolio Tab: Copy Trading --}}

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    @include('user.partials.stat-card-compact', ['label' => 'Active Positions', 'value' => $activeCopyPositions->count(), 'icon' => 'copy'])
    @include('user.partials.stat-card-compact', ['label' => 'Total Invested', 'value' => \App\Helpers\CurrencyHelper::formatForUser($totalCopyInvested), 'icon' => 'chart-bar', 'color' => 'info'])
    @include('user.partials.stat-card-compact', ['label' => 'Accumulated Profit', 'value' => \App\Helpers\CurrencyHelper::formatForUser($totalCopyProfit), 'icon' => 'arrow-trending-up', 'color' => 'gain'])
</div>

@if($activeCopyPositions->count() > 0)
    {{-- Desktop Table --}}
    <div class="hidden md:block bg-surface-raised border border-surface-border rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-surface-border">
                    <th class="px-4 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Expert</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider">Invested</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider">Profit</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider">Daily ROI</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Started</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Expires</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-border">
                @foreach($activeCopyPositions as $position)
                    <tr class="hover:bg-surface-overlay/50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if($position->expert && $position->expert->profile_picture)
                                    <img src="{{ asset('storage/app/public/' . $position->expert->profile_picture) }}" class="w-8 h-8 rounded-full bg-surface-overlay" alt="">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-surface-overlay flex items-center justify-center text-xs font-bold text-content-secondary">
                                        {{ strtoupper(substr($position->expert->name ?? '?', 0, 2)) }}
                                    </div>
                                @endif
                                <div>
                                    <span class="text-sm font-semibold text-content-primary">{{ $position->expert->name ?? 'Unknown' }}</span>
                                    @if($position->expert && $position->expert->area_of_expertise)
                                        <span class="text-[10px] text-content-tertiary block">{{ $position->expert->area_of_expertise }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="text-sm font-medium text-content-primary">@money($position->invested_amount)</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="text-sm font-medium {{ $position->accumulated_profit >= 0 ? 'text-gain' : 'text-loss' }}">
                                @money($position->accumulated_profit)
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="text-xs font-medium text-content-secondary">{{ number_format($position->daily_roi_snapshot ?? 0, 2) }}%</span>
                        </td>
                        <td class="px-4 py-3 text-xs text-content-secondary">
                            {{ $position->started_at ? \Carbon\Carbon::parse($position->started_at)->format('M d, Y') : '—' }}
                        </td>
                        <td class="px-4 py-3">
                            @if($position->expires_at)
                                @php $daysLeft = now()->diffInDays(\Carbon\Carbon::parse($position->expires_at), false); @endphp
                                <span class="text-xs {{ $daysLeft <= 3 ? 'text-warning font-medium' : 'text-content-secondary' }}">
                                    {{ \Carbon\Carbon::parse($position->expires_at)->format('M d, Y') }}
                                </span>
                            @else
                                <span class="text-xs text-content-tertiary">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden space-y-3">
        @foreach($activeCopyPositions as $position)
            <div class="bg-surface-raised border border-surface-border rounded-xl p-4">
                <div class="flex items-center gap-3 mb-3">
                    @if($position->expert && $position->expert->profile_picture)
                        <img src="{{ asset('storage/app/public/' . $position->expert->profile_picture) }}" class="w-10 h-10 rounded-full bg-surface-overlay" alt="">
                    @else
                        <div class="w-10 h-10 rounded-full bg-surface-overlay flex items-center justify-center text-sm font-bold text-content-secondary">
                            {{ strtoupper(substr($position->expert->name ?? '?', 0, 2)) }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <span class="text-sm font-semibold text-content-primary block truncate">{{ $position->expert->name ?? 'Unknown' }}</span>
                        <span class="text-xs text-content-tertiary">Daily ROI: {{ number_format($position->daily_roi_snapshot ?? 0, 2) }}%</span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-content-tertiary block">Invested</span>
                        <span class="text-content-primary font-medium">@money($position->invested_amount)</span>
                    </div>
                    <div>
                        <span class="text-content-tertiary block">Profit</span>
                        <span class="{{ $position->accumulated_profit >= 0 ? 'text-gain' : 'text-loss' }} font-medium">
                            @money($position->accumulated_profit)
                        </span>
                    </div>
                    <div>
                        <span class="text-content-tertiary block">Started</span>
                        <span class="text-content-secondary">{{ $position->started_at ? \Carbon\Carbon::parse($position->started_at)->format('M d') : '—' }}</span>
                    </div>
                    <div>
                        <span class="text-content-tertiary block">Expires</span>
                        <span class="text-content-secondary">{{ $position->expires_at ? \Carbon\Carbon::parse($position->expires_at)->format('M d') : '—' }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    @include('user.trades.partials.empty-state', [
        'icon' => 'copy',
        'title' => 'No copy trading positions',
        'message' => 'You haven\'t started copying any experts yet.',
        'actionUrl' => route('copyTrading'),
        'actionLabel' => 'Browse Experts',
    ])
@endif
