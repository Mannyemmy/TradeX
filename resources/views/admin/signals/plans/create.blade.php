@extends('layouts.admin-dash')
@section('title', 'Create Signal Plan')
@section('content')

    <x-admin.page-header title="Create Signal Plan">
        <x-slot name="actions">
            <a href="{{ route('signal-plans.index') }}" class="inline-flex items-center gap-1.5 bg-surface-alt text-content hover:bg-surface-alt/80 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors border border-border">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Back to Plans
            </a>
        </x-slot>
    </x-admin.page-header>

    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="mt-4">
            <x-admin.alert type="danger" :dismissible="true">{{ session('error') }}</x-admin.alert>
        </div>
    @endif
    @if($errors->any())
        <div class="mt-4">
            <x-admin.alert type="danger" :dismissible="true">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-admin.alert>
        </div>
    @endif

    <div class="mt-6 max-w-2xl">
        <x-admin.card>
            <form action="{{ route('signal-plans.store') }}" method="POST">
                @csrf

                <x-admin.form-group label="Plan Name" class="mb-4" required>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </x-admin.form-group>

                <x-admin.form-group label="Price ($)" class="mb-4" required>
                    <input type="number" name="price" value="{{ old('price') }}" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </x-admin.form-group>

                <x-admin.form-group label="Features" class="mb-4" required>
                    <input type="text" name="features" value="{{ old('features') }}" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </x-admin.form-group>

                <x-admin.form-group label="Duration (Weeks)" class="mb-6" required>
                    <input type="number" name="duration" value="{{ old('duration') }}" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </x-admin.form-group>

                <button type="submit" class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Create Plan
                </button>
            </form>
        </x-admin.card>
    </div>

@endsection
