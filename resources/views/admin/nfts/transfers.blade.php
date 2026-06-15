@extends('layouts.admin-dash')

@section('title', 'Transfer History')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="NFT Transfer History" subtitle="All ownership transfers">
            <x-slot name="actions">
                <a href="{{ route('admin.nfts.index') }}" class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                    Back to NFTs
                </a>
            </x-slot>
        </x-admin.page-header>

        <x-admin.table-card title="Transfers">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-alt">
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">NFT</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Type</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">From</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">To</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Price</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transfers as $t)
                        <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors">
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2">
                                    @if($t->nft)
                                        <img src="{{ asset('storage/app/public/' . $t->nft->image) }}" class="w-8 h-8 rounded object-cover border border-border">
                                        <span class="text-sm font-medium text-content">{{ Str::limit($t->nft->name, 20) }}</span>
                                    @else
                                        <span class="text-sm text-content-muted">Deleted</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3.5">
                                @php
                                    $typeColor = match($t->type) {
                                        'mint' => 'success',
                                        'sale' => 'info',
                                        'bid_accept' => 'warning',
                                        default => 'neutral',
                                    };
                                @endphp
                                <x-admin.badge :type="$typeColor">{{ ucfirst(str_replace('_', ' ', $t->type)) }}</x-admin.badge>
                            </td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $t->fromUser->name ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $t->toUser->name ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-sm font-medium text-content">{{ $t->price > 0 ? $t->price . ' ETH' : '—' }}</td>
                            <td class="px-4 py-3.5 text-xs text-content-muted">{{ $t->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-8 text-center text-content-muted">No transfers recorded.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($transfers->hasPages())
                <div class="px-4 py-3 border-t border-border">{{ $transfers->links() }}</div>
            @endif
        </x-admin.table-card>
    </div>
@endsection
