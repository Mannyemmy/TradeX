<x-admin.card>
    <h3 class="text-lg font-semibold text-content mb-1">API Configuration</h3>
    <p class="text-sm text-content-muted mb-6">Configure API keys for live market data. CoinGecko (crypto) works without a key on the free tier. TwelveData requires an API key for forex, stocks, ETFs, and indices.</p>

    <form action="{{ route('updateapikeys') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            {{-- CoinGecko --}}
            <x-admin.form-group label="CoinGecko API Key" for="coingecko_api_key"
                helper="Optional — free tier works without it">
                <div class="flex gap-2">
                    <input type="text" id="coingecko_api_key" name="coingecko_api_key"
                        class="flex-1 bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        value="{{ $settingsCont->coingecko_api_key ?? '' }}"
                        placeholder="Enter CoinGecko API key (optional)">
                    <button type="button" class="btn-test-api shrink-0 bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-3 py-2 text-sm font-medium transition-colors" data-provider="coingecko">
                        <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m9.86-2.686a4.5 4.5 0 00-1.242-7.244l4.5-4.5a4.5 4.5 0 016.364 6.364l-1.757 1.757" /></svg>
                        Test
                    </button>
                </div>
                <small id="coingecko-result" class="text-xs mt-1 block"></small>
            </x-admin.form-group>

            {{-- TwelveData --}}
            <x-admin.form-group label="TwelveData API Key" for="twelvedata_api_key">
                <div class="flex gap-2">
                    <input type="text" id="twelvedata_api_key" name="twelvedata_api_key"
                        class="flex-1 bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        value="{{ $settingsCont->twelvedata_api_key ?? '' }}"
                        placeholder="Enter TwelveData API key">
                    <button type="button" class="btn-test-api shrink-0 bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-3 py-2 text-sm font-medium transition-colors" data-provider="twelvedata">
                        <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m9.86-2.686a4.5 4.5 0 00-1.242-7.244l4.5-4.5a4.5 4.5 0 016.364 6.364l-1.757 1.757" /></svg>
                        Test
                    </button>
                </div>
                <small id="twelvedata-result" class="text-xs mt-1 block"></small>
                <p class="text-xs text-danger mt-1">Required for market data</p>
            </x-admin.form-group>
        </div>

        {{-- Submit --}}
        <div class="mt-6 pt-4 border-t border-border">
            <button type="submit"
                class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-6 py-2.5 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30">
                <svg class="w-4 h-4 inline mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                Save API Keys
            </button>
        </div>
    </form>

    {{-- API Usage Info --}}
    <div class="border-t border-border mt-6 pt-6">
        <h4 class="text-base font-medium text-content mb-4">API Usage Info</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-surface-alt/50 rounded-lg p-4 border border-border">
                <h5 class="text-sm font-medium text-content mb-2">CoinGecko (Crypto)</h5>
                <ul class="text-xs text-content-secondary space-y-1.5">
                    <li class="flex items-start gap-2">
                        <svg class="w-3.5 h-3.5 text-content-muted mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Updates every <strong class="text-content">5 minutes</strong>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-3.5 h-3.5 text-content-muted mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                        Free tier: ~30 calls/min
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-3.5 h-3.5 text-content-muted mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375" /></svg>
                        Fetches top 35 crypto assets by market cap
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-3.5 h-3.5 text-content-muted mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" /></svg>
                        Cron URL: <code class="bg-surface-alt px-1.5 py-0.5 rounded text-xs text-primary">/run-crypto-prices</code>
                    </li>
                </ul>
            </div>

            <div class="bg-surface-alt/50 rounded-lg p-4 border border-border">
                <h5 class="text-sm font-medium text-content mb-2">TwelveData (Forex, Stocks, ETFs, Indices)</h5>
                <ul class="text-xs text-content-secondary space-y-1.5">
                    <li class="flex items-start gap-2">
                        <svg class="w-3.5 h-3.5 text-content-muted mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Updates every <strong class="text-content">15 minutes</strong>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-3.5 h-3.5 text-content-muted mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                        Free tier: 800 credits/day
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-3.5 h-3.5 text-content-muted mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375" /></svg>
                        Uses batched symbol requests (~5 calls per run)
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-3.5 h-3.5 text-content-muted mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" /></svg>
                        Cron URL: <code class="bg-surface-alt px-1.5 py-0.5 rounded text-xs text-primary">/run-market-prices</code>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</x-admin.card>

@push('scripts')
<script>
$(document).ready(function() {
    $('.btn-test-api').on('click', function() {
        var btn = $(this);
        var provider = btn.data('provider');
        var resultEl = $('#' + provider + '-result');

        btn.prop('disabled', true).html('<svg class="animate-spin w-4 h-4 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Testing...');
        resultEl.html('').removeClass('text-success text-danger');

        $.ajax({
            url: '{{ route("testapiconnection") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                provider: provider
            },
            success: function(response) {
                if (response.success) {
                    resultEl.html('&#10003; ' + response.message).addClass('text-success');
                } else {
                    resultEl.html('&#10007; ' + response.message).addClass('text-danger');
                }
            },
            error: function() {
                resultEl.html('&#10007; Request failed.').addClass('text-danger');
            },
            complete: function() {
                btn.prop('disabled', false).html('<svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m9.86-2.686a4.5 4.5 0 00-1.242-7.244l4.5-4.5a4.5 4.5 0 016.364 6.364l-1.757 1.757" /></svg> Test');
            }
        });
    });
});
</script>
@endpush
