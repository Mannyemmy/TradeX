@extends('layouts.admin-dash')
@section('title', 'Crypto Assets Settings')

@section('content')
    {{-- Page Header --}}
    <x-admin.page-header title="Crypto Assets / Exchange Settings" subtitle="Manage crypto exchange features and asset availability." />

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert type="success" :dismissible="true" class="mt-4">
            {{ session('success') }}
        </x-admin.alert>
    @endif
    @if (session('error'))
        <x-admin.alert type="danger" :dismissible="true" class="mt-4">
            {{ session('error') }}
        </x-admin.alert>
    @endif
    @if (count($errors) > 0)
        <x-admin.alert type="danger" :dismissible="true" class="mt-4">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </x-admin.alert>
    @endif

    {{-- Feature Toggle & Exchange Fee --}}
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Feature Toggle --}}
        <x-admin.card>
            <h3 class="text-base font-medium text-content mb-4">Crypto Exchange Feature</h3>
            <div x-data="{ enabled: {{ $moresettings->use_crypto_feature == 'true' ? 'true' : 'false' }} }">
                <div class="flex items-center gap-3">
                    <button @click="enabled = true; toggleCrypto(true)"
                        :class="enabled ? 'bg-primary text-primary-foreground' : 'bg-surface-alt text-content-secondary border border-border'"
                        class="rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                        On
                    </button>
                    <button @click="enabled = false; toggleCrypto(false)"
                        :class="!enabled ? 'bg-danger text-white' : 'bg-surface-alt text-content-secondary border border-border'"
                        class="rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                        Off
                    </button>
                </div>
                <p class="text-xs text-content-muted mt-2">Your users will not be able to see/use this service if turned off.</p>
            </div>
        </x-admin.card>

        {{-- Exchange Fee Form --}}
        <x-admin.card>
            <h3 class="text-base font-medium text-content mb-4">Exchange Fee Settings</h3>
            <form action="{{ route('exchangefee') }}" method="post">
                @csrf
                <div class="space-y-4">
                    <x-admin.form-group label="Exchange Fee" for="fee" :required="true">
                        <input id="fee" type="text" name="fee" value="{{ $moresettings->fee }}"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    @if ($settings->currency != '$')
                        <x-admin.form-group label="{{ $settings->s_currency }}/USD Rate" for="rate"
                            helper="This rate will be used to calculate your users crypto equivalent in your chosen currency.">
                            <input id="rate" type="number" name="rate" value="{{ $moresettings->currency_rate }}"
                                step=".0" placeholder="450"
                                class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        </x-admin.form-group>
                    @endif

                    <div>
                        <button type="submit"
                            class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-5 py-2.5 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30 inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </x-admin.card>
    </div>

    {{-- Assets Table --}}
    <div class="mt-6">
        <x-admin.table-card title="Crypto Assets">
            <x-slot name="actions">
                <p class="text-xs text-content-muted">Ensure no users have balances &gt; 0 before disabling an asset.</p>
            </x-slot>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Asset Name</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Symbol</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Status</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @include('admin.Settings.Crypto.assets')
                </tbody>
            </table>
        </x-admin.table-card>
    </div>
@endsection

@section('scripts')
    <script>
        function toggleCrypto(value) {
            var url = "{{ url('admin/dashboard/useexchange') }}" + '/' + (value ? 'true' : 'false');
            fetch(url)
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (data.status === 200) {
                        // Dispatch toast via Alpine (layout listens for this)
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: { type: 'success', message: data.success || 'Setting updated successfully.' }
                        }));
                    }
                })
                .catch(function(error) { console.error(error); });
        }
    </script>
@endsection
