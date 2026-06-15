<div>
    <p class="text-content-secondary text-sm mb-6">Enable or disable platform modules. Changes take effect immediately for all users.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

        @php $modules = [
            'trading' => ['label' => 'Trading', 'desc' => 'Binary options trading page — place trades, view positions, portfolio & market data.'],
            'investment' => ['label' => 'Investment Plans', 'desc' => 'Plan purchasing, ROI earnings, profit history & auto top-up features.'],
            'copy_trading' => ['label' => 'Copy Trading', 'desc' => 'Copy expert traders — browse experts, start/stop copying, view positions.'],
            'bot_trading' => ['label' => 'Bot Trading', 'desc' => 'Automated trading bots — users subscribe, bots simulate trades at intervals.'],
            'signal' => ['label' => 'Trade Signals', 'desc' => 'Signal plans, subscriptions & trade signal feeds for users.'],
            'nft' => ['label' => 'NFT Gallery', 'desc' => 'NFT marketplace — create, browse, bid, buy & sell digital assets.'],
            'loan' => ['label' => 'Loans', 'desc' => 'Loan applications, repayment tracking & loan plan management.'],
            'pre_ipo' => ['label' => 'Pre-IPO Shares', 'desc' => 'Pre-IPO share trading — browse companies, buy & sell shares before listing.'],
            'cryptoswap' => ['label' => 'Crypto Swap', 'desc' => 'Cryptocurrency swapping — exchange between crypto assets on the platform.'],
            'membership' => ['label' => 'Education / Courses', 'desc' => 'Educational courses & membership content for users to purchase and learn.'],
            'stocktrading' => ['label' => 'Stock Shares', 'desc' => 'Fractional stock shares trading — buy & sell real stocks by dollar amount.'],
        ] @endphp

        @foreach ($modules as $key => $info)
            @php $enabled = !empty($mod[$key]) @endphp
            <div class="bg-surface-card rounded-xl border border-border p-5">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-sm font-semibold text-content">{{ $info['label'] }}</h4>
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $enabled ? 'bg-success-light text-success' : 'bg-danger-light text-danger' }}">
                        {{ $enabled ? 'ON' : 'OFF' }}
                    </span>
                </div>
                <p class="text-xs text-content-muted mb-4">{{ $info['desc'] }}</p>
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('toggleModule') }}" class="inline">
                        @csrf
                        <input type="hidden" name="module" value="{{ $key }}">
                        <input type="hidden" name="value" value="true">
                        <button type="submit" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ $enabled ? 'bg-primary text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Enable</button>
                    </form>
                    <form method="POST" action="{{ route('toggleModule') }}" class="inline">
                        @csrf
                        <input type="hidden" name="module" value="{{ $key }}">
                        <input type="hidden" name="value" value="false">
                        <button type="submit" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ !$enabled ? 'bg-danger text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Disable</button>
                    </form>
                </div>
            </div>
        @endforeach

    </div>
</div>
