@php
use App\Models\Wdmethod;
$dmethods = Wdmethod::where(function ($query) {
    $query->where('type', '=', 'withdrawal')
        ->orWhere('type', '=', 'both');
})->where('status', 'enabled')->orderByDesc('id')->get();
@endphp

@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />

    @include('user.partials.ticker-tape')
    @include('user.partials.quick-nav')
    @include('user.partials.page-header', ['title' => 'Request Withdrawal', 'subtitle' => 'Withdraw funds from your account'])

    <div class="max-w-3xl" x-data="withdrawalWizard()">

        {{-- Step Indicator --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <template x-for="(s, idx) in steps" :key="idx">
                    <div class="flex items-center" :class="idx < steps.length - 1 ? 'flex-1' : ''">
                        <div class="flex flex-col items-center">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-300"
                                 :class="step > idx + 1 ? 'bg-primary text-content-inverse' : (step === idx + 1 ? 'bg-primary text-content-inverse ring-4 ring-primary/20' : 'bg-surface-overlay text-content-tertiary border border-surface-border')">
                                <template x-if="step > idx + 1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                </template>
                                <template x-if="step <= idx + 1">
                                    <span x-text="idx + 1"></span>
                                </template>
                            </div>
                            <span class="text-[10px] mt-1.5 font-medium whitespace-nowrap"
                                  :class="step >= idx + 1 ? 'text-primary' : 'text-content-tertiary'" x-text="s"></span>
                        </div>
                        <template x-if="idx < steps.length - 1">
                            <div class="flex-1 h-0.5 mx-3 mt-[-18px] rounded-full transition-all duration-300"
                                 :class="step > idx + 1 ? 'bg-primary' : 'bg-surface-border'"></div>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        <form method="POST" action="{{ route('completewithdrawal') }}" @submit="return validateFinal()">
            @csrf

            {{-- STEP 1: Select Method --}}
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-surface-raised border border-surface-border rounded-xl p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-content-primary">Select Withdrawal Method</h3>
                            <p class="text-xs text-content-tertiary">Choose how you'd like to receive your funds</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($dmethods as $dmethod)
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="method" value="{{ $dmethod->name }}"
                                       data-methodtype="{{ $dmethod->methodtype }}"
                                       data-charges-type="{{ $dmethod->charges_type }}"
                                       data-charges-amount="{{ $dmethod->charges_amount }}"
                                       data-minimum="{{ $dmethod->minimum }}"
                                       x-model="selectedMethod"
                                       @change="onMethodSelect($event.target)"
                                       class="sr-only peer">
                                <div class="p-4 rounded-xl border-2 transition-all duration-200 peer-checked:border-primary peer-checked:bg-primary/5 border-surface-border hover:border-content-tertiary">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-surface-overlay flex items-center justify-center flex-shrink-0">
                                            @if(in_array(strtolower($dmethod->name), ['bitcoin', 'ethereum', 'litecoin', 'usdt']))
                                                <svg class="w-5 h-5 text-content-secondary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" /></svg>
                                            @else
                                                <svg class="w-5 h-5 text-content-secondary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" /></svg>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-content-primary">{{ $dmethod->name }}</p>
                                            <p class="text-[11px] text-content-tertiary">
                                                Fee: {{ $dmethod->charges_type === 'percentage' ? $dmethod->charges_amount . '%' : \App\Helpers\CurrencyHelper::formatForUser($dmethod->charges_amount) }}
                                                &middot; Min: @money($dmethod->minimum)
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <p x-show="methodError" x-cloak class="text-xs text-loss mt-3">Please select a withdrawal method.</p>
                </div>
            </div>

            {{-- STEP 2: Payment Details --}}
            <div x-show="step === 2" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-surface-raised border border-surface-border rounded-xl p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" /></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-content-primary">Payment Details</h3>
                            <p class="text-xs text-content-tertiary">Enter your <span x-text="selectedMethod" class="font-medium text-content-secondary"></span> details</p>
                        </div>
                    </div>

                    {{-- Crypto --}}
                    <div x-show="methodType === 'crypto'" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-content-secondary mb-1.5">Wallet Address</label>
                            <input type="text" name="wallet_address" x-model="walletAddress"
                                   :placeholder="'Enter your ' + selectedMethod + ' wallet address'" autocomplete="off"
                                   class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm placeholder-content-tertiary focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                        <p x-show="detailsError" x-cloak class="text-xs text-loss">Please enter your wallet address.</p>
                    </div>

                    {{-- Bank --}}
                    <div x-show="methodType === 'currency'" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-content-secondary mb-1.5">Bank Name</label>
                            <input type="text" name="bankname" x-model="bankName" maxlength="100" autocomplete="off" placeholder="Enter your bank name"
                                   class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm placeholder-content-tertiary focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-content-secondary mb-1.5">Account Name</label>
                            <input type="text" name="account_name" x-model="accountName" maxlength="100" autocomplete="off" placeholder="Name on your account"
                                   class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm placeholder-content-tertiary focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-content-secondary mb-1.5">Account Number</label>
                            <input type="number" name="account_number" x-model="accountNumber" autocomplete="off" placeholder="Enter your account number"
                                   class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm placeholder-content-tertiary focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-content-secondary mb-1.5">Routing Number / SWIFT Code</label>
                            <input type="text" name="swift_code" x-model="swiftCode" maxlength="50" autocomplete="off" placeholder="Enter routing number or SWIFT/BIC code"
                                   class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm placeholder-content-tertiary focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                        <p x-show="detailsError" x-cloak class="text-xs text-loss">Please fill in all bank details.</p>
                    </div>
                </div>
            </div>

            {{-- STEP 3: Amount --}}
            <div x-show="step === 3" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-surface-raised border border-surface-border rounded-xl p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-content-primary">Enter Amount</h3>
                            <p class="text-xs text-content-tertiary">Available balance: <span class="text-gain font-semibold">@money(Auth::user()->account_bal)</span></p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-content-secondary mb-1.5">Withdrawal Amount (@userCurrency)</label>
                            <input type="number" name="amount" x-model="amount" placeholder="0.00" step="0.01" min="0" autocomplete="off"
                                   @input="calculateFees()"
                                   class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm placeholder-content-tertiary focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>

                        {{-- Fee Breakdown --}}
                        <div x-show="amount > 0" x-cloak class="bg-surface-overlay/50 rounded-lg p-4 space-y-2.5 border border-surface-border">
                            <div class="flex justify-between text-xs">
                                <span class="text-content-tertiary">Withdrawal Amount</span>
                                <span class="text-content-primary font-medium">@userCurrency<span x-text="parseFloat(amount || 0).toFixed(2)"></span></span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-content-tertiary">Fee (<span x-text="feeLabel"></span>)</span>
                                <span class="text-warning font-medium">- @userCurrency<span x-text="fees.toFixed(2)"></span></span>
                            </div>
                            <div class="border-t border-surface-border pt-2.5 flex justify-between text-sm">
                                <span class="text-content-secondary font-medium">Total Deducted</span>
                                <span class="text-content-primary font-semibold">@userCurrency<span x-text="totalDeducted.toFixed(2)"></span></span>
                            </div>
                        </div>

                        <p x-show="amountError" x-cloak class="text-xs text-loss" x-text="amountError"></p>
                    </div>
                </div>
            </div>

            {{-- STEP 4: Review --}}
            <div x-show="step === 4" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-surface-raised border border-surface-border rounded-xl p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-content-primary">Review & Confirm</h3>
                            <p class="text-xs text-content-tertiary">Please verify the details before submitting</p>
                        </div>
                    </div>

                    <div class="bg-surface-overlay/50 rounded-lg border border-surface-border divide-y divide-surface-border">
                        <div class="flex justify-between px-4 py-3">
                            <span class="text-xs text-content-tertiary">Method</span>
                            <span class="text-sm font-medium text-content-primary" x-text="selectedMethod"></span>
                        </div>
                        <div class="px-4 py-3" x-show="methodType === 'crypto'">
                            <span class="text-xs text-content-tertiary">Wallet Address</span>
                            <p class="text-sm font-mono text-content-primary mt-0.5 break-all" x-text="walletAddress"></p>
                        </div>
                        <div x-show="methodType === 'currency'" class="px-4 py-3 space-y-1">
                            <span class="text-xs text-content-tertiary">Bank Details</span>
                            <p class="text-sm text-content-primary" x-text="bankName"></p>
                            <p class="text-xs text-content-secondary"><span x-text="accountName"></span> &middot; <span x-text="accountNumber"></span></p>
                            <p class="text-xs text-content-secondary" x-show="swiftCode">SWIFT/Routing: <span x-text="swiftCode" class="font-mono"></span></p>
                        </div>
                        <div class="flex justify-between px-4 py-3">
                            <span class="text-xs text-content-tertiary">Amount</span>
                            <span class="text-sm font-medium text-content-primary">@userCurrency<span x-text="parseFloat(amount || 0).toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between px-4 py-3">
                            <span class="text-xs text-content-tertiary">Fee</span>
                            <span class="text-sm font-medium text-warning">- @userCurrency<span x-text="fees.toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between px-4 py-3 bg-surface-overlay/30">
                            <span class="text-sm font-semibold text-content-primary">Total Deducted</span>
                            <span class="text-sm font-bold text-content-primary">@userCurrency<span x-text="totalDeducted.toFixed(2)"></span></span>
                        </div>
                    </div>

                    <div class="mt-4 p-3 rounded-lg bg-warning/10 border border-warning/20">
                        <p class="text-xs text-content-primary">
                            <span class="font-semibold text-warning">Note:</span>
                            By confirming, the total amount (including fees) will be deducted from your account. Withdrawal requests are processed during business hours.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Navigation Buttons --}}
            <div class="flex items-center justify-between mt-6">
                <button type="button" x-show="step > 1" @click="step--"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg border border-surface-border text-sm font-medium text-content-secondary hover:bg-surface-overlay transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                    Back
                </button>
                <div x-show="step === 1" class="w-px"></div>

                <button type="button" x-show="step < 4" @click="nextStep()"
                        class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-semibold transition-colors ml-auto">
                    Continue
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                </button>

                <button type="submit" x-show="step === 4" x-cloak
                        class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-lg bg-gain hover:bg-gain/90 text-white text-sm font-semibold transition-colors ml-auto">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                    Confirm Withdrawal
                </button>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
@parent
<script>
    function withdrawalWizard() {
        return {
            step: 1,
            steps: ['Method', 'Details', 'Amount', 'Review'],
            selectedMethod: '',
            methodType: '',
            chargesType: '',
            chargesAmount: 0,
            minimum: 0,
            walletAddress: '',
            bankName: '',
            accountName: '',
            accountNumber: '',
            swiftCode: '',
            amount: '',
            fees: 0,
            totalDeducted: 0,
            feeLabel: '',
            methodError: false,
            detailsError: false,
            amountError: '',

            onMethodSelect(el) {
                this.methodType = el.getAttribute('data-methodtype');
                this.chargesType = el.getAttribute('data-charges-type');
                this.chargesAmount = parseFloat(el.getAttribute('data-charges-amount')) || 0;
                this.minimum = parseFloat(el.getAttribute('data-minimum')) || 0;
                this.methodError = false;
                this.calculateFees();
            },

            calculateFees() {
                const amt = parseFloat(this.amount) || 0;
                if (this.chargesType === 'percentage') {
                    this.fees = amt * this.chargesAmount / 100;
                    this.feeLabel = this.chargesAmount + '%';
                } else {
                    this.fees = this.chargesAmount;
                    this.feeLabel = 'fixed';
                }
                this.totalDeducted = amt + this.fees;
            },

            nextStep() {
                if (this.step === 1) {
                    if (!this.selectedMethod) { this.methodError = true; return; }
                    this.methodError = false;
                }
                if (this.step === 2) {
                    if (this.methodType === 'crypto' && !this.walletAddress.trim()) { this.detailsError = true; return; }
                    if (this.methodType === 'currency' && (!this.bankName.trim() || !this.accountName.trim() || !this.accountNumber || !this.swiftCode.trim())) { this.detailsError = true; return; }
                    this.detailsError = false;
                }
                if (this.step === 3) {
                    const amt = parseFloat(this.amount) || 0;
                    if (amt <= 0) { this.amountError = 'Please enter a valid amount.'; return; }
                    if (amt < this.minimum) { this.amountError = 'Minimum withdrawal is @userCurrency' + this.minimum.toFixed(2); return; }
                    this.amountError = '';
                    this.calculateFees();
                }
                this.step++;
            },

            validateFinal() {
                return this.step === 4 && this.selectedMethod && this.amount > 0;
            }
        };
    }
</script>
@endsection
