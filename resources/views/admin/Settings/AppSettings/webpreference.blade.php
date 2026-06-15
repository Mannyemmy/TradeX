<x-admin.card>
    <h3 class="text-lg font-semibold text-content mb-1">Preferences</h3>
    <p class="text-sm text-content-muted mb-6">Control platform behavior — toggles, currencies, verification, and feature flags.</p>

    <form method="post" action="javascript:void(0)" id="updatepreference">
        @csrf
        @method('PUT')

        {{-- General Settings --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <x-admin.form-group label="Contact Email" for="contact_email" required>
                <input type="text" name="contact_email" id="contact_email"
                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                    value="{{ $settings->contact_email }}" required>
            </x-admin.form-group>

            <div>
                <input name="s_currency" value="{{ $settings->s_currency }}" id="s_c" type="hidden">
                <x-admin.form-group label="Website Currency" for="select_c">
                    <select name="currency" id="select_c" class="select2 w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        onchange="changecurr()" style="width: 100%">
                        <option value="{{ htmlentities($settings->currency) }}">{{ $settings->currency }}</option>
                        @foreach ($currencies as $key => $currency)
                            <option id="{{ $key }}" value="{{ html_entity_decode($currency) }}">
                                {{ $key . ' (' . html_entity_decode($currency) . ')' }}
                            </option>
                        @endforeach
                    </select>
                </x-admin.form-group>
            </div>

            <input type="hidden" value="{{ $settings->site_preference }}" name="site_preference">

            <x-admin.form-group label="HomePage URL (Redirect)" for="redirect_url"
                helper="If you use a custom homepage, enter the URL here. If empty, the system uses the default pages.">
                <input type="text" name="redirect_url" id="redirect_url" placeholder="eg https://myhomepage.com"
                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                    value="{{ $settings->redirect_url }}">
            </x-admin.form-group>
        </div>

        {{-- Feature Toggles --}}
        <div class="border-t border-border mt-6 pt-6">
            <h4 class="text-base font-medium text-content mb-5">Feature Toggles</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Announcement --}}
                <div class="bg-surface-alt/50 rounded-lg p-4 border border-border">
                    <label class="block text-sm font-medium text-content mb-2">Announcement</label>
                    <div class="flex items-center gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="annouc" value="on" class="text-primary focus:ring-primary"
                                {{ $settings->enable_annoc == 'on' ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">On</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="annouc" value="off" class="text-primary focus:ring-primary"
                                {{ $settings->enable_annoc != 'on' ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">Off</span>
                        </label>
                    </div>
                </div>

                {{-- Weekend Trade --}}
                <div class="bg-surface-alt/50 rounded-lg p-4 border border-border">
                    <label class="block text-sm font-medium text-content mb-2">Weekend Trade</label>
                    <div class="flex items-center gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="weekend_trade" value="on" class="text-primary focus:ring-primary"
                                {{ $settings->weekend_trade == 'on' ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">On</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="weekend_trade" value="off" class="text-primary focus:ring-primary"
                                {{ $settings->weekend_trade != 'on' ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">Off</span>
                        </label>
                    </div>
                    <p class="text-xs text-content-muted mt-2">If off, users won't receive ROI on weekends</p>
                </div>

                {{-- Withdrawals --}}
                <div class="bg-surface-alt/50 rounded-lg p-4 border border-border">
                    <label class="block text-sm font-medium text-content mb-2">Withdrawals</label>
                    <div class="flex items-center gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="withdraw" value="true" class="text-primary focus:ring-primary"
                                {{ $settings->enable_with == 'true' ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">Enable</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="withdraw" value="false" class="text-primary focus:ring-primary"
                                {{ $settings->enable_with != 'true' ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">Disable</span>
                        </label>
                    </div>
                    <p class="text-xs text-content-muted mt-2">If disabled, users can't place withdrawal requests</p>
                </div>

                {{-- Google ReCaptcha --}}
                <div class="bg-surface-alt/50 rounded-lg p-4 border border-border">
                    <label class="block text-sm font-medium text-content mb-2">Google ReCaptcha</label>
                    <div class="flex items-center gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="captcha" value="true" class="text-primary focus:ring-primary"
                                {{ $settings->captcha == 'true' ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">On</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="captcha" value="false" class="text-primary focus:ring-primary"
                                {{ $settings->captcha != 'true' ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">Off</span>
                        </label>
                    </div>
                    <p class="text-xs text-content-muted mt-2">If on, users must pass Google reCAPTCHA on registration</p>
                </div>

                {{-- Translation --}}
                <div class="bg-surface-alt/50 rounded-lg p-4 border border-border">
                    <label class="block text-sm font-medium text-content mb-2">Translation</label>
                    <div class="flex items-center gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="googlet" value="on" class="text-primary focus:ring-primary"
                                {{ $settings->google_translate == 'on' ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">On</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="googlet" value="off" class="text-primary focus:ring-primary"
                                {{ $settings->google_translate != 'on' ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">Off</span>
                        </label>
                    </div>
                    <p class="text-xs text-content-muted mt-2">Enables language selection via Google Translate</p>
                </div>

                {{-- Trade Mode --}}
                <div class="bg-surface-alt/50 rounded-lg p-4 border border-border">
                    <label class="block text-sm font-medium text-content mb-2">Trade Mode</label>
                    <div class="flex items-center gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="trade_mode" value="on" class="text-primary focus:ring-primary"
                                {{ $settings->trade_mode == 'on' ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">On</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="trade_mode" value="off" class="text-primary focus:ring-primary"
                                {{ $settings->trade_mode != 'on' ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">Off</span>
                        </label>
                    </div>
                    <p class="text-xs text-content-muted mt-2">If off, users won't receive any ROI</p>
                </div>

                {{-- KYC --}}
                <div class="bg-surface-alt/50 rounded-lg p-4 border border-border">
                    <label class="block text-sm font-medium text-content mb-2">KYC (Verification)</label>
                    <div class="flex items-center gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="enable_kyc" value="yes" class="text-primary focus:ring-primary"
                                {{ $settings->enable_kyc == 'yes' ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">On</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="enable_kyc" value="no" class="text-primary focus:ring-primary"
                                {{ $settings->enable_kyc != 'yes' ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">Off</span>
                        </label>
                    </div>
                    <p class="text-xs text-content-muted mt-2">Require document verification before withdrawals</p>
                </div>

                {{-- KYC on Registration --}}
                <div class="bg-surface-alt/50 rounded-lg p-4 border border-border">
                    <label class="block text-sm font-medium text-content mb-2">KYC on Registration</label>
                    <div class="flex items-center gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="enable_kyc_registration" value="yes" class="text-primary focus:ring-primary"
                                {{ $settings->enable_kyc_registration == 'yes' ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">On</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="enable_kyc_registration" value="no" class="text-primary focus:ring-primary"
                                {{ $settings->enable_kyc_registration != 'yes' ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">Off</span>
                        </label>
                    </div>
                    <p class="text-xs text-content-muted mt-2">Force KYC during registration. Blocks all operations until admin verifies.</p>
                </div>

                {{-- Google Login --}}
                <div class="bg-surface-alt/50 rounded-lg p-4 border border-border">
                    <label class="block text-sm font-medium text-content mb-2">Google Login</label>
                    <div class="flex items-center gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="social" value="yes" class="text-primary focus:ring-primary"
                                {{ $settings->enable_social_login == 'yes' ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">On</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="social" value="no" class="text-primary focus:ring-primary"
                                {{ $settings->enable_social_login != 'yes' ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">Off</span>
                        </label>
                    </div>
                    <p class="text-xs text-content-muted mt-2">Allow login/register via Google account</p>
                </div>

                {{-- Email Verification --}}
                <div class="bg-surface-alt/50 rounded-lg p-4 border border-border">
                    <label class="block text-sm font-medium text-content mb-2">Email Verification</label>
                    <div class="flex items-center gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="enail_verify" value="true" class="text-primary focus:ring-primary"
                                {{ $settings->enable_verification == 'true' ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">Enable</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="enail_verify" value="false" class="text-primary focus:ring-primary"
                                {{ $settings->enable_verification != 'true' ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">Disable</span>
                        </label>
                    </div>
                    <p class="text-xs text-content-muted mt-2">If disabled, users won't be asked to verify their email</p>
                </div>

                {{-- Return Capital --}}
                <div class="bg-surface-alt/50 rounded-lg p-4 border border-border">
                    <label class="block text-sm font-medium text-content mb-2">Return Capital</label>
                    <div class="flex items-center gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="return_capital" value="true" class="text-primary focus:ring-primary"
                                {{ $settings->return_capital ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">Yes</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="return_capital" value="false" class="text-primary focus:ring-primary"
                                {{ !$settings->return_capital ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">No</span>
                        </label>
                    </div>
                    <p class="text-xs text-content-muted mt-2">If No, capital isn't returned after investment plan expires</p>
                </div>

                {{-- Plan Cancellation --}}
                <div class="bg-surface-alt/50 rounded-lg p-4 border border-border">
                    <label class="block text-sm font-medium text-content mb-2">Plan Cancellation</label>
                    <div class="flex items-center gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="should_cancel_plan" value="1" class="text-primary focus:ring-primary"
                                {{ $settings->should_cancel_plan ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">On</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="should_cancel_plan" value="0" class="text-primary focus:ring-primary"
                                {{ !$settings->should_cancel_plan ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">Off</span>
                        </label>
                    </div>
                    <p class="text-xs text-content-muted mt-2">Allow users to cancel active investment plans (capital returned)</p>
                </div>

            </div>
        </div>

        {{-- Submit --}}
        <div class="mt-6 pt-4 border-t border-border">
            <button type="submit"
                class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-6 py-2.5 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30">
                Save Preferences
            </button>
        </div>
    </form>
</x-admin.card>
