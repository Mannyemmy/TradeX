@extends('layouts.admin-dash')

@section('title', 'Edit NFT')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Edit NFT" subtitle="Update NFT details — {{ $nft->token_id }}">
            <x-slot name="actions">
                <a href="{{ route('admin.nfts.index') }}" class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                    Back to NFTs
                </a>
            </x-slot>
        </x-admin.page-header>

        @if($errors->any())
            <x-admin.alert type="danger" :dismissible="true">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </x-admin.alert>
        @endif

        <div class="max-w-3xl">
            <x-admin.card>
                <form action="{{ route('admin.nfts.update', $nft->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-admin.form-group label="Name" for="name" :required="true">
                            <input type="text" id="name" name="name" value="{{ old('name', $nft->name) }}" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors" required>
                        </x-admin.form-group>

                        <x-admin.form-group label="Blockchain" for="blockchain" :required="true">
                            <select id="blockchain" name="blockchain" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                                @foreach(['Ethereum','Polygon','Solana','BNB Chain'] as $chain)
                                    <option value="{{ $chain }}" {{ old('blockchain', $nft->blockchain) == $chain ? 'selected' : '' }}>{{ $chain }}</option>
                                @endforeach
                            </select>
                        </x-admin.form-group>
                    </div>

                    <x-admin.form-group label="Description" for="description">
                        <textarea id="description" name="description" rows="4" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors min-h-[80px] resize-y">{{ old('description', $nft->description) }}</textarea>
                    </x-admin.form-group>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <x-admin.form-group label="Price (ETH)" for="price" :required="true">
                            <input type="number" id="price" name="price" step="0.0001" value="{{ old('price', $nft->price) }}" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors" required>
                        </x-admin.form-group>

                        <x-admin.form-group label="Category" for="category_id" :required="true">
                            <select id="category_id" name="category_id" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $nft->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </x-admin.form-group>

                        <x-admin.form-group label="Royalty %" for="royalty_percent">
                            <input type="number" id="royalty_percent" name="royalty_percent" step="0.1" min="0" max="50" value="{{ old('royalty_percent', $nft->royalty_percent) }}" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                        </x-admin.form-group>
                    </div>

                    <x-admin.form-group label="Collection" for="collection_id">
                        <select id="collection_id" name="collection_id" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                            <option value="">No collection</option>
                            @foreach($collections as $col)
                                <option value="{{ $col->id }}" {{ old('collection_id', $nft->collection_id) == $col->id ? 'selected' : '' }}>{{ $col->name }}</option>
                            @endforeach
                        </select>
                    </x-admin.form-group>

                    <x-admin.form-group label="Properties (JSON)" for="properties">
                        <textarea id="properties" name="properties" rows="3" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content font-mono focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors resize-y">{{ old('properties', $nft->properties ? json_encode($nft->properties, JSON_PRETTY_PRINT) : '') }}</textarea>
                    </x-admin.form-group>

                    <x-admin.form-group label="Current Image">
                        <img src="{{ asset('storage/app/public/' . $nft->image) }}" alt="{{ $nft->name }}" class="w-24 h-24 rounded-lg object-cover border border-border mt-1">
                    </x-admin.form-group>

                    <x-admin.form-group label="Replace Image" for="image" helper="Leave empty to keep current">
                        <input type="file" id="image" name="image" accept="image/*" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-primary-light file:text-primary hover:file:bg-primary/10 transition-colors">
                    </x-admin.form-group>

                    <x-admin.form-group label="Date Created (Backdate)" for="created_at" helper="Change the original transaction date. Leave blank to keep current.">
                        <input type="datetime-local" id="created_at" name="created_at"
                               value="{{ old('created_at', $nft->created_at ? $nft->created_at->format('Y-m-d\TH:i') : '') }}"
                               class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                    </x-admin.form-group>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-5 py-2.5 text-sm font-medium transition-colors">Update NFT</button>
                        <a href="{{ route('admin.nfts.index') }}" class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-5 py-2.5 text-sm font-medium transition-colors">Cancel</a>
                    </div>
                </form>
            </x-admin.card>
        </div>
    </div>
@endsection
