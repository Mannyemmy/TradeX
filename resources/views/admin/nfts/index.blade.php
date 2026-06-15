@extends('layouts.admin-dash')

@section('title', 'Manage NFTs')

@section('content')
    <div class="space-y-6">
        {{-- Page Header --}}
        <x-admin.page-header title="Manage NFTs" subtitle="Create, edit and manage NFT listings">
            <x-slot name="actions">
                <a href="{{ route('admin.nft.categories.index') }}"
                   class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" /></svg>
                    Categories
                </a>
                <a href="{{ route('admin.nft.collections.index') }}"
                   class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 01-1.125-1.125v-3.75zM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-8.25zM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-2.25z" /></svg>
                    Collections
                </a>
                <a href="{{ route('admin.nfts.sold') }}"
                   class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" /></svg>
                    Sold
                </a>
                <a href="{{ route('admin.bids.index') }}"
                   class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5" /></svg>
                    Bids
                </a>
                <a href="{{ route('admin.nfts.create') }}"
                   class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Add NFT
                </a>
            </x-slot>
        </x-admin.page-header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <x-admin.alert type="success" :dismissible="true">{{ session('success') }}</x-admin.alert>
        @endif
        @if(session('error') || session('message'))
            <x-admin.alert type="danger" :dismissible="true">{{ session('error') ?? session('message') }}</x-admin.alert>
        @endif

        {{-- Stats Row --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-admin.stat-card label="Total NFTs" :value="$stats['total']" icon="photo" />
            <x-admin.stat-card label="Available" :value="$stats['available']" icon="check-circle" />
            <x-admin.stat-card label="Sold" :value="$stats['sold']" icon="currency-dollar" />
            <x-admin.stat-card label="Pending Approval" :value="$stats['pending']" icon="clock" />
        </div>

        {{-- Filters --}}
        <x-admin.card>
            <form method="GET" action="{{ route('admin.nfts.index') }}" class="flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-content-muted mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or Token ID..."
                           class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                </div>
                <div class="min-w-[150px]">
                    <label class="block text-xs font-medium text-content-muted mb-1">Status</label>
                    <select name="status" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30">
                        <option value="">All</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                    </select>
                </div>
                <div class="min-w-[150px]">
                    <label class="block text-xs font-medium text-content-muted mb-1">Category</label>
                    <select name="category_id" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30">
                        <option value="">All</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-[150px]">
                    <label class="block text-xs font-medium text-content-muted mb-1">Collection</label>
                    <select name="collection_id" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30">
                        <option value="">All</option>
                        @foreach($collections as $col)
                            <option value="{{ $col->id }}" {{ request('collection_id') == $col->id ? 'selected' : '' }}>{{ $col->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">Filter</button>
                <a href="{{ route('admin.nfts.index') }}" class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors">Reset</a>
            </form>
        </x-admin.card>

        {{-- NFTs Table --}}
        <x-admin.table-card title="All NFTs">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-alt">
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">NFT</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Token ID</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Category</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Collection</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Price</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Owner</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-center">Status</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-center">Featured</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nfts as $nft)
                        <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors">
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset('storage/app/public/' . $nft->image) }}" alt="{{ $nft->name }}" class="w-10 h-10 rounded-lg object-cover border border-border">
                                    <span class="text-sm font-medium text-content">{{ Str::limit($nft->name, 25) }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-xs font-mono text-content-secondary">{{ $nft->token_id }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $nft->nftCategory->name ?? $nft->category }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $nft->collection->name ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-sm font-medium text-content">{{ $nft->price }} ETH</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $nft->user->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3.5 text-center">
                                @php
                                    $statusType = match(strtolower($nft->status ?? '')) {
                                        'available' => 'success',
                                        'sold' => 'info',
                                        default => 'neutral',
                                    };
                                @endphp
                                <x-admin.badge :type="$statusType">{{ ucfirst($nft->status) }}</x-admin.badge>
                                @if(!$nft->is_approved)
                                    <x-admin.badge type="warning">Unapproved</x-admin.badge>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <form action="{{ route('admin.nfts.toggle-featured', $nft->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs {{ $nft->is_featured ? 'text-warning' : 'text-content-muted hover:text-warning' }} transition-colors" title="{{ $nft->is_featured ? 'Unfeature' : 'Feature' }}">
                                        <svg class="w-5 h-5" fill="{{ $nft->is_featured ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-1.5">
                                    @if(!$nft->is_approved)
                                        <form action="{{ route('admin.nfts.toggle-approval', $nft->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-success text-white hover:bg-success/90 rounded-lg px-2.5 py-1.5 text-xs font-medium transition-colors">Approve</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.nfts.edit', $nft->id) }}" class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-2.5 py-1.5 text-xs font-medium transition-colors">Edit</a>
                                    <form action="{{ route('admin.nfts.destroy', $nft->id) }}" method="POST" onsubmit="return confirm('Delete this NFT?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bg-danger text-white hover:bg-danger/90 rounded-lg px-2.5 py-1.5 text-xs font-medium transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-content-muted">
                                <svg class="w-12 h-12 mx-auto mb-3 text-content-muted/50" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M2.25 15.75V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-2.25" /></svg>
                                No NFTs found. Create one to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if($nfts->hasPages())
                <div class="px-4 py-3 border-t border-border">{{ $nfts->withQueryString()->links() }}</div>
            @endif
        </x-admin.table-card>
    </div>
@endsection
