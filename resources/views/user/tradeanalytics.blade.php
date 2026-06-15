{{-- Trade Analytics Stats --}}
<div class="grid grid-cols-2 gap-3 mb-6">
    @include('user.partials.stat-card-compact', ['label' => 'Total Trades', 'value' => $totalTrades, 'icon' => 'chart-bar', 'color' => 'info'])
    @include('user.partials.stat-card-compact', ['label' => 'Open Trades', 'value' => $openTrades, 'icon' => 'folder-open', 'color' => 'primary'])
    @include('user.partials.stat-card-compact', ['label' => 'Closed Trades', 'value' => $closedTrades, 'icon' => 'folder', 'color' => 'warning'])
    @include('user.partials.stat-card-compact', ['label' => 'Win/Loss Ratio', 'value' => $winLossRatio, 'icon' => 'trophy', 'color' => 'gain'])
</div>
