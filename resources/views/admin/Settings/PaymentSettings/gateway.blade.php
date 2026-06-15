{{-- Gateways Tab --}}
<x-admin.card>
    <form action="javascript:void(0)" method="POST" id="gatewayform">
        @csrf
        @method('PUT')
        <div class="space-y-8">
            {{-- Stripe Section --}}
            <div>
                <h4 class="text-base font-semibold text-primary flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
                    Stripe
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-admin.form-group label="Stripe Secret Key" for="s_s_k">
                        <input type="text" name="s_s_k" value="{{ $settings->s_s_k }}"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>
                    <x-admin.form-group label="Stripe Publishable Key" for="s_p_k">
                        <input type="text" name="s_p_k" value="{{ $settings->s_p_k }}"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>
                </div>
            </div>

            <hr class="border-border">

            {{-- Paypal Section --}}
            <div>
                <h4 class="text-base font-semibold text-primary flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    PayPal
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-admin.form-group label="Paypal Client ID" for="pp_ci">
                        <input type="text" name="pp_ci" value="{{ $settings->pp_ci }}"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>
                    <x-admin.form-group label="Paypal Client Secret" for="pp_cs">
                        <input type="text" name="pp_cs" value="{{ $settings->pp_cs }}"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>
                </div>
            </div>

            <hr class="border-border">

            {{-- Paystack Section --}}
            <div>
                <h4 class="text-base font-semibold text-primary flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
                    Paystack
                </h4>
                <x-admin.alert type="info" class="mb-4">
                    Make sure to set in your Paystack dashboard the Callback URL: <strong>{{ $settings->site_address }}/dashboard/paystackcallback</strong>
                </x-admin.alert>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-admin.form-group label="Paystack Public Key" for="paystack_public_key">
                        <input type="text" name="paystack_public_key" value="{{ $paystack->paystack_public_key }}"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>
                    <x-admin.form-group label="Paystack Secret Key" for="paystack_secret_key">
                        <input type="text" name="paystack_secret_key" value="{{ $paystack->paystack_secret_key }}"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>
                    <x-admin.form-group label="Paystack URL" for="paystack_url">
                        <input type="text" name="paystack_url" value="{{ $paystack->paystack_url }}" readonly
                            class="w-full bg-surface-alt border border-border rounded-lg px-3 py-2 text-sm text-content-muted focus:outline-none cursor-not-allowed">
                    </x-admin.form-group>
                    <x-admin.form-group label="Paystack Email" for="paystack_email">
                        <input type="text" name="paystack_email" value="{{ $paystack->paystack_email }}"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>
                </div>
            </div>

            <hr class="border-border">

            {{-- Flutterwave Section --}}
            <div>
                <h4 class="text-base font-semibold text-primary flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
                    Flutterwave
                </h4>
                <p class="text-sm text-content-muted mb-4">
                    From <a href="https://dashboard.flutterwave.com/login" target="_blank" class="text-primary hover:text-primary-hover transition-colors">dashboard.flutterwave.com</a>
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-admin.form-group label="Flutterwave Public Key" for="flw_public_key">
                        <input type="text" name="flw_public_key" value="{{ $moresettings->flw_public_key }}"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>
                    <x-admin.form-group label="Flutterwave Secret Key" for="flw_secret_key">
                        <input type="text" name="flw_secret_key" value="{{ $moresettings->flw_secret_key }}"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>
                    <x-admin.form-group label="Flutterwave Secret Hash" for="flw_secret_hash" class="md:col-span-2">
                        <input type="text" name="flw_secret_hash" value="{{ $moresettings->flw_secret_hash }}"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>
                </div>
            </div>

            <hr class="border-border">

            {{-- Binance Section --}}
            <div>
                <h4 class="text-base font-semibold text-primary flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" /></svg>
                    Binance
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-admin.form-group label="Binance API Key" for="bnc_api_key" helper="From merchant.binance.com">
                        <input type="text" name="bnc_api_key" value="{{ $moresettings->bnc_api_key }}"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>
                    <x-admin.form-group label="Binance Secret Key" for="bnc_secret_key" helper="From merchant.binance.com">
                        <input type="text" name="bnc_secret_key" value="{{ $moresettings->bnc_secret_key }}"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30">
                    Save Settings
                </button>
            </div>
        </div>
    </form>
</x-admin.card>
