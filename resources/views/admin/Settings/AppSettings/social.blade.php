<x-admin.card>
    <h3 class="text-lg font-semibold text-content mb-1">Social Login</h3>
    <p class="text-sm text-content-muted mb-6">Configure social login providers for your users.</p>

    <form action="" method="post">
        @csrf

        {{-- Provider Selection --}}
        <div class="mb-6">
            <label class="block text-sm font-medium text-content mb-3">Choose social login to use</label>
            <div class="flex items-center gap-4">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="social" id="both" value="both"
                        class="text-primary focus:ring-primary" checked>
                    <span class="text-sm text-content-secondary">Both</span>
                </label>
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="social" id="facebook" value="facebook"
                        class="text-primary focus:ring-primary">
                    <span class="text-sm text-content-secondary">Facebook</span>
                </label>
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="social" id="google" value="google"
                        class="text-primary focus:ring-primary">
                    <span class="text-sm text-content-secondary">Google</span>
                </label>
            </div>
        </div>

        {{-- Facebook Credentials --}}
        <div class="border-t border-border mt-6 pt-6">
            <h4 class="text-base font-medium text-content mb-4">Facebook Credentials</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-admin.form-group label="App ID" for="fb_app_id" helper="From developer.facebook.com">
                    <input type="text" name="site_name" id="fb_app_id"
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        value="{{ $settings->site_name }}" required>
                </x-admin.form-group>

                <x-admin.form-group label="App Secret" for="fb_app_secret" helper="From developer.facebook.com">
                    <input type="text" name="site_name" id="fb_app_secret"
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        value="{{ $settings->site_name }}" required>
                </x-admin.form-group>

                <div class="md:col-span-2">
                    <x-admin.form-group label="Redirect URL" for="fb_redirect"
                        helper="Set this as Valid OAuth Redirect URI in developers.facebook.com">
                        <input type="text" name="site_name" id="fb_redirect"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                            value="{{ $settings->site_name }}" required>
                    </x-admin.form-group>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="mt-6 pt-4 border-t border-border">
            <button type="submit"
                class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-6 py-2.5 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30">
                Save
            </button>
        </div>
    </form>
</x-admin.card>
