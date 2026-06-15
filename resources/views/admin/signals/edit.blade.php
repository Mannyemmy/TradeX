@extends('layouts.admin-dash')
@section('title', 'Edit Signal')
@section('content')

    <x-admin.page-header title="Edit Signal">
        <x-slot name="actions">
            <a href="{{ route('signal.index') }}" class="inline-flex items-center gap-1.5 bg-surface-alt text-content hover:bg-surface-alt/80 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors border border-border">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Back to Signals
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
            <form action="{{ route('signal.update', $signal->id) }}" method="POST">
                @csrf
                @method('PUT')

                <x-admin.form-group label="Signal Name" class="mb-4" required>
                    <input type="text" name="name" value="{{ old('name', $signal->name) }}" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </x-admin.form-group>

                <x-admin.form-group label="Entry Price" class="mb-4" required>
                    <input type="number" name="entry_price" value="{{ old('entry_price', $signal->entry_price) }}" step="0.01" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </x-admin.form-group>

                <x-admin.form-group label="Take Profit" class="mb-4" required>
                    <input type="number" name="take_profit" value="{{ old('take_profit', $signal->take_profit) }}" step="0.01" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </x-admin.form-group>

                <x-admin.form-group label="Stop Loss" class="mb-4" required>
                    <input type="number" name="stop_loss" value="{{ old('stop_loss', $signal->stop_loss) }}" step="0.01" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </x-admin.form-group>

                <x-admin.form-group label="Leverage" class="mb-4" required>
                    <input type="number" name="leverage" value="{{ old('leverage', $signal->leverage) }}" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                </x-admin.form-group>

                <x-admin.form-group label="Status" class="mb-6" required>
                    <select name="status" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                        <option value="active" {{ old('status', $signal->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="closed" {{ old('status', $signal->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </x-admin.form-group>

                <input type="hidden" name="id" value="{{ $signal->id }}">

                <button type="submit" class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                    Update Signal
                </button>
            </form>
        </x-admin.card>
    </div>

@endsection
