<x-admin.card>
    <h3 class="text-lg font-semibold text-content mb-5">Other Bonuses</h3>
    <form method="post" action="javascript:void(0)" id="bonusform">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-admin.form-group label="Registration/Welcome Bonus ({{ $settings->currency }})" for="signup_bonus" helper="New registration bonus gets added to new users account." :required="true">
                <input type="text" name="signup_bonus" id="signup_bonus" value="{{ $settings->signup_bonus }}" required
                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
            </x-admin.form-group>

            <x-admin.form-group label="Deposit Bonus (%)" for="deposit_bonus" helper="The system calculates the percentage amount you specified with the users deposit amount and adds it as a bonus for every deposit." :required="true">
                <input type="text" name="deposit_bonus" id="deposit_bonus" value="{{ $settings->deposit_bonus }}" required
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
