@extends('layouts.admin-dash')
@section('title', $title)
@section('content')
<div class="space-y-6">

    <x-admin.page-header title="Add Pre-IPO Company" subtitle="List a new pre-IPO company for share offerings">
        <x-slot name="actions">
            <a href="{{ route('admin.pre-ipo.index') }}"
               class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" /></svg>
                Back to List
            </a>
        </x-slot>
    </x-admin.page-header>

    @if($errors->any())
        <x-admin.alert type="danger" :dismissible="true">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-admin.alert>
    @endif

    <form action="{{ route('admin.pre-ipo.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Main Info --}}
            <div class="lg:col-span-2 space-y-6">
                <x-admin.card title="Company Details">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-admin.form-group label="Company Name" name="name" required>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                        </x-admin.form-group>

                        <x-admin.form-group label="Ticker Symbol" name="symbol" required>
                            <input type="text" name="symbol" value="{{ old('symbol') }}" required maxlength="20" placeholder="e.g. SPACEX"
                                   class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content uppercase focus:outline-none focus:ring-2 focus:ring-primary">
                        </x-admin.form-group>

                        <x-admin.form-group label="Sector" name="sector">
                            <input type="text" name="sector" value="{{ old('sector') }}" placeholder="e.g. Aerospace, Technology"
                                   class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                        </x-admin.form-group>

                        <x-admin.form-group label="Expected IPO Date" name="expected_ipo_date">
                            <input type="date" name="expected_ipo_date" value="{{ old('expected_ipo_date') }}"
                                   class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                        </x-admin.form-group>
                    </div>

                    <div class="mt-4">
                        <x-admin.form-group label="Description" name="description">
                            <textarea name="description" rows="4" placeholder="Company overview and investment thesis..."
                                      class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">{{ old('description') }}</textarea>
                        </x-admin.form-group>
                    </div>
                </x-admin.card>

                <x-admin.card title="Share Configuration">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-admin.form-group label="Share Price ($)" name="share_price" required>
                            <input type="number" name="share_price" value="{{ old('share_price') }}" required step="0.01" min="0.01"
                                   class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                        </x-admin.form-group>

                        <x-admin.form-group label="Total Shares" name="total_shares" required>
                            <input type="number" name="total_shares" value="{{ old('total_shares') }}" required min="1"
                                   class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                        </x-admin.form-group>

                        <x-admin.form-group label="Minimum Purchase (shares)" name="min_shares" required>
                            <input type="number" name="min_shares" value="{{ old('min_shares', 1) }}" required min="1"
                                   class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                        </x-admin.form-group>

                        <x-admin.form-group label="Max Shares Per User" name="max_shares_per_user" helper="Leave empty for unlimited">
                            <input type="number" name="max_shares_per_user" value="{{ old('max_shares_per_user') }}" min="1"
                                   class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                        </x-admin.form-group>
                    </div>
                </x-admin.card>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <x-admin.card title="Logo">
                    <x-admin.form-group label="Company Logo" name="logo" helper="Max 2MB. JPG, PNG, SVG, WEBP.">
                        <input type="file" name="logo" accept="image/*"
                               class="w-full text-sm text-content-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary file:text-primary-foreground hover:file:bg-primary-hover">
                    </x-admin.form-group>
                </x-admin.card>

                <x-admin.card title="Options">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-border text-primary focus:ring-primary">
                        <span class="text-sm text-content">Featured Listing</span>
                    </label>
                    <p class="mt-2 text-xs text-content-muted">Featured companies appear at the top of the Pre-IPO listings page.</p>
                </x-admin.card>

                <button type="submit"
                        class="w-full bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg py-2.5 text-sm font-medium transition-colors">
                    Create Company
                </button>
            </div>

        </div>
    </form>

</div>
@endsection
