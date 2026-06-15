<x-admin.card>
    <h3 class="text-lg font-semibold text-content mb-1">Website Information</h3>
    <p class="text-sm text-content-muted mb-6">Update your website name, description, logo, and other public-facing details.</p>

    <form method="post" action="{{ route('updatewebinfo') }}" id="appinfoform" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        {{-- Basic Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <x-admin.form-group label="Website Name" for="site_name" required>
                <input type="text" name="site_name" id="site_name"
                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                    value="{{ $settings->site_name }}" required>
            </x-admin.form-group>

            <x-admin.form-group label="Website Title" for="site_title" required>
                <input type="text" name="site_title" id="site_title"
                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                    value="{{ $settings->site_title }}" required>
            </x-admin.form-group>

            <x-admin.form-group label="Website Keywords" for="keywords" required>
                <input type="text" name="keywords" id="keywords"
                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                    value="{{ $settings->keywords }}" required>
            </x-admin.form-group>

            <x-admin.form-group label="Website URL" for="site_address" required helper="e.g. https://yoursite.com">
                <input type="text" name="site_address" id="site_address" placeholder="https://yoursite.com"
                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                    value="{{ $settings->site_address }}" required>
            </x-admin.form-group>
        </div>

        <div class="mt-5">
            <x-admin.form-group label="Website Description" for="description">
                <textarea name="description" id="description" rows="3"
                    class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">{{ $settings->description }}</textarea>
            </x-admin.form-group>
        </div>

        {{-- Announcements & Messages --}}
        <div class="border-t border-border mt-6 pt-6">
            <h4 class="text-base font-medium text-content mb-4">Announcements & Notifications</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <x-admin.form-group label="Announcement" for="update">
                        <textarea name="update" id="update" rows="2"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">{{ $settings->newupdate }}</textarea>
                    </x-admin.form-group>
                </div>

                <x-admin.form-group label="Welcome Message" for="welcome_message"
                    helper="Displayed to users whose registration date is ≤ 3 days">
                    <textarea name="welcome_message" id="welcome_message" rows="2"
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">{{ $settings->welcome_message }}</textarea>
                </x-admin.form-group>

                <div>
                    <label class="block text-sm font-medium text-content mb-1.5">Send Trades Email</label>
                    <div class="flex items-center gap-4 mt-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="trade_message" value="on"
                                class="text-primary focus:ring-primary"
                                {{ $settings->trade_mode == 'on' ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">On</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="trade_message" value="off"
                                class="text-primary focus:ring-primary"
                                {{ $settings->trade_mode != 'on' ? 'checked' : '' }}>
                            <span class="text-sm text-content-secondary">Off</span>
                        </label>
                    </div>
                    <p class="text-xs text-content-muted mt-1">If off, users won't receive email when a trade is executed.</p>
                </div>
            </div>
        </div>

        {{-- Contact & Misc --}}
        <div class="border-t border-border mt-6 pt-6">
            <h4 class="text-base font-medium text-content mb-4">Contact & Miscellaneous</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-admin.form-group label="WhatsApp Number" for="whatsapp">
                    <input type="text" name="whatsapp" id="whatsapp"
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        value="{{ $settings->whatsapp }}">
                </x-admin.form-group>

                <x-admin.form-group label="NFT Gas Fee (ETH)" for="gasfee">
                    <input type="text" name="gasfee" id="gasfee"
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        value="{{ $settings->gasfee }}"
                        pattern="^\d*(\.\d{0,8})?$"
                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1')">
                </x-admin.form-group>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                <x-admin.form-group label="Timezone" for="timezone">
                    <select name="timezone" id="timezone" class="select2 w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        <option>{{ $settings->timezone }}</option>
                        @foreach ($timezones as $list)
                            <option value="{{ $list }}">{{ $list }}</option>
                        @endforeach
                    </select>
                </x-admin.form-group>

                <x-admin.form-group label="Installation Type" for="install_type">
                    <select name="install_type" id="install_type"
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        <option>{{ $settings->install_type }}</option>
                        <option>Main-Domain</option>
                        <option>Sub-Domain</option>
                        <option>Sub-Folder</option>
                    </select>
                </x-admin.form-group>
            </div>
        </div>

        {{-- Logo & Favicon --}}
        <div class="border-t border-border mt-6 pt-6">
            <h4 class="text-base font-medium text-content mb-4">Branding</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <x-admin.form-group label="Logo" for="logo" helper="Recommended: max 200×100px">
                        <input type="file" name="logo" id="logo"
                            class="w-full text-sm text-content-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-light file:text-primary hover:file:bg-primary/20 cursor-pointer">
                    </x-admin.form-group>
                    <div class="mt-3 p-3 border border-border rounded-lg text-center bg-surface-alt">
                        <img src="{{ asset('storage/app/public/' . $settings->logo) }}" alt="Logo" class="max-h-16 inline-block">
                    </div>
                </div>
                <div>
                    <x-admin.form-group label="Favicon" for="favicon" helper="Recommended: max 32×32px">
                        <input type="file" name="favicon" id="favicon"
                            class="w-full text-sm text-content-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-light file:text-primary hover:file:bg-primary/20 cursor-pointer">
                    </x-admin.form-group>
                    <div class="mt-3 p-3 border border-border rounded-lg text-center bg-surface-alt">
                        <img src="{{ asset('storage/app/public/' . $settings->favicon) }}" alt="Favicon" class="max-h-8 inline-block">
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="mt-6 pt-4 border-t border-border">
            <button type="submit"
                class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-6 py-2.5 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30">
                Update Information
            </button>
        </div>
    </form>
</x-admin.card>
