{{-- Payment Preference Tab --}}
<x-admin.card>
    <h3 class="text-lg font-semibold text-content mb-5">Payment Preferences</h3>
    <form action="javascript:void(0)" method="POST" id="paypreform">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <x-admin.form-group label="Deposit Option" for="deposit_option">
                <select name="deposit_option"
                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    <option value="{{ $settings->deposit_option }}">{{ $settings->deposit_option }} (Current)</option>
                    <option value="manual">Manual</option>
                    <option value="auto">Automatic</option>
                </select>
            </x-admin.form-group>

            <x-admin.form-group label="Withdrawal Option" for="withdrawal_option">
                <select name="withdrawal_option"
                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    <option value="{{ $settings->withdrawal_option }}">{{ $settings->withdrawal_option }} (Current)</option>
                    <option value="manual">Manual</option>
                    <option value="auto">Automatic</option>
                </select>
            </x-admin.form-group>

            <x-admin.form-group label="Minimum Deposit Amount" for="minamt" helper="This amount indicates the minimum amount a user can deposit" :required="true">
                <input type="text" name="minamt" value="{{ $moresettings->minamt }}" required
                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
            </x-admin.form-group>

            <x-admin.form-group label="Merchant for Automatic USDT Payment" for="merchat" helper="Please make sure you have entered your API keys for your selected USDT Merchant. Click the Gateway/Coinpayment tab to confirm that. NOTE: Your website currency must be USD in order to use Binance.">
                <select name="merchat"
                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    <option value="{{ $settings->auto_merchant_option }}">{{ $settings->auto_merchant_option }}</option>
                    <option value="Coinpayment">Coinpayment</option>
                    <option value="Binance">Binance</option>
                </select>
            </x-admin.form-group>

            <x-admin.form-group label="Withdrawal Deduction" for="deduction_option" helper="This specifies if you want users account to be deducted immediately they place a withdrawal request or when admin approves it.">
                <select name="deduction_option"
                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    <option value="{{ $settings->deduction_option }}">
                        {{ $settings->deduction_option == 'userRequest' ? 'Deduct on user request' : 'Deduct when admin approves' }}
                    </option>
                    <option value="userRequest">Deduct on user request</option>
                    <option value="AdminApprove">Deduct when admin approves</option>
                </select>
            </x-admin.form-group>

            <x-admin.form-group label="Credit Card Payment Provider" for="credit_card_provider" helper="Signifies the provider to be used when users choose to deposit with their credit/debit card. Ensure you have entered the correct API keys for your selected option.">
                <select name="credit_card_provider"
                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    <option>{{ $settings->credit_card_provider }}</option>
                    <option>Paystack</option>
                    <option>Flutterwave</option>
                    <option>Stripe</option>
                </select>
            </x-admin.form-group>

            <div class="md:col-span-2">
                <button type="submit"
                    class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30">
                    Save
                </button>
            </div>
        </div>
    </form>
</x-admin.card>
