@extends('layouts.admin-dash')
@section('title', $title)
@section('content')
<div class="space-y-6">

    <x-admin.page-header title="Edit: {{ $company->name }}" subtitle="Update company details, share price, and status">
        <x-slot name="actions">
            <a href="{{ route('admin.pre-ipo.show', $company->id) }}"
               class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                View
            </a>
            <a href="{{ route('admin.pre-ipo.index') }}"
               class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" /></svg>
                Back to List
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Edit Form --}}
        <div class="lg:col-span-2 space-y-6">
            <form action="{{ route('admin.pre-ipo.update', $company->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <x-admin.card title="Company Details">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <x-admin.form-group label="Company Name" name="name" required>
                                <input type="text" name="name" value="{{ old('name', $company->name) }}" required
                                       class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                            </x-admin.form-group>

                            <x-admin.form-group label="Ticker Symbol" name="symbol" required>
                                <input type="text" name="symbol" value="{{ old('symbol', $company->symbol) }}" required maxlength="20"
                                       class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content uppercase focus:outline-none focus:ring-2 focus:ring-primary">
                            </x-admin.form-group>

                            <x-admin.form-group label="Sector" name="sector">
                                <input type="text" name="sector" value="{{ old('sector', $company->sector) }}"
                                       class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                            </x-admin.form-group>

                            <x-admin.form-group label="Expected IPO Date" name="expected_ipo_date">
                                <input type="date" name="expected_ipo_date" value="{{ old('expected_ipo_date', $company->expected_ipo_date?->format('Y-m-d')) }}"
                                       class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                            </x-admin.form-group>

                            <x-admin.form-group label="Date Created (Backdate)" name="created_at">
                                <input type="datetime-local" name="created_at"
                                       value="{{ old('created_at', $company->created_at ? $company->created_at->format('Y-m-d\TH:i') : '') }}"
                                       class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                                <p class="text-xs text-content-muted mt-1">Leave blank to keep the current date.</p>
                            </x-admin.form-group>
                        </div>

                        <div class="mt-4">
                            <x-admin.form-group label="Description" name="description">
                                <textarea name="description" rows="4"
                                          class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">{{ old('description', $company->description) }}</textarea>
                            </x-admin.form-group>
                        </div>
                    </x-admin.card>

                    <x-admin.card title="Share Configuration">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <x-admin.form-group label="Share Price ($)" name="share_price" required>
                                <input type="number" name="share_price" value="{{ old('share_price', $company->share_price) }}" required step="0.01" min="0.01"
                                       class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                            </x-admin.form-group>

                            <x-admin.form-group label="Price Change Note" name="price_note" helper="Shown in price history if price changed">
                                <input type="text" name="price_note" value="{{ old('price_note') }}" placeholder="e.g. Series D valuation"
                                       class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                            </x-admin.form-group>

                            <x-admin.form-group label="Total Shares" name="total_shares" required>
                                <input type="number" name="total_shares" value="{{ old('total_shares', $company->total_shares) }}" required min="1"
                                       class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                            </x-admin.form-group>

                            <x-admin.form-group label="Minimum Purchase (shares)" name="min_shares" required>
                                <input type="number" name="min_shares" value="{{ old('min_shares', $company->min_shares) }}" required min="1"
                                       class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                            </x-admin.form-group>

                            <x-admin.form-group label="Max Shares Per User" name="max_shares_per_user" helper="Leave empty for unlimited">
                                <input type="number" name="max_shares_per_user" value="{{ old('max_shares_per_user', $company->max_shares_per_user) }}" min="1"
                                       class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                            </x-admin.form-group>

                            @if(in_array($company->status, ['ipo', 'public']))
                                <x-admin.form-group label="Link Trading Asset" name="trading_asset_id" helper="Required to go public">
                                    <select name="trading_asset_id"
                                            class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                                        <option value="">— Select Asset —</option>
                                        @foreach($tradingAssets as $asset)
                                            <option value="{{ $asset->id }}" {{ $company->trading_asset_id == $asset->id ? 'selected' : '' }}>
                                                {{ $asset->name }} ({{ $asset->symbol }}) — ${{ number_format($asset->price, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </x-admin.form-group>
                            @endif
                        </div>
                    </x-admin.card>

                    <x-admin.card title="Logo">
                        <div class="flex items-center gap-4">
                            @if($company->logo)
                                <img src="{{ asset('storage/app/public/' . $company->logo) }}" alt="{{ $company->name }}" class="w-16 h-16 rounded-lg object-cover border border-border">
                            @endif
                            <div class="flex-1">
                                <input type="file" name="logo" accept="image/*"
                                       class="w-full text-sm text-content-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary file:text-primary-foreground hover:file:bg-primary-hover">
                            </div>
                        </div>
                    </x-admin.card>

                    <x-admin.card title="Options">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" {{ $company->is_featured ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-border text-primary focus:ring-primary">
                            <span class="text-sm text-content">Featured Listing</span>
                        </label>
                    </x-admin.card>

                    <button type="submit"
                            class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-6 py-2.5 text-sm font-medium transition-colors">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- Sidebar: Status + Quick Price + Price History --}}
        <div class="space-y-6">

            {{-- Status Management --}}
            <x-admin.card title="Status">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-content-secondary">Current Status</span>
                        @php
                            $statusColors = ['upcoming' => 'info', 'open' => 'success', 'closed' => 'warning', 'ipo' => 'neutral', 'public' => 'success'];
                        @endphp
                        <x-admin.badge :type="$statusColors[$company->status] ?? 'neutral'">{{ ucfirst($company->status) }}</x-admin.badge>
                    </div>

                    @php
                        $nextStatus = ['upcoming' => 'open', 'open' => 'closed', 'closed' => 'ipo', 'ipo' => 'public'];
                        $next = $nextStatus[$company->status] ?? null;
                    @endphp

                    @if($next)
                        <form action="{{ route('admin.pre-ipo.status', $company->id) }}" method="POST"
                              onsubmit="return confirm('Transition status to {{ $next }}? This cannot be undone.')">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="{{ $next }}">
                            <button type="submit"
                                    class="w-full bg-warning/10 text-warning border border-warning/20 hover:bg-warning/20 rounded-lg px-4 py-2.5 text-sm font-medium transition-colors">
                                Advance to: {{ ucfirst($next) }}
                            </button>
                        </form>
                    @else
                        <p class="text-xs text-content-muted">This company has reached its final status.</p>
                    @endif

                    @if($company->opened_at)
                        <p class="text-xs text-content-muted">Opened: {{ $company->opened_at->format('M d, Y H:i') }}</p>
                    @endif
                    @if($company->closed_at)
                        <p class="text-xs text-content-muted">Closed: {{ $company->closed_at->format('M d, Y H:i') }}</p>
                    @endif
                    @if($company->went_public_at)
                        <p class="text-xs text-content-muted">Public: {{ $company->went_public_at->format('M d, Y H:i') }}</p>
                    @endif
                </div>
            </x-admin.card>

            {{-- Quick Price Update --}}
            <x-admin.card title="Quick Price Update">
                <form action="{{ route('admin.pre-ipo.price', $company->id) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <x-admin.form-group label="New Price ($)" name="share_price">
                        <input type="number" name="share_price" value="{{ $company->share_price }}" step="0.01" min="0.01" required
                               class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                    </x-admin.form-group>
                    <x-admin.form-group label="Note" name="note">
                        <input type="text" name="note" placeholder="Reason for change"
                               class="w-full bg-surface border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                    </x-admin.form-group>
                    <button type="submit"
                            class="w-full bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg py-2.5 text-sm font-medium transition-colors">
                        Update Price
                    </button>
                </form>
            </x-admin.card>

            {{-- Delete --}}
            @if($company->shares_sold == 0)
                <x-admin.card title="Danger Zone">
                    <form action="{{ route('admin.pre-ipo.destroy', $company->id) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this company? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full bg-danger text-white hover:bg-danger/90 rounded-lg py-2.5 text-sm font-medium transition-colors">
                            Delete Company
                        </button>
                    </form>
                    <p class="mt-2 text-xs text-content-muted">Only available when no shares have been sold.</p>
                </x-admin.card>
            @endif

            {{-- Price History --}}
            <x-admin.card title="Price History">
                @if($priceHistory->count() > 0)
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @foreach($priceHistory as $ph)
                            <div class="flex items-center justify-between py-2 border-b border-border last:border-0">
                                <div>
                                    <p class="text-sm font-medium text-content">${{ number_format($ph->price, 2) }}</p>
                                    <p class="text-xs text-content-muted">{{ $ph->note ?? 'No note' }}</p>
                                </div>
                                <p class="text-xs text-content-muted">{{ $ph->created_at->format('M d, Y') }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-content-muted">No price history yet.</p>
                @endif
            </x-admin.card>

        </div>
    </div>

</div>
@endsection
