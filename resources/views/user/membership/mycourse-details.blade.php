@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />

    {{-- Ticker Tape --}}
    @include('user.partials.ticker-tape')

    {{-- Quick Nav --}}
    @include('user.partials.quick-nav')

    {{-- Back + Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('user.mycourses') }}" class="p-2 rounded-lg bg-surface-overlay hover:bg-surface-border text-content-secondary transition-colors">
            <x-icon name="arrow-left" class="w-5 h-5" />
        </a>
        <div>
            <h2 class="text-xl font-bold text-primary">{{ $course->title }}</h2>
            <p class="text-sm text-content-secondary mt-0.5">{{ $course->category->name ?? '—' }} &middot; Purchased {{ \Carbon\Carbon::parse($course->pivot->created_at ?? $course->created_at)->toDayDateTimeString() }}</p>
        </div>
    </div>

    {{-- Course Meta --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border p-6 mb-6">
        <div class="grid grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-xs text-content-tertiary mb-0.5">CREATED BY</p>
                <p class="font-medium text-content-primary">{{ $settings->site_name }}</p>
            </div>
            <div>
                <p class="text-xs text-content-tertiary mb-0.5">CATEGORY</p>
                <p class="font-medium text-content-primary">{{ $course->category->name ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-content-tertiary mb-0.5">PURCHASED</p>
                <p class="font-medium text-content-primary">{{ \Carbon\Carbon::parse($course->pivot->created_at ?? $course->created_at)->toDayDateTimeString() }}</p>
            </div>
        </div>
    </div>

    {{-- Lessons List --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden">
        <div class="px-5 py-3 border-b border-surface-border">
            <h3 class="text-sm font-semibold text-content-primary">Course Lessons</h3>
        </div>
        <div class="divide-y divide-surface-border">
            @forelse ($lessons as $lesson)
                <div class="flex items-center justify-between px-5 py-3 hover:bg-surface-overlay/50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-loss/10 flex items-center justify-center flex-shrink-0">
                            <x-icon name="play" class="w-4 h-4 text-loss" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-content-primary">{{ $lesson->title }}</p>
                            <p class="text-xs text-content-tertiary">{{ $lesson->description }} &middot; {{ $lesson->length }}</p>
                        </div>
                    </div>
                    <a href="{{ route('user.learning', ['lesson' => $lesson->id, 'course' => $course->id]) }}"
                       class="px-3 py-1.5 rounded-lg bg-info/10 text-info text-xs font-medium hover:bg-info/20 transition-colors">
                        Watch
                    </a>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-content-tertiary text-sm">No lessons available.</div>
            @endforelse
        </div>
    </div>

@endsection
