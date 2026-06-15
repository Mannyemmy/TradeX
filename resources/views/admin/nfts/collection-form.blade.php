@extends('layouts.admin-dash')

@section('title', $title)

@section('content')
    <div class="space-y-6">
        <x-admin.page-header :title="$title" :subtitle="isset($collection) ? 'Update collection details' : 'Create a new curated collection'">
            <x-slot name="actions">
                <a href="{{ route('admin.nft.collections.index') }}" class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                    Back
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
                <form action="{{ isset($collection) ? route('admin.nft.collections.update', $collection->id) : route('admin.nft.collections.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @if(isset($collection)) @method('PUT') @endif

                    <x-admin.form-group label="Name" for="name" :required="true">
                        <input type="text" id="name" name="name" value="{{ old('name', $collection->name ?? '') }}" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors" required>
                    </x-admin.form-group>

                    <x-admin.form-group label="Description" for="description">
                        <textarea id="description" name="description" rows="3" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors resize-y">{{ old('description', $collection->description ?? '') }}</textarea>
                    </x-admin.form-group>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-admin.form-group label="Category" for="category_id">
                            <select id="category_id" name="category_id" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                                <option value="">None</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $collection->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </x-admin.form-group>

                        <x-admin.form-group label="Royalty %" for="royalty_percent">
                            <input type="number" id="royalty_percent" name="royalty_percent" step="0.1" min="0" max="50" value="{{ old('royalty_percent', $collection->royalty_percent ?? 0) }}" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                        </x-admin.form-group>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-admin.form-group label="Banner Image" for="banner_image">
                            @if(isset($collection) && $collection->banner_image)
                                <img src="{{ asset('storage/app/public/' . $collection->banner_image) }}" class="w-full h-20 rounded-lg object-cover border border-border mb-2">
                            @endif
                            <input type="file" id="banner_image" name="banner_image" accept="image/*" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-primary-light file:text-primary hover:file:bg-primary/10 transition-colors">
                        </x-admin.form-group>

                        <x-admin.form-group label="Logo Image" for="logo_image">
                            @if(isset($collection) && $collection->logo_image)
                                <img src="{{ asset('storage/app/public/' . $collection->logo_image) }}" class="w-16 h-16 rounded-lg object-cover border border-border mb-2">
                            @endif
                            <input type="file" id="logo_image" name="logo_image" accept="image/*" class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-primary-light file:text-primary hover:file:bg-primary/10 transition-colors">
                        </x-admin.form-group>
                    </div>

                    @if(isset($collection))
                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-2 text-sm text-content">
                                <input type="checkbox" name="is_featured" value="1" {{ $collection->is_featured ? 'checked' : '' }} class="rounded border-border text-primary focus:ring-primary/30">
                                Featured
                            </label>
                            <label class="flex items-center gap-2 text-sm text-content">
                                <input type="checkbox" name="is_active" value="1" {{ $collection->is_active ? 'checked' : '' }} class="rounded border-border text-primary focus:ring-primary/30">
                                Active
                            </label>
                        </div>
                    @endif

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-5 py-2.5 text-sm font-medium transition-colors">{{ isset($collection) ? 'Update' : 'Create' }} Collection</button>
                        <a href="{{ route('admin.nft.collections.index') }}" class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-5 py-2.5 text-sm font-medium transition-colors">Cancel</a>
                    </div>
                </form>
            </x-admin.card>
        </div>
    </div>
@endsection
