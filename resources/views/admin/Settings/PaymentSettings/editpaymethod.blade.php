@extends('layouts.admin-dash')
@section('title', 'Update Payment Method')

@section('content')
    {{-- Page Header --}}
    <x-admin.page-header title="Update Payment Method" subtitle="Edit payment method configuration and details.">
        <x-slot name="actions">
            <a href="{{ route('paymentview') }}"
                class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Back
            </a>
        </x-slot>
    </x-admin.page-header>

    {{-- USDT Info Alert --}}
    @if ($method->name == 'USDT')
        <x-admin.alert type="info" :dismissible="true" class="mt-4">
            For your users to be able to withdraw via USDT when you use Binance as your merchant and you set withdrawal to automatic,
            you need to whitelist their IP address, else they will not be able to withdraw. To do that, check users login activities
            from manage users then collect their IP address and whitelist it on your Binance merchant dashboard.
        </x-admin.alert>
    @endif

    {{-- Edit Form --}}
    <div class="mt-6 max-w-3xl mx-auto" x-data="{ methodType: '{{ $method->methodtype }}' }">
        <x-admin.card>
            <form method="POST" action="{{ route('updatemethod') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Name --}}
                    <x-admin.form-group label="Name" for="name" class="md:col-span-2" :required="true">
                        @if ($method->defaultpay == 'yes')
                            <input type="text" name="name" placeholder="Payment method name" value="{{ $method->name }}" readonly
                                class="w-full bg-surface-alt border border-border rounded-lg px-3 py-2 text-sm text-content-muted cursor-not-allowed">
                        @else
                            <input type="text" name="name" placeholder="Payment method name" value="{{ $method->name }}" required
                                class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        @endif
                        @if ($method->name == 'Credit Card')
                            <p class="text-xs text-content-muted mt-1">Please ensure you have selected a credit card provider from the payment preference tab. Please delete paystack and stripe payment option as this method already makes use of them.</p>
                        @endif
                    </x-admin.form-group>

                    <x-admin.form-group label="Minimum Amount" for="minamount" helper="Required but only applies to withdrawal" :required="true">
                        <input type="number" name="minimum" id="minamount" value="{{ $method->minimum }}" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    <x-admin.form-group label="Maximum Amount" for="maxamount" helper="Required but only applies to withdrawal" :required="true">
                        <input type="number" name="maximum" id="maxamount" value="{{ $method->maximum }}" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    <x-admin.form-group label="Charges" for="charges" helper="Required but only applies to withdrawal" :required="true">
                        <input type="number" name="charges" id="charges" value="{{ $method->charges_amount }}" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    <x-admin.form-group label="Charges Type" helper="Required but only applies to withdrawal" :required="true">
                        <select name="chargetype" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            <option value="{{ $method->charges_type }}">{{ $method->charges_type }}</option>
                            <option value="percentage">Percentage(%)</option>
                            <option value="fixed">Fixed({{ $settings->currency }})</option>
                        </select>
                    </x-admin.form-group>

                    <x-admin.form-group label="Type" for="methodtype" :required="true">
                        <select name="methodtype" id="methodtype" required x-model="methodType"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            <option value="{{ $method->methodtype }}">{{ $method->methodtype }}</option>
                            <option value="currency">Currency</option>
                            <option value="crypto">Crypto</option>
                        </select>
                    </x-admin.form-group>

                    <x-admin.form-group label="Image URL" for="url">
                        <input type="text" name="url" id="url" value="{{ $method->img_url }}"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    {{-- Currency inputs --}}
                    <template x-if="methodType === 'currency'">
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-admin.form-group label="Bank Name" for="bank">
                                <input type="text" name="bank" id="bank" value="{{ $method->bankname }}" required
                                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            </x-admin.form-group>
                            <x-admin.form-group label="Account Name" for="acnt_name">
                                <input type="text" name="account_name" id="acnt_name" value="{{ $method->account_name }}" required
                                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            </x-admin.form-group>
                            <x-admin.form-group label="Account Number" for="acnt_number">
                                <input type="number" name="account_number" id="acnt_number" value="{{ $method->account_number }}" required
                                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            </x-admin.form-group>
                            <x-admin.form-group label="Swift/Other Code" for="swift">
                                <input type="text" name="swift" id="swift" value="{{ $method->swift_code }}"
                                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            </x-admin.form-group>
                        </div>
                    </template>

                    {{-- Cryptocurrency inputs --}}
                    <template x-if="methodType === 'crypto'">
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-admin.form-group label="Wallet Address" for="walletaddress">
                                <input type="text" name="walletaddress" id="walletaddress" value="{{ $method->wallet_address }}" required
                                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            </x-admin.form-group>
                            <x-admin.form-group label="Barcode">
                                <input type="file" name="barcode"
                                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            </x-admin.form-group>
                            <x-admin.form-group label="Wallet Address Network Type" for="wallettype">
                                <input type="text" name="wallettype" id="wallettype" value="{{ $method->network }}" required
                                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                @if ($method->name == 'USDT' or $method->name == 'BUSD')
                                    <p class="text-xs text-content-muted mt-1">Ensure your network for USDT payment is always TRC20 and BUSD payment is ERC20 if you set payment option to automatic and you are using coinpayment option. If you want to use manual payment option, you can use whatever network you prefer.</p>
                                @endif
                            </x-admin.form-group>
                        </div>
                    </template>

                    <x-admin.form-group label="Status" :required="true">
                        <select name="status" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            <option value="{{ $method->status }}">{{ $method->status }}</option>
                            <option value="enabled">Enable</option>
                            <option value="disabled">Disable</option>
                        </select>
                    </x-admin.form-group>

                    <x-admin.form-group label="Type for" :required="true">
                        <select name="typefor" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            <option value="{{ $method->type }}">{{ $method->type }}</option>
                            <option value="withdrawal">Withdrawal</option>
                            <option value="deposit">Deposit</option>
                            <option value="both">Both</option>
                        </select>
                    </x-admin.form-group>

                    <x-admin.form-group label="Optional Note" class="md:col-span-2">
                        <input type="text" name="note" value="{{ $method->duration }}" placeholder="Payment may take up to 24 hours"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    <input type="hidden" name="id" value="{{ $method->id }}">

                    <div class="md:col-span-2 flex items-center gap-3">
                        <button type="submit"
                            class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30">
                            Save Changes
                        </button>
                        <a href="{{ route('paymentview') }}"
                            class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
