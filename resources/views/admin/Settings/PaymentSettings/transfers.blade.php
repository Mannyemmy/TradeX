{{-- Transfer Settings Tab --}}
<x-admin.card>
    <h3 class="text-lg font-semibold text-content mb-5">Transfer Settings</h3>
    <div class="max-w-2xl mx-auto">
        <form action="javascript:void(0)" method="POST" id="trasfer">
            @csrf
            @method('PUT')
            <div class="space-y-5">
                <x-admin.form-group label="User Transfers" helper="Turn on if you want to use this feature, otherwise turn it off.">
                    <div class="flex items-center gap-4 mt-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="usertransfer" value="1"
                                class="w-4 h-4 text-primary border-border focus:ring-primary/30"
                                {{ $moresettings->use_transfer ? 'checked' : '' }}>
                            <span class="text-sm text-content">On</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="usertransfer" value="0"
                                class="w-4 h-4 text-primary border-border focus:ring-primary/30"
                                {{ $moresettings->use_transfer ? '' : 'checked' }}>
                            <span class="text-sm text-content">Off</span>
                        </label>
                    </div>
                </x-admin.form-group>

                <x-admin.form-group label="Minimum Transfer Amount ({{ $settings->currency }})" for="min_transfer">
                    <input type="number" name="min_transfer" value="{{ $moresettings->min_transfer }}"
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </x-admin.form-group>

                <x-admin.form-group label="Charges (%)" for="charges" helper="Enter 0 if you don't want any charges">
                    <input type="number" name="charges" value="{{ $moresettings->transfer_charges }}"
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </x-admin.form-group>

                <div>
                    <button type="submit"
                        class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30">
                        Save Settings
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-admin.card>
