{{-- Coinpayment Tab --}}
<x-admin.card>
    <h3 class="text-lg font-semibold text-content mb-5">Coinpayment Configuration</h3>
    <div class="max-w-2xl mx-auto">
        <form action="javascript:void(0)" method="POST" id="coinpayform">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <x-admin.form-group label="Merchant ID" for="cp_m_id" :required="true">
                    <input type="text" name="cp_m_id" placeholder="Merchant ID" value="{{ $cpd->cp_m_id }}" required
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </x-admin.form-group>

                <x-admin.form-group label="CoinPayment IPN Secret" for="cp_ipn_secret" :required="true">
                    <input type="text" name="cp_ipn_secret" placeholder="CoinPayment IPN Secret" value="{{ $cpd->cp_ipn_secret }}" required
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </x-admin.form-group>

                <x-admin.form-group label="CoinPayments Debug Email" for="cp_debug_email" :required="true">
                    <input type="text" name="cp_debug_email" placeholder="CoinPayments debug email" value="{{ $cpd->cp_debug_email }}" required
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </x-admin.form-group>

                <x-admin.form-group label="Public Key" for="cp_p_key" :required="true">
                    <input type="text" name="cp_p_key" placeholder="Public key" value="{{ $cpd->cp_p_key }}" required
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </x-admin.form-group>

                <x-admin.form-group label="Private Key" for="cp_pv_key" :required="true">
                    <input type="text" name="cp_pv_key" placeholder="Private key" value="{{ $cpd->cp_pv_key }}" required
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </x-admin.form-group>

                <div>
                    <button type="submit"
                        class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-5 py-2 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30">
                        Save
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-admin.card>
