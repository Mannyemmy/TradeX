<div>
    <p class="text-content-secondary text-sm mb-6">Enable or disable platform modules. Changes take effect immediately for all users.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

        {{-- Trading --}}
        <div class="bg-surface-card rounded-xl border border-border p-5">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm font-semibold text-content">Trading</h4>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ !empty($mod['trading']) ? 'bg-success-light text-success' : 'bg-danger-light text-danger' }}">
                    {{ !empty($mod['trading']) ? 'ON' : 'OFF' }}
                </span>
            </div>
            <p class="text-xs text-content-muted mb-4">Binary options trading page — place trades, view positions, portfolio & market data.</p>
            <div class="flex gap-2">
                <button wire:click="updateModule('trading','true')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ !empty($mod['trading']) ? 'bg-primary text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Enable</button>
                <button wire:click="updateModule('trading','false')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ empty($mod['trading']) ? 'bg-danger text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Disable</button>
            </div>
        </div>

        {{-- Investment --}}
        <div class="bg-surface-card rounded-xl border border-border p-5">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm font-semibold text-content">Investment Plans</h4>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ !empty($mod['investment']) ? 'bg-success-light text-success' : 'bg-danger-light text-danger' }}">
                    {{ !empty($mod['investment']) ? 'ON' : 'OFF' }}
                </span>
            </div>
            <p class="text-xs text-content-muted mb-4">Plan purchasing, ROI earnings, profit history & auto top-up features.</p>
            <div class="flex gap-2">
                <button wire:click="updateModule('investment','true')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ !empty($mod['investment']) ? 'bg-primary text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Enable</button>
                <button wire:click="updateModule('investment','false')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ empty($mod['investment']) ? 'bg-danger text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Disable</button>
            </div>
        </div>

        {{-- Copy Trading --}}
        <div class="bg-surface-card rounded-xl border border-border p-5">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm font-semibold text-content">Copy Trading</h4>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ !empty($mod['copy_trading']) ? 'bg-success-light text-success' : 'bg-danger-light text-danger' }}">
                    {{ !empty($mod['copy_trading']) ? 'ON' : 'OFF' }}
                </span>
            </div>
            <p class="text-xs text-content-muted mb-4">Copy expert traders — browse experts, start/stop copying, view positions.</p>
            <div class="flex gap-2">
                <button wire:click="updateModule('copy_trading','true')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ !empty($mod['copy_trading']) ? 'bg-primary text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Enable</button>
                <button wire:click="updateModule('copy_trading','false')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ empty($mod['copy_trading']) ? 'bg-danger text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Disable</button>
            </div>
        </div>

        {{-- Bot Trading --}}
        <div class="bg-surface-card rounded-xl border border-border p-5">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm font-semibold text-content">Bot Trading</h4>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ !empty($mod['bot_trading']) ? 'bg-success-light text-success' : 'bg-danger-light text-danger' }}">
                    {{ !empty($mod['bot_trading']) ? 'ON' : 'OFF' }}
                </span>
            </div>
            <p class="text-xs text-content-muted mb-4">Automated trading bots — users subscribe, bots simulate trades at intervals.</p>
            <div class="flex gap-2">
                <button wire:click="updateModule('bot_trading','true')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ !empty($mod['bot_trading']) ? 'bg-primary text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Enable</button>
                <button wire:click="updateModule('bot_trading','false')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ empty($mod['bot_trading']) ? 'bg-danger text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Disable</button>
            </div>
        </div>

        {{-- Signals --}}
        <div class="bg-surface-card rounded-xl border border-border p-5">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm font-semibold text-content">Trade Signals</h4>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ !empty($mod['signal']) ? 'bg-success-light text-success' : 'bg-danger-light text-danger' }}">
                    {{ !empty($mod['signal']) ? 'ON' : 'OFF' }}
                </span>
            </div>
            <p class="text-xs text-content-muted mb-4">Signal plans, subscriptions & trade signal feeds for users.</p>
            <div class="flex gap-2">
                <button wire:click="updateModule('signal','true')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ !empty($mod['signal']) ? 'bg-primary text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Enable</button>
                <button wire:click="updateModule('signal','false')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ empty($mod['signal']) ? 'bg-danger text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Disable</button>
            </div>
        </div>

        {{-- NFTs --}}
        <div class="bg-surface-card rounded-xl border border-border p-5">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm font-semibold text-content">NFT Gallery</h4>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ !empty($mod['nft']) ? 'bg-success-light text-success' : 'bg-danger-light text-danger' }}">
                    {{ !empty($mod['nft']) ? 'ON' : 'OFF' }}
                </span>
            </div>
            <p class="text-xs text-content-muted mb-4">NFT marketplace — create, browse, bid, buy & sell digital assets.</p>
            <div class="flex gap-2">
                <button wire:click="updateModule('nft','true')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ !empty($mod['nft']) ? 'bg-primary text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Enable</button>
                <button wire:click="updateModule('nft','false')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ empty($mod['nft']) ? 'bg-danger text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Disable</button>
            </div>
        </div>

        {{-- Loans --}}
        <div class="bg-surface-card rounded-xl border border-border p-5">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm font-semibold text-content">Loans</h4>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ !empty($mod['loan']) ? 'bg-success-light text-success' : 'bg-danger-light text-danger' }}">
                    {{ !empty($mod['loan']) ? 'ON' : 'OFF' }}
                </span>
            </div>
            <p class="text-xs text-content-muted mb-4">Loan applications, repayment tracking & loan plan management.</p>
            <div class="flex gap-2">
                <button wire:click="updateModule('loan','true')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ !empty($mod['loan']) ? 'bg-primary text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Enable</button>
                <button wire:click="updateModule('loan','false')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ empty($mod['loan']) ? 'bg-danger text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Disable</button>
            </div>
        </div>

        {{-- Pre-IPO --}}
        <div class="bg-surface-card rounded-xl border border-border p-5">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm font-semibold text-content">Pre-IPO Shares</h4>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ !empty($mod['pre_ipo']) ? 'bg-success-light text-success' : 'bg-danger-light text-danger' }}">
                    {{ !empty($mod['pre_ipo']) ? 'ON' : 'OFF' }}
                </span>
            </div>
            <p class="text-xs text-content-muted mb-4">Pre-IPO share trading — browse companies, buy & sell shares before listing.</p>
            <div class="flex gap-2">
                <button wire:click="updateModule('pre_ipo','true')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ !empty($mod['pre_ipo']) ? 'bg-primary text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Enable</button>
                <button wire:click="updateModule('pre_ipo','false')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ empty($mod['pre_ipo']) ? 'bg-danger text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Disable</button>
            </div>
        </div>

        {{-- Crypto Swap --}}
        <div class="bg-surface-card rounded-xl border border-border p-5">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm font-semibold text-content">Crypto Swap</h4>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ !empty($mod['cryptoswap']) ? 'bg-success-light text-success' : 'bg-danger-light text-danger' }}">
                    {{ !empty($mod['cryptoswap']) ? 'ON' : 'OFF' }}
                </span>
            </div>
            <p class="text-xs text-content-muted mb-4">Cryptocurrency swapping — exchange between crypto assets on the platform.</p>
            <div class="flex gap-2">
                <button wire:click="updateModule('cryptoswap','true')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ !empty($mod['cryptoswap']) ? 'bg-primary text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Enable</button>
                <button wire:click="updateModule('cryptoswap','false')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ empty($mod['cryptoswap']) ? 'bg-danger text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Disable</button>
            </div>
        </div>

        {{-- Membership / Education --}}
        <div class="bg-surface-card rounded-xl border border-border p-5">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm font-semibold text-content">Education / Courses</h4>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ !empty($mod['membership']) ? 'bg-success-light text-success' : 'bg-danger-light text-danger' }}">
                    {{ !empty($mod['membership']) ? 'ON' : 'OFF' }}
                </span>
            </div>
            <p class="text-xs text-content-muted mb-4">Educational courses & membership content for users to purchase and learn.</p>
            <div class="flex gap-2">
                <button wire:click="updateModule('membership','true')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ !empty($mod['membership']) ? 'bg-primary text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Enable</button>
                <button wire:click="updateModule('membership','false')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ empty($mod['membership']) ? 'bg-danger text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Disable</button>
            </div>
        </div>

        {{-- Stock Shares Trading --}}
        <div class="bg-surface-card rounded-xl border border-border p-5">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm font-semibold text-content">Stock Shares</h4>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ !empty($mod['stocktrading']) ? 'bg-success-light text-success' : 'bg-danger-light text-danger' }}">
                    {{ !empty($mod['stocktrading']) ? 'ON' : 'OFF' }}
                </span>
            </div>
            <p class="text-xs text-content-muted mb-4">Fractional stock shares trading — buy & sell real stocks by dollar amount.</p>
            <div class="flex gap-2">
                <button wire:click="updateModule('stocktrading','true')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ !empty($mod['stocktrading']) ? 'bg-primary text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Enable</button>
                <button wire:click="updateModule('stocktrading','false')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ empty($mod['stocktrading']) ? 'bg-danger text-content-inverse' : 'bg-surface-alt text-content-secondary hover:bg-surface-raised' }}">Disable</button>
            </div>
        </div>

    </div>
</div>
