@extends('layouts.dash1')
@section('title', 'Calendar')
@section('content')

    <x-danger-alert />
    <x-success-alert />

    {{-- Ticker Tape --}}
    @include('user.partials.ticker-tape')

    {{-- Quick Nav --}}
    @include('user.partials.quick-nav')

    {{-- Page Header --}}
    @include('user.partials.page-header', ['title' => 'Economic Calendar', 'subtitle' => 'Stay updated with key economic events and forex news'])

    {{-- Forex Calendar Widget --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden">
        <div class="px-5 py-3 border-b border-surface-border flex items-center gap-2">
            <x-icon name="calendar" class="w-5 h-5 text-primary" />
            <h3 class="text-sm font-semibold text-content-primary">Forex Economic Calendar</h3>
        </div>
        <div class="p-1">
            <iframe src="https://sslecal2.forexprostools.com/"
                    allowtransparency="true" marginwidth="0" marginheight="0"
                    width="100%" height="500" frameborder="0"
                    class="rounded-b-xl"
                    style="min-height: 500px;"></iframe>
        </div>
    </div>

@endsection
