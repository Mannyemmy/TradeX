@extends('layouts.admin-dash')
@section('title', 'Assistant Knowledge')

@section('content')
    @if (session('success'))
        <x-admin.alert type="success" :dismissible="true" class="mb-6">{{ session('success') }}</x-admin.alert>
    @endif
    @if ($errors->any())
        <x-admin.alert type="danger" :dismissible="true" class="mb-6">{{ $errors->first() }}</x-admin.alert>
    @endif

    <div class="mb-6">
        <h1 class="text-xl font-bold text-content">AI Assistant Knowledge</h1>
        <p class="text-sm text-content-muted mt-1">
            This is the information the WealthWise Assistant uses to answer visitors. Write clear facts about your
            platform — deposits, withdrawals, payment methods, verification, plans, fees, policies, contact, etc.
            The AI only answers from what's here; if something isn't covered, it offers to connect a human.
        </p>
    </div>

    <form method="POST" action="{{ route('admin.assistant.settings.update') }}">
        @csrf
        <div class="bg-surface-card rounded-xl border border-border shadow-card p-5">
            <label class="block text-sm font-medium text-content mb-2">Knowledge base</label>
            <textarea name="assistant_knowledge" rows="22"
                class="w-full px-4 py-3 rounded-lg bg-surface-alt border border-border text-sm text-content placeholder-content-muted focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary font-mono leading-relaxed"
                placeholder="Write what the assistant should know about your site...">{{ old('assistant_knowledge', $knowledge) }}</textarea>

            <div class="mt-3 text-xs text-content-muted space-y-1">
                <p><strong>Tips:</strong> Use short bullet points or Q&amp;A. Keep it factual and specific (limits, timeframes, steps).</p>
                <p>Example: <span class="text-content-secondary">"- Minimum deposit is $50. Crypto deposits credit after 1 network confirmation."</span></p>
                <p>Changes take effect on the next message — no restart needed.</p>
            </div>

            <div class="mt-5 flex items-center gap-3">
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary hover:bg-primary-hover text-white text-sm font-medium">Save knowledge</button>
                <a href="{{ route('admin.assistant.index') }}" class="text-sm text-content-muted hover:text-content">Back to chats</a>
            </div>
        </div>
    </form>
@endsection
