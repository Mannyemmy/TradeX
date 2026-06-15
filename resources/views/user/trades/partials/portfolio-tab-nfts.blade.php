{{-- Portfolio Tab: NFTs --}}

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    @include('user.partials.stat-card-compact', ['label' => 'NFTs Owned', 'value' => $ownedNfts->count(), 'icon' => 'gem'])
    @include('user.partials.stat-card-compact', ['label' => 'Estimated Value', 'value' => \App\Helpers\CurrencyHelper::formatForUser($totalNftValue), 'icon' => 'banknotes', 'color' => 'info'])
    @include('user.partials.stat-card-compact', ['label' => 'Listed', 'value' => $ownedNfts->where('status', 'listed')->count(), 'icon' => 'tag', 'color' => 'gain'])
</div>

@if($ownedNfts->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($ownedNfts as $nft)
            <a href="{{ route('user.nfts.show', $nft->id) }}" class="bg-surface-raised border border-surface-border rounded-xl overflow-hidden hover:border-primary/30 transition-colors group">
                {{-- Image --}}
                <div class="aspect-square bg-surface-overlay overflow-hidden">
                    @if($nft->image)
                        <img src="{{ asset('storage/app/public/' . $nft->image) }}" alt="{{ $nft->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <x-icon name="gem" class="w-12 h-12 text-content-tertiary" />
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="p-3">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <span class="text-sm font-semibold text-content-primary truncate">{{ $nft->name }}</span>
                        <span class="px-1.5 py-0.5 text-[10px] font-medium rounded-full shrink-0 {{ $nft->status === 'listed' ? 'bg-gain/10 text-gain' : 'bg-surface-overlay text-content-tertiary' }}">
                            {{ ucfirst($nft->status) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-content-primary">@money($nft->price)</span>
                        @if($nft->category)
                            <span class="text-[10px] text-content-tertiary">{{ $nft->category }}</span>
                        @endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@else
    @include('user.trades.partials.empty-state', [
        'icon' => 'gem',
        'title' => 'No NFTs in collection',
        'message' => 'You don\'t own any NFTs yet. Browse the gallery or mint your own.',
        'actionUrl' => route('nft.gallery'),
        'actionLabel' => 'Browse Gallery',
    ])
@endif
