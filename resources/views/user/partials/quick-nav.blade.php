{{-- Quick-action navigation pills --}}
<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ url('dashboard') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg transition-colors
        {{ request()->is('dashboard') && !request()->is('dashboard/*') ? 'bg-primary text-content-inverse' : 'bg-surface-overlay text-content-secondary hover:bg-surface-border hover:text-content-primary' }}">
        <x-icon name="home" class="w-3.5 h-3.5" /> Account
    </a>
    <a href="{{ url('dashboard/deposits') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg transition-colors
        {{ request()->is('dashboard/deposits*') ? 'bg-primary text-content-inverse' : 'bg-surface-overlay text-content-secondary hover:bg-surface-border hover:text-content-primary' }}">
        <x-icon name="arrow-down-tray" class="w-3.5 h-3.5" /> Deposit
    </a>
    @if(!empty($mod['investment']) || !empty($mod['cryptoswap']))
    <a href="{{ route('withdrawalsdeposits') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg transition-colors
        {{ request()->routeIs('withdrawalsdeposits') ? 'bg-primary text-content-inverse' : 'bg-surface-overlay text-content-secondary hover:bg-surface-border hover:text-content-primary' }}">
        <x-icon name="arrow-up-tray" class="w-3.5 h-3.5" /> Withdraw
    </a>
    @endif
    @if(!empty($mod['trading']))
    <a href="{{ route('trade') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg transition-colors
        {{ request()->routeIs('trade') ? 'bg-primary text-content-inverse' : 'bg-surface-overlay text-content-secondary hover:bg-surface-border hover:text-content-primary' }}">
        <x-icon name="chart-bar" class="w-3.5 h-3.5" /> Trade
    </a>
    <a href="{{ route('user.trades.portfolio') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg transition-colors
        {{ request()->routeIs('user.trades.portfolio') ? 'bg-primary text-content-inverse' : 'bg-surface-overlay text-content-secondary hover:bg-surface-border hover:text-content-primary' }}">
        <x-icon name="briefcase" class="w-3.5 h-3.5" /> Portfolio
    </a>
    <a href="{{ route('user.trades.positions') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg transition-colors
        {{ request()->routeIs('user.trades.positions') ? 'bg-primary text-content-inverse' : 'bg-surface-overlay text-content-secondary hover:bg-surface-border hover:text-content-primary' }}">
        <x-icon name="bolt" class="w-3.5 h-3.5" /> Positions
    </a>
    <a href="{{ route('user.trades.markets') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg transition-colors
        {{ request()->routeIs('user.trades.markets') ? 'bg-primary text-content-inverse' : 'bg-surface-overlay text-content-secondary hover:bg-surface-border hover:text-content-primary' }}">
        <x-icon name="building-library" class="w-3.5 h-3.5" /> Markets
    </a>
    @endif
    <a href="{{ route('accounthistory') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg transition-colors
        {{ request()->routeIs('accounthistory') ? 'bg-primary text-content-inverse' : 'bg-surface-overlay text-content-secondary hover:bg-surface-border hover:text-content-primary' }}">
        <x-icon name="document-text" class="w-3.5 h-3.5" /> Transactions
    </a>
    <a href="{{ route('profile') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg transition-colors
        {{ request()->routeIs('profile') ? 'bg-primary text-content-inverse' : 'bg-surface-overlay text-content-secondary hover:bg-surface-border hover:text-content-primary' }}">
        <x-icon name="cog" class="w-3.5 h-3.5" /> Settings
    </a>
    <button @click="$dispatch('open-mail-support')" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg bg-surface-overlay text-content-secondary hover:bg-surface-border hover:text-content-primary transition-colors">
        <x-icon name="information-circle" class="w-3.5 h-3.5" /> Support
    </button>
</div>
