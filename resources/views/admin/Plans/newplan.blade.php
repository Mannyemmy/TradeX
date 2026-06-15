@extends('layouts.admin-dash')
@section('title', 'Add Investment Plan')
@section('content')
    <x-admin.page-header title="Add Investment Plan">
        <x-slot name="actions">
            <a href="{{ route('plans') }}"
               class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Back
            </a>
        </x-slot>
    </x-admin.page-header>

    <div class="mt-6">
        <x-admin.card>
            <form role="form" method="post" action="{{ route('addplan') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-admin.form-group label="Plan Name" :required="true">
                        <input class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                               placeholder="Enter Plan name" type="text" name="name" required>
                    </x-admin.form-group>

                    <x-admin.form-group label="Plan Price ({{ $settings->currency }})" :required="true"
                                        helper="This is the maximum amount a user can pay to invest in this plan, enter the value without a comma(,)">
                        <input class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                               placeholder="Enter Plan price" type="number" name="price" required>
                    </x-admin.form-group>

                    <x-admin.form-group label="Plan Minimum Price ({{ $settings->currency }})" :required="true"
                                        helper="This is the minimum amount a user can pay to invest in this plan, enter the value without a comma(,)">
                        <input class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                               placeholder="Enter Plan minimum price" type="number" name="min_price" required>
                    </x-admin.form-group>

                    <x-admin.form-group label="Plan Maximum Price ({{ $settings->currency }})" :required="true"
                                        helper="Same as plan price, enter the value without a comma(,)">
                        <input class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                               placeholder="Enter Plan maximum price" type="number" name="max_price" required>
                    </x-admin.form-group>

                    <x-admin.form-group label="Minimum Return (%)" :required="true"
                                        helper="This is the minimum return (ROI) for this plan, enter the value without a comma(,)">
                        <input class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                               placeholder="Enter minimum return" type="number" step="any" name="minr" required>
                    </x-admin.form-group>

                    <x-admin.form-group label="Maximum Return (%)" :required="true"
                                        helper="This is the Maximum return (ROI) for this plan, enter the value without a comma(,)">
                        <input class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                               placeholder="Enter maximum return" type="number" step="any" name="maxr" required>
                    </x-admin.form-group>

                    <x-admin.form-group label="Gift Bonus ({{ $settings->currency }})"
                                        helper="Optional Bonus if a user buys this plan. Enter the value without a comma(,)">
                        <input class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                               placeholder="Enter Additional Gift Bonus" type="number" step="any" name="gift" value="0" required>
                    </x-admin.form-group>

                    <x-admin.form-group label="Plan Tag"
                                        helper="Optional Plan tag. Tags for each plan eg 'Popular', 'VIP' etc">
                        <input class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                               placeholder="Enter Plan Tag" type="text" name="tag">
                    </x-admin.form-group>

                    <x-admin.form-group label="Top up Interval"
                                        helper="This specifies how often the system should add profit (ROI) to user account.">
                        <select class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" name="t_interval">
                            <option>Monthly</option>
                            <option>Weekly</option>
                            <option>Daily</option>
                            <option>Hourly</option>
                            <option>Every 30 Minutes</option>
                            <option>Every 10 Minutes</option>
                        </select>
                    </x-admin.form-group>

                    <x-admin.form-group label="Top up Type"
                                        helper="This specifies if the system should add profit in percentage (%) or a fixed amount.">
                        <select class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" name="t_type">
                            <option>Percentage</option>
                            <option>Fixed</option>
                        </select>
                    </x-admin.form-group>

                    <x-admin.form-group label="Top up Amount (in % or {{ $settings->currency }} as specified above)" :required="true"
                                        helper="This is the amount the system will add to users account as profit, based on what you selected in topup type and topup interval above.">
                        <input class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                               placeholder="top up amount" type="number" step="any" name="t_amount" required>
                    </x-admin.form-group>

                    <x-admin.form-group label="Investment Duration" :required="true">
                        <input class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                               placeholder="eg 1 Days, 2 Weeks, 1 Months" type="text" name="expiration" required>
                        <p class="text-xs text-content-muted mt-1">
                            This specifies how long the investment plan will run. Please strictly follow the guide on
                            <button type="button" @click="$dispatch('open-durationModal')" class="text-primary hover:underline">how to setup investment duration</button>
                            else it may not work.
                        </p>
                    </x-admin.form-group>

                    <div class="md:col-span-2">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                            Add Plan
                        </button>
                    </div>
                </div>
            </form>
        </x-admin.card>
    </div>

    {{-- Duration Help Modal --}}
    <x-admin.modal id="durationModal" title="Investment Duration Guide">
        <div class="space-y-4">
            <p class="text-sm text-content-secondary">
                <strong>FIRSTLY</strong>, always precede the time frame with a digit, that is do not write the number in letters.
            </p>
            <p class="text-sm text-content-secondary">
                <strong>SECONDLY</strong>, always add a space after the number.
            </p>
            <p class="text-sm text-content-secondary">
                <strong>LASTLY</strong>, the first letter of the timeframe should be in CAPS and always add 's' to the timeframe even if your duration is just a day, month or year.
            </p>
            <p class="text-lg font-semibold text-content">
                Eg, 1 Days, 3 Weeks, 1 Hours, 48 Hours, 4 Months, 1 Years, 9 Months
            </p>
        </div>
    </x-admin.modal>
@endsection
