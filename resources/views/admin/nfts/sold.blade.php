@extends('layouts.admin-dash')

@section('title', 'Sold NFTs')

@section('content')
    <div class="space-y-6">
        {{-- Page Header --}}
        <x-admin.page-header title="Sold NFTs" subtitle="View all purchased NFTs">
            <x-slot name="actions">
                <a href="{{ route('admin.nfts.index') }}"
                   class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                    Back to NFTs
                </a>
            </x-slot>
        </x-admin.page-header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <x-admin.alert type="success" :dismissible="true">{{ session('success') }}</x-admin.alert>
        @endif
        @if(session('error'))
            <x-admin.alert type="danger" :dismissible="true">{{ session('error') }}</x-admin.alert>
        @endif

        {{-- Sold NFTs Table --}}
        <x-admin.table-card title="Sold NFTs">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-alt">
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">NFT</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Token ID</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Collection</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Price</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Creator</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Owner</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Sold On</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($soldNFTs as $nft)
                        <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors">
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset('storage/app/public/' . $nft->image) }}" alt="{{ $nft->name }}" class="w-10 h-10 rounded-lg object-cover border border-border">
                                    <span class="text-sm font-medium text-content">{{ Str::limit($nft->name, 25) }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-xs text-content-muted font-mono">{{ $nft->token_id ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $nft->collection->name ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-sm font-medium text-content">{{ $nft->price }} ETH</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $nft->originalCreator->name ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $nft->user->name ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-xs text-content-muted">{{ $nft->updated_at->format('M d, Y H:i') }}</td>
                            <td class="px-4 py-3.5">
                                <a href="{{ route('admin.nfts.edit', $nft->id) }}" class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="py-12 text-center text-content-muted">No sold NFTs found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($soldNFTs->hasPages())
                <div class="px-4 py-3 border-t border-border">{{ $soldNFTs->links() }}</div>
            @endif
        </x-admin.table-card>
    </div>
@endsection
