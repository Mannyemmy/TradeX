@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />

    {{-- Ticker Tape --}}
    @include('user.partials.ticker-tape')

    {{-- Quick Nav --}}
    @include('user.partials.quick-nav')

    {{-- Header with back button --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-content-primary">{{ $lesson->title }}</h2>
        @if ($course)
            <a href="{{ route('user.mycoursedetails', ['id' => $course->id]) }}" class="px-4 py-2 rounded-lg bg-surface-overlay border border-surface-border text-content-secondary hover:text-content-primary text-sm font-medium transition-colors">
                <x-icon name="arrow-left" class="w-4 h-4 inline-block mr-1" />
                Back
            </a>
        @endif
    </div>

    {{-- Description --}}
    <p class="text-sm text-content-secondary mb-4">{{ $lesson->description }}</p>

    {{-- Video Player --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden mb-6">
        <div class="relative w-full" style="padding-top: 56.25%;">
            <iframe src="{{ $lesson->video_link }}" allowfullscreen
                    class="absolute inset-0 w-full h-full"></iframe>
        </div>
    </div>

    {{-- Prev/Next Navigation --}}
    @if ($course)
        <div class="flex items-center justify-between">
            <div>
                @if ($previous)
                    <a href="{{ route('user.learning', ['course' => $course->id, 'lesson' => $previous]) }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-surface-overlay border border-surface-border text-content-secondary hover:text-content-primary text-sm font-medium transition-colors">
                        <x-icon name="arrow-left" class="w-4 h-4" />
                        Prev Lesson
                    </a>
                @else
                    <button disabled class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-surface-overlay border border-surface-border text-content-tertiary text-sm font-medium opacity-50 cursor-not-allowed">
                        <x-icon name="arrow-left" class="w-4 h-4" />
                        Prev Lesson
                    </button>
                @endif
            </div>
            <div>
                @if ($next)
                    <a href="{{ route('user.learning', ['course' => $course->id, 'lesson' => $next]) }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-medium transition-colors">
                        Next Lesson
                        <x-icon name="arrow-right" class="w-4 h-4" />
                    </a>
                @else
                    <button disabled class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-surface-overlay border border-surface-border text-content-tertiary text-sm font-medium opacity-50 cursor-not-allowed">
                        Next Lesson
                        <x-icon name="arrow-right" class="w-4 h-4" />
                    </button>
                @endif
            </div>
        </div>
    @endif

@endsection
