<x-admin.card>
    <h3 class="text-lg font-semibold text-content mb-1">Email & Authentication</h3>
    <p class="text-sm text-content-muted mb-6">Configure your mail server, Google OAuth, and reCAPTCHA credentials.</p>

    <form action="javascript:void(0)" method="POST" id="emailform">
        @csrf
        @method('PUT')

        {{-- Mail Server --}}
        <div class="mb-6">
            <label class="block text-sm font-medium text-content mb-3">Mail Server</label>
            <div class="flex items-center gap-4">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="server" id="sendmailserver" value="sendmail"
                        class="text-primary focus:ring-primary" checked>
                    <span class="text-sm text-content-secondary">Sendmail</span>
                </label>
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="server" id="smtpserver" value="smtp"
                        class="text-primary focus:ring-primary">
                    <span class="text-sm text-content-secondary">SMTP</span>
                </label>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <x-admin.form-group label="Email From" for="emailfrom" required>
                <input type="email" name="emailfrom" id="emailfrom"
                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                    value="{{ $settings->emailfrom }}" required>
            </x-admin.form-group>

            <x-admin.form-group label="Email From Name" for="emailfromname" required>
                <input type="text" name="emailfromname" id="emailfromname"
                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                    value="{{ $settings->emailfromname }}" required>
            </x-admin.form-group>

            {{-- SMTP Fields (hidden by default) --}}
            <div class="smtp-field hidden">
                <x-admin.form-group label="SMTP Host" for="smtp_host">
                    <input type="text" name="smtp_host" id="smtp_host"
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        value="{{ $settings->smtp_host }}">
                </x-admin.form-group>
            </div>

            <div class="smtp-field hidden">
                <x-admin.form-group label="SMTP Port" for="smtp_port">
                    <input type="text" name="smtp_port" id="smtp_port"
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        value="{{ $settings->smtp_port }}">
                </x-admin.form-group>
            </div>

            <div class="smtp-field hidden">
                <x-admin.form-group label="SMTP Encryption" for="smtp_encrypt">
                    <input type="text" name="smtp_encrypt" id="smtp_encrypt"
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        value="{{ $settings->smtp_encrypt }}">
                </x-admin.form-group>
            </div>

            <div class="smtp-field hidden">
                <x-admin.form-group label="SMTP Username" for="smtp_user">
                    <input type="text" name="smtp_user" id="smtp_user"
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        value="{{ $settings->smtp_user }}">
                </x-admin.form-group>
            </div>

            <div class="smtp-field hidden">
                <x-admin.form-group label="SMTP Password" for="smtp_password">
                    <input type="password" name="smtp_password" id="smtp_password"
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        value="{{ $settings->smtp_password }}">
                </x-admin.form-group>
            </div>
        </div>

        {{-- Google Login Credentials --}}
        <div class="border-t border-border mt-6 pt-6">
            <h4 class="text-base font-medium text-content mb-1">Google Login Credentials</h4>
            <p class="text-sm text-content-muted mb-4">From console.cloud.google.com</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-admin.form-group label="Client ID" for="google_id" helper="From console.cloud.google.com">
                    <input type="text" name="google_id" id="google_id"
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        value="{{ $settings->google_id }}">
                </x-admin.form-group>

                <x-admin.form-group label="Client Secret" for="google_secret" helper="From console.cloud.google.com">
                    <input type="text" name="google_secret" id="google_secret"
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        value="{{ $settings->google_secret }}">
                </x-admin.form-group>

                <div class="md:col-span-2">
                    <x-admin.form-group label="Redirect URL" for="google_redirect"
                        helper="Set this as Valid OAuth Redirect URI in console.cloud.google.com">
                        <input type="text" name="google_redirect" id="google_redirect"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                            value="{{ $settings->google_redirect }}">
                    </x-admin.form-group>
                </div>
            </div>
        </div>

        {{-- Google Captcha Credentials --}}
        <div class="border-t border-border mt-6 pt-6">
            <h4 class="text-base font-medium text-content mb-1">Google Captcha Credentials</h4>
            <p class="text-sm text-content-muted mb-4">From google.com/recaptcha/admin/create</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-admin.form-group label="Captcha Secret" for="capt_secret">
                    <input type="text" name="capt_secret" id="capt_secret"
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        value="{{ $settings->capt_secret }}">
                </x-admin.form-group>

                <x-admin.form-group label="Captcha Site-Key" for="capt_sitekey">
                    <input type="text" name="capt_sitekey" id="capt_sitekey"
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        value="{{ $settings->capt_sitekey }}">
                </x-admin.form-group>
            </div>
        </div>

        {{-- Submit --}}
        <div class="mt-6 pt-4 border-t border-border">
            <button type="submit"
                class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-6 py-2.5 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30">
                Save Email Settings
            </button>
        </div>
    </form>
</x-admin.card>

@if ($settings->mail_server == 'sendmail')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById("sendmailserver").checked = true;
        });
    </script>
@else
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById("smtpserver").checked = true;
            document.querySelectorAll('.smtp-field').forEach(el => {
                el.classList.remove('hidden');
                el.setAttribute('required', '');
            });
        });
    </script>
@endif
