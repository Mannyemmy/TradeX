<div>
    @if (count($plans) > 0)
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column: Plan Selection & Amount --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-surface-raised border border-surface-border rounded-xl p-6">
                    <x-danger-alert />
                    <x-success-alert />

                    {{-- Plan Selector Dropdown --}}
                    <div x-data="{ open: false }" class="relative mb-6">
                        <label class="block text-sm font-medium text-content-secondary mb-2">Select Investment Plan</label>
                        <button type="button" @click="open = !open"
                            class="w-full flex items-center justify-between bg-surface-overlay border border-surface-border rounded-lg px-4 py-3 text-left hover:border-primary/50 transition-colors">
                            <div class="flex items-center gap-3">
                                @if ($planSelected)
                                    <span class="text-primary">
                                        <x-icon name="chart-bar" class="w-5 h-5" />
                                    </span>
                                    <span class="text-content-primary font-medium">{{ $planSelected->name }}</span>
                                @else
                                    <span class="text-content-tertiary">Select a plan</span>
                                @endif
                            </div>
                            <x-icon name="chevron-down" class="w-5 h-5 text-content-tertiary transition-transform" x-bind:class="open ? 'rotate-180' : ''" />
                        </button>
                        <ul x-show="open" @click.outside="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute z-20 mt-2 w-full bg-surface-overlay border border-surface-border rounded-lg shadow-xl max-h-60 overflow-y-auto">
                            @foreach ($plans as $plan)
                                <li wire:click="selectPlan({{ $plan->id }})" @click="open = false"
                                    class="flex items-center gap-3 px-4 py-3 cursor-pointer hover:bg-primary/10 transition-colors">
                                    <span class="text-primary">
                                        <x-icon name="chart-bar" class="w-4 h-4" />
                                    </span>
                                    <span class="text-content-primary">{{ $plan->name }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Quick Amount Buttons --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-content-secondary mb-3">Choose Quick Amount to Invest</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach ([100, 250, 500, 1000, 1500, 2000] as $amt)
                                <button wire:click="selectAmount('{{ $amt }}')"
                                    class="px-4 py-2 bg-surface-overlay border border-surface-border rounded-lg text-sm text-content-primary hover:border-primary/50 hover:bg-primary/10 transition-all">
                                    @money($amt)
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Custom Amount Input --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-content-secondary mb-2">Or Enter Your Amount</label>
                        <input type="number" required wire:model="amountToInvest" wire:keyup="checkIfAmountIsEmpty"
                            placeholder="1000"
                            min="{{ $planSelected ? $planSelected->min_price : '0' }}"
                            max="{{ $planSelected ? $planSelected->max_price : '10000000000' }}"
                            class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-3 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                    </div>

                    {{-- Payment Method --}}
                    <div>
                        <label class="block text-sm font-medium text-content-secondary mb-3">Choose Payment Method</label>
                        <button wire:click="chanegePaymentMethod('Account Balance')"
                            class="w-full flex items-center gap-4 p-4 rounded-lg border transition-all cursor-pointer
                                {{ $paymentMethod == 'Account Balance'
                                    ? 'bg-primary/10 border-primary/50'
                                    : 'bg-surface-overlay border-surface-border hover:border-primary/30' }}">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                <span class="text-primary">
                                    <x-icon name="wallet" class="w-5 h-5" />
                                </span>
                            </div>
                            <div class="text-left">
                                <p class="text-content-primary font-medium">Account Balance</p>
                                <p class="text-sm text-content-secondary">@money(Auth::user()->account_bal)</p>
                            </div>
                            @if ($paymentMethod == 'Account Balance')
                                <div class="ml-auto">
                                    <span class="text-primary">
                                        <x-icon name="check-circle" class="w-5 h-5" />
                                    </span>
                                </div>
                            @endif
                        </button>
                    </div>
                </div>
            </div>

            {{-- Right Column: Investment Summary --}}
            <div>
                <div class="bg-surface-raised border border-surface-border rounded-xl p-6 sticky top-24">
                    <h3 class="text-content-primary font-semibold mb-4">Your Investment Details</h3>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-content-tertiary mb-1">Name of Plan</p>
                            <p class="text-sm text-primary font-medium">{{ $planSelected ? $planSelected->name : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-content-tertiary mb-1">Plan Price</p>
                            <p class="text-sm text-primary font-medium">{{ $planSelected ? \App\Helpers\CurrencyHelper::formatForUser($planSelected->price) : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-content-tertiary mb-1">Duration</p>
                            <p class="text-sm text-primary font-medium">{{ $planSelected ? $planSelected->expiration : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-content-tertiary mb-1">Profit</p>
                            <p class="text-sm text-primary font-medium">
                                @if ($planSelected)
                                    @if ($planSelected->increment_type == 'Fixed')
                                        {{ \App\Helpers\CurrencyHelper::formatForUser($planSelected->increment_amount) }}
                                        {{ $planSelected->increment_interval }}
                                    @else
                                        {{ $planSelected->increment_amount }}%
                                        {{ $planSelected->increment_interval }}
                                    @endif
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-content-tertiary mb-1">Minimum Deposit</p>
                            <p class="text-sm text-primary font-medium">{{ $planSelected ? \App\Helpers\CurrencyHelper::formatForUser($planSelected->min_price) : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-content-tertiary mb-1">Maximum Deposit</p>
                            <p class="text-sm text-primary font-medium">{{ $planSelected ? \App\Helpers\CurrencyHelper::formatForUser($planSelected->max_price) : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-content-tertiary mb-1">Minimum Return</p>
                            <p class="text-sm text-primary font-medium">{{ $planSelected ? $planSelected->minr . '%' : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-content-tertiary mb-1">Maximum Return</p>
                            <p class="text-sm text-primary font-medium">{{ $planSelected ? $planSelected->maxr . '%' : '-' }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-xs text-content-tertiary mb-1">Bonus</p>
                            <p class="text-sm text-primary font-medium">{{ $planSelected ? \App\Helpers\CurrencyHelper::formatForUser($planSelected->gift) : '-' }}</p>
                        </div>
                    </div>

                    <div class="border-t border-surface-border pt-4 mb-3">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-content-secondary">Payment method</span>
                            <span class="text-sm text-primary">{{ $paymentMethod ? $paymentMethod : '-' }}</span>
                        </div>
                    </div>

                    <div class="border-t border-surface-border pt-4 mb-6">
                        <div class="flex items-center justify-between">
                            <span class="text-content-primary font-semibold">Amount to Invest</span>
                            <span class="text-primary font-bold text-lg">{{ \App\Helpers\CurrencyHelper::formatForUser($amountToInvest ?? 0) }}</span>
                        </div>
                    </div>

                    <form wire:submit.prevent="joinPlan">
                        <button type="submit" {{ $disabled }}
                            class="w-full bg-primary hover:bg-primary-dark disabled:opacity-50 disabled:cursor-not-allowed text-content-inverse font-semibold py-3 px-4 rounded-lg transition-colors">
                            Confirm & Invest
                        </button>
                    </form>
                    <p class="text-center mt-3 text-sm text-primary" wire:loading wire:target="joinPlan">
                        Processing...
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="mt-6">
            <div class="bg-surface-raised border border-surface-border rounded-xl p-8 text-center">
                <div class="w-16 h-16 mx-auto bg-surface-overlay rounded-full flex items-center justify-center mb-4">
                    <span class="text-content-tertiary">
                        <x-icon name="chart-bar" class="w-8 h-8" />
                    </span>
                </div>
                <p class="text-content-secondary">No investment plan at the moment, please contact our support for more information.</p>
            </div>
        </div>
    @endif
</div>
