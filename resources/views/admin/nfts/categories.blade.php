@extends('layouts.admin-dash')

@section('title', 'NFT Categories')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="NFT Categories" subtitle="Manage marketplace categories">
            <x-slot name="actions">
                <a href="{{ route('admin.nfts.index') }}" class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                    Back to NFTs
                </a>
            </x-slot>
        </x-admin.page-header>

        @if(session('success'))
            <x-admin.alert type="success" :dismissible="true">{{ session('success') }}</x-admin.alert>
        @endif

        {{-- Add Category --}}
        <x-admin.card>
            <h3 class="text-sm font-semibold text-content mb-3">Add Category</h3>
            <form action="{{ route('admin.nft.categories.store') }}" method="POST" class="flex flex-wrap items-end gap-3">
                @csrf
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-content-muted mb-1">Name</label>
                    <input type="text" name="name" required class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors" placeholder="e.g. Digital Art">
                </div>
                <div class="w-32">
                    <label class="block text-xs font-medium text-content-muted mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="0" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                </div>
                <div class="min-w-[160px]">
                    <label class="block text-xs font-medium text-content-muted mb-1">Icon (optional)</label>
                    <input type="text" name="icon" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors" placeholder="e.g. art">
                </div>
                <button type="submit" class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">Add</button>
            </form>
        </x-admin.card>

        {{-- Categories Table --}}
        <x-admin.table-card title="All Categories">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-alt">
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Name</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Slug</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-center">NFTs</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-center">Order</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-center">Status</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors" x-data="{ editing: false }">
                            <td class="px-4 py-3.5 text-sm font-medium text-content">
                                <span x-show="!editing">{{ $cat->name }}</span>
                                <form x-show="editing" action="{{ route('admin.nft.categories.update', $cat->id) }}" method="POST" class="flex items-center gap-2" x-cloak>
                                    @csrf @method('PUT')
                                    <input type="text" name="name" value="{{ $cat->name }}" class="bg-surface-card border border-border rounded px-2 py-1 text-sm text-content w-36">
                                    <input type="number" name="sort_order" value="{{ $cat->sort_order }}" class="bg-surface-card border border-border rounded px-2 py-1 text-sm text-content w-16">
                                    <label class="flex items-center gap-1 text-xs text-content-secondary">
                                        <input type="checkbox" name="is_active" value="1" {{ $cat->is_active ? 'checked' : '' }} class="rounded">
                                        Active
                                    </label>
                                    <button type="submit" class="bg-primary text-primary-foreground rounded px-2 py-1 text-xs font-medium">Save</button>
                                    <button type="button" @click="editing = false" class="text-xs text-content-muted">Cancel</button>
                                </form>
                            </td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary font-mono">{{ $cat->slug }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary text-center">{{ $cat->nfts_count }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary text-center">{{ $cat->sort_order }}</td>
                            <td class="px-4 py-3.5 text-center">
                                <x-admin.badge :type="$cat->is_active ? 'success' : 'neutral'">{{ $cat->is_active ? 'Active' : 'Inactive' }}</x-admin.badge>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-1.5">
                                    <button @click="editing = !editing" class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-2.5 py-1.5 text-xs font-medium transition-colors">Edit</button>
                                    <form action="{{ route('admin.nft.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bg-danger text-white hover:bg-danger/90 rounded-lg px-2.5 py-1.5 text-xs font-medium transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-8 text-center text-content-muted">No categories. Add one above.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-admin.table-card>
    </div>
@endsection
