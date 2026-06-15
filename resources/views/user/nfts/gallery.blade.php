@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />

    @include('user.partials.ticker-tape')
    @include('user.partials.quick-nav')

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold text-content-primary">NFT Marketplace</h2>
            <p class="text-sm text-content-secondary mt-1">Discover, collect, and trade unique digital assets</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('user.nfts.create') }}" class="px-4 py-2 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-medium transition-colors inline-flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Mint NFT
            </a>
            <a href="{{ route('user.nfts.my') }}" class="px-4 py-2 rounded-lg bg-surface-overlay border border-surface-border text-content-secondary hover:text-content-primary text-sm font-medium transition-colors">My NFTs</a>
        </div>
    </div>

    {{-- Featured NFTs Carousel --}}
    @if($featuredNfts->count())
        <div class="mb-6">
            <h3 class="text-sm font-semibold text-content-primary mb-3 flex items-center gap-1.5">
                <x-icon name="star" class="w-4 h-4 text-warning" /> Featured
            </h3>
            <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-thin">
                @foreach($featuredNfts as $feat)
                    <a href="{{ route('user.nfts.show', $feat->id) }}" class="flex-shrink-0 w-56 rounded-xl bg-surface-raised border border-surface-border overflow-hidden hover:border-primary/50 transition-colors group">
                        <div class="aspect-[4/3] overflow-hidden">
                            <img src="{{ asset('storage/app/public/' . $feat->image) }}" alt="{{ $feat->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="p-3">
                            <p class="text-sm font-medium text-content-primary truncate">{{ $feat->name }}</p>
                            <p class="text-xs text-primary font-semibold">{{ $feat->price }} ETH</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Collections Strip --}}
    @if($collections->count())
        <div class="mb-6">
            <h3 class="text-sm font-semibold text-content-primary mb-3 flex items-center gap-1.5">
                <x-icon name="folder" class="w-4 h-4 text-content-tertiary" /> Collections
            </h3>
            <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-thin">
                @foreach($collections as $col)
                    <a href="{{ route('user.nfts.collection', $col->id) }}" class="flex-shrink-0 px-4 py-2.5 rounded-xl bg-surface-raised border border-surface-border hover:border-primary/50 transition-colors text-center min-w-[120px]">
                        <p class="text-sm font-medium text-content-primary truncate">{{ $col->name }}</p>
                        <p class="text-xs text-content-tertiary">{{ $col->nfts_count }} items</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Filters & Search --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border p-4 mb-6" x-data="{ showFilters: false }">
        <form method="GET" action="{{ route('nft.gallery') }}">
            <div class="flex flex-wrap items-center gap-2 mb-3">
                {{-- Category Pills --}}
                <a href="{{ route('nft.gallery') }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ !request('category_id') ? 'bg-primary text-content-inverse' : 'bg-surface-overlay border border-surface-border text-content-secondary hover:text-content-primary' }}">All</a>
                @foreach($categories as $cat)
                    <a href="{{ route('nft.gallery', ['category_id' => $cat->id]) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ request('category_id') == $cat->id ? 'bg-primary text-content-inverse' : 'bg-surface-overlay border border-surface-border text-content-secondary hover:text-content-primary' }}">{{ $cat->name }}</a>
                @endforeach

                <button type="button" @click="showFilters = !showFilters" class="ml-auto px-3 py-1.5 rounded-lg bg-surface-overlay border border-surface-border text-content-secondary hover:text-content-primary text-xs font-medium transition-colors inline-flex items-center gap-1">
                    <x-icon name="funnel" class="w-3.5 h-3.5" /> Filters
                </button>
            </div>

            {{-- Search Bar --}}
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, description, or token ID..."
                       class="flex-1 px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm placeholder-content-tertiary focus:outline-none focus:ring-2 focus:ring-primary">
                <button type="submit" class="px-4 py-2.5 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-medium transition-colors">Search</button>
            </div>

            {{-- Advanced Filters --}}
            <div x-show="showFilters" x-transition x-cloak class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-3 pt-3 border-t border-surface-border">
                <div>
                    <label class="block text-xs text-content-tertiary mb-1">Min Price (ETH)</label>
                    <input type="number" name="min_price" step="0.01" value="{{ request('min_price') }}" class="w-full px-3 py-2 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm focus:outline-none focus:ring-2 focus:ring-primary" placeholder="0">
                </div>
                <div>
                    <label class="block text-xs text-content-tertiary mb-1">Max Price (ETH)</label>
                    <input type="number" name="max_price" step="0.01" value="{{ request('max_price') }}" class="w-full px-3 py-2 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm focus:outline-none focus:ring-2 focus:ring-primary" placeholder="100">
                </div>
                <div>
                    <label class="block text-xs text-content-tertiary mb-1">Collection</label>
                    <select name="collection_id" class="w-full px-3 py-2 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">All</option>
                        @foreach($collections as $col)
                            <option value="{{ $col->id }}" {{ request('collection_id') == $col->id ? 'selected' : '' }}>{{ $col->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-content-tertiary mb-1">Sort By</label>
                    <select name="sort" class="w-full px-3 py-2 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low → High</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High → Low</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Viewed</option>
                        <option value="liked" {{ request('sort') == 'liked' ? 'selected' : '' }}>Most Liked</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    {{-- NFT Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @forelse($nfts as $nft)
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
                    <div class="flex items-start justify-between mb-1">
                        <h4 class="text-sm font-semibold text-content-primary truncate flex-1">{{ $nft->name }}</h4>
                        <form action="{{ route('user.nfts.like', $nft->id) }}" method="POST" class="ml-2 flex-shrink-0">
                            @csrf
                            <button type="submit" class="text-content-tertiary hover:text-loss transition-colors" title="Like">
                                <svg class="w-4 h-4" fill="{{ $nft->isLikedBy(auth()->id()) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                            </button>
                        </form>
                    </div>
                    @if($nft->collection)
                        <p class="text-[10px] text-content-tertiary mb-1">{{ $nft->collection->name }}</p>
                    @endif
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-bold text-primary">{{ $nft->price }} ETH</span>
                        <span class="text-[10px] text-content-tertiary flex items-center gap-0.5">
                            <x-icon name="eye" class="w-3 h-3" /> {{ $nft->views_count }}
                        </span>
                    </div>
                    <a href="{{ route('user.nfts.show', $nft->id) }}" class="w-full block text-center py-2 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-medium transition-colors">View</a>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-xl bg-surface-raised border border-surface-border p-12 text-center">
                <x-icon name="photo" class="w-12 h-12 text-content-tertiary mx-auto mb-3" />
                <p class="text-content-secondary mb-1">No NFTs found</p>
                <p class="text-xs text-content-tertiary">Try adjusting your search or filters</p>
            </div>
        @endforelse
    </div>

    @if($nfts->hasPages())
        <div class="mt-6">{{ $nfts->withQueryString()->links() }}</div>
    @endif

@endsection
