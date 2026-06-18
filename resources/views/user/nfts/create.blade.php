@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />

    @include('user.partials.ticker-tape')
    @include('user.partials.quick-nav')

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-content-primary">Mint NFT</h2>
            <p class="text-sm text-content-secondary mt-1">Create a new digital asset on the blockchain</p>
        </div>
        <a href="{{ route('nft.gallery') }}" class="px-4 py-2 rounded-lg bg-surface-overlay border border-surface-border text-content-secondary hover:text-content-primary text-sm font-medium transition-colors">
            Back to Gallery
        </a>
    </div>

    {{-- Mint Form --}}
    <div class="max-w-2xl">
        <div class="rounded-xl bg-surface-raised border border-surface-border p-6">
            <form id="nftForm" action="{{ route('user.nfts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1">NFT Name <span class="text-loss">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm placeholder-content-tertiary focus:outline-none focus:ring-2 focus:ring-primary" placeholder="e.g. Cosmic Explorer #42">
                </div>

                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm placeholder-content-tertiary focus:outline-none focus:ring-2 focus:ring-primary resize-y" placeholder="Describe your NFT...">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-content-secondary mb-1">Price (ETH) <span class="text-loss">*</span></label>
                        <input type="number" name="price" step="0.001" min="0" value="{{ old('price') }}" required
                               class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm placeholder-content-tertiary focus:outline-none focus:ring-2 focus:ring-primary" placeholder="0.05">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-content-secondary mb-1">Category <span class="text-loss">*</span></label>
                        <select name="category_id" required
                                class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="">Select category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1">Collection (optional)</label>
                    <select name="collection_id"
                            class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">No collection</option>
                        @foreach($collections as $col)
                            <option value="{{ $col->id }}" {{ old('collection_id') == $col->id ? 'selected' : '' }}>{{ $col->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1">Properties (optional)</label>
                    <p class="text-xs text-content-tertiary mb-1.5">JSON format, e.g. {"Background": "Blue", "Rarity": "Legendary"}</p>
                    <textarea name="properties" rows="2"
                              class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm font-mono placeholder-content-tertiary focus:outline-none focus:ring-2 focus:ring-primary resize-y" placeholder='{"trait": "value"}'>{{ old('properties') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1">NFT Image <span class="text-loss">*</span></label>
                    <div class="border-2 border-dashed border-surface-border rounded-lg p-6 text-center hover:border-primary/50 transition-colors">
                        <x-icon name="photo" class="w-8 h-8 text-content-tertiary mx-auto mb-2" />
                        <p class="text-xs text-content-tertiary mb-1">PNG, JPG, GIF, or WebP — max 5MB</p>
                        <input type="file" name="image" required accept="image/jpeg,image/png,image/gif,image/webp"
                               class="w-full text-sm text-content-tertiary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary file:text-content-inverse hover:file:bg-primary-dark file:cursor-pointer">
                    </div>
                </div>

                <div class="bg-surface-overlay rounded-lg p-3 text-xs text-content-tertiary">
                    <x-icon name="information-circle" class="w-4 h-4 inline-block mr-1 text-info" />
                    A gas fee will be deducted from your account balance upon minting.
                </div>

                <button type="button" id="mintBtn"
                        class="w-full py-2.5 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-semibold transition-colors">
                    Mint NFT
                </button>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
@parent
<script>
    document.getElementById('mintBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'Mint NFT?',
            text: 'A gas fee will be deducted from your wallet balance. Continue?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2E5C8A',
            cancelButtonColor: '#2A2F36',
            confirmButtonText: 'Yes, Mint It!',
            cancelButtonText: 'Cancel',
            background: '#161A1E',
            color: '#E8EAED'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('nftForm').submit();
        });
    });
</script>
@endsection
