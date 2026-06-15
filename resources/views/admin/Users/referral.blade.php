@extends('layouts.admin-dash')
@section('title', 'Add Referral - ' . $user->name)
@section('content')
    <x-admin.page-header title="Add user to {{ $user->name }} referrals list">
        <x-slot name="actions">
            <a class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-3 py-1.5 text-xs font-medium transition-colors" href="{{ route('viewuser', $user->id) }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                back
            </a>
        </x-slot>
    </x-admin.page-header>

    <div class="mt-6 max-w-2xl mx-auto">
        <x-admin.card>
            <form method="POST" action="{{ route('addref') }}">
                @csrf
                <x-admin.form-group label="Select User" helper="This indicates that the selected user was referred by {{ $user->name }}">
                    <select class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary select2" name="ref_id">
                        @foreach ($ref as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </x-admin.form-group>

                <input type="hidden" name="user_id" value="{{ $user->id }}">

                <div class="mt-4">
                    <button type="submit" class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                        Save Referral
                    </button>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2();
});
</script>
@endpush
