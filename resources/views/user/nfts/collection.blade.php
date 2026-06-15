@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />

    @include('user.partials.ticker-tape')
    @include('user.partials.quick-nav')

    {{-- Collection Banner --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden mb-6">
        @if($collection->banner_image)
            <div class="h-40 sm:h-56 overflow-hidden">
                <img src="{{ asset('storage/app/public/' . $collection->banner_image) }}" alt="{{ $collection->name }}" class="w-full h-full object-cover">
            </div>
        @else
            <div class="h-32 bg-gradient-to-r from-primary/20 to-primary/5"></div>
        @endif

        <div class="p-5 -mt-8 relative">
            <div class="flex items-end gap-4">
                @if($collection->logo_image)
                    <img src="{{ asset('storage/app/public/' . $collection->logo_image) }}" class="w-16 h-16 rounded-xl border-4 border-surface-raised object-cover">
                @else
                    <div class="w-16 h-16 rounded-xl border-4 border-surface-raised bg-surface-overlay flex items-center justify-center">
                        <x-icon name="folder" class="w-8 h-8 text-content-tertiary" />
                    </div>
                @endif
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-content-primary">{{ $collection->name }}</h2>
                    @if($collection->category)
                        <p class="text-xs text-content-tertiary">{{ $collection->category->name }}</p>
                    @endif
                </div>
                <a href="{{ route('nft.gallery') }}" class="px-3 py-1.5 rounded-lg bg-surface-overlay border border-surface-border text-content-secondary hover:text-content-primary text-xs font-medium transition-colors">
                    ← Gallery
                </a>
            </div>

            @if($collection->description)
                <p class="text-sm text-content-secondary mt-3">{{ $collection->description }}</p>
            @endif

            {{-- Collection Stats --}}
            <div class="flex items-center gap-6 mt-4 text-sm">
                <div>
                    <span class="text-content-tertiary text-xs">Items</span>
                    <p class="font-semibold text-content-primary">{{ $nfts->total() }}</p>
                </div>
                <div>
                    <span class="text-content-tertiary text-xs">Floor Price</span>
                    <p class="font-semibold text-primary">{{ $collection->floor_price }} ETH</p>
                </div>
                <div>
                    <span class="text-content-tertiary text-xs">Volume</span>
                    <p class="font-semibold text-content-primary">{{ $collection->total_volume }} ETH</p>
                </div>
                @if($collection->royalty_percent > 0)
                    <div>
                        <span class="text-content-tertiary text-xs">Royalty</span>
                        <p class="font-semibold text-content-primary">{{ $collection->royalty_percent }}%</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- NFT Grid --}}
    @if($nfts->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($nfts as $nft)
                <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden hover:border-primary/50 transition-colors group">
                    <a href="{{ route('user.nfts.show', $nft->id) }}" class="block relative">
                        <div class="aspect-square overflow-hidden">
                            <img src="{{ asset('storage/app/public/' . $nft->image) }}" alt="{{ $nft->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                        </div>
                        @if($nft->is_featured)
                            <span class="absolute top-2 left-2 px-2 py-0.5 rounded-full bg-warning/90 text-surface-base text-[10px] font-bold">Featured</span>
                        @endif
                    </a>
                    <div class="p-4">
                        <h4 class="text-sm font-semibold text-content-primary truncate mb-1">{{ $nft->name }}</h4>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm font-bold text-primary">{{ $nft->price }} ETH</span>
                            <span class="text-[10px] text-content-tertiary flex items-center gap-0.5">
                                <x-icon name="eye" class="w-3 h-3" /> {{ $nft->views_count }}
                            </span>
                        </div>
                        <a href="{{ route('user.nfts.show', $nft->id) }}" class="w-full block text-center py-2 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-medium transition-colors">View</a>
                    </div>
                </div>
            @endforeach
        </div>

        @if($nfts->hasPages())
            <div class="mt-6">{{ $nfts->links() }}</div>
        @endif
    @else
        <div class="rounded-xl bg-surface-raised border border-surface-border p-12 text-center">
            <x-icon name="photo" class="w-12 h-12 text-content-tertiary mx-auto mb-3" />
            <p class="text-content-secondary">No NFTs in this collection yet</p>
        </div>
    @endif

@endsection
