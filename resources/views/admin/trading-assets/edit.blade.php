@extends('layouts.admin-dash')
@section('title', $title ?? 'Edit Asset')
@section('content')
<div class="space-y-6">

    <x-admin.page-header title="Edit: {{ $asset->name }}" subtitle="Update asset details, pricing, and status">
        <x-slot name="actions">
            <a href="{{ route('admin.assets.index') }}"
               class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" /></svg>
                Back to Assets
            </a>
        </x-slot>
    </x-admin.page-header>

    @if(session('success'))
        <x-admin.alert type="success" :dismissible="true">{{ session('success') }}</x-admin.alert>
    @endif
    @if(session('message'))
        <x-admin.alert type="danger" :dismissible="true">{{ session('message') }}</x-admin.alert>
    @endif
    @if($errors->any())
        <x-admin.alert type="danger" :dismissible="true">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-admin.alert>
    @endif

    <form action="{{ route('admin.assets.update', $asset->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Main Details --}}
            <div class="lg:col-span-2 space-y-6">
                <x-admin.card title="Asset Details">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-admin.form-group label="Name" name="name" required>
                            <input type="text" name="name" value="{{ old('name', $asset->name) }}" required
                                   class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                        </x-admin.form-group>

                        <x-admin.form-group label="Symbol" name="symbol" required>
                            <input type="text" name="symbol" value="{{ old('symbol', $asset->symbol) }}" required maxlength="20"
                                   class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content uppercase focus:outline-none focus:ring-2 focus:ring-primary">
                        </x-admin.form-group>

                        <x-admin.form-group label="Asset Class" name="asset_class" required>
                            <select name="asset_class" required
                                    class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                                @foreach($assetClasses as $class)
                                    <option value="{{ $class }}" {{ old('asset_class', $asset->asset_class) === $class ? 'selected' : '' }}>
                                        {{ ucfirst($class) }}
                                    </option>
                                @endforeach
                            </select>
                        </x-admin.form-group>

                        <x-admin.form-group label="Data Source" name="data_source">
                            <input type="text" value="{{ $asset->data_source }}" disabled
                                   class="w-full bg-surface-alt border border-border rounded-lg px-3 py-2.5 text-sm text-content-muted cursor-not-allowed">
                        </x-admin.form-group>
                    </div>
                </x-admin.card>

                <x-admin.card title="Pricing">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-admin.form-group label="Price ($)" name="price" required>
                            <input type="number" name="price" value="{{ old('price', $asset->price) }}" required step="0.00000001" min="0"
                                   class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                        </x-admin.form-group>

                        <x-admin.form-group label="24h Change ($)" name="price_change_24h">
                            <input type="number" name="price_change_24h" value="{{ old('price_change_24h', $asset->price_change_24h) }}" step="0.00000001"
                                   class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                        </x-admin.form-group>

                        <x-admin.form-group label="24h Change (%)" name="price_change_pct_24h">
                            <input type="number" name="price_change_pct_24h" value="{{ old('price_change_pct_24h', $asset->price_change_pct_24h) }}" step="0.01"
                                   class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                        </x-admin.form-group>

                        <x-admin.form-group label="24h High ($)" name="high_24h">
                            <input type="number" name="high_24h" value="{{ old('high_24h', $asset->high_24h) }}" step="0.00000001" min="0"
                                   class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                        </x-admin.form-group>

                        <x-admin.form-group label="24h Low ($)" name="low_24h">
                            <input type="number" name="low_24h" value="{{ old('low_24h', $asset->low_24h) }}" step="0.00000001" min="0"
                                   class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                        </x-admin.form-group>
                    </div>
                </x-admin.card>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <x-admin.card title="Logo">
                    <div class="space-y-4" x-data="{ logoUrl: '{{ old('logo_url', $asset->logo_url) }}' }">
                        <x-admin.form-group label="Logo URL" name="logo_url">
                            <input type="url" name="logo_url" x-model="logoUrl" placeholder="https://..."
                                   class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                        </x-admin.form-group>

                        <div class="flex items-center justify-center p-4 bg-surface-alt rounded-lg border border-border min-h-[80px]">
                            <template x-if="logoUrl">
                                <img :src="logoUrl" alt="Logo preview" class="w-12 h-12 rounded-full object-cover">
                            </template>
                            <template x-if="!logoUrl">
                                <div class="text-center">
                                    <svg class="w-10 h-10 mx-auto text-content-muted/40" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" /></svg>
                                    <p class="text-xs text-content-muted mt-1">No logo</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </x-admin.card>

                <x-admin.card title="Status">
                    <div class="space-y-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <div class="relative">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $asset->is_active) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-surface-alt peer-focus:ring-2 peer-focus:ring-primary/30 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-content-inverse after:border-border after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </div>
                            <span class="text-sm font-medium text-content">Active</span>
                        </label>
                        <p class="text-xs text-content-muted">Inactive assets won't appear in user trading screens.</p>

                        <div class="pt-3 border-t border-border">
                            <dl class="space-y-2 text-xs">
                                <div class="flex justify-between">
                                    <dt class="text-content-muted">Created</dt>
                                    <dd class="text-content">{{ $asset->created_at?->format('M d, Y') ?? '—' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-content-muted">Last Updated</dt>
                                    <dd class="text-content">{{ $asset->updated_at?->diffForHumans() ?? '—' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </x-admin.card>

                <div class="flex flex-col gap-3">
                    <button type="submit"
                            class="w-full bg-primary text-primary-foreground rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-primary-hover transition-colors inline-flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                        Save Changes
                    </button>
                    <a href="{{ route('admin.assets.index') }}"
                       class="w-full bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2.5 text-sm font-medium transition-colors text-center">
                        Cancel
                    </a>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection
