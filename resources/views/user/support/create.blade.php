@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />

    {{-- Ticker Tape --}}
    @include('user.partials.ticker-tape')

    {{-- Quick Nav --}}
    @include('user.partials.quick-nav')

    {{-- Page Header --}}
    @include('user.partials.page-header', ['title' => 'New Support Ticket', 'subtitle' => 'Describe your issue and we\'ll get back to you'])

    <div class="max-w-2xl mx-auto">
        {{-- Back Link --}}
        <div class="mb-4">
            <a href="{{ route('support') }}" class="inline-flex items-center gap-1 text-sm text-content-tertiary hover:text-primary transition-colors">
                <x-icon name="arrow-left" class="w-4 h-4" />
                Back to Tickets
            </a>
        </div>

        {{-- Ticket Form --}}
        <div class="rounded-xl bg-surface-raised border border-surface-border p-6">
            <form method="POST" action="{{ route('support.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-content-primary mb-1.5">
                        Subject <span class="text-loss">*</span>
                    </label>
                    <input type="text" name="subject" value="{{ old('subject') }}" required maxlength="255"
                           class="w-full px-4 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm placeholder-content-tertiary focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors"
                           placeholder="Brief summary of your issue" />
                    @error('subject')
                        <p class="text-xs text-loss mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-content-primary mb-1.5">
                        Priority
                    </label>
                    <select name="priority"
                            class="w-full px-4 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors">
                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-content-primary mb-1.5">
                        Message <span class="text-loss">*</span>
                    </label>
                    <textarea name="message" rows="6" required minlength="10"
                              class="w-full px-4 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm placeholder-content-tertiary focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors"
                              placeholder="Describe your issue in detail...">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-xs text-loss mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full py-2.5 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-semibold transition-colors">
                    Submit Ticket
                </button>
            </form>
        </div>
    </div>

@endsection
