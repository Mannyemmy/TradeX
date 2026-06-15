@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />

    @include('user.partials.ticker-tape')
    @include('user.partials.quick-nav')

    {{-- Back + Title --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('nft.gallery') }}" class="p-2 rounded-lg bg-surface-overlay hover:bg-surface-border text-content-secondary transition-colors">
            <x-icon name="arrow-left" class="w-5 h-5" />
        </a>
        <div>
            <h2 class="text-xl font-bold text-content-primary">{{ $nft->name }}</h2>
            @if($nft->token_id)
                <p class="text-xs text-content-tertiary font-mono">{{ $nft->token_id }}</p>
            @endif
        </div>
        <div class="ml-auto">
            <form action="{{ route('user.nfts.like', $nft->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="p-2 rounded-lg {{ $isLiked ? 'bg-loss/10 text-loss' : 'bg-surface-overlay text-content-tertiary hover:text-loss' }} transition-colors" title="{{ $isLiked ? 'Unlike' : 'Like' }}">
                    <svg class="w-5 h-5" fill="{{ $isLiked ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                </button>
            </form>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">
        {{-- Image (3 cols) --}}
        <div class="lg:col-span-3 rounded-xl bg-surface-raised border border-surface-border overflow-hidden">
            <img src="{{ asset('storage/app/public/' . $nft->image) }}" alt="{{ $nft->name }}" class="w-full h-auto max-h-[600px] object-contain">
        </div>

        {{-- Details (2 cols) --}}
        <div class="lg:col-span-2 space-y-4">
            {{-- Price & Action Card --}}
            <div class="rounded-xl bg-surface-raised border border-surface-border p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-xs text-content-tertiary mb-1">Current Price</p>
                        <p class="text-2xl font-bold text-primary">{{ $nft->price }} ETH</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-content-tertiary mb-1">Blockchain</p>
                        <p class="text-sm font-medium text-content-primary">{{ $nft->blockchain ?? 'Ethereum' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-4 text-xs">
                    <div class="bg-surface-overlay rounded-lg p-2.5">
                        <span class="text-content-tertiary">Owner</span>
                        <p class="font-medium text-content-primary truncate">{{ $nft->user->name }}</p>
                    </div>
                    <div class="bg-surface-overlay rounded-lg p-2.5">
                        <span class="text-content-tertiary">Creator</span>
                        <p class="font-medium text-content-primary truncate">{{ $nft->originalCreator->name ?? $nft->user->name }}</p>
                    </div>
                    <div class="bg-surface-overlay rounded-lg p-2.5">
                        <span class="text-content-tertiary">Views</span>
                        <p class="font-medium text-content-primary">{{ number_format($nft->views_count) }}</p>
                    </div>
                    <div class="bg-surface-overlay rounded-lg p-2.5">
                        <span class="text-content-tertiary">Likes</span>
                        <p class="font-medium text-content-primary">{{ number_format($nft->likes_count) }}</p>
                    </div>
                </div>

                @if($nft->status == 'available' && $nft->user_id != auth()->id())
                    <form id="buy-form" action="{{ route('nfts.buy', $nft->id) }}" method="POST">
                        @csrf
                        <button type="button" id="buy-button" class="w-full py-3 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-semibold transition-colors">Buy Now</button>
                    </form>
                @elseif($nft->user_id == auth()->id())
                    <div class="py-3 text-center text-sm text-content-tertiary bg-surface-overlay rounded-lg">You own this NFT</div>
                @else
                    <div class="py-3 text-center text-sm text-content-tertiary bg-surface-overlay rounded-lg">Not available for purchase</div>
                @endif
            </div>

            {{-- Bid Card --}}
            @if($nft->status == 'available' && $nft->user_id != auth()->id())
                <div class="rounded-xl bg-surface-raised border border-surface-border p-5">
                    <h4 class="text-sm font-semibold text-content-primary mb-3">Place a Bid (ETH)</h4>
                    <form id="bid-form" action="{{ route('bids.place', $nft->id) }}" method="POST">
                        @csrf
                        <input type="number" name="amount" step="0.01" min="0.01" required placeholder="Enter bid amount"
                               class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm placeholder-content-tertiary focus:outline-none focus:ring-2 focus:ring-primary mb-3">
                        <button type="button" id="bid-button" class="w-full py-2.5 rounded-lg bg-info hover:bg-info/80 text-white text-sm font-semibold transition-colors">Submit Bid</button>
                    </form>
                </div>
            @endif

            {{-- Description --}}
            <div class="rounded-xl bg-surface-raised border border-surface-border p-5">
                <h4 class="text-sm font-semibold text-content-primary mb-2">Description</h4>
                <p class="text-sm text-content-secondary leading-relaxed">{{ $nft->description ?: 'No description provided.' }}</p>
            </div>

            {{-- Details Metadata --}}
            <div class="rounded-xl bg-surface-raised border border-surface-border p-5">
                <h4 class="text-sm font-semibold text-content-primary mb-3">Details</h4>
                <dl class="space-y-2 text-sm">
                    @if($nft->nftCategory)
                        <div class="flex justify-between"><dt class="text-content-tertiary">Category</dt><dd class="text-content-primary">{{ $nft->nftCategory->name }}</dd></div>
                    @endif
                    @if($nft->collection)
                        <div class="flex justify-between"><dt class="text-content-tertiary">Collection</dt><dd><a href="{{ route('user.nfts.collection', $nft->collection->id) }}" class="text-primary hover:underline">{{ $nft->collection->name }}</a></dd></div>
                    @endif
                    @if($nft->royalty_percent > 0)
                        <div class="flex justify-between"><dt class="text-content-tertiary">Royalty</dt><dd class="text-content-primary">{{ $nft->royalty_percent }}%</dd></div>
                    @endif
                    @if($nft->minted_at)
                        <div class="flex justify-between"><dt class="text-content-tertiary">Minted</dt><dd class="text-content-primary">{{ $nft->minted_at->format('M d, Y') }}</dd></div>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    {{-- Properties/Traits --}}
    @if($nft->properties && count($nft->properties))
        <div class="rounded-xl bg-surface-raised border border-surface-border p-5 mb-6">
            <h3 class="text-sm font-semibold text-content-primary mb-3 flex items-center gap-1.5">
                <x-icon name="tag" class="w-4 h-4 text-content-tertiary" /> Properties
            </h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($nft->properties as $key => $value)
                    <div class="bg-primary/5 border border-primary/20 rounded-lg p-3 text-center">
                        <p class="text-[10px] text-primary uppercase font-semibold tracking-wide">{{ $key }}</p>
                        <p class="text-sm font-medium text-content-primary mt-0.5">{{ $value }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Bid History --}}
        <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden">
            <div class="px-5 py-3 border-b border-surface-border">
                <h3 class="text-sm font-semibold text-content-primary">Bid History</h3>
            </div>
            @if($nft->bids->count())
                <div class="divide-y divide-surface-border max-h-64 overflow-y-auto">
                    @foreach($nft->bids as $bid)
                        <div class="flex items-center justify-between px-5 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-surface-overlay flex items-center justify-center">
                                    <x-icon name="user" class="w-3.5 h-3.5 text-content-tertiary" />
                                </div>
                                <div>
                                    <span class="text-sm text-content-primary">{{ $bid->user->name ?? $bid->user->username }}</span>
                                    <span class="text-xs text-content-tertiary ml-1">{{ $bid->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-semibold text-primary">{{ $bid->amount }} ETH</span>
                                @if($bid->status != 'pending')
                                    <span class="block text-[10px] {{ $bid->status == 'accepted' ? 'text-gain' : 'text-loss' }}">{{ ucfirst($bid->status) }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-5 text-center text-content-tertiary text-sm">No bids yet.</div>
            @endif
        </div>

        {{-- Transfer History --}}
        <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden">
            <div class="px-5 py-3 border-b border-surface-border">
                <h3 class="text-sm font-semibold text-content-primary">Ownership History</h3>
            </div>
            @if($nft->transfers->count())
                <div class="divide-y divide-surface-border max-h-64 overflow-y-auto">
                    @foreach($nft->transfers->sortByDesc('created_at') as $transfer)
                        <div class="flex items-center justify-between px-5 py-3">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $transfer->type == 'mint' ? 'bg-gain/10 text-gain' : ($transfer->type == 'sale' ? 'bg-info/10 text-info' : 'bg-warning/10 text-warning') }}">{{ ucfirst(str_replace('_', ' ', $transfer->type)) }}</span>
                                <div class="text-xs text-content-secondary">
                                    {{ $transfer->fromUser->name ?? 'Minted' }} → {{ $transfer->toUser->name ?? '?' }}
                                </div>
                            </div>
                            <div class="text-right text-xs">
                                @if($transfer->price > 0)
                                    <span class="font-medium text-content-primary">{{ $transfer->price }} ETH</span>
                                @endif
                                <span class="block text-content-tertiary">{{ $transfer->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-5 text-center text-content-tertiary text-sm">No history.</div>
            @endif
        </div>
    </div>

    {{-- Related NFTs --}}
    @if($relatedNfts->count())
        <div class="mb-6">
            <h3 class="text-sm font-semibold text-content-primary mb-3">More Like This</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach($relatedNfts as $related)
                    <a href="{{ route('user.nfts.show', $related->id) }}" class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden hover:border-primary/50 transition-colors group">
                        <div class="aspect-square overflow-hidden">
                            <img src="{{ asset('storage/app/public/' . $related->image) }}" alt="{{ $related->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                        </div>
                        <div class="p-3">
                            <p class="text-xs font-medium text-content-primary truncate">{{ $related->name }}</p>
                            <p class="text-xs text-primary font-semibold">{{ $related->price }} ETH</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

@endsection

@section('scripts')
@parent
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buyBtn = document.getElementById('buy-button');
        if (buyBtn) {
            buyBtn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Confirm Purchase',
                    text: 'Are you sure you want to buy this NFT for {{ $nft->price }} ETH?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#059669',
                    cancelButtonColor: '#2A2F36',
                    confirmButtonText: 'Yes, Buy Now',
                    cancelButtonText: 'Cancel',
                    background: '#161A1E',
                    color: '#E8EAED'
                }).then((result) => {
                    if (result.isConfirmed) document.getElementById('buy-form').submit();
                });
            });
        }

        const bidBtn = document.getElementById('bid-button');
        if (bidBtn) {
            bidBtn.addEventListener('click', function() {
                const amount = document.querySelector('#bid-form input[name="amount"]').value;
                if (!amount || amount <= 0) {
                    Swal.fire({ title: 'Error', text: 'Please enter a valid bid amount.', icon: 'error', background: '#161A1E', color: '#E8EAED', confirmButtonColor: '#059669' });
                    return;
                }
                Swal.fire({
                    title: 'Confirm Bid',
                    text: 'Place a bid of ' + amount + ' ETH?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#059669',
                    cancelButtonColor: '#2A2F36',
                    confirmButtonText: 'Yes, Place Bid',
                    cancelButtonText: 'Cancel',
                    background: '#161A1E',
                    color: '#E8EAED'
                }).then((result) => {
                    if (result.isConfirmed) document.getElementById('bid-form').submit();
                });
            });
        }
    });
</script>
@endsection
