<x-admin.card>
    <h3 class="text-lg font-semibold text-content mb-5">Referral Commissions</h3>
    <form method="post" action="javascript:void(0)" id="refform">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-admin.form-group label="Direct Referral Commission (%)" for="ref_commission" :required="true">
                <input type="text" name="ref_commission" id="ref_commission" value="{{ $settings->referral_commission }}" required
                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
            </x-admin.form-group>

            <x-admin.form-group label="Indirect Referral Commission 1 (%)" for="ref_commission1" :required="true">
                <input type="text" name="ref_commission1" id="ref_commission1" value="{{ $settings->referral_commission1 }}" required
                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
            </x-admin.form-group>

            <x-admin.form-group label="Indirect Referral Commission 2 (%)" for="ref_commission2" :required="true">
                <input type="text" name="ref_commission2" id="ref_commission2" value="{{ $settings->referral_commission2 }}" required
                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
            </x-admin.form-group>

            <x-admin.form-group label="Indirect Referral Commission 3 (%)" for="ref_commission3" :required="true">
                <input type="text" name="ref_commission3" id="ref_commission3" value="{{ $settings->referral_commission3 }}" required
                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
            </x-admin.form-group>

            <x-admin.form-group label="Indirect Referral Commission 4 (%)" for="ref_commission4" :required="true">
                <input type="text" name="ref_commission4" id="ref_commission4" value="{{ $settings->referral_commission4 }}" required
                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
            </x-admin.form-group>

            <x-admin.form-group label="Indirect Referral Commission 5 (%)" for="ref_commission5" :required="true">
                <input type="text" name="ref_commission5" id="ref_commission5" value="{{ $settings->referral_commission5 }}" required
                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
            </x-admin.form-group>

            <div class="md:col-span-2">
                <button type="submit"
                    class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30">
                    Update
                </button>
                <input type="hidden" name="id" value="1">
            </div>
        </div>
    </form>
</x-admin.card>
