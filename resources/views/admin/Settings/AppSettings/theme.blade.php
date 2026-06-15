<x-admin.card>
    <h3 class="text-lg font-semibold text-content mb-1">Theme & Display</h3>
    <p class="text-sm text-content-muted mb-6">Upload a new dashboard theme or change the color scheme.</p>

    {{-- Theme Upload --}}
    <div class="border border-border rounded-xl p-5 bg-surface-alt/30">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h4 class="text-base font-medium text-content">Upload New Theme</h4>
                <p class="text-sm text-content-muted mt-1">Your new theme will be applied to the user dashboard upon successful upload.</p>
            </div>
        </div>

        <form method="post" action="{{ route('theme.update') }}" enctype="multipart/form-data" id="themeForm" class="flex flex-col sm:flex-row items-start sm:items-end gap-4">
            @csrf
            <div class="flex-1">
                <input type="file" name="theme" required
                    class="w-full text-sm text-content-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-light file:text-primary hover:file:bg-primary/20 cursor-pointer">
                @error('theme')
                    <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                @enderror
                @if (session()->has('error'))
                    <span class="text-danger text-xs mt-1 block">{{ session('error') }}</span>
                @endif
            </div>
            <button type="submit" id="themeBtn"
                class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-5 py-2 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30 shrink-0">
                Upload Theme
            </button>
        </form>

        {{-- Upload progress --}}
        <div class="mt-3 hidden" id="loadingTheme">
            <div class="flex items-center gap-3 text-sm text-content-muted">
                <svg class="animate-spin w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Please wait while the theme is being uploaded…
            </div>
        </div>
    </div>

    {{-- Theme Display (Livewire) --}}
    <div class="mt-6">
        <livewire:admin.theme-display />
    </div>
</x-admin.card>
