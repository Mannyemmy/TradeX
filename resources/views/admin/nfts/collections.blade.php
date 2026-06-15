@extends('layouts.admin-dash')

@section('title', 'NFT Collections')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="NFT Collections" subtitle="Manage curated collections">
            <x-slot name="actions">
                <a href="{{ route('admin.nfts.index') }}" class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                    Back to NFTs
                </a>
                <a href="{{ route('admin.nft.collections.create') }}" class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    New Collection
                </a>
            </x-slot>
        </x-admin.page-header>

        @if(session('success'))
            <x-admin.alert type="success" :dismissible="true">{{ session('success') }}</x-admin.alert>
        @endif

        <x-admin.table-card title="All Collections">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-alt">
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Collection</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Category</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-center">Items</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Floor</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Volume</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-center">Royalty</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-center">Status</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collections as $col)
                        <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors">
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    @if($col->logo_image)
                                        <img src="{{ asset('storage/app/public/' . $col->logo_image) }}" class="w-10 h-10 rounded-lg object-cover border border-border">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-surface-alt flex items-center justify-center">
                                            <svg class="w-5 h-5 text-content-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 01-1.125-1.125v-3.75z" /></svg>
                                        </div>
                                    @endif
                                    <div>
                                        <span class="text-sm font-medium text-content block">{{ $col->name }}</span>
                                        @if($col->is_featured) <span class="text-xs text-warning">Featured</span> @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $col->category->name ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary text-center">{{ $col->nfts_count }}</td>
                            <td class="px-4 py-3.5 text-sm text-content">{{ $col->floor_price }} ETH</td>
                            <td class="px-4 py-3.5 text-sm text-content">{{ $col->total_volume }} ETH</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary text-center">{{ $col->royalty_percent }}%</td>
                            <td class="px-4 py-3.5 text-center">
                                <x-admin.badge :type="$col->is_active ? 'success' : 'neutral'">{{ $col->is_active ? 'Active' : 'Inactive' }}</x-admin.badge>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-1.5">
                                    <form action="{{ route('admin.nft.collections.toggle-featured', $col->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="{{ $col->is_featured ? 'text-warning' : 'text-content-muted hover:text-warning' }} transition-colors" title="Toggle featured">
                                            <svg class="w-4 h-4" fill="{{ $col->is_featured ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.nft.collections.edit', $col->id) }}" class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-2.5 py-1.5 text-xs font-medium transition-colors">Edit</a>
                                    <form action="{{ route('admin.nft.collections.destroy', $col->id) }}" method="POST" onsubmit="return confirm('Delete this collection?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bg-danger text-white hover:bg-danger/90 rounded-lg px-2.5 py-1.5 text-xs font-medium transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="py-8 text-center text-content-muted">No collections yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-admin.table-card>
    </div>
@endsection
