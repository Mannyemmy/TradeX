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
    @include('user.partials.page-header', ['title' => 'Your Courses', 'subtitle' => 'Continue learning where you left off'])

    {{-- Course Grid --}}
    @forelse ($courses as $course)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden hover:border-primary/50 transition-colors">
                <img src="{{ $course->image && str_starts_with($course->image, 'http') ? $course->image : asset('storage/' . $course->image) }}"
                     alt="course image" class="w-full h-40 object-cover">
                <div class="p-4">
                    <h4 class="text-sm font-semibold text-content-primary mb-2">{{ $course->title }}</h4>
                    <div class="flex items-center gap-1 text-xs text-content-tertiary mb-3">
                        <x-icon name="academic-cap" class="w-3.5 h-3.5" />
                        <span>{{ $course->lessons->count() }} {{ $course->lessons->count() === 1 ? 'Lesson' : 'Lessons' }}</span>
                    </div>
                    <a href="{{ route('user.mycoursedetails', $course->id) }}"
                       class="w-full block text-center py-2 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-medium transition-colors">
                        Watch
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="rounded-xl bg-surface-raised border border-surface-border p-8 text-center">
            <x-icon name="academic-cap" class="w-12 h-12 text-content-tertiary mx-auto mb-3" />
            <p class="text-content-secondary mb-3">You haven't purchased any courses yet.</p>
            <a href="{{ route('user.courses') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-medium transition-colors">Browse Courses</a>
        </div>
    @endforelse

@endsection
